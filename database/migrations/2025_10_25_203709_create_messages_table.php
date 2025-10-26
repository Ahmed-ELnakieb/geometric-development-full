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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('conversation_id');
            $table->string('whatsapp_message_id')->nullable();
            $table->enum('direction', ['inbound', 'outbound']);
            $table->enum('sender_type', ['visitor', 'agent', 'system']);
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->enum('message_type', ['text', 'image', 'document', 'template'])->default('text');
            $table->text('content');
            $table->string('media_url', 500)->nullable();
            $table->enum('status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('conversation_id', 'idx_chat_msg_conversation');
            $table->index('status', 'idx_chat_msg_status');
            $table->index('whatsapp_message_id', 'idx_chat_msg_whatsapp_id');
            
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
