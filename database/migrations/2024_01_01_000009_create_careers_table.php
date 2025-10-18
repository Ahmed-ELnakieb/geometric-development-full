<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('location');
            $table->enum('job_type', ['full_time', 'part_time', 'contract', 'internship']);
            $table->string('salary_range')->nullable();
            $table->string('working_days')->nullable();
            $table->text('overview');
            $table->longText('responsibilities');
            $table->longText('requirements')->nullable();
            $table->longText('benefits')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('expires_at')->nullable();
            $table->integer('display_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('is_active');
            $table->index('expires_at');
            $table->index('is_featured');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
