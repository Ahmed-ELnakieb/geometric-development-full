<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    /**
     * Process sync request from service worker
     */
    public function processSync(Request $request)
    {
        try {
            // Validate sync request
            $validator = Validator::make($request->all(), [
                'action' => 'required|string',
                'endpoint' => 'required|string',
                'method' => 'required|string|in:GET,POST,PUT,PATCH,DELETE',
                'data' => 'nullable|array',
                'sync_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid sync request',
                    'errors' => $validator->errors()
                ], 400);
            }

            $action = $request->input('action');
            $endpoint = $request->input('endpoint');
            $method = $request->input('method');
            $data = $request->input('data', []);
            $syncId = $request->input('sync_id');

            Log::info('Processing sync request', [
                'sync_id' => $syncId,
                'action' => $action,
                'endpoint' => $endpoint,
                'method' => $method
            ]);

            // Route the sync request to appropriate handler
            $result = $this->routeSyncRequest($endpoint, $method, $data, $request);

            return response()->json([
                'success' => true,
                'message' => 'Sync processed successfully',
                'data' => $result,
                'sync_id' => $syncId
            ]);

        } catch (\Exception $e) {
            Log::error('Sync processing failed', [
                'error' => $e->getMessage(),
                'sync_id' => $request->input('sync_id'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sync processing failed: ' . $e->getMessage(),
                'sync_id' => $request->input('sync_id')
            ], 500);
        }
    }

    /**
     * Route sync request to appropriate controller method
     */
    private function routeSyncRequest($endpoint, $method, $data, Request $originalRequest)
    {
        // Create a new request with the sync data
        $syncRequest = Request::create($endpoint, $method, $data);
        
        // Copy headers from original request
        foreach ($originalRequest->headers->all() as $key => $value) {
            $syncRequest->headers->set($key, $value);
        }

        // Handle common sync endpoints
        switch (true) {
            case str_contains($endpoint, '/contact'):
                return $this->handleContactSync($syncRequest, $data);
                
            case str_contains($endpoint, '/careers'):
                return $this->handleCareersSync($syncRequest, $data);
                
            case str_contains($endpoint, '/newsletter'):
                return $this->handleNewsletterSync($syncRequest, $data);
                
            default:
                // For other endpoints, try to forward to the appropriate controller
                return $this->forwardToController($syncRequest);
        }
    }

    /**
     * Handle contact form sync
     */
    private function handleContactSync(Request $request, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'type' => 'validation_error',
                'message' => 'Contact form validation failed',
                'errors' => $validator->errors()->toArray(),
                'data' => $data,
                'requires_user_action' => true
            ];
        }

        // Process contact form submission
        // This would typically save to database or send email
        Log::info('Contact form synced', $data);

        return [
            'success' => true,
            'type' => 'contact_form',
            'status' => 'submitted',
            'message' => 'Contact form submitted successfully'
        ];
    }

    /**
     * Handle careers form sync
     */
    private function handleCareersSync(Request $request, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'experience' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'type' => 'validation_error',
                'message' => 'Career application validation failed',
                'errors' => $validator->errors()->toArray(),
                'data' => $data,
                'requires_user_action' => true
            ];
        }

        // Process career application
        Log::info('Career application synced', $data);

        return [
            'success' => true,
            'type' => 'career_application',
            'status' => 'submitted',
            'message' => 'Career application submitted successfully'
        ];
    }

    /**
     * Handle newsletter subscription sync
     */
    private function handleNewsletterSync(Request $request, array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'type' => 'validation_error',
                'message' => 'Newsletter subscription validation failed',
                'errors' => $validator->errors()->toArray(),
                'data' => $data,
                'requires_user_action' => true
            ];
        }

        // Process newsletter subscription
        Log::info('Newsletter subscription synced', $data);

        return [
            'success' => true,
            'type' => 'newsletter_subscription',
            'status' => 'subscribed',
            'message' => 'Newsletter subscription successful'
        ];
    }

    /**
     * Forward request to appropriate controller
     */
    private function forwardToController(Request $request)
    {
        // This is a simplified approach - in a real application,
        // you might want to use Laravel's routing system more directly
        
        try {
            $response = app()->handle($request);
            
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                return [
                    'status' => 'forwarded',
                    'message' => 'Request processed successfully'
                ];
            } else {
                throw new \Exception('Controller returned error status: ' . $response->getStatusCode());
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to forward request: ' . $e->getMessage());
        }
    }

    /**
     * Get sync queue status
     */
    public function getQueueStatus(Request $request)
    {
        // This endpoint can be used by the frontend to check sync status
        return response()->json([
            'success' => true,
            'message' => 'Sync service is operational',
            'timestamp' => now()->toISOString(),
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Clear sync queue (for debugging)
     */
    public function clearQueue(Request $request)
    {
        // This is mainly for debugging purposes
        Log::info('Sync queue clear requested');
        
        return response()->json([
            'success' => true,
            'message' => 'Queue clear request acknowledged',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Refresh CSRF token for sync requests
     */
    public function refreshToken(Request $request)
    {
        return response()->json([
            'success' => true,
            'csrf_token' => csrf_token(),
            'timestamp' => now()->toISOString()
        ]);
    }
}