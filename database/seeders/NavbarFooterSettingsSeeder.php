<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class NavbarFooterSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Logos
            [
                'key' => 'logo_dark',
                'value' => 'assets/img/logo/logo_dark.png',
                'type' => 'text',
                'group' => 'branding',
            ],
            [
                'key' => 'logo_light',
                'value' => 'assets/img/logo/logo_light.png',
                'type' => 'text',
                'group' => 'branding',
            ],
            
            // Navbar Button
            [
                'key' => 'navbar_button_text_1',
                'value' => 'request a quote',
                'type' => 'text',
                'group' => 'navbar',
            ],
            [
                'key' => 'navbar_button_text_2',
                'value' => 'Contact us',
                'type' => 'text',
                'group' => 'navbar',
            ],
            [
                'key' => 'navbar_button_link',
                'value' => 'contact.index',
                'type' => 'text',
                'group' => 'navbar',
            ],
            
            // Contact Info Labels
            [
                'key' => 'contact_email_label',
                'value' => 'Email',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@geometric-development.com',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone_label',
                'value' => 'phone',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address_label',
                'value' => 'Address',
                'type' => 'text',
                'group' => 'contact',
            ],
            
            // Contact Info
            [
                'key' => 'phone_1',
                'value' => '+20 127 2777919',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'phone_1_whatsapp',
                'value' => '201272777919',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'phone_2',
                'value' => '+20 120 0111338',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'phone_2_whatsapp',
                'value' => '201200111338',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'address',
                'value' => '6 October - Sheikh Zayed',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'address_map_url',
                'value' => 'https://maps.google.com/?q=6+October+Sheikh+Zayed+Egypt',
                'type' => 'text',
                'group' => 'contact',
            ],
            
            // Footer
            [
                'key' => 'footer_bg_image',
                'value' => 'assets/img/footer/f4-bg-1.png',
                'type' => 'image',
                'group' => 'footer',
            ],
            [
                'key' => 'footer_bg_color',
                'value' => '#1a1a1a',
                'type' => 'text',
                'group' => 'footer',
            ],
            [
                'key' => 'footer_text_color',
                'value' => '#ffffff',
                'type' => 'text',
                'group' => 'footer',
            ],
            [
                'key' => 'footer_copyright_text',
                'value' => 'Geometric-Development',
                'type' => 'text',
                'group' => 'footer',
            ],
            [
                'key' => 'footer_contact_title',
                'value' => 'contact us',
                'type' => 'text',
                'group' => 'footer',
            ],
            
            // Social Media
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/geometric-development',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/geometric-development',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/geometric-development',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/company/geometric-development',
                'type' => 'text',
                'group' => 'social',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
