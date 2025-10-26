<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for WhatsApp Cloud API integration
    |
    */

    // HTTP request timeouts
    'timeout' => env('WHATSAPP_TIMEOUT', 30),
    'connect_timeout' => env('WHATSAPP_CONNECT_TIMEOUT', 10),

    // SSL Configuration
    'ssl_verify' => env('WHATSAPP_SSL_VERIFY', null), // null = auto-detect based on environment
    'ca_bundle' => env('WHATSAPP_CA_BUNDLE', null), // Path to custom CA bundle

    // API Configuration
    'api_version' => env('WHATSAPP_API_VERSION', 'v18.0'),
    'base_url' => env('WHATSAPP_BASE_URL', 'https://graph.facebook.com'),

    // Rate limiting
    'rate_limit_retries' => env('WHATSAPP_RATE_LIMIT_RETRIES', 3),
    'rate_limit_backoff' => env('WHATSAPP_RATE_LIMIT_BACKOFF', 2), // Exponential backoff multiplier

    // Logging
    'log_requests' => env('WHATSAPP_LOG_REQUESTS', false),
    'log_responses' => env('WHATSAPP_LOG_RESPONSES', false),
];