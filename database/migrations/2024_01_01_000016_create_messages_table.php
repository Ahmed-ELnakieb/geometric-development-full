<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['contact', 'project_inquiry', 'broker_registration', 'general'])->default('contact');
            $table->string('name');
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->enum('user_type', ['customer', 'broker', 'applicant', 'other'])->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $table->timestamp('replied_at')->nullable();
            $table->text('reply_message')->nullable();
            $table->string('messageable_type')->nullable();
            $table->unsignedBigInteger('messageable_id')->nullable();
            $table->string('source_url', 500)->nullable();
            $table->string('source_page')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('type');
            $table->index('status');
            $table->index(['messageable_type', 'messageable_id']);
            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
