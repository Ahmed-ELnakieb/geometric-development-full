<?php

namespace App\Http\Middleware;

use App\Jobs\RecordVisit;
use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful responses
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->trackVisit($request);
        }

        return $response;
    }

    /**
     * Track the visit
     */
    protected function trackVisit(Request $request): void
    {
        try {
            // Check if tracking is enabled
            if (!\App\Models\VisitTrackingSetting::get('enabled', config('visit-tracking.enabled', true))) {
                return;
            }

            // Skip if route should be excluded
            if ($this->shouldExcludeRoute($request)) {
                return;
            }

            // Skip if IP should be excluded
            if ($this->shouldExcludeIp($request)) {
                return;
            }

            // Skip if user agent should be excluded
            if ($this->shouldExcludeUserAgent($request)) {
                return;
            }

            $isAdmin = $this->isAdminRoute($request);

            // Check if we should track this type of visit
            if ($isAdmin && !\App\Models\VisitTrackingSetting::get('track_admin', config('visit-tracking.track_admin', true))) {
                return;
            }

            if (!$isAdmin && !\App\Models\VisitTrackingSetting::get('track_frontend', config('visit-tracking.track_frontend', true))) {
                return;
            }

            $visitData = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'is_admin' => $isAdmin,
                'data' => $this->getAdditionalData($request),
            ];

            // Add visitor information if authenticated
            if (Auth::check()) {
                $visitData['visitor_type'] = get_class(Auth::user());
                $visitData['visitor_id'] = Auth::id();
            }

            // Add visitable information if available
            $visitable = $this->getVisitableModel($request);
            if ($visitable) {
                $visitData['visitable_type'] = get_class($visitable);
                $visitData['visitable_id'] = $visitable->getKey();
            }

            // Record visit (queue or direct)
            if (\App\Models\VisitTrackingSetting::get('use_queue', config('visit-tracking.use_queue', false))) {
                $queueName = \App\Models\VisitTrackingSetting::get('queue_name', config('visit-tracking.queue_name', 'default'));
                RecordVisit::dispatch($visitData)->onQueue($queueName);
            } else {
                Visit::create($visitData);
            }
        } catch (\Exception $e) {
            // Log error but don't break the request
            \Log::error('Visit tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Check if route should be excluded from tracking
     */
    protected function shouldExcludeRoute(Request $request): bool
    {
        $path = $request->path();
        
        // Always exclude /admin routes
        if (Str::startsWith($path, 'admin/')) {
            return true;
        }
        
        // Always exclude system/technical routes
        $systemRoutes = [
            'livewire/*',
            'manifest.json',
            'sw.js',
            'service-worker.js',
            'robots.txt',
            'sitemap.xml',
            'favicon.ico',
            '_debugbar/*',
            'telescope/*',
            'horizon/*',
        ];
        
        foreach ($systemRoutes as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        $excludedRoutes = \App\Models\VisitTrackingSetting::get('excluded_routes', config('visit-tracking.excluded_routes', []));
        
        foreach ($excludedRoutes as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP should be excluded from tracking
     */
    protected function shouldExcludeIp(Request $request): bool
    {
        $excludedIps = \App\Models\VisitTrackingSetting::get('excluded_ips', config('visit-tracking.excluded_ips', []));
        return in_array($request->ip(), $excludedIps);
    }

    /**
     * Check if user agent should be excluded from tracking
     */
    protected function shouldExcludeUserAgent(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');
        $excludedAgents = \App\Models\VisitTrackingSetting::get('excluded_user_agents', config('visit-tracking.excluded_user_agents', []));

        foreach ($excludedAgents as $agent) {
            if (Str::contains($userAgent, strtolower($agent))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this is an admin route
     */
    protected function isAdminRoute(Request $request): bool
    {
        return Str::startsWith($request->path(), 'admin') || 
               Str::startsWith($request->path(), 'filament');
    }

    /**
     * Get additional data for the visit
     */
    protected function getAdditionalData(Request $request): array
    {
        return [
            'route_name' => $request->route()?->getName(),
            'session_id' => $request->session()?->getId(),
        ];
    }

    /**
     * Get the visitable model from the request
     */
    protected function getVisitableModel(Request $request)
    {
        // Try to extract model from route parameters
        $route = $request->route();
        if (!$route) {
            return null;
        }

        $parameters = $route->parameters();
        $trackableModels = config('visit-tracking.trackable_models', []);

        foreach ($parameters as $parameter) {
            if (is_object($parameter)) {
                $modelClass = get_class($parameter);
                if (in_array($modelClass, $trackableModels)) {
                    return $parameter;
                }
            }
        }

        return null;
    }
}
