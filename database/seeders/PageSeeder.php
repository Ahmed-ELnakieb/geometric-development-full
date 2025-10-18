<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'template' => 'home',
                'meta_title' => 'Geometric Development - Leading Real Estate Developer in UAE & Egypt',
                'meta_description' => 'Geometric Development is a leading real estate company specializing in luxury residential and commercial properties in UAE and Egypt. Discover our premium projects.',
                'meta_keywords' => 'real estate, property development, UAE, Egypt, luxury homes, commercial properties',
                'sections' => [
                    'hero' => [
                        'title' => 'Building Dreams, Creating Communities',
                        'subtitle' => 'Leading real estate development in UAE and Egypt',
                        'background_image' => null,
                        'cta_text' => 'Explore Projects',
                        'cta_link' => '/projects'
                    ],
                    'about' => [
                        'title' => 'About Geometric Development',
                        'content' => 'With years of experience in real estate development, we create exceptional communities that combine luxury, comfort, and innovation.',
                        'features' => [
                            ['title' => 'Premium Quality', 'description' => 'Highest construction standards'],
                            ['title' => 'Strategic Locations', 'description' => 'Prime locations across UAE & Egypt'],
                            ['title' => 'Customer Focus', 'description' => 'Dedicated to customer satisfaction'],
                        ]
                    ],
                    'stats' => [
                        ['number' => '50+', 'label' => 'Projects Completed'],
                        ['number' => '10,000+', 'label' => 'Happy Families'],
                        ['number' => '15+', 'label' => 'Years Experience'],
                        ['number' => '2', 'label' => 'Countries'],
                    ]
                ],
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'template' => 'about',
                'meta_title' => 'About Geometric Development - Our Story & Vision',
                'meta_description' => 'Learn about Geometric Development\'s journey, vision, and commitment to creating exceptional real estate projects in UAE and Egypt.',
                'meta_keywords' => 'about geometric development, company history, vision, mission, real estate developer',
                'sections' => [
                    'hero' => [
                        'title' => 'Our Story',
                        'subtitle' => 'Building the future of real estate development',
                        'content' => 'Geometric Development has been at the forefront of real estate innovation, creating communities that enhance lives and build lasting value.'
                    ],
                    'values' => [
                        ['title' => 'Excellence', 'description' => 'We strive for excellence in every aspect of our developments'],
                        ['title' => 'Innovation', 'description' => 'Embracing cutting-edge technology and design'],
                        ['title' => 'Sustainability', 'description' => 'Building responsibly for future generations'],
                        ['title' => 'Community', 'description' => 'Creating spaces that bring people together'],
                    ]
                ],
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'template' => 'contact',
                'meta_title' => 'Contact Geometric Development - Get in Touch',
                'meta_description' => 'Contact Geometric Development for inquiries about our real estate projects in UAE and Egypt. We\'re here to help you find your perfect property.',
                'meta_keywords' => 'contact geometric development, real estate inquiries, property information',
                'sections' => [
                    'hero' => [
                        'title' => 'Get in Touch',
                        'subtitle' => 'We\'re here to help you find your perfect property'
                    ],
                    'contact_info' => [
                        'address' => '6 October - Sheikh Zayed, Egypt',
                        'phone' => '+20 127 2777919',
                        'email' => 'info@geometric-development.com',
                        'working_hours' => 'Sunday - Thursday: 9:00 AM - 6:00 PM'
                    ]
                ],
                'is_published' => true,
                'published_at' => now(),
            ]
        ];

        foreach ($pages as &$pageData) {
            $pageData['sections'] = json_encode($pageData['sections']);
            $pageData['created_at'] = now();
            $pageData['updated_at'] = now();
        }
        
        DB::table('pages')->insert($pages);
    }
}
