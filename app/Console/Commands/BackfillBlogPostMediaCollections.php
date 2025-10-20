<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackfillBlogPostMediaCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:backfill-media
                            {--post=* : Specific blog post IDs to backfill}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate blog post featured images to Spatie Media Library collections';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $postIds = $this->option('post');

        $this->info('Starting blog post media collection backfill...');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        // Get blog posts to process
        $query = BlogPost::whereNotNull('featured_image_id');
        if (!empty($postIds)) {
            $query->whereIn('id', $postIds);
        }
        $posts = $query->get();

        $this->info("Processing {$posts->count()} blog posts...");

        $processedCount = 0;
        $skippedCount = 0;

        foreach ($posts as $post) {
            $this->line("\nProcessing post: {$post->title} (ID: {$post->id})");

            // Check if post already has featured_image collection populated
            $hasFeaturedImage = $post->getMedia('featured_image')->count() > 0;

            if ($hasFeaturedImage) {
                $this->info("  → Skipping - Featured image collection already populated");
                $skippedCount++;
                continue;
            }

            // Get the legacy featured image
            $media = $post->featuredImage;

            if (!$media) {
                $this->warn("  → Legacy featured image not found");
                $skippedCount++;
                continue;
            }

            $this->info("  → Migrating featured image: {$media->name}");

            if (!$isDryRun) {
                try {
                    // Copy the media to the new collection using stream to support non-local disks
                    $post->addMediaFromStream(Storage::disk($media->disk)->readStream($media->getPath()))
                        ->usingFileName($media->file_name)
                        ->usingName($media->name)
                        ->toMediaCollection('featured_image');
                } catch (\Exception $e) {
                    $this->error("    Error migrating image {$media->name}: " . $e->getMessage());
                    continue;
                }
            }

            $processedCount++;
            $this->info("  → Post processing complete");
        }

        $this->newLine();
        $this->info("Backfill complete!");
        $this->info("Processed: {$processedCount} posts");
        $this->info("Skipped: {$skippedCount} posts");

        if ($isDryRun) {
            $this->warn('This was a dry run - no changes were made. Run without --dry-run to apply changes.');
        }

        $this->info('Review the results and run the migration to drop the featured_image_id column.');

        return Command::SUCCESS;
    }
}