<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PWAController extends Controller
{
    /**
     * Generate dynamic manifest.json
     */
    public function manifest()
    {
        $manifest = [
            'name' => config('pwa.name', 'Geometric Development'),
            'short_name' => config('pwa.short_name', 'Geometric'),
            'description' => config('pwa.description', 'Leading Saudi real estate company'),
            'start_url' => config('pwa.start_url', '/'),
            'display' => config('pwa.display', 'standalone'),
            'theme_color' => config('pwa.theme_color', '#1a1a1a'),
            'background_color' => config('pwa.background_color', '#1a1a1a'),
            'orientation' => config('pwa.orientation', 'portrait-primary'),
            'scope' => config('pwa.scope', '/'),
            'icons' => config('pwa.icons', [
                [
                    'src' => '/assets/img/logo/favicon.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => '/assets/img/logo/favicon.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ])
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Generate dynamic service worker
     */
    public function serviceWorker()
    {
        $cacheRoutes = config('pwa.cache.routes', ['/']);
        $cacheAssets = config('pwa.cache.assets', []);
        $excludePatterns = config('pwa.cache.exclude_patterns', []);

        $sw = view('pwa.service-worker', compact('cacheRoutes', 'cacheAssets', 'excludePatterns'));

        return response($sw)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=0, must-revalidate')
            ->header('Service-Worker-Allowed', '/');
    }

    /**
     * PWA test page
     */
    public function test()
    {
        return view('pwa.test');
    }
}
