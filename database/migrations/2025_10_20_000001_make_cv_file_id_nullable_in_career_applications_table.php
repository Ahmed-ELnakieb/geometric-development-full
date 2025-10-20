<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make cv_file_id nullable and change FK to nullOnDelete
     */
    public function up(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            // Drop existing foreign key constraint
            try {
                $table->dropForeign(['cv_file_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist or have different name
            }

            // Make column nullable
            $table->foreignId('cv_file_id')->nullable()->change();

            // Re-add foreign key with nullOnDelete
            $table->foreign('cv_file_id')
                ->references('id')
                ->on('media')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            // Drop the nullable foreign key
            try {
                $table->dropForeign(['cv_file_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }

            // Make column NOT NULL again (warning: this will fail if any nulls exist)
            $table->foreignId('cv_file_id')->nullable(false)->change();

            // Re-add foreign key with cascadeOnDelete
            $table->foreign('cv_file_id')
                ->references('id')
                ->on('media')
                ->cascadeOnDelete();
        });
    }
};
