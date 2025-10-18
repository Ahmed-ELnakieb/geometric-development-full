<?php

// Script to temporarily fix LogsActivity references in models

$files = [
    'app/Models/Message.php',
    'app/Models/Career.php',
    'app/Models/CareerApplication.php',
    'app/Models/BlogComment.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Comment out imports
        $content = str_replace('use Spatie\Activitylog\LogOptions;', '// use Spatie\Activitylog\LogOptions;', $content);
        $content = str_replace('use Spatie\Activitylog\LogsActivity;', '// use Spatie\Activitylog\LogsActivity;', $content);
        
        // Remove trait usage
        $content = preg_replace('/,\s*LogsActivity/', '', $content);
        $content = str_replace('LogsActivity, ', '', $content);
        $content = str_replace('use LogsActivity;', 'use LogsActivity;', $content); // Keep if it's the only trait
        $content = str_replace('LogsActivity;', '// LogsActivity;', $content);
        
        // Comment out getActivitylogOptions methods
        $content = preg_replace('/public function getActivitylogOptions\(\): LogOptions\s*\{[^}]+\}/s', '// public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly([])
    //         ->logOnlyDirty();
    // }', $content);
        
        file_put_contents($file, $content);
        echo "Fixed: $file\n";
    }
}

echo "Done!\n";
