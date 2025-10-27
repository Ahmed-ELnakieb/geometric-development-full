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
            DeveloperAccountSeeder::class, // Developer account (must be first)
            UserSeeder::class,
            SettingSeeder::class,
            NavbarFooterSettingsSeeder::class, // Navbar & Footer settings
            ProjectCategorySeeder::class,
            BlogCategorySeeder::class,
            BlogTagSeeder::class,
            PageSeeder::class,
            HomePageSeeder::class, // Homepage with all sections
            AboutPageSeeder::class, // About page with all sections
            ContactPageSeeder::class, // Contact page with all sections
            ProjectsPageSeeder::class, // Projects page with all sections
            CareersPageSeeder::class, // Careers page with sections
            BlogPageSeeder::class, // Blog page with sections
            CareerSeeder::class,
            BlogPostSeeder::class,
            ProjectSeeder::class,
            MenuItemSeeder::class, // Menu items (navbar & footer)
            SeoSettingSeeder::class, // SEO settings (runs last to include project names)
        ]);
    }
}
