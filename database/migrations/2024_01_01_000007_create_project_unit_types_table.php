<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_unit_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('size_min', 10, 2)->nullable();
            $table->decimal('size_max', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('image_id')->nullable()->constrained('media')->nullOnDelete();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('project_id');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_unit_types');
    }
};
