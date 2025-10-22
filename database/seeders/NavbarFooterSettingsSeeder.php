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
            
            // Contact Info (if not exists)
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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
