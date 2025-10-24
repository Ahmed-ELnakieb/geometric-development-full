<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class NotificationPreferencesController extends Controller
{
    protected $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Show notification preferences page
     */
    public function index()
    {
        $userId = auth()->id();
        $subscriptions = [];
        $subscriptionCount = 0;

        if ($userId) {
            $subscriptions = PushSubscription::forUser($userId)->get();
            $subscriptionCount = $subscriptions->count();
        }

        return view('notifications.preferences', compact('subscriptions', 'subscriptionCount'));
    }

    /**
     * Get current subscription status
     */
    public function getStatus()
    {
        $userId = auth()->id();
        $subscriptions = [];

        if ($userId) {
            $subscriptions = PushSubscription::forUser($userId)
                ->select('id', 'endpoint', 'created_at', 'last_used_at')
                ->get()
                ->map(function ($subscription) {
                    return [
                        'id' => $subscription->id,
                        'endpoint' => substr($subscription->endpoint, 0, 50) . '...',
                        'created_at' => $subscription->created_at->format('M j, Y'),
                        'last_used_at' => $subscription->last_used_at ? $subscription->last_used_at->diffForHumans() : 'Never'
                    ];
                });
        }

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions,
            'count' => $subscriptions->count()
        ]);
    }

    /**
     * Remove specific subscription
     */
    public function removeSubscription(Request $request)
    {
        $userId = auth()->id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $subscription = PushSubscription::where('id', $request->subscription_id)
            ->where('user_id', $userId)
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        try {
            $subscription->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subscription'
            ], 500);
        }
    }

    /**
     * Send test notification to user
     */
    public function sendTest()
    {
        $userId = auth()->id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $user = auth()->user();
        $subscriptions = PushSubscription::forUser($userId)->active()->get();

        if ($subscriptions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscriptions found'
            ], 404);
        }

        try {
            $notification = $this->pushService->createNotification(
                'Test Notification',
                'This is a test notification to verify your settings are working correctly.',
                [
                    'data' => [
                        'url' => route('notifications.preferences'),
                        'action' => 'test_notification',
                        'timestamp' => now()->toISOString()
                    ]
                ]
            );

            $result = $this->pushService->sendToUser($user, $notification);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'sent' => $result['sent'],
                'failed' => $result['failed']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification: ' . $e->getMessage()
            ], 500);
        }
    }
}
