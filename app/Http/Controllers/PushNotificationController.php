<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PushNotificationController extends Controller
{
    protected $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Get push notification configuration
     */
    public function getConfig()
    {
        return response()->json([
            'vapid_public_key' => config('pwa.push_notifications.vapid.public_key'),
            'enabled' => config('pwa.push_notifications.enabled', true)
        ]);
    }

    /**
     * Subscribe to push notifications
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid subscription data',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $userId = auth()->id(); // null for guest users
            $userAgent = $request->header('User-Agent');

            // Check if subscription already exists
            $existingSubscription = PushSubscription::where('endpoint', $request->endpoint)
                ->where('user_id', $userId)
                ->first();

            if ($existingSubscription) {
                // Update existing subscription
                $existingSubscription->update([
                    'keys' => $request->keys,
                    'user_agent' => $userAgent,
                    'last_used_at' => now()
                ]);

                $subscription = $existingSubscription;
            } else {
                // Create new subscription
                $subscription = PushSubscription::create([
                    'endpoint' => $request->endpoint,
                    'keys' => $request->keys,
                    'user_id' => $userId,
                    'user_agent' => $userAgent,
                    'last_used_at' => now()
                ]);
            }

            Log::info('Push subscription created/updated', [
                'subscription_id' => $subscription->id,
                'user_id' => $userId,
                'endpoint' => substr($request->endpoint, 0, 50) . '...'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to push notifications',
                'subscription_id' => $subscription->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create push subscription', [
                'error' => $e->getMessage(),
                'endpoint' => $request->endpoint
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to push notifications'
            ], 500);
        }
    }

    /**
     * Unsubscribe from push notifications
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid unsubscribe data',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $userId = auth()->id();

            $subscription = PushSubscription::where('endpoint', $request->endpoint)
                ->where('user_id', $userId)
                ->first();

            if ($subscription) {
                $subscription->delete();
                
                Log::info('Push subscription removed', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $userId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully unsubscribed from push notifications'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscription not found'
                ], 404);
            }

        } catch (\Exception $e) {
            Log::error('Failed to remove push subscription', [
                'error' => $e->getMessage(),
                'endpoint' => $request->endpoint
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe from push notifications'
            ], 500);
        }
    }

    /**
     * Verify subscription exists
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification data'
            ], 400);
        }

        $userId = auth()->id();
        
        $subscription = PushSubscription::where('endpoint', $request->endpoint)
            ->where('user_id', $userId)
            ->first();

        if ($subscription) {
            $subscription->updateLastUsed();
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription verified',
                'subscription_id' => $subscription->id
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }
    }

    /**
     * Send test notification
     */
    public function sendTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid test data'
            ], 400);
        }

        try {
            $userId = auth()->id();
            
            $subscription = PushSubscription::where('endpoint', $request->endpoint)
                ->where('user_id', $userId)
                ->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscription not found'
                ], 404);
            }

            $notification = [
                'title' => 'Test Notification',
                'body' => 'This is a test notification from Geometric Development PWA',
                'icon' => '/assets/img/logo/favicon.png',
                'badge' => '/assets/img/logo/favicon.png',
                'data' => [
                    'url' => '/',
                    'timestamp' => now()->toISOString()
                ]
            ];

            $result = $this->pushService->sendToSubscription($subscription, $notification);

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send test notification', [
                'error' => $e->getMessage(),
                'endpoint' => $request->endpoint
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification to all subscribers
     */
    public function sendToAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:500',
            'url' => 'nullable|url',
            'icon' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid notification data',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $notification = [
                'title' => $request->title,
                'body' => $request->body,
                'icon' => $request->icon ?: '/assets/img/logo/favicon.png',
                'badge' => '/assets/img/logo/favicon.png',
                'data' => [
                    'url' => $request->url ?: '/',
                    'timestamp' => now()->toISOString()
                ]
            ];

            $result = $this->pushService->sendToAll($notification);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to all subscribers',
                'sent_count' => $result['sent'],
                'failed_count' => $result['failed']
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send notification to all', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subscription statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_subscriptions' => PushSubscription::count(),
                'active_subscriptions' => PushSubscription::active()->count(),
                'user_subscriptions' => PushSubscription::whereNotNull('user_id')->count(),
                'guest_subscriptions' => PushSubscription::whereNull('user_id')->count(),
                'recent_subscriptions' => PushSubscription::where('created_at', '>', now()->subDays(7))->count()
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics'
            ], 500);
        }
    }

    /**
     * Clean up expired subscriptions
     */
    public function cleanup()
    {
        try {
            $expiredCount = PushSubscription::where('last_used_at', '<', now()->subDays(30))->count();
            PushSubscription::where('last_used_at', '<', now()->subDays(30))->delete();

            Log::info('Cleaned up expired push subscriptions', [
                'deleted_count' => $expiredCount
            ]);

            return response()->json([
                'success' => true,
                'message' => "Cleaned up {$expiredCount} expired subscriptions"
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to cleanup expired subscriptions', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cleanup expired subscriptions'
            ], 500);
        }
    }
}
