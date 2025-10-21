<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class AboutPageSeeder extends Seeder
{
    /**
     * Seed the about page with all sections
     */
    public function run(): void
    {
        $aboutPage = Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Us',
                'template' => 'about',
                'is_published' => true,
                'published_at' => now(),
                'meta_title' => 'About Us - Geometric Development | Leading Real Estate Developer in Egypt',
                'meta_description' => 'Learn about Geometric Development, Egypt\'s premier real estate developer with over 20 years of excellence in creating exceptional communities.',
                'sections' => $this->getAboutPageSections(),
            ]
        );

        $this->command->info("✅ About page created/updated successfully!");
        $this->command->info("📄 Page ID: {$aboutPage->id}");
    }

    /**
     * Get all about page sections data
     */
    private function getAboutPageSections(): array
    {
        return [
            // BREADCRUMB SECTION
            'breadcrumb' => [
                'is_active' => true,
                'page_title' => 'About Us',
                'background_image' => 'assets/img/breadcrumb/breadcrumb-img.png',
            ],

            // CORE FEATURES SECTION
            'core_features' => [
                'is_active' => true,
                'items' => [
                    [
                        'icon' => 'assets/img/core-features/cf-icon-1.png',
                        'title' => 'Masterplanned Communities',
                        'description' => 'Thoughtfully designed living spaces',
                        'link' => '#',
                    ],
                    [
                        'icon' => 'assets/img/core-features/cf-icon-2.png',
                        'title' => 'Sustainable Development',
                        'description' => 'Green building practices',
                        'link' => '#',
                    ],
                    [
                        'icon' => 'assets/img/core-features/cf-icon-3.png',
                        'title' => 'Strategic Locations',
                        'description' => 'Prime Egyptian destinations',
                        'link' => '#',
                    ],
                    [
                        'icon' => 'assets/img/core-features/cf-icon-4.png',
                        'title' => 'Premium Amenities',
                        'description' => 'Luxury lifestyle facilities',
                        'link' => '#',
                    ],
                ],
            ],

            // ABOUT SECTION
            'about' => [
                'is_active' => true,
                'subtitle' => 'Welcome to Geometric Development',
                'title' => 'Exceptional Communities <br> Across Egypt',
                'description' => 'Geometric Development creates exceptional and sustainable real estate communities. <b>We specialize in contemporary developments</b> that integrate excellence, innovation, and nature to enrich lifestyles across Egypt.',
                'button_text' => 'learn more about',
                'button_link' => '#',
                'show_button' => true,
                'images' => [
                    ['image' => 'assets/img/about/a1-img-1.png'],
                    ['image' => 'assets/img/about/a1-img-2.png'],
                    ['image' => 'assets/img/about/a1-img-3.png'],
                    ['image' => 'assets/img/about/a1-img-4.png'],
                ],
            ],

            // COUNTERS SECTION
            'counters' => [
                'is_active' => true,
                'items' => [
                    [
                        'title' => 'Properties Sold',
                        'value' => '15',
                        'suffix' => 'k+',
                        'description' => 'Successfully delivered thousands of premium properties across Egypt.',
                    ],
                    [
                        'title' => 'Years in Real Estate',
                        'value' => '23',
                        'suffix' => '+',
                        'description' => 'Over two decades of excellence in Egyptian real estate development.',
                    ],
                    [
                        'title' => 'Happy Homeowners',
                        'value' => '98',
                        'suffix' => '%',
                        'description' => '98% Happy Homeowners achieved through exceptional architectural services.',
                    ],
                    [
                        'title' => 'Market Leadership',
                        'value' => '21',
                        'suffix' => '+',
                        'description' => 'Leading real estate company specializing in premium Egyptian properties.',
                    ],
                ],
            ],

            // VALUES SECTION
            'values' => [
                'is_active' => true,
                'subtitle' => 'OUR BRAND VALUES',
                'title' => 'Building Communities with Purpose and Vision',
                'description' => 'At Geometric Development, we create exceptional real estate developments that blend innovation, sustainability, and timeless value, creating communities that inspire and elevate daily living across Egypt.',
                'button_text' => 'learn more',
                'button_link' => '#',
                'show_button' => true,
                'section_image' => 'assets/img/services/s4-img-1.png',
                'background_image' => 'assets/img/services/s4-bg.png',
                'values' => [
                    [
                        'title' => 'Community-Centric',
                        'description' => 'At the heart of our endeavours is a profound commitment to people. From the communities we nurture to the broader ecosystem of Egypt, our focus is on creating environments where individuals can connect, grow, and flourish.',
                        'image' => 'assets/img/services/s4-img-2.png',
                        'link' => '#',
                        'active' => true,
                    ],
                    [
                        'title' => 'Value-Driven',
                        'description' => 'Our ethos is anchored in adding meaningful value. We believe in the power of positive contributions, whether economic, social, or environmental. Our commitment to sustainable practices reflects our dedication to the betterment of society.',
                        'image' => 'assets/img/services/s4-img-3.png',
                        'link' => '#',
                        'active' => false,
                    ],
                    [
                        'title' => 'Responsible & Accountable',
                        'description' => 'Integrity, respect, and transparency guide our actions. We approach every decision with a sense of responsibility towards the people, the places, and the environment we interact with.',
                        'image' => 'assets/img/services/s4-img-4.png',
                        'link' => '#',
                        'active' => false,
                    ],
                    [
                        'title' => 'Excellence',
                        'description' => 'Our pursuit of excellence is relentless. We believe in setting new benchmarks, constantly evolving, and striving to exceed expectations in everything we do.',
                        'image' => 'assets/img/services/s4-img-5.png',
                        'link' => '#',
                        'active' => false,
                    ],
                    [
                        'title' => 'Sustainability',
                        'description' => 'Sustainability is the cornerstone of our vision for the future. We are driven by the long-term impact of our actions on our community and the environment.',
                        'image' => 'assets/img/services/s4-img-2.png',
                        'link' => '#',
                        'active' => false,
                    ],
                ],
            ],

            // EXPERTISE SECTION
            'expertise' => [
                'is_active' => true,
                'title' => 'Geometric Development',
                'tags' => [
                    ['text' => 'Residential Communities', 'icon' => 'flaticon-check', 'icon_position' => 'left', 'icon_color' => 'has-clr-3'],
                    ['text' => 'Luxury Villas', 'icon' => 'flaticon-check', 'icon_position' => 'right', 'icon_color' => 'has-clr-3'],
                    ['text' => 'Beachfront Properties', 'icon' => 'flaticon-check', 'icon_position' => 'left', 'icon_color' => 'has-clr-3'],
                    ['text' => 'Smart Homes', 'icon' => 'flaticon-check', 'icon_position' => 'right', 'icon_color' => 'has-clr-2'],
                    ['text' => 'Sustainable Design', 'icon' => 'flaticon-check', 'icon_position' => 'right', 'icon_color' => ''],
                    ['text' => 'Premium Amenities', 'icon' => 'flaticon-check', 'icon_position' => 'left', 'icon_color' => ''],
                ],
            ],

            // PORTFOLIO SECTION
            'portfolio' => [
                'is_active' => true,
                'subtitle' => 'projects',
                'title' => 'Our Development Portfolio',
                'featured_image' => 'assets/img/award/a5-img-1.png',
                'use_real_projects' => true, // Use actual projects from database
                'project_limit' => 6,
            ],

            // WHY CHOOSE US SECTION
            'why_choose_us' => [
                'is_active' => true,
                'subtitle' => 'Why choose us',
                'title' => 'Leading Real Estate Developer Transforming Egypt\'s Landscape',
                'description' => 'With decades of expertise in real estate development, Geometric Development delivers exceptional communities across Egypt. Our commitment to quality, innovation, and sustainability sets us apart.',
                'background_image' => 'assets/img/choose/ch4-bg-img-1.png',
                'video_url' => 'https://www.youtube.com/watch?v=e45TPIcx5CY',
                'show_video' => true,
                'progress' => [
                    ['title' => 'Customer Satisfaction', 'percentage' => 84],
                    ['title' => 'Project Delivery', 'percentage' => 72],
                    ['title' => 'Quality Standards', 'percentage' => 96],
                ],
                'features' => [
                    [
                        'icon' => 'flaticon-minimalist',
                        'title' => 'Prime Locations',
                        'description' => 'We strategically select locations across Egypt\'s most desirable destinations, from coastal retreats to urban centers, ensuring lasting value for investors.',
                        'link' => '#',
                    ],
                    [
                        'icon' => 'flaticon-blueprint',
                        'title' => 'Proven Track Record',
                        'description' => 'With successful projects spanning over two decades, we\'ve established ourselves as Egypt\'s trusted developer with thousands of satisfied homeowners.',
                        'link' => '#',
                    ],
                    [
                        'icon' => 'flaticon-property-insurance',
                        'title' => 'Integrated Amenities',
                        'description' => 'Our developments feature world-class facilities including sports clubs, retail centers, and green spaces designed for modern living.',
                        'link' => '#',
                    ],
                    [
                        'icon' => 'flaticon-goodwill-1',
                        'title' => 'Investment Value',
                        'description' => 'Our properties offer exceptional ROI potential, combining strategic locations with premium construction to maximize long-term investment value.',
                        'link' => '#',
                    ],
                ],
            ],
        ];
    }
}
