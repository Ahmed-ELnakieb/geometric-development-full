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
        ];

        foreach ($pages as &$pageData) {
            $pageData['sections'] = json_encode($pageData['sections']);
            $pageData['created_at'] = now();
            $pageData['updated_at'] = now();
        }
        
        DB::table('pages')->insert($pages);
    }
}
