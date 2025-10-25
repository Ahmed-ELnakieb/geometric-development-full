<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        try {
            // Static pages
            $urls = [
            [
                'loc' => route('home'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ],
            [
                'loc' => route('page.show', 'about'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'loc' => route('page.show', 'services'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'loc' => route('projects.index'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            [
                'loc' => route('blog.index'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '0.8'
            ],
            [
                'loc' => route('careers.index'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'loc' => route('contact.index'),
                'lastmod' => now()->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ]
        ];

        // Add all projects dynamically
        $projects = \App\Models\Project::where('is_published', true)->get();
        foreach ($projects as $project) {
            $urls[] = [
                'loc' => route('projects.show', $project->slug),
                'lastmod' => $project->updated_at->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
        }

        // Add all published blog posts dynamically
        $blogPosts = \App\Models\BlogPost::where('is_published', true)->get();
        foreach ($blogPosts as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ];
        }

        // Add all blog categories
        $categories = \App\Models\BlogCategory::all();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('blog.category', $category->slug),
                'lastmod' => $category->updated_at->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.5'
            ];
        }

        // Add all careers
        $careers = \App\Models\Career::where('is_active', true)->get();
        foreach ($careers as $career) {
            $urls[] = [
                'loc' => route('careers.show', $career->slug),
                'lastmod' => $career->updated_at->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
        }

            $xml = view('sitemap', compact('urls'))->render();
            
            return response($xml, 200, [
                'Content-Type' => 'application/xml'
            ]);
        } catch (\Exception $e) {
            \Log::error('Sitemap Generation Error: ' . $e->getMessage());
            // Return minimal sitemap on error
            $minimalXml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>' . url('/') . '</loc></url></urlset>';
            return response($minimalXml, 200, [
                'Content-Type' => 'application/xml'
            ]);
        }
    }
}