<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop legacy media FK columns that are no longer used.
     * The admin form now uses Spatie Media Library collections instead.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['featured_image_id']);
            $table->dropForeign(['video_file_id']);
            $table->dropForeign(['brochure_id']);
            $table->dropForeign(['factsheet_id']);
            
            // Drop the columns
            $table->dropColumn([
                'featured_image_id',
                'video_file_id',
                'brochure_id',
                'factsheet_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Restore the columns
            $table->foreignId('featured_image_id')->nullable()->after('description')->constrained('media')->nullOnDelete();
            $table->foreignId('video_file_id')->nullable()->after('video_url')->constrained('media')->nullOnDelete();
            $table->foreignId('brochure_id')->nullable()->after('completion_date')->constrained('media')->nullOnDelete();
            $table->foreignId('factsheet_id')->nullable()->after('brochure_id')->constrained('media')->nullOnDelete();
        });
    }
};
