<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Default SEO Configuration
    |--------------------------------------------------------------------------
    */
    
    'defaults' => [
        'title' => 'Geometric Development - Leading Engineering & Construction Company in Egypt',
        'description' => 'Geometric Development is a premier Saudi engineering and construction company providing innovative architectural design, civil works, infrastructure development, and project management solutions across Egypt and Emirates.',
        'keywords' => [
            'geometric development',
            'engineering company egypt',
            'construction company egypt',
            'architectural design egypt',
            'civil engineering egypt',
            'infrastructure development',
            'project management egypt',
            'real estate development',
            'construction services',
            'building contractors egypt',
            'engineering consultancy',
            'residential projects egypt',
            'commercial construction',
            'saudi engineering company',
            'ras el hekma projects',
            'egypt construction',
            'architectural services',
            'civil works egypt',
            'infrastructure projects',
            'construction management'
        ],
        'image' => '/assets/img/logo/favicon.png',
        'url' => env('APP_URL'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Organization Information
    |--------------------------------------------------------------------------
    */
    
    'organization' => [
        'name' => 'Geometric Development',
        'legal_name' => 'Geometric Development Company',
        'url' => env('APP_URL'),
        'logo' => '/assets/img/logo/favicon.png',
        'description' => 'Leading Saudi engineering and construction company providing comprehensive solutions in Egypt and Emirates.',
        'founding_date' => '2010',
        'employees' => '50-100',
        'industry' => 'Construction and Engineering',
        'address' => [
            'country' => 'Egypt',
            'region' => 'Cairo',
        ],
        'contact' => [
            'type' => 'customer service',
            'languages' => ['English', 'Arabic']
        ],
        'social_media' => [
            'facebook' => 'https://www.facebook.com/GeometricDevelopment',
            'linkedin' => 'https://www.linkedin.com/company/geometric-development',
            'twitter' => 'https://twitter.com/GeometricDev',
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Social Media Configuration
    |--------------------------------------------------------------------------
    */
    
    'social' => [
        'twitter' => [
            'site' => '@GeometricDev',
            'creator' => '@GeometricDev',
        ],
        'facebook' => [
            'app_id' => env('FACEBOOK_APP_ID'),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Analytics Configuration
    |--------------------------------------------------------------------------
    */
    
    'analytics' => [
        'google' => [
            'tracking_id' => env('GOOGLE_ANALYTICS_ID'),
            'tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID'),
        ],
        'facebook' => [
            'pixel_id' => env('FACEBOOK_PIXEL_ID'),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Webmaster Tools
    |--------------------------------------------------------------------------
    */
    
    'webmaster' => [
        'google' => env('GOOGLE_SITE_VERIFICATION'),
        'bing' => env('BING_SITE_VERIFICATION'),
        'yandex' => env('YANDEX_SITE_VERIFICATION'),
        'pinterest' => env('PINTEREST_SITE_VERIFICATION'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    */
    
    'sitemap' => [
        'cache_duration' => 60 * 24, // 24 hours in minutes
        'exclude_routes' => [
            'admin.*',
            'filament.*',
            'api.*',
            'pwa-test',
            'notifications.*',
        ],
    ],
    
];