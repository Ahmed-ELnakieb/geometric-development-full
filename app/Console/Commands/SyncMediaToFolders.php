<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use TomatoPHP\FilamentMediaManager\Models\Folder;
use TomatoPHP\FilamentMediaManager\Models\Media as ManagerMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SyncMediaToFolders extends Command
{
    protected $signature = 'media:sync-manager';
    protected $description = 'Link all Spatie Media Library records to Filament Media Manager folders';

    public function handle(): int
    {
        try {
            $root = Folder::firstOrCreate([
                'name' => 'Root',
                'slug' => 'root',
                'disk' => config('media-library.disk_name', 'public'),
            ]);
        } catch (\Exception $e) {
            $this->error("Failed to create or retrieve root folder: " . $e->getMessage());
            Log::error("SyncMediaToFolders: Root folder creation failed", ['error' => $e->getMessage()]);
            return self::FAILURE;
        }

        $totalMedia = Media::count();
        if ($totalMedia === 0) {
            $this->info("No media records found to sync.");
            return self::SUCCESS;
        }

        $this->info("Starting sync of {$totalMedia} media items...");
        $bar = $this->output->createProgressBar($totalMedia);
        $bar->start();

        $count = 0;
        $skipped = 0;
        $errors = 0;

        Media::chunk(100, function ($chunk) use ($root, &$count, &$skipped, &$errors, $bar) {
            foreach ($chunk as $media) {
                // Validation checks
                if (empty($media->collection_name)) {
                    $this->warn("Skipping media ID {$media->id}: collection_name is empty.");
                    Log::warning("SyncMediaToFolders: Skipped media ID {$media->id} due to empty collection_name");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                if (!Storage::disk($media->disk)->exists($media->id . '/' . $media->file_name)) {
                    $this->warn("Skipping media ID {$media->id}: file does not exist on disk '{$media->disk}'.");
                    Log::warning("SyncMediaToFolders: Skipped media ID {$media->id} due to missing file on disk '{$media->disk}'");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $validDisks = array_keys(config('filesystems.disks', []));
                if (!in_array($media->disk, $validDisks)) {
                    $this->warn("Skipping media ID {$media->id}: invalid disk '{$media->disk}'.");
                    Log::warning("SyncMediaToFolders: Skipped media ID {$media->id} due to invalid disk '{$media->disk}'");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                try {
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
                } catch (\Exception $e) {
                    $this->error("Failed to sync media ID {$media->id}: " . $e->getMessage());
                    Log::error("SyncMediaToFolders: Failed to sync media ID {$media->id}", ['error' => $e->getMessage()]);
                    $errors++;
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();

        $this->info("Synced {$count} media items to Filament Media Manager.");
        if ($skipped > 0) {
            $this->warn("Skipped {$skipped} invalid media items.");
        }
        if ($errors > 0) {
            $this->error("Encountered {$errors} errors during sync. Check logs for details.");
        }

        return ($errors > 0) ? self::FAILURE : self::SUCCESS;
    }
}
