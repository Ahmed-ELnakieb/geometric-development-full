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
        // Set default JSON values for existing records
        \DB::statement("UPDATE `media` SET 
            `manipulations` = JSON_ARRAY() WHERE `manipulations` IS NULL");
        \DB::statement("UPDATE `media` SET 
            `custom_properties` = JSON_OBJECT() WHERE `custom_properties` IS NULL");
        \DB::statement("UPDATE `media` SET 
            `generated_conversions` = JSON_OBJECT() WHERE `generated_conversions` IS NULL");
        \DB::statement("UPDATE `media` SET 
            `responsive_images` = JSON_ARRAY() WHERE `responsive_images` IS NULL");
        
        // Alter columns to have defaults
        \DB::statement("ALTER TABLE `media` 
            MODIFY COLUMN `manipulations` JSON DEFAULT (JSON_ARRAY())");
        \DB::statement("ALTER TABLE `media` 
            MODIFY COLUMN `custom_properties` JSON DEFAULT (JSON_OBJECT())");
        \DB::statement("ALTER TABLE `media` 
            MODIFY COLUMN `generated_conversions` JSON DEFAULT (JSON_OBJECT())");
        \DB::statement("ALTER TABLE `media` 
            MODIFY COLUMN `responsive_images` JSON DEFAULT (JSON_ARRAY())");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse JSON defaults, skip
    }
};
