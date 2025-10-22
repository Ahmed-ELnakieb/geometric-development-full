<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'route',
        'link_type',
        'type',
        'parent_id',
        'project_id',
        'order',
        'is_active',
        'open_in_new_tab',
        'icon',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Parent menu item relationship
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Children menu items relationship
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * Project relationship for project links
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class);
    }

    /**
     * Scope to get only navbar items
     */
    public function scopeNavbar($query)
    {
        return $query->where('type', 'navbar');
    }

    /**
     * Scope to get only footer items
     */
    public function scopeFooter($query)
    {
        return $query->where('type', 'footer');
    }

    /**
     * Scope to get only active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only parent items (no parent_id)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
