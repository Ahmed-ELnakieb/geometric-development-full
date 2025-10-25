<?php
return [
    'datetime_format' => 'd/m/Y H:i:s',
    'date_format' => 'd/m/Y',

    'activity_resource' => \App\Filament\Resources\ActivityLogResource::class,
	'scoped_to_tenant' => false,
	'navigation_sort' => 99,

    'resources' => [
        'enabled' => false, // Disabled to prevent infinite loops
        'log_name' => 'Resource',
        'logger' => \Z3d0X\FilamentLogger\Loggers\ResourceLogger::class,
        'color' => 'success',
		
        'exclude' => [
            //App\Filament\Resources\UserResource::class,
            \App\Filament\Resources\ActivityLogResource::class,
        ],
        'cluster' => null,
        'navigation_group' => null,
    ],

    'access' => [
        'enabled' => true,
        'logger' => \Z3d0X\FilamentLogger\Loggers\AccessLogger::class,
        'color' => 'danger',
        'log_name' => 'Access',
    ],

    'notifications' => [
        'enabled' => true,
        'logger' => \Z3d0X\FilamentLogger\Loggers\NotificationLogger::class,
        'color' => null,
        'log_name' => 'Notification',
    ],

    'models' => [
        'enabled' => false, // Disabled to prevent infinite loops
        'log_name' => 'Model',
        'color' => 'warning',
        'logger' => \Z3d0X\FilamentLogger\Loggers\ModelLogger::class,
        'register' => [
            \App\Models\User::class,
            \App\Models\Page::class,
            \App\Models\Project::class,
            \App\Models\BlogPost::class,
            \App\Models\Career::class,
            \App\Models\Setting::class,
            \App\Models\Message::class,
            \App\Models\CareerApplication::class,
            // Exclude Activity model to prevent infinite loop
            // \Spatie\Activitylog\Models\Activity::class,
        ],
    ],

    'custom' => [
        // [
        //     'log_name' => 'Custom',
        //     'color' => 'primary',
        // ]
    ],
];
