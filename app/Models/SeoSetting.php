<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'site_title',
        'site_description',
        'site_keywords',
        'facebook_url',
        'twitter_handle',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'google_analytics_id',
        'google_site_verification',
    ];

    protected $casts = [
        'site_keywords' => 'array',
    ];
}