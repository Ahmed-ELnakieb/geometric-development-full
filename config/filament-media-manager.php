<?php

return [
    "model" => [
        "folder" => \TomatoPHP\FilamentMediaManager\Models\Folder::class,
        "media" => \Spatie\MediaLibrary\MediaCollections\Models\Media::class,
    ],

    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'video/mp4',
        'video/webm',
        'video/quicktime',
        'video/x-msvideo',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ],

    'max_file_size' => 102400, // 100MB in KB, aligning with config/media-library.php

    "api" => [
        "active" => false,
        "middlewares" => [
            "api",
            "auth:sanctum"
        ],
        "prefix" => "api/media-manager",
        "resources" => [
            "folders" => \TomatoPHP\FilamentMediaManager\Http\Resources\FoldersResource::class,
            "folder" => \TomatoPHP\FilamentMediaManager\Http\Resources\FolderResource::class,
            "media" => \TomatoPHP\FilamentMediaManager\Http\Resources\MediaResource::class
        ]
    ],

    "user" => [
        "model" => \App\Models\User::class,
        "column_name" => "name",
    ],

    "navigation_sort" => 0,

    'features' => [
        'folders' => true,
        'user_access' => false,
        'password_protection' => false,
    ],
];
