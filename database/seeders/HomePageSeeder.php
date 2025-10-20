<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * HomePageSeeder
 * Seeds the homepage with all dynamic content from home.blade.php
 */
class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Create or update the home page
            $homePage = Page::updateOrCreate(
                ['slug' => 'home'],
                [
                    'title' => 'Home',
                    'template' => 'home',
                    'is_published' => true,
                    'published_at' => now(),
                    'meta_title' => 'Geometric Development - Leading Real Estate Developer in Egypt & Emirates',
                    'meta_description' => 'Discover your perfect property with Geometric Development. Premium real estate across Egypt\'s most sought-after destinations including Muroj, Rich Hills, and Ras Al Khaimah.',
                    'meta_keywords' => ['real estate', 'Egypt properties', 'Muroj', 'geometric development', 'luxury homes', 'investment'],
                    'sections' => $this->getHomePageSections(),
                ]
            );

            $this->command->info('✅ Home page created/updated successfully!');
            $this->command->info('📄 Page ID: ' . $homePage->id);
        });
    }

    /**
     * Get all homepage sections data
     */
    private function getHomePageSections(): array
    {
        return [
            // HERO SECTION (Lines 9-56 in home.blade.php)
            'hero' => [
                'is_active' => true,
                'main_title' => 'Leading Community Developer in MUROJ',
                'subtitle' => 'Inspiration of MUROJ in EGYPT',
                'rotating_texts' => [
                    'Luxury Living',
                    'Invest Smart',
                    'Buy Quality',
                    'Dream Home',
                ],
                'button_text' => 'IN GEOMETRIC',
                'button_link' => '/projects',
                'background_image' => 'assets/img/hero/h5-bg-img-1.png',
                'foreground_image' => 'assets/img/hero/h5-img-1.png',
                'icon_image' => 'assets/img/hero/h5-img-3.png',
                'icon_class' => 'flaticon-next-1',
                'background_color' => '#ffffff',
            ],

            // ABOUT SECTION (Lines 58-139 in home.blade.php)
            'about' => [
                'is_active' => true,
                'section_number' => '01',
                'section_subtitle' => 'about us',
                'section_title' => 'Your trusted partner in finding properties and investment opportunities in Egypt\'s most desirable locations.',
                'description' => 'Discover your perfect property with Geometric Development. We specialize in premium real estate sales across Egypt\'s most sought-after destinations, including exclusive residential communities in Muroj. Our expert team guides you through every step of your property buying journey.',
                'button_text' => 'know about us',
                'button_link' => '/about',
                'bg_shape_1' => 'assets/img/about/a5-bg-shape.png',
                'bg_shape_2' => 'assets/img/about/a5-bg-shape-2.png',
                'image_1' => 'assets/img/about/a5-img-1.png',
                'image_2' => 'assets/img/about/a5-img-2.png',
                'features' => [
                    [
                        'title' => 'Prime Locations',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Luxury Amenities',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Modern Design',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Smart Homes',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Eco-Friendly',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Premium Finishes',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Investment Opportunities',
                        'icon' => 'fa-solid fa-plus',
                    ],
                    [
                        'title' => 'Customizable Options',
                        'icon' => 'fa-solid fa-plus',
                    ],
                ],
            ],

            // COUNTERS SECTION (Lines 141-199 in home.blade.php)
            'counters' => [
                'is_active' => true,
                'items' => [
                    [
                        'title' => 'Properties Sold',
                        'value' => '2.5',
                        'suffix' => 'k+',
                        'description' => 'Successfully helping thousands of clients find their perfect properties across UAE.',
                        'animation_delay' => '0s',
                    ],
                    [
                        'title' => 'Years in Real Estate',
                        'value' => '15',
                        'suffix' => '+',
                        'description' => 'Over 15 years of expertise in UAE real estate market and property sales.',
                        'animation_delay' => '0.2s',
                    ],
                    [
                        'title' => 'Happy Homeowners',
                        'value' => '98',
                        'suffix' => '%',
                        'description' => '98% of our clients are satisfied with their property purchases and our service.',
                        'animation_delay' => '0.4s',
                    ],
                    [
                        'title' => 'Market Leadership',
                        'value' => '1',
                        'suffix' => 'st',
                        'description' => 'Leading real estate company specializing in premium Ras Al Khaimah properties.',
                        'animation_delay' => '0.6s',
                    ],
                ],
            ],

            // VIDEO SECTION (Lines 201-221 in home.blade.php)
            'video' => [
                'is_active' => true,
                'video_file' => 'assets/img/video/v2-video-1.mp4',
                'youtube_url' => 'https://www.youtube.com/watch?v=e45TPIcx5CY',
                'autoplay' => true,
                'loop' => true,
                'muted' => true,
                'show_controls' => false,
            ],

            // SERVICES/PROJECTS TABS SECTION (Lines 222-464 in home.blade.php)
            'services' => [
                'is_active' => true,
                'section_subtitle' => 'our services',
                'section_title' => 'Geometric Development <br> Premium Real Estate Services',
                'star_icon' => 'assets/img/illus/star-shape.png',
                'background_image' => 'assets/img/projects/p1-bg-img-1.png',
                'tabs' => [
                    [
                        'name' => 'Muroj Villa',
                        'year' => '2025',
                        'active' => false,
                        'button_image' => 'assets/img/projects/p1-btn-img-1.png',
                        'arrow_image' => 'assets/img/illus/long-right-arrow.png',
                        'main_image' => 'assets/img/projects/p1-img-1.png',
                        'start_date' => 'jan 02, 2025',
                        'end_date' => 'aug 02, 2025',
                        'location' => '136 North Coast, Egypt',
                        'link' => '/projects',
                        'social_links' => [
                            ['platform' => 'facebook', 'icon' => 'fa-brands fa-facebook-f', 'url' => '#'],
                            ['platform' => 'twitter', 'icon' => 'fa-brands fa-x-twitter', 'url' => '#'],
                            ['platform' => 'linkedin', 'icon' => 'fa-brands fa-linkedin-in', 'url' => '#'],
                            ['platform' => 'pinterest', 'icon' => 'fa-brands fa-pinterest-p', 'url' => '#'],
                        ],
                    ],
                    [
                        'name' => 'Mina Marina',
                        'year' => '2025',
                        'active' => true,
                        'button_image' => 'assets/img/projects/p1-btn-img-1.png',
                        'arrow_image' => 'assets/img/illus/long-right-arrow.png',
                        'main_image' => 'assets/img/projects/p1-img-2.png',
                        'start_date' => 'jan 02, 2025',
                        'end_date' => 'aug 02, 2025',
                        'location' => 'New Cairo, Egypt',
                        'link' => '/projects',
                        'social_links' => [
                            ['platform' => 'facebook', 'icon' => 'fa-brands fa-facebook-f', 'url' => '#'],
                            ['platform' => 'twitter', 'icon' => 'fa-brands fa-x-twitter', 'url' => '#'],
                            ['platform' => 'linkedin', 'icon' => 'fa-brands fa-linkedin-in', 'url' => '#'],
                            ['platform' => 'pinterest', 'icon' => 'fa-brands fa-pinterest-p', 'url' => '#'],
                        ],
                    ],
                    [
                        'name' => 'Rich Hills',
                        'year' => '2025',
                        'active' => false,
                        'button_image' => 'assets/img/projects/p1-btn-img-1.png',
                        'arrow_image' => 'assets/img/illus/long-right-arrow.png',
                        'main_image' => 'assets/img/projects/p1-img-3.png',
                        'start_date' => 'jan 02, 2025',
                        'end_date' => 'aug 02, 2025',
                        'location' => 'North Coast, Egypt',
                        'link' => '/projects',
                        'social_links' => [
                            ['platform' => 'facebook', 'icon' => 'fa-brands fa-facebook-f', 'url' => '#'],
                            ['platform' => 'twitter', 'icon' => 'fa-brands fa-x-twitter', 'url' => '#'],
                            ['platform' => 'linkedin', 'icon' => 'fa-brands fa-linkedin-in', 'url' => '#'],
                            ['platform' => 'pinterest', 'icon' => 'fa-brands fa-pinterest-p', 'url' => '#'],
                        ],
                    ],
                    [
                        'name' => 'Ras Al Khaimah',
                        'year' => '2025',
                        'active' => false,
                        'button_image' => 'assets/img/projects/p1-btn-img-1.png',
                        'arrow_image' => 'assets/img/illus/long-right-arrow.png',
                        'main_image' => 'assets/img/projects/p1-img-4.png',
                        'start_date' => 'jan 02, 2025',
                        'end_date' => 'aug 02, 2025',
                        'location' => 'Hurghada, Egypt',
                        'link' => '/projects',
                        'social_links' => [
                            ['platform' => 'facebook', 'icon' => 'fa-brands fa-facebook-f', 'url' => '#'],
                            ['platform' => 'twitter', 'icon' => 'fa-brands fa-x-twitter', 'url' => '#'],
                            ['platform' => 'linkedin', 'icon' => 'fa-brands fa-linkedin-in', 'url' => '#'],
                            ['platform' => 'pinterest', 'icon' => 'fa-brands fa-pinterest-p', 'url' => '#'],
                        ],
                    ],
                ],
            ],

            // PROJECTS SECTION - Featured projects grid
            // Uses dynamic $projects from database (is_featured + display_order)
            'projects' => [
                'is_active' => true,
                'section_title' => 'RESIDENTIAL PROPERTIES',
                'section_subtitle' => '', // Optional subtitle
                'project_limit' => 6, // Number of projects to display (3, 4, 6, 8, 9, or 12)
                'show_button' => true, // Show "View All Projects" button
                'button_text' => 'view all projects',
                'button_bg_image' => 'assets/img/projects/p5-btn-bg-shape.png',
            ],

            // SHOWCASE CAROUSEL SECTION (Lines 596-648 in home.blade.php)
            'showcase' => [
                'is_active' => true,
                'items' => [
                    [
                        'subtitle' => 'Muroj',
                        'title' => 'Luxury waterfront living with muroj views',
                        'image' => 'assets/img/showcase/sh1-img-1.png',
                        'link' => '/projects',
                        'button_text' => 'more details',
                        'showcase' => true, // Enabled by default
                        'project_id' => null,
                    ],
                    [
                        'subtitle' => 'Rich Hills',
                        'title' => 'World-class Rich Hills with premium amenities',
                        'image' => 'assets/img/showcase/sh1-img-2.png',
                        'link' => '/projects',
                        'button_text' => 'more details',
                        'showcase' => true, // Enabled by default
                        'project_id' => null,
                    ],
                ],
                'left_arrow' => 'assets/img/illus/left-arrow.png',
                'right_arrow' => 'assets/img/illus/right-arrow.png',
            ],

            // GALLERY SECTION (Lines 650-748 in home.blade.php)
            'gallery' => [
                'is_active' => true,
                'section_subtitle' => 'Stay Inspired with Instagram',
                'section_title' => '<i class="fa-brands fa-instagram"></i> Instagram',
                'star_icon' => 'assets/img/illus/star-shape.png',
                'button_text' => 'Follow Us',
                'button_link' => 'https://instagram.com/geometric_development',
                'items' => [
                    [
                        'image' => 'assets/img/gallery/g2-img-1.png',
                        'instagram_url' => 'https://instagram.com/p/example1',
                        'size' => 'normal',
                    ],
                    [
                        'image' => 'assets/img/gallery/g2-img-2.png',
                        'instagram_url' => 'https://instagram.com/p/example2',
                        'size' => 'xs-size',
                    ],
                    [
                        'image' => 'assets/img/gallery/g2-img-3.png',
                        'instagram_url' => 'https://instagram.com/p/example3',
                        'size' => 'normal',
                    ],
                    [
                        'image' => 'assets/img/gallery/g2-img-4.png',
                        'instagram_url' => 'https://instagram.com/p/example4',
                        'size' => 'xs-size',
                    ],
                    [
                        'image' => 'assets/img/gallery/g2-img-5.png',
                        'instagram_url' => 'https://instagram.com/p/example5',
                        'size' => 'sm-size',
                    ],
                    [
                        'image' => 'assets/img/gallery/g2-img-6.png',
                        'instagram_url' => 'https://instagram.com/p/example6',
                        'size' => 'sm-size',
                    ],
                    [
                        'image' => 'assets/img/gallery/g2-img-7.png',
                        'instagram_url' => 'https://instagram.com/p/example7',
                        'size' => 'sm-size',
                    ],
                ],
            ],

            // BLOG SECTION (Lines 750-802 in home.blade.php)
            // This section uses dynamic $blogPosts from database
            'blog' => [
                'is_active' => true,
                'section_subtitle' => 'recent blog',
                'section_title' => 'news & ideas',
                'star_icon' => 'assets/img/hero/h3-start.png',
                'button_text' => 'view all blogs',
                'button_link' => '/blog',
                'max_posts' => 3,
                'show_fallback_image' => true,
                'fallback_image' => 'assets/img/random/random (10).png',
            ],
        ];
    }
}
