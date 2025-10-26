<?php

namespace App\Models;

use App\Traits\Visitable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, HasSlug, InteractsWithMedia, Visitable;

    protected $fillable = [
        'title',
        'slug',
        'template',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sections',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'sections' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function ogImage()
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }
    
    public function pageSections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }
    
    // Get section by key
    public function getSection(string $key)
    {
        return $this->pageSections()->where('section_key', $key)->first();
    }
    
    // Get or create section
    public function getOrCreateSection(string $key, array $default = [])
    {
        return $this->pageSections()->firstOrCreate(
            ['section_key' => $key],
            ['content' => $default, 'is_active' => true]
        );
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeTemplate($query, $template)
    {
        return $query->where('template', $template);
    }

    // Methods
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image')->singleFile();
        $this->addMediaCollection('sections');
    }

    public function isPublished(): bool
    {
        return $this->is_published;
    }

    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
        ]);
    }
}
