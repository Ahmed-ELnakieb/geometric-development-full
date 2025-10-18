<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Real Estate', 'slug' => 'real-estate', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Property Development', 'slug' => 'property-development', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Investment', 'slug' => 'investment', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UAE', 'slug' => 'uae', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Egypt', 'slug' => 'egypt', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ras Al Khaimah', 'slug' => 'ras-al-khaimah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Luxury', 'slug' => 'luxury', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apartments', 'slug' => 'apartments', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Villas', 'slug' => 'villas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Commercial', 'slug' => 'commercial', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smart Homes', 'slug' => 'smart-homes', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Green Building', 'slug' => 'green-building', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Market Analysis', 'slug' => 'market-analysis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tips', 'slug' => 'tips', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Guide', 'slug' => 'guide', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coastal', 'slug' => 'coastal', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sustainability', 'slug' => 'sustainability', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technology', 'slug' => 'technology', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('blog_tags')->insert($tags);
    }
}
