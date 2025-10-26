<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
    protected $fillable = [
        'visitable_type',
        'visitable_id',
        'visitor_type',
        'visitor_id',
        'ip',
        'user_agent',
        'referer',
        'url',
        'method',
        'data',
        'is_admin',
    ];

    protected $casts = [
        'data' => 'array',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the visitable model (the model being visited)
     */
    public function visitable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the visitor model (usually User)
     */
    public function visitor(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for frontend visits only
     */
    public function scopeFrontend($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Scope for admin visits only
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope for visits within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for visits to specific URL
     */
    public function scopeUrl($query, $url)
    {
        return $query->where('url', $url);
    }
}
