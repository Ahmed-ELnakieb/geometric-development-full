<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PWAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
            
            // Only add PWA headers to frontend routes (exclude admin/filament)
            if (!$request->is('admin/*') && !$request->is('filament/*')) {
                // Add PWA-related headers
                $response->headers->set('Cache-Control', 'public, max-age=3600');
                
                // Add service worker headers for better caching
                if ($request->is('sw.js') || $request->is('manifest.json')) {
                    $response->headers->set('Cache-Control', 'public, max-age=0, must-revalidate');
                    $response->headers->set('Service-Worker-Allowed', '/');
                }
            }
            
            return $response;
        } catch (\Exception $e) {
            // Log error but don't crash
            \Log::error('PWA Middleware Error: ' . $e->getMessage());
            return $next($request);
        }
    }
}
