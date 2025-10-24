<?php

return [
    'meta' => [
        'defaults' => [
            'title' => 'Geometric Development - Leading Engineering & Construction Company in Egypt',
            'titleBefore' => false,
            'description' => 'Geometric Development is a premier Saudi engineering and construction company providing innovative architectural design, civil works, infrastructure development, and project management solutions across Egypt and Emirates.',
            'separator' => ' | ',
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
            'canonical' => null,
            'robots' => 'index,follow',
        ],
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],
        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title' => 'Geometric Development - Leading Engineering & Construction Company',
            'description' => 'Premier Saudi engineering and construction company providing innovative solutions in Egypt and Emirates. Specializing in architectural design, civil works, and infrastructure development.',
            'url' => null,
            'type' => 'website',
            'site_name' => 'Geometric Development',
            'images' => [
                '/assets/img/logo/favicon.png'
            ],
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => '@GeometricDev',
            'creator' => '@GeometricDev',
            'title' => 'Geometric Development - Engineering Excellence',
            'description' => 'Leading Saudi engineering and construction company delivering innovative projects in Egypt and Emirates.',
            'image' => '/assets/img/logo/favicon.png',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Geometric Development',
            'url' => config('app.url'),
            'logo' => '/assets/img/logo/favicon.png',
            'description' => 'Leading Saudi engineering and construction company providing comprehensive solutions in Egypt and Emirates.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'EG',
                'addressRegion' => 'Egypt',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'availableLanguage' => ['English', 'Arabic']
            ],
            'sameAs' => [
                'https://www.facebook.com/GeometricDevelopment',
                'https://www.linkedin.com/company/geometric-development',
                'https://twitter.com/GeometricDev'
            ]
        ],
    ],
];