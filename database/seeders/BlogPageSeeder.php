<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class BlogPageSeeder extends Seeder
{
    /**
     * Seed the blog page with dynamic sections
     */
    public function run(): void
    {
        $blogPage = Page::updateOrCreate(
            ['slug' => 'blog'],
            [
                'title' => 'Blogs and News',
                'template' => 'blog',
                'is_published' => true,
                'published_at' => now(),
                'meta_title' => 'Blogs and News - Geometric Development | Market Updates & Insights',
                'meta_description' => 'Stay updated with latest real estate market insights, news, and tips from Geometric Development. Expert articles on property investment and development.',
                'meta_keywords' => 'blog, real estate news, market insights, property tips, real estate articles',
                'sections' => $this->getBlogSections(),
            ]
        );

        $this->command->info("✅ Blog page created/updated successfully!");
        $this->command->info("📄 Page ID: {$blogPage->id}");
    }

    /**
     * Get all blog page sections
     */
    private function getBlogSections(): array
    {
        return [
            // BREADCRUMB SECTION
            'breadcrumb' => [
                'is_active' => true,
                'page_title' => 'Blogs and News',
                'background_image' => 'assets/img/breadcrumb/breadcrumb-img.png',
            ],

            // BLOG LISTING SECTION
            'blog_listing' => [
                'is_active' => true,
                'subtitle' => 'Latest Real Estate Insights',
                'title' => 'Market Updates & News',
                'description' => 'Stay informed with our latest articles and insights on real estate market trends.',
                'posts_per_page' => 12,
                'show_featured_only' => false,
            ],

            // SIDEBAR SECTION
            'sidebar' => [
                'is_active' => true,
                'show_categories' => true,
                'show_recent_posts' => true,
                'show_tags' => true,
                'recent_posts_limit' => 5,
            ],

            // BLOG POST DETAIL PAGE CONFIGURATION
            'blog_detail' => [
                'breadcrumb_active' => true,
                'show_back_button' => true,
                'back_button_text' => 'Back to Blog',
                'show_author' => true,
                'show_date' => true,
                'show_categories' => true,
                'show_tags' => true,
                'show_related_posts' => true,
                'related_posts_title' => 'Related Articles',
                'related_posts_limit' => 3,
                'show_comments' => true,
                'comments_title' => 'Comments',
            ],
        ];
    }
}
