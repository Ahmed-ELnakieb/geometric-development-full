<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    protected $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => config('pwa.push_notifications.vapid.subject'),
                'publicKey' => config('pwa.push_notifications.vapid.public_key'),
                'privateKey' => config('pwa.push_notifications.vapid.private_key'),
            ]
        ]);
    }

    /**
     * Send notification to a specific subscription
     */
    public function sendToSubscription(PushSubscription $pushSubscription, array $notification)
    {
        try {
            $subscription = Subscription::create([
                'endpoint' => $pushSubscription->endpoint,
                'keys' => $pushSubscription->keys
            ]);

            $payload = json_encode($notification);
            
            $report = $this->webPush->sendOneNotification($subscription, $payload);

            // Update last used timestamp
            $pushSubscription->updateLastUsed();

            if ($report->isSuccess()) {
                Log::info('Push notification sent successfully', [
                    'subscription_id' => $pushSubscription->id,
                    'title' => $notification['title'] ?? 'No title'
                ]);

                return [
                    'success' => true,
                    'message' => 'Notification sent successfully'
                ];
            } else {
                Log::warning('Push notification failed', [
                    'subscription_id' => $pushSubscription->id,
                    'reason' => $report->getReason(),
                    'response' => $report->getResponse()
                ]);

                // If subscription is invalid, remove it
                if ($report->isSubscriptionExpired()) {
                    $pushSubscription->delete();
                    Log::info('Removed expired push subscription', [
                        'subscription_id' => $pushSubscription->id
                    ]);
                }

                return [
                    'success' => false,
                    'message' => 'Failed to send notification: ' . $report->getReason()
                ];
            }

        } catch (\Exception $e) {
            Log::error('Push notification service error', [
                'subscription_id' => $pushSubscription->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Push notification service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send notification to a specific user (all their subscriptions)
     */
    public function sendToUser(User $user, array $notification)
    {
        $subscriptions = PushSubscription::forUser($user->id)->active()->get();
        
        if ($subscriptions->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No active subscriptions found for user',
                'sent' => 0,
                'failed' => 0
            ];
        }

        $sent = 0;
        $failed = 0;

        foreach ($subscriptions as $subscription) {
            $result = $this->sendToSubscription($subscription, $notification);
            
            if ($result['success']) {
                $sent++;
            } else {
                $failed++;
            }
        }

        Log::info('Sent notifications to user', [
            'user_id' => $user->id,
            'sent' => $sent,
            'failed' => $failed,
            'title' => $notification['title'] ?? 'No title'
        ]);

        return [
            'success' => $sent > 0,
            'message' => "Sent to {$sent} subscriptions, {$failed} failed",
            'sent' => $sent,
            'failed' => $failed
        ];
    }

    /**
     * Send notification to all active subscriptions
     */
    public function sendToAll(array $notification)
    {
        $subscriptions = PushSubscription::active()->get();
        
        if ($subscriptions->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No active subscriptions found',
                'sent' => 0,
                'failed' => 0
            ];
        }

        $sent = 0;
        $failed = 0;
        $notifications = [];

        // Prepare all notifications
        foreach ($subscriptions as $pushSubscription) {
            try {
                $subscription = Subscription::create([
                    'endpoint' => $pushSubscription->endpoint,
                    'keys' => $pushSubscription->keys
                ]);

                $notifications[] = [
                    'subscription' => $subscription,
                    'payload' => json_encode($notification),
                    'push_subscription' => $pushSubscription
                ];

            } catch (\Exception $e) {
                Log::error('Failed to prepare notification', [
                    'subscription_id' => $pushSubscription->id,
                    'error' => $e->getMessage()
                ]);
                $failed++;
            }
        }

        // Send all notifications
        foreach ($notifications as $notificationData) {
            try {
                $report = $this->webPush->sendOneNotification(
                    $notificationData['subscription'],
                    $notificationData['payload']
                );

                if ($report->isSuccess()) {
                    $sent++;
                    $notificationData['push_subscription']->updateLastUsed();
                } else {
                    $failed++;
                    
                    // Remove expired subscriptions
                    if ($report->isSubscriptionExpired()) {
                        $notificationData['push_subscription']->delete();
                    }
                    
                    Log::warning('Notification failed', [
                        'subscription_id' => $notificationData['push_subscription']->id,
                        'reason' => $report->getReason()
                    ]);
                }

            } catch (\Exception $e) {
                $failed++;
                Log::error('Failed to send notification', [
                    'subscription_id' => $notificationData['push_subscription']->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Broadcast notification completed', [
            'sent' => $sent,
            'failed' => $failed,
            'title' => $notification['title'] ?? 'No title'
        ]);

        return [
            'success' => $sent > 0,
            'message' => "Sent to {$sent} subscriptions, {$failed} failed",
            'sent' => $sent,
            'failed' => $failed
        ];
    }

    /**
     * Send notification to users by IDs
     */
    public function sendToUsers(array $userIds, array $notification)
    {
        $subscriptions = PushSubscription::whereIn('user_id', $userIds)->active()->get();
        
        if ($subscriptions->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No active subscriptions found for specified users',
                'sent' => 0,
                'failed' => 0
            ];
        }

        $sent = 0;
        $failed = 0;

        foreach ($subscriptions as $subscription) {
            $result = $this->sendToSubscription($subscription, $notification);
            
            if ($result['success']) {
                $sent++;
            } else {
                $failed++;
            }
        }

        Log::info('Sent notifications to multiple users', [
            'user_ids' => $userIds,
            'sent' => $sent,
            'failed' => $failed,
            'title' => $notification['title'] ?? 'No title'
        ]);

        return [
            'success' => $sent > 0,
            'message' => "Sent to {$sent} subscriptions, {$failed} failed",
            'sent' => $sent,
            'failed' => $failed
        ];
    }

    /**
     * Create a standard notification payload
     */
    public function createNotification(string $title, string $body, array $options = []): array
    {
        return array_merge([
            'title' => $title,
            'body' => $body,
            'icon' => '/assets/img/logo/favicon.png',
            'badge' => '/assets/img/logo/favicon.png',
            'data' => [
                'url' => '/',
                'timestamp' => now()->toISOString()
            ],
            'requireInteraction' => false,
            'silent' => false
        ], $options);
    }

    /**
     * Create notification with action buttons
     */
    public function createActionNotification(string $title, string $body, array $actions, array $options = []): array
    {
        $notification = $this->createNotification($title, $body, $options);
        $notification['actions'] = $actions;
        $notification['requireInteraction'] = true; // Required for action buttons
        
        return $notification;
    }

    /**
     * Send welcome notification to new subscriber
     */
    public function sendWelcomeNotification(PushSubscription $subscription)
    {
        $notification = $this->createNotification(
            'Welcome to Geometric Development!',
            'Thank you for enabling notifications. Stay updated with our latest projects and news.',
            [
                'data' => [
                    'url' => '/',
                    'action' => 'welcome',
                    'timestamp' => now()->toISOString()
                ]
            ]
        );

        return $this->sendToSubscription($subscription, $notification);
    }

    /**
     * Test push notification service configuration
     */
    public function testConfiguration(): array
    {
        try {
            $publicKey = config('pwa.push_notifications.vapid.public_key');
            $privateKey = config('pwa.push_notifications.vapid.private_key');
            $subject = config('pwa.push_notifications.vapid.subject');

            if (empty($publicKey) || empty($privateKey) || empty($subject)) {
                return [
                    'success' => false,
                    'message' => 'VAPID configuration is incomplete'
                ];
            }

            // Test WebPush initialization
            $testWebPush = new WebPush([
                'VAPID' => [
                    'subject' => $subject,
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ]
            ]);

            return [
                'success' => true,
                'message' => 'Push notification service is properly configured',
                'config' => [
                    'public_key_length' => strlen($publicKey),
                    'private_key_length' => strlen($privateKey),
                    'subject' => $subject
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Configuration test failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get service statistics
     */
    public function getStatistics(): array
    {
        return [
            'total_subscriptions' => PushSubscription::count(),
            'active_subscriptions' => PushSubscription::active()->count(),
            'user_subscriptions' => PushSubscription::whereNotNull('user_id')->count(),
            'guest_subscriptions' => PushSubscription::whereNull('user_id')->count(),
            'recent_subscriptions' => PushSubscription::where('created_at', '>', now()->subDays(7))->count(),
            'expired_subscriptions' => PushSubscription::where('last_used_at', '<', now()->subDays(30))->count()
        ];
    }

    /**
     * Clean up expired subscriptions
     */
    public function cleanupExpiredSubscriptions(): int
    {
        $expiredCount = PushSubscription::where('last_used_at', '<', now()->subDays(30))->count();
        PushSubscription::where('last_used_at', '<', now()->subDays(30))->delete();

        Log::info('Cleaned up expired push subscriptions', [
            'deleted_count' => $expiredCount
        ]);

        return $expiredCount;
    }
}