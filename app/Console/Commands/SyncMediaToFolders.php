<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TomatoPHP\FilamentMediaManager\Models\Folder;
use TomatoPHP\FilamentMediaManager\Models\Media as ManagerMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SyncMediaToFolders extends Command
{
    protected $signature = 'media:sync-manager';
    protected $description = 'Link all Spatie Media Library records to Filament Media Manager folders';

    public function handle(): int
    {
        $root = Folder::firstOrCreate([
            'name' => 'Root',
            'slug' => 'root',
            'disk' => config('media-library.disk_name', 'public'),
        ]);

        $count = 0;
        Media::chunk(100, function ($chunk) use ($root, &$count) {
            foreach ($chunk as $media) {
                $created = ManagerMedia::firstOrCreate(
                    ['media_id' => $media->id],
                    [
                        'folder_id' => $root->id,
                        'disk' => $media->disk,
                        'collection_name' => $media->collection_name,
                        'file_name' => $media->file_name,
                        'name' => $media->name,
                        'uuid' => $media->uuid,
                    ]
                );
                if ($created->wasRecentlyCreated) {
                    $count++;
                }
            }
        });

        $this->info("Synced {$count} media items to Filament Media Manager.");
        return self::SUCCESS;
    }
}
