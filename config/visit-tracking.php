<?php

return [
    /*
     * Enable or disable visit tracking globally
     */
    'enabled' => env('VISIT_TRACKING_ENABLED', true),

    /*
     * Track frontend visits
     */
    'track_frontend' => env('VISIT_TRACKING_FRONTEND', true),

    /*
     * Track admin panel visits
     * Note: Routes starting with 'admin/' are always excluded regardless of this setting
     */
    'track_admin' => env('VISIT_TRACKING_ADMIN', false),

    /*
     * Exclude specific routes from tracking
     * Note: Routes starting with 'admin/' and system routes are automatically excluded
     */
    'excluded_routes' => [
        'api/*',
        // System routes are automatically excluded: livewire/*, manifest.json, sw.js, etc.
    ],

    /*
     * Exclude specific IPs from tracking
     */
    'excluded_ips' => [
        // '127.0.0.1',
        // '::1',
    ],

    /*
     * Exclude specific user agents from tracking
     */
    'excluded_user_agents' => [
        'bot',
        'crawler',
        'spider',
        'scraper',
    ],

    /*
     * Data retention period in days (0 = keep forever)
     */
    'retention_days' => env('VISIT_TRACKING_RETENTION_DAYS', 365),

    /*
     * Use queue for visit recording (recommended for high traffic)
     */
    'use_queue' => env('VISIT_TRACKING_USE_QUEUE', false),

    /*
     * Queue name for visit recording jobs
     */
    'queue_name' => env('VISIT_TRACKING_QUEUE', 'default'),

    /*
     * Models that can be tracked for visits
     */
    'trackable_models' => [
        App\Models\Page::class,
        App\Models\BlogPost::class,
        App\Models\Project::class,
    ],
];