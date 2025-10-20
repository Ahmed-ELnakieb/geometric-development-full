<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\LogOptions;
// use Spatie\Activitylog\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia;

    /**
     * Boot the model and register model events
     */
    protected static function booted(): void
    {
        // Auto-generate thumbnails from hero slider images if thumbnails are empty
        static::saved(function (Project $project) {
            $thumbnails = $project->getMedia('hero_thumbnails');
            $heroSliderImages = $project->getMedia('hero_slider');
            
            // Enforce max 3 thumbnails - remove excess if more than 3
            if ($thumbnails->count() > 3) {
                $excess = $thumbnails->slice(3);
                foreach ($excess as $media) {
                    $media->delete();
                }
                // Refresh thumbnails after deletion
                $thumbnails = $project->getMedia('hero_thumbnails');
            }
            
            // Only auto-generate if thumbnails are empty and hero slider has images
            if ($thumbnails->isEmpty() && $heroSliderImages->isNotEmpty()) {
                $imagesToCopy = $heroSliderImages->take(3);
                
                foreach ($imagesToCopy as $media) {
                    $media->copy($project, 'hero_thumbnails');
                }
            }
        });
    }

    protected $fillable = [
        "title",
        "slug",
        "location",
        "type",
        "status",
        "excerpt",
        "description",
        "video_url",
        "video_preview_url",
        "total_units",
        "property_size_min",
        "property_size_max",
        "completion_date",
        "is_featured",
        "showcase",
        "showcase_order",
        "display_order",
        "meta_title",
        "meta_description",
        "is_published",
        "published_at",
    ];

    protected $casts = [
        "completion_date" => "date",
        "property_size_min" => "decimal:2",
        "property_size_max" => "decimal:2",
        "is_featured" => "boolean",
        "showcase" => "boolean",
        "is_published" => "boolean",
        "published_at" => "datetime",
    ];

    public function unitTypes(): HasMany
    {
        return $this->hasMany(ProjectUnitType::class);
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(ProjectAmenity::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProjectCategory::class, "project_category");
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, "messageable");
    }

    public function scopePublished($query)
    {
        return $query->where("is_published", true);
    }

    public function scopeFeatured($query)
    {
        return $query->where("is_featured", true);
    }

    public function scopeType($query, $type)
    {
        return $query->where("type", $type);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where("status", $status);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy("display_order");
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom("title")
            ->saveSlugsTo("slug");
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection("gallery")->acceptsMimeTypes([
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp",
        ]);

        $this->addMediaCollection("hero_slider")->acceptsMimeTypes([
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp",
        ]);

        $this->addMediaCollection("hero_thumbnails")->acceptsMimeTypes([
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp",
        ]);

        $this->addMediaCollection("about_image")
            ->acceptsMimeTypes([
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/webp",
            ])
            ->singleFile();

        $this->addMediaCollection("documents")->acceptsMimeTypes([
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        ]);

        $this->addMediaCollection("brochure")
            ->acceptsMimeTypes([
                "application/pdf",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            ])
            ->singleFile();

        $this->addMediaCollection("factsheet")
            ->acceptsMimeTypes([
                "application/pdf",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            ])
            ->singleFile();

        $this->addMediaCollection("video_preview")
            ->acceptsMimeTypes([
                "video/mp4",
                "video/webm",
                "video/ogg",
            ])
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // Temporarily disabled to avoid memory issues during seeding
        // TODO: Re-enable after optimizing image sizes

        // // Define thumb conversion for all image collections
        // $this->addMediaConversion("thumb")
        //     ->width(200)
        //     ->height(200)
        //     ->sharpen(10)
        //     ->performOnCollections(
        //         "gallery",
        //         "hero_slider",
        //         "hero_thumbnails",
        //         "about_image",
        //     );
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly(['title', 'location', 'type', 'status', 'is_published'])
    //         ->logOnlyDirty();
    // }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function publish(): void
    {
        $this->update([
            "is_published" => true,
            "published_at" => now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update(["is_published" => false]);
    }

    public function getGalleryImages()
    {
        return $this->getMedia("gallery");
    }
}
