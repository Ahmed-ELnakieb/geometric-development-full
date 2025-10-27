<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Group
        Setting::set('site_name', 'Geometric Development', 'text', 'general');
        Setting::set('site_tagline', 'Leading Community Developer in Ras Al Khaimah', 'text', 'general');
        Setting::set('contact_email', 'info@geometric-development.com', 'text', 'general');
        Setting::set('contact_phone', '+20 127 2777919', 'text', 'general');
        Setting::set('contact_address', '6 October - Sheikh Zayed, Egypt', 'text', 'general');

        // Social Group
        Setting::set('facebook_url', '', 'text', 'social');
        Setting::set('instagram_url', '', 'text', 'social');
        Setting::set('twitter_url', '', 'text', 'social');
        Setting::set('linkedin_url', '', 'text', 'social');

        // SEO Group
        Setting::set('default_meta_title', 'Geometric Development - Real Estate in Egypt & Emirates', 'text', 'seo');
        Setting::set('default_meta_description', 'Leading Saudi real estate company...', 'textarea', 'seo');
        Setting::set('google_analytics_id', '', 'text', 'seo');

        // Email Group
        Setting::set('admin_notification_email', 'admin@geometric-development.com', 'text', 'email');
        Setting::set('mail_from_name', 'Geometric Development', 'text', 'email');

        // Preloader Group
        Setting::set('preloader_enabled', '1', 'text', 'preloader');
        Setting::set('preloader_use_logo', '1', 'text', 'preloader');
        Setting::set('preloader_main_text', 'GEOMETRIC DEVELOPMENT', 'text', 'preloader');
        Setting::set('preloader_sub_text', 'LEADING COMMUNITY DEVELOPER IN MUROJ, RICH HILLS', 'text', 'preloader');
        Setting::set('preloader_background_type', 'color', 'text', 'preloader');
        Setting::set('preloader_background_color', '#060606', 'text', 'preloader');
        Setting::set('preloader_background_image', '', 'image', 'preloader');
        Setting::set('preloader_custom_image', '', 'image', 'preloader');
    }
}
