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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('visitable'); // For model associations
            $table->nullableMorphs('visitor'); // For user associations
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('url');
            $table->string('method')->default('GET');
            $table->json('data')->nullable(); // For additional metadata
            $table->boolean('is_admin')->default(false); // Distinguish admin vs frontend
            $table->timestamps();
            
            // Indexes for better query performance (morphs already create their own indexes)
            $table->index(['created_at']);
            $table->index(['url']);
            $table->index(['is_admin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
