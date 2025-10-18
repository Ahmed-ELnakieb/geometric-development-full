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
