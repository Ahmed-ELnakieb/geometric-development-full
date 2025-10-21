<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            ProjectCategorySeeder::class,
            BlogCategorySeeder::class,
            BlogTagSeeder::class,
            PageSeeder::class,
            HomePageSeeder::class, // Homepage with all sections
            AboutPageSeeder::class, // About page with all sections
            CareerSeeder::class,
            BlogPostSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
