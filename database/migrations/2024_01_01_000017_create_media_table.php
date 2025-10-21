<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id')->nullable(); // FilamentMediaManager reference
            $table->unsignedBigInteger('folder_id')->nullable(); // Folder reference
            $table->string('model_type')->nullable(); // Nullable morphs
            $table->unsignedBigInteger('model_id')->nullable(); // Nullable morphs
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk')->default('public');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
            
            // Indexes
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
