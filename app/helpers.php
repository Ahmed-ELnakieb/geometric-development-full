<?php

use App\Models\Setting;

if (!function_exists('settings')) {
    /**
     * Get setting value by key
     */
    function settings(string $key, mixed $default = null): mixed
    {
        static $settings = null;
        
        if ($settings === null) {
            $settings = Setting::pluck('value', 'key')->toArray();
        }
        
        return $settings[$key] ?? $default;
    }
}

if (!function_exists('formatPrice')) {
    /**
     * Format price with currency
     */
    function formatPrice(float $price, string $currency = 'AED'): string
    {
        return $currency . ' ' . number_format($price, 0, '.', ',');
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate text to specified length
     */
    function truncateText(string $text, int $length = 150, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('getImageUrl')) {
    /**
     * Get image URL from media or return default
     */
    function getImageUrl($media, string $default = 'assets/img/default.jpg'): string
    {
        if (!$media) {
            return asset($default);
        }
        
        if (is_string($media)) {
            return asset($media);
        }
        
        if (method_exists($media, 'getUrl')) {
            return $media->getUrl();
        }
        
        return asset($default);
    }
}

if (!function_exists('getNavbarMenu')) {
    /**
     * Get navbar menu items
     */
    function getNavbarMenu(): \Illuminate\Support\Collection
    {
        return \App\Models\MenuItem::navbar()
            ->active()
            ->parents()
            ->with(['children' => function ($query) {
                $query->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
    }
}

if (!function_exists('getFooterMenu')) {
    /**
     * Get footer menu items
     */
    function getFooterMenu(): \Illuminate\Support\Collection
    {
        return \App\Models\MenuItem::footer()
            ->active()
            ->parents()
            ->with(['children' => function ($query) {
                $query->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
    }
}

if (!function_exists('getMenuUrl')) {
    /**
     * Get menu item URL based on link type
     */
    function getMenuUrl(\App\Models\MenuItem $item): string
    {
        // Handle project links
        if ($item->link_type === 'project' && $item->project_id) {
            $project = \App\Models\Project::find($item->project_id);
            if ($project) {
                return route('projects.show', $project->slug);
            }
        }
        
        // Prioritize direct URL
        if ($item->url) {
            // If URL starts with http/https, return as is (external link)
            if (str_starts_with($item->url, 'http://') || str_starts_with($item->url, 'https://')) {
                return $item->url;
            }
            // Otherwise, make it a full URL
            return url($item->url);
        }
        
        // Try to use route if no URL is set
        if ($item->route) {
            try {
                return route($item->route);
            } catch (\Exception $e) {
                // Route might require parameters, return # as fallback
                return '#';
            }
        }
        
        return '#';
    }
}
