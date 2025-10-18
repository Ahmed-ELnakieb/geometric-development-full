<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Residential', 'slug' => 'residential', 'description' => 'Residential projects including villas and apartments', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Commercial', 'slug' => 'commercial', 'description' => 'Commercial and office buildings', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mixed Use', 'slug' => 'mixed-use', 'description' => 'Mixed-use developments', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Investment', 'slug' => 'investment', 'description' => 'Investment opportunities', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('project_categories')->insert($categories);
    }
}
