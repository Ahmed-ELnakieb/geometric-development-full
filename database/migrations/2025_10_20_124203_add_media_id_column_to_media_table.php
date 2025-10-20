<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add media_id to reference FilamentMediaManager media records
     */
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('id');
            $table->unsignedBigInteger('folder_id')->nullable()->after('media_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['media_id', 'folder_id']);
        });
    }
};
