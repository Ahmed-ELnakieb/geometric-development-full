<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PWA Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Progressive Web App features.
    |
    */

    'name' => env('PWA_NAME', 'Geometric Development'),
    'short_name' => env('PWA_SHORT_NAME', 'Geometric'),
    'description' => env('PWA_DESCRIPTION', 'Leading Saudi real estate company providing comprehensive residential and commercial solutions in Egypt and Emirates'),
    'theme_color' => env('PWA_THEME_COLOR', '#1a1a1a'),
    'background_color' => env('PWA_BACKGROUND_COLOR', '#1a1a1a'),
    'display' => env('PWA_DISPLAY', 'standalone'),
    'orientation' => env('PWA_ORIENTATION', 'portrait-primary'),
    'start_url' => env('PWA_START_URL', '/'),
    'scope' => env('PWA_SCOPE', '/'),

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Define which routes and assets should be cached for offline access.
    |
    */

    'cache' => [
        'routes' => [
            '/',
        ],
        'assets' => [
            '/assets/css/bootstrap.min.css',
            '/assets/css/main.css',
            '/assets/js/jquery-3.7.1.min.js',
            '/assets/js/bootstrap.bundle.min.js',
            '/assets/js/main.js',
            '/assets/img/logo/favicon.png',
            '/manifest.json',
        ],
        'exclude_patterns' => [
            '/admin/*',
            '/filament/*',
            '/api/*',
            '*.php',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Background Sync Configuration
    |--------------------------------------------------------------------------
    |
    | Configure background sync functionality for offline actions.
    |
    */

    'background_sync' => [
        'enabled' => env('PWA_SYNC_ENABLED', true),
        'max_retries' => env('PWA_SYNC_MAX_RETRIES', 3),
        'retry_delay' => env('PWA_SYNC_RETRY_DELAY', 1000),
        'queue_limit' => env('PWA_SYNC_QUEUE_LIMIT', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notifications Configuration
    |--------------------------------------------------------------------------
    |
    | Configure push notification functionality.
    |
    */

    'push_notifications' => [
        'enabled' => env('PWA_PUSH_ENABLED', true),
        'vapid' => [
            'public_key' => env('PWA_VAPID_PUBLIC_KEY'),
            'private_key' => env('PWA_VAPID_PRIVATE_KEY'),
            'subject' => env('PWA_VAPID_SUBJECT', 'mailto:admin@geometric-development.com'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Icons Configuration
    |--------------------------------------------------------------------------
    |
    | Define the icons for different sizes and purposes.
    |
    */

    'icons' => [
        [
            'src' => '/assets/img/logo/favicon.png',
            'sizes' => '192x192',
            'type' => 'image/png',
            'purpose' => 'any maskable'
        ],
        [
            'src' => '/assets/img/logo/favicon.png',
            'sizes' => '512x512',
            'type' => 'image/png',
            'purpose' => 'any maskable'
        ],
    ],
];