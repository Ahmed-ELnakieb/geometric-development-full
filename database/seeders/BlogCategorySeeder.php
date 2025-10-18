<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Market Trends', 'slug' => 'market-trends', 'description' => 'Latest real estate market trends and insights', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Investment', 'slug' => 'investment', 'description' => 'Investment opportunities and guides', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sustainability', 'slug' => 'sustainability', 'description' => 'Sustainable development and green building practices', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'PropTech and construction technology', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Lifestyle and community living', 'display_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('blog_categories')->insert($categories);
    }
}
