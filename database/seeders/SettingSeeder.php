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
    }
}
