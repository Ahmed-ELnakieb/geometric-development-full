<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->text('cover_letter')->nullable();
            $table->foreignId('cv_file_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('portfolio_url', 500)->nullable();
            $table->enum('status', ['new', 'reviewing', 'shortlisted', 'interviewed', 'hired', 'rejected'])->default('new');
            $table->text('notes')->nullable();
            $table->string('source_url', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('career_id');
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};
