<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('blog_posts', 'featured_image_id')) {
            Schema::table('blog_posts', function (Blueprint $table) {
                // Drop foreign key if it exists
                try {
                    $table->dropForeign(['featured_image_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue to drop column
                }
                $table->dropColumn('featured_image_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignId('featured_image_id')->nullable()->after('content');
            $table->foreign('featured_image_id')->references('id')->on('media')->nullOnDelete();
        });
    }
};