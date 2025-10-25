<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Add indexes using raw SQL to avoid errors if they already exist
            DB::statement('CREATE INDEX IF NOT EXISTS activity_log_created_at_index ON activity_log(created_at)');
            DB::statement('CREATE INDEX IF NOT EXISTS activity_log_log_name_created_at_index ON activity_log(log_name, created_at)');
            DB::statement('CREATE INDEX IF NOT EXISTS activity_log_subject_index ON activity_log(subject_type, subject_id)');
            
            // Optimize table
            DB::statement('OPTIMIZE TABLE activity_log');
        } catch (\Exception $e) {
            // Indexes might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('DROP INDEX IF EXISTS activity_log_created_at_index ON activity_log');
            DB::statement('DROP INDEX IF EXISTS activity_log_log_name_created_at_index ON activity_log');
            DB::statement('DROP INDEX IF EXISTS activity_log_subject_index ON activity_log');
        } catch (\Exception $e) {
            // Ignore errors
        }
    }
};
