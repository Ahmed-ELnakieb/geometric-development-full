<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('location');
            $table->enum('type', ['villa', 'apartment', 'commercial', 'investment', 'mixed_use']);
            $table->enum('status', ['in_progress', 'completed', 'upcoming', 'on_hold'])->default('in_progress');
            $table->text('excerpt')->nullable();
            $table->longText('description');
            $table->foreignId('featured_image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('video_url', 500)->nullable();
            $table->foreignId('video_file_id')->nullable()->constrained('media')->nullOnDelete();
            $table->integer('total_units')->nullable();
            $table->decimal('property_size_min', 10, 2)->nullable();
            $table->decimal('property_size_max', 10, 2)->nullable();
            $table->date('completion_date')->nullable();
            $table->foreignId('brochure_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('factsheet_id')->nullable()->constrained('media')->nullOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->boolean('showcase')->default(true);
            $table->integer('showcase_order')->default(0);
            $table->integer('display_order')->default(0);
            $table->string('video_preview_url', 500)->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('type');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_published');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
