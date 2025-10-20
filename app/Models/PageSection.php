<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * PageSection Model
 * Stores dynamic homepage section content
 */
class PageSection extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'page_id',
        'section_key',
        'section_title',
        'content',
        'order',
        'is_active',
    ];
    
    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];
    
    /**
     * Relationship to Page
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
    
    /**
     * Register media collections for section images
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_background')->singleFile();
        $this->addMediaCollection('hero_images');
        $this->addMediaCollection('about_images');
        $this->addMediaCollection('video_poster')->singleFile();
        $this->addMediaCollection('services_images');
        $this->addMediaCollection('showcase_images');
        $this->addMediaCollection('gallery_images');
    }
    
    /**
     * Get content value by key
     */
    public function get(string $key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }
    
    /**
     * Set content value by key
     */
    public function set(string $key, $value): self
    {
        $content = $this->content ?? [];
        data_set($content, $key, $value);
        $this->content = $content;
        return $this;
    }
}
