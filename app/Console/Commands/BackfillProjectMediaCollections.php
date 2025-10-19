<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class BackfillProjectMediaCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:backfill-media
                            {--project=* : Specific project IDs to backfill}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill new media collections (hero_slider, hero_thumbnails, about_image) from existing gallery collection';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $projectIds = $this->option('project');

        $this->info('Starting media collection backfill...');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Get projects to process
        $query = Project::query();
        if (!empty($projectIds)) {
            $query->whereIn('id', $projectIds);
        }
        $projects = $query->get();

        $this->info("Processing {$projects->count()} projects...");

        $processedCount = 0;
        $skippedCount = 0;

        foreach ($projects as $project) {
            $this->line("\nProcessing project: {$project->title} (ID: {$project->id})");

            // Check if project already has new collections populated
            $hasHeroSlider = $project->getMedia('hero_slider')->count() > 0;
            $hasHeroThumbnails = $project->getMedia('hero_thumbnails')->count() > 0;
            $hasAboutImage = $project->getMedia('about_image')->count() > 0;

            if ($hasHeroSlider && $hasHeroThumbnails && $hasAboutImage) {
                $this->info("  → Skipping - All new collections already populated");
                $skippedCount++;
                continue;
            }

            // Get existing gallery images
            $galleryImages = $project->getMedia('gallery');

            if ($galleryImages->isEmpty()) {
                $this->warn("  → No gallery images found to migrate");
                $skippedCount++;
                continue;
            }

            $this->info("  → Found {$galleryImages->count()} gallery images");

            // Backfill hero_slider (first 4 images from gallery)
            if (!$hasHeroSlider) {
                $heroSliderImages = $galleryImages->take(4);
                $this->line("  → Copying {$heroSliderImages->count()} images to hero_slider");

                if (!$isDryRun) {
                    foreach ($heroSliderImages as $image) {
                        try {
                            // Copy the media to the new collection
                            $project->addMedia($image->getPath())
                                ->preservingOriginal()
                                ->usingName($image->name)
                                ->usingFileName($image->file_name)
                                ->toMediaCollection('hero_slider');
                        } catch (\Exception $e) {
                            $this->error("    Error copying image {$image->name}: " . $e->getMessage());
                        }
                    }
                }
            } else {
                $this->line("  → hero_slider already populated, skipping");
            }

            // Backfill hero_thumbnails (same first 4 images, or generate from hero_slider)
            if (!$hasHeroThumbnails) {
                // If hero_slider was just populated or exists, use those images
                $heroSliderCollection = $hasHeroSlider
                    ? $project->getMedia('hero_slider')
                    : $galleryImages->take(4);

                $this->line("  → Copying {$heroSliderCollection->count()} images to hero_thumbnails");

                if (!$isDryRun) {
                    foreach ($heroSliderCollection as $image) {
                        try {
                            // Copy the media to the new collection
                            $project->addMedia($image->getPath())
                                ->preservingOriginal()
                                ->usingName($image->name)
                                ->usingFileName($image->file_name)
                                ->toMediaCollection('hero_thumbnails');
                        } catch (\Exception $e) {
                            $this->error("    Error copying thumbnail {$image->name}: " . $e->getMessage());
                        }
                    }
                }
            } else {
                $this->line("  → hero_thumbnails already populated, skipping");
            }

            // Backfill about_image (first image from gallery or 5th if first 4 used for hero)
            if (!$hasAboutImage) {
                // Use the 5th image if available, otherwise use the first
                $aboutImage = $galleryImages->count() > 4
                    ? $galleryImages->skip(4)->first()
                    : $galleryImages->first();

                if ($aboutImage) {
                    $this->line("  → Copying image to about_image");

                    if (!$isDryRun) {
                        try {
                            $project->addMedia($aboutImage->getPath())
                                ->preservingOriginal()
                                ->usingName($aboutImage->name)
                                ->usingFileName($aboutImage->file_name)
                                ->toMediaCollection('about_image');
                        } catch (\Exception $e) {
                            $this->error("    Error copying about image: " . $e->getMessage());
                        }
                    }
                } else {
                    $this->warn("  → No image available for about_image");
                }
            } else {
                $this->line("  → about_image already populated, skipping");
            }

            $processedCount++;
            $this->info("  → Project processing complete");
        }

        $this->newLine();
        $this->info("Backfill complete!");
        $this->info("Processed: {$processedCount} projects");
        $this->info("Skipped: {$skippedCount} projects");

        if ($isDryRun) {
            $this->warn('This was a dry run - no changes were made. Run without --dry-run to apply changes.');
        }

        return Command::SUCCESS;
    }
}
