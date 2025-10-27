<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class ProjectsPageSeeder extends Seeder
{
    /**
     * Seed the projects page with all sections
     */
    public function run(): void
    {
        $projectsPage = Page::updateOrCreate(
            ['slug' => 'projects'],
            [
                'title' => 'Our Projects',
                'slug' => 'projects', // Explicitly set slug to prevent auto-generation
                'template' => 'projects',
                'is_published' => true,
                'published_at' => now(),
                'meta_title' => 'Our Projects - Geometric Development | Real Estate Projects in UAE & Egypt',
                'meta_description' => 'Explore Geometric Development\'s portfolio of luxury residential and commercial projects across UAE and Egypt. Find your perfect property today.',
                'sections' => $this->getProjectsPageSections(),
            ]
        );

        $this->command->info('✅ Projects page created/updated successfully!');
        $this->command->info("📄 Page ID: {$projectsPage->id}");
    }

    /**
     * Get all projects page sections data
     */
    private function getProjectsPageSections(): array
    {
        return [
            // HERO/SHOWCASE SECTION
            'hero' => [
                'is_active' => true,
                'title' => 'Discover Our',
                'subtitle' => 'Projects',
                'trust_badge_text' => '500+ Properties Delivered',
                'trust_badge_rating' => 5,
                'button_text' => 'explore more',
                'button_link' => '#projects',
                'background_image' => 'assets/img/hero/hero.png',
                'show_slider' => true,
                'max_slider_projects' => 5,
            ],

            // COUNTERS SECTION
            'counters' => [
                'is_active' => true,
                'items' => [
                    [
                        'number' => '15',
                        'label' => 'Years of Excellence',
                    ],
                    [
                        'number' => '2500',
                        'label' => 'Properties Delivered',
                    ],
                    [
                        'number' => '12',
                        'label' => 'Active Projects',
                    ],
                    [
                        'number' => '98',
                        'label' => 'Happy Clients',
                    ],
                ],
            ],

            // LATEST TRENDS SECTION
            'latest_trends' => [
                'is_active' => true,
                'title' => 'Latest',
                'subtitle' => 'TRENDS',
                'max_projects' => 4,
            ],

            // FEATURED PROJECTS SECTION
            'featured_projects' => [
                'is_active' => true,
                'background_image' => 'assets/img/random/geometric1.png',
                'show_only_featured' => true,
                'max_projects' => 4,
            ],

            // VIDEO SECTION
            'video' => [
                'is_active' => true,
                'video_url' => 'https://www.youtube.com/watch?v=e45TPIcx5CY',
                'background_image' => 'assets/img/random/random (27).png',
            ],

            // TESTIMONIAL SECTION
            'testimonial' => [
                'is_active' => true,
                'title' => 'Building Dreams Across Horizons',
                'description' => 'Embark on a journey where the serenity of nature meets the elegance of modern living. Our developments invite you to experience pristine landscapes, luxurious living spaces, and a lifestyle that marries tranquillity with sophistication. From coastal paradises to vibrant urban communities, we create spaces where your dreams take shape.',
                'background_image' => 'assets/img/testimonial/t4-bg.png',
            ],

            // ALL PROJECTS SECTION
            'all_projects' => [
                'is_active' => true,
                'subtitle' => 'Our Projects',
                'title' => 'Our Projects Across Egypt & UAE',
                'show_tabs' => true,
                'tabs' => [
                    ['id' => 'all', 'label' => 'All', 'filter' => null],
                    ['id' => 'villas', 'label' => 'Villas', 'filter' => 'Residential'],
                    ['id' => 'apartments', 'label' => 'Apartments', 'filter' => 'Residential'],
                    ['id' => 'commercial', 'label' => 'Commercial', 'filter' => 'Commercial'],
                    ['id' => 'investment', 'label' => 'Investment', 'filter' => 'Investment'],
                ],
            ],

            // CONTACT SECTION
            'contact' => [
                'is_active' => true,
                'subtitle' => 'contact us',
                'title' => 'Get in Touch',
                'left_image' => 'assets/img/random/random (23).png',
                'video_background' => 'assets/img/random/random (2).png',
                'video_url' => 'https://www.youtube.com/watch?v=e45TPIcx5CY',
                'video_text' => '10 years experience',
            ],
        ];
    }
}
