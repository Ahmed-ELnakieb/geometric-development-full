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
        Schema::create('conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('visitor_session_id');
            $table->string('whatsapp_phone_number', 20);
            $table->string('visitor_phone_number', 20)->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->enum('status', ['active', 'waiting', 'closed'])->default('active');
            $table->tinyInteger('priority')->default(1);
            $table->string('source', 100)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_conv_status');
            $table->index('agent_id', 'idx_conv_agent');
            $table->index('visitor_session_id', 'idx_conv_visitor_session');
            
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
