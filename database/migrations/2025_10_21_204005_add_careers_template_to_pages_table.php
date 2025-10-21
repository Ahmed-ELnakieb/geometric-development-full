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
        // Modify template enum to include 'careers' and 'blog'
        \DB::statement("ALTER TABLE pages MODIFY COLUMN template ENUM('home', 'about', 'contact', 'careers', 'blog', 'custom') DEFAULT 'custom'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        \DB::statement("ALTER TABLE pages MODIFY COLUMN template ENUM('home', 'about', 'contact', 'custom') DEFAULT 'custom'");
    }
};
