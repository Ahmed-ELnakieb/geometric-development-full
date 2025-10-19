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

    protected $fillable = [
        "title",
        "slug",
        "location",
        "type",
        "status",
        "excerpt",
        "description",
        "video_url",
        "total_units",
        "property_size_min",
        "property_size_max",
        "completion_date",
        "is_featured",
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
        "is_published" => "boolean",
        "published_at" => "datetime",
    ];

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, "featured_image_id");
    }

    public function videoFile(): BelongsTo
    {
        return $this->belongsTo(Media::class, "video_file_id");
    }

    public function brochure(): BelongsTo
    {
        return $this->belongsTo(Media::class, "brochure_id");
    }

    public function factsheet(): BelongsTo
    {
        return $this->belongsTo(Media::class, "factsheet_id");
    }

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
