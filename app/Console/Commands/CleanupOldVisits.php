<?php

namespace App\Console\Commands;

use App\Models\Visit;
use App\Models\VisitTrackingSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupOldVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:cleanup {--days= : Number of days to keep (overrides config)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old visit records based on retention policy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $retentionDays = $this->option('days') ?? VisitTrackingSetting::get('retention_days', config('visit-tracking.retention_days', 365));

        if ($retentionDays <= 0) {
            $this->info('Retention is set to keep visits forever. No cleanup performed.');

            return 0;
        }

        $cutoffDate = Carbon::now()->subDays($retentionDays);

        $this->info("Cleaning up visits older than {$retentionDays} days (before {$cutoffDate->format('Y-m-d H:i:s')})...");

        $count = Visit::where('created_at', '<', $cutoffDate)->count();

        if ($count === 0) {
            $this->info('No old visits found to clean up.');

            return 0;
        }

        if ($this->confirm("This will delete {$count} visit records. Continue?")) {
            $deleted = Visit::where('created_at', '<', $cutoffDate)->delete();

            $this->info("Successfully deleted {$deleted} old visit records.");

            // Optimize the table after deletion
            $this->info('Optimizing visits table...');
            \DB::statement('OPTIMIZE TABLE visits');

            $this->info('Cleanup completed successfully.');
        } else {
            $this->info('Cleanup cancelled.');
        }

        return 0;
    }
}
