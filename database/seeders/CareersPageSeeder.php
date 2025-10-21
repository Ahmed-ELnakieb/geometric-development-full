<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class CareersPageSeeder extends Seeder
{
    /**
     * Seed the careers page with dynamic sections
     */
    public function run(): void
    {
        $careersPage = Page::updateOrCreate(
            ['slug' => 'careers'],
            [
                'title' => 'Careers & Vacancies',
                'template' => 'careers',
                'is_published' => true,
                'published_at' => now(),
                'meta_title' => 'Careers & Vacancies - Geometric Development | Join Our Team',
                'meta_description' => 'Join Geometric Development and build your career in real estate. Explore exciting opportunities in Egypt and UAE with competitive benefits.',
                'meta_keywords' => 'careers, jobs, vacancies, real estate jobs, Geometric Development careers',
                'sections' => $this->getCareersSections(),
            ]
        );

        $this->command->info("✅ Careers page created/updated successfully!");
        $this->command->info("📄 Page ID: {$careersPage->id}");
    }

    /**
     * Get all careers page sections
     */
    private function getCareersSections(): array
    {
        return [
            // BREADCRUMB HERO SECTION
            'hero' => [
                'is_active' => true,
                'title' => 'Build Your Career in Real Estate Development.',
                'description' => 'Join a developer shaping communities across Egypt & UAE. Be part of a team building coastal destinations, luxury residences, and vibrant mixed-use projects.',
                'button_text' => 'View Openings',
                'button_link' => '#positions',
                'background_image' => 'assets/img/breadcrumb/geo-banner.png',
                'side_image_large' => 'assets/img/breadcrumb/geo-1.png',
                'side_image_small' => 'assets/img/breadcrumb/geo-2.png',
            ],

            // BENEFITS SECTION
            'benefits' => [
                'is_active' => true,
                'subtitle' => 'Inspiration',
                'title' => 'Inspiring benefits to grow with us',
                'items' => [
                    [
                        'title' => 'Competitive salary',
                        'description' => 'With a proven track record in leading cross-functional teams, developing innovative product strategies.',
                        'icon_type' => 'money', // Will use default SVG
                    ],
                    [
                        'title' => 'Innovation',
                        'description' => 'We thrive on fresh ideas, driving us to pioneer solutions that redefine norms and position us as a trailblazing team!',
                        'icon_type' => 'innovation',
                    ],
                    [
                        'title' => 'Health Insurance',
                        'description' => 'The purpose of health insurance is to mitigate the financial burden of unexpected medical expenses.',
                        'icon_type' => 'health',
                    ],
                    [
                        'title' => 'Retirement Plans',
                        'description' => 'These are retirement plans offered by employers to their employees as part of their benefits package.',
                        'icon_type' => 'retirement',
                    ],
                    [
                        'title' => 'Team Building',
                        'description' => 'The primary objectives of team building include fostering better relationships among team members.',
                        'icon_type' => 'team',
                    ],
                    [
                        'title' => 'Trainings',
                        'description' => 'Attending training sessions helps build a strong foundation and enhances expertise.',
                        'icon_type' => 'training',
                    ],
                ],
            ],

            // JOB LISTINGS SECTION
            'job_listings' => [
                'is_active' => true,
                'subtitle' => 'Openings',
                'title' => 'Available Positions',
                'description' => 'Explore our current job openings and take the next step in your career.',
            ],

            // CAREER DETAIL PAGE CONFIGURATION
            'career_detail' => [
                'breadcrumb_active' => true,
                'breadcrumb_bg' => 'assets/img/breadcrumb/geo-banner.png',
                'overview_title' => 'Position Overview',
                'responsibilities_title' => 'Key Responsibilities',
                'requirements_title' => 'Requirements',
                'benefits_title' => 'What We Offer',
                'show_job_info_sidebar' => true, // Toggle 1: Job details (location, type, salary, days)
                'show_application_form' => true, // Toggle 2: Application form
            ],
        ];
    }
}
