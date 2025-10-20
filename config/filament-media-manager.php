<?php

return [
    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'video/mp4',
        'video/quicktime',
        'video/x-msvideo',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ],

    'max_file_size' => 102400, // 100MB in KB, aligning with config/media-library.php

    'api' => [
        'active' => false,
    ],

    'features' => [
        'folders' => true,
        'user_access' => false,
        'password_protection' => false,
    ],
];