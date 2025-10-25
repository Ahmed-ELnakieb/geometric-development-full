<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RobotsController extends Controller
{
    public function index()
    {
        try {
            $content = "User-agent: *
Allow: /

# Disallow admin and API routes
Disallow: /admin/
Disallow: /filament/
Disallow: /api/
Disallow: /pwa-test
Disallow: /notifications/
Disallow: /_debugbar/
Disallow: /telescope/
Disallow: /horizon/

# Allow important files
Allow: /assets/
Allow: /manifest.json
Allow: /sw.js

# Sitemap
Sitemap: " . config('app.url') . "/sitemap.xml

# Crawl-delay for respectful crawling
Crawl-delay: 1

# Allow specific search engine bots
User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /

User-agent: Slurp
Allow: /

User-agent: DuckDuckBot
Allow: /

User-agent: Baiduspider
Allow: /

User-agent: YandexBot
Allow: /

User-agent: facebookexternalhit
Allow: /

User-agent: Twitterbot
Allow: /

User-agent: LinkedInBot
Allow: /

User-agent: WhatsApp
Allow: /

# Block aggressive crawlers
User-agent: AhrefsBot
Disallow: /

User-agent: MJ12bot
Disallow: /

User-agent: DotBot
Disallow: /

User-agent: SemrushBot
Disallow: /

User-agent: MajesticSEO
Disallow: /";

            return response($content, 200, [
                'Content-Type' => 'text/plain'
            ]);
        } catch (\Exception $e) {
            \Log::error('Robots.txt Generation Error: ' . $e->getMessage());
            // Return minimal robots.txt on error
            $minimalContent = "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml');
            return response($minimalContent, 200, [
                'Content-Type' => 'text/plain'
            ]);
        }
    }
}