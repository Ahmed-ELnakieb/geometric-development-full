<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menu items
        MenuItem::truncate();

        // Navbar Menu Items
        MenuItem::create([
            'title' => 'Home',
            'route' => 'home',
            'link_type' => 'route',
            'type' => 'navbar',
            'order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'About',
            'url' => '/about',
            'link_type' => 'page',
            'type' => 'navbar',
            'order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Projects',
            'route' => 'projects.index',
            'link_type' => 'route',
            'type' => 'navbar',
            'order' => 3,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Vacancies',
            'route' => 'careers.index',
            'link_type' => 'route',
            'type' => 'navbar',
            'order' => 4,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Blogs',
            'route' => 'blog.index',
            'link_type' => 'route',
            'type' => 'navbar',
            'order' => 5,
            'is_active' => true,
        ]);

        // Footer Menu Items - Main Links
        MenuItem::create([
            'title' => 'Home',
            'route' => 'home',
            'link_type' => 'route',
            'type' => 'footer',
            'order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'About Us',
            'url' => '/about',
            'link_type' => 'page',
            'type' => 'footer',
            'order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Vacancies',
            'route' => 'careers.index',
            'link_type' => 'route',
            'type' => 'footer',
            'order' => 3,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Projects',
            'route' => 'projects.index',
            'link_type' => 'route',
            'type' => 'footer',
            'order' => 4,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Blog',
            'route' => 'blog.index',
            'link_type' => 'route',
            'type' => 'footer',
            'order' => 5,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Contact Us',
            'route' => 'contact.index',
            'link_type' => 'route',
            'type' => 'footer',
            'order' => 6,
            'is_active' => true,
        ]);

        // Footer Featured Projects (order 20-29)
        $featuredProjects = \App\Models\Project::featured()->published()->take(4)->get();
        $projectOrder = 20;
        foreach ($featuredProjects as $project) {
            MenuItem::create([
                'title' => $project->title,
                'project_id' => $project->id,
                'link_type' => 'project',
                'type' => 'footer',
                'order' => $projectOrder++,
                'is_active' => true,
            ]);
        }

        // Footer Social Media Links
        $socialParent = MenuItem::create([
            'title' => 'Follow Us',
            'type' => 'footer',
            'order' => 100,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Facebook',
            'url' => 'https://facebook.com/geometric-development',
            'link_type' => 'external',
            'type' => 'footer',
            'parent_id' => $socialParent->id,
            'icon' => 'fa-brands fa-facebook-f',
            'open_in_new_tab' => true,
            'order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Instagram',
            'url' => 'https://instagram.com/geometric-development',
            'link_type' => 'external',
            'type' => 'footer',
            'parent_id' => $socialParent->id,
            'icon' => 'fa-brands fa-instagram',
            'open_in_new_tab' => true,
            'order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'Twitter',
            'url' => 'https://twitter.com/geometric-development',
            'link_type' => 'external',
            'type' => 'footer',
            'parent_id' => $socialParent->id,
            'icon' => 'fa-brands fa-x-twitter',
            'open_in_new_tab' => true,
            'order' => 3,
            'is_active' => true,
        ]);

        MenuItem::create([
            'title' => 'LinkedIn',
            'url' => 'https://linkedin.com/company/geometric-development',
            'link_type' => 'external',
            'type' => 'footer',
            'parent_id' => $socialParent->id,
            'icon' => 'fa-brands fa-linkedin-in',
            'open_in_new_tab' => true,
            'order' => 4,
            'is_active' => true,
        ]);
    }
}
