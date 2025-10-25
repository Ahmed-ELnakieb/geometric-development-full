<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class ContactPageSeeder extends Seeder
{
    /**
     * Seed the contact page with all sections
     */
    public function run(): void
    {
        $contactPage = Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'template' => 'contact',
                'is_published' => true,
                'published_at' => now(),
                'meta_title' => 'Contact Us - Geometric Development | Get in Touch',
                'meta_description' => 'Contact Geometric Development for inquiries about our real estate projects. Find your perfect property today with Egypt\'s leading developer.',
                'sections' => $this->getContactPageSections(),
            ]
        );

        $this->command->info("✅ Contact page created/updated successfully!");
        $this->command->info("📄 Page ID: {$contactPage->id}");
    }

    /**
     * Get all contact page sections data
     */
    private function getContactPageSections(): array
    {
        return [
            // BREADCRUMB SECTION
            'breadcrumb' => [
                'is_active' => true,
                'page_title' => 'Contact Us',
                'background_image' => 'assets/img/hero/hero.png', // Header background image
            ],

            // CONTACT INFO SECTION
            'contact_info' => [
                'is_active' => true,
                'background_image' => 'assets/img/footer/f4-bg-1.png', // Background image for core feature section
                'items' => [
                    [
                        'title' => 'Address',
                        'icon' => 'fas fa-map-marker-alt',
                        'links' => [
                            [
                                'text' => '6 October - Sheikh Zayed, Egypt',
                                'url' => 'https://maps.app.goo.gl/Fi718eLMwvU4RF8s6',
                                'type' => 'link',
                                'new_tab' => true,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Emails Address',
                        'icon' => 'fas fa-envelope',
                        'links' => [
                            [
                                'text' => 'info@geometric-development.com',
                                'url' => 'mailto:info@geometric-development.com',
                                'type' => 'link',
                                'new_tab' => false,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Call Us',
                        'icon' => 'fas fa-phone',
                        'links' => [
                            [
                                'text' => '+20 127 2777919',
                                'url' => 'https://wa.me/201272777919',
                                'type' => 'link',
                                'new_tab' => true,
                            ],
                            [
                                'text' => '+20 120 0111338',
                                'url' => 'https://wa.me/201200111338',
                                'type' => 'link',
                                'new_tab' => true,
                            ],
                        ],
                    ],
                ],
            ],

            // CONTACT FORM SECTION
            'contact_form' => [
                'is_active' => true,
                'subtitle' => 'Contact us',
                'title' => 'Find Your Perfect Property Today!',
                'side_image' => null, // Can be set through admin
                'form_action' => route('contact.store'),
                'show_user_type' => true,
            ],

            // MAP SECTION
            'map' => [
                'is_active' => true,
                'embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.0757788567597!2d30.9502945!3d30.0774592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1458596ebd719e87%3A0xdff54007e2e42e6a!2sGeometric%20Development!5e0!3m2!1sen!2seg!4v1738045355332!5m2!1sen!2seg',
                'height' => '450',
            ],
        ];
    }
}
