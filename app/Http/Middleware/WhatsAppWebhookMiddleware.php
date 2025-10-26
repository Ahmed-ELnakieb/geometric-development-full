<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rate limiting
        $key = 'whatsapp-webhook:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 100)) { // 100 requests per minute
            Log::warning('WhatsApp webhook rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response('Too Many Requests', 429);
        }

        RateLimiter::hit($key, 60); // 1 minute window

        // Basic security checks
        if (!$this->isValidRequest($request)) {
            Log::warning('Invalid WhatsApp webhook request', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'content_type' => $request->header('Content-Type')
            ]);
            
            return response('Bad Request', 400);
        }

        // Log webhook request for monitoring
        Log::info('WhatsApp webhook request processed', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'content_length' => $request->header('Content-Length', 0)
        ]);

        return $next($request);
    }

    /**
     * Validate webhook request
     */
    private function isValidRequest(Request $request): bool
    {
        // Check if it's a GET request for verification
        if ($request->isMethod('GET')) {
            return $request->has(['hub_mode', 'hub_verify_token', 'hub_challenge']);
        }

        // Check if it's a POST request with proper content type
        if ($request->isMethod('POST')) {
            $contentType = $request->header('Content-Type');
            return str_contains($contentType, 'application/json');
        }

        return false;
    }
}
