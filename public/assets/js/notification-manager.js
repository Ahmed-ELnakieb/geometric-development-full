/**
 * NotificationManager - Handles push notifications for PWA
 */
class NotificationManager {
    constructor() {
        this.vapidPublicKey = null;
        this.subscription = null;
        this.isSupported = this.checkSupport();
        this.permissionStatus = 'default';
        
        this.init();
    }

    /**
     * Check if push notifications are supported
     */
    checkSupport() {
        return 'serviceWorker' in navigator && 
               'PushManager' in window && 
               'Notification' in window;
    }

    /**
     * Initialize notification manager
     */
    async init() {
        if (!this.isSupported) {
            console.warn('Push notifications are not supported in this browser');
            return;
        }

        // Get VAPID public key from config
        try {
            const response = await fetch('/api/push/config');
            const config = await response.json();
            this.vapidPublicKey = config.vapid_public_key;
        } catch (error) {
            console.error('Failed to get push notification config:', error);
        }

        // Check current permission status
        this.permissionStatus = Notification.permission;
        
        // Check if already subscribed
        await this.checkExistingSubscription();
    }

    /**
     * Check for existing subscription
     */
    async checkExistingSubscription() {
        if (!('serviceWorker' in navigator)) return;

        try {
            const registration = await navigator.serviceWorker.ready;
            this.subscription = await registration.pushManager.getSubscription();
            
            if (this.subscription) {
                console.log('Existing push subscription found');
                // Verify subscription is still valid on server
                await this.verifySubscription();
            }
        } catch (error) {
            console.error('Failed to check existing subscription:', error);
        }
    }

    /**
     * Request notification permission
     */
    async requestPermission() {
        if (!this.isSupported) {
            throw new Error('Push notifications are not supported');
        }

        if (this.permissionStatus === 'granted') {
            return true;
        }

        try {
            const permission = await Notification.requestPermission();
            this.permissionStatus = permission;
            
            if (permission === 'granted') {
                console.log('Notification permission granted');
                return true;
            } else if (permission === 'denied') {
                console.warn('Notification permission denied');
                return false;
            } else {
                console.log('Notification permission dismissed');
                return false;
            }
        } catch (error) {
            console.error('Failed to request notification permission:', error);
            return false;
        }
    }

    /**
     * Subscribe to push notifications
     */
    async subscribe() {
        if (!this.isSupported) {
            throw new Error('Push notifications are not supported');
        }

        if (!this.vapidPublicKey) {
            throw new Error('VAPID public key not available');
        }

        // Request permission first
        const hasPermission = await this.requestPermission();
        if (!hasPermission) {
            throw new Error('Notification permission not granted');
        }

        try {
            const registration = await navigator.serviceWorker.ready;
            
            // Subscribe to push manager
            this.subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey)
            });

            console.log('Push subscription created:', this.subscription);

            // Send subscription to server
            await this.sendSubscriptionToServer(this.subscription);
            
            return this.subscription;
        } catch (error) {
            console.error('Failed to subscribe to push notifications:', error);
            throw error;
        }
    }

    /**
     * Unsubscribe from push notifications
     */
    async unsubscribe() {
        if (!this.subscription) {
            console.log('No active subscription to unsubscribe');
            return true;
        }

        try {
            // Unsubscribe from push manager
            const success = await this.subscription.unsubscribe();
            
            if (success) {
                console.log('Successfully unsubscribed from push notifications');
                
                // Remove subscription from server
                await this.removeSubscriptionFromServer(this.subscription);
                
                this.subscription = null;
                return true;
            } else {
                console.error('Failed to unsubscribe from push notifications');
                return false;
            }
        } catch (error) {
            console.error('Error unsubscribing from push notifications:', error);
            return false;
        }
    }

    /**
     * Send subscription to server
     */
    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                    keys: {
                        p256dh: this.arrayBufferToBase64(subscription.getKey('p256dh')),
                        auth: this.arrayBufferToBase64(subscription.getKey('auth'))
                    }
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('Subscription sent to server:', result);
            
            return result;
        } catch (error) {
            console.error('Failed to send subscription to server:', error);
            throw error;
        }
    }

    /**
     * Remove subscription from server
     */
    async removeSubscriptionFromServer(subscription) {
        try {
            const response = await fetch('/api/push/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('Subscription removed from server:', result);
            
            return result;
        } catch (error) {
            console.error('Failed to remove subscription from server:', error);
            throw error;
        }
    }

    /**
     * Verify subscription with server
     */
    async verifySubscription() {
        if (!this.subscription) return false;

        try {
            const response = await fetch('/api/push/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    endpoint: this.subscription.endpoint
                })
            });

            if (!response.ok) {
                // Subscription not valid on server, remove it
                await this.subscription.unsubscribe();
                this.subscription = null;
                return false;
            }

            return true;
        } catch (error) {
            console.error('Failed to verify subscription:', error);
            return false;
        }
    }

    /**
     * Get subscription status
     */
    getSubscriptionStatus() {
        return {
            isSupported: this.isSupported,
            permission: this.permissionStatus,
            isSubscribed: !!this.subscription,
            subscription: this.subscription
        };
    }

    /**
     * Handle push event (called from service worker)
     */
    handlePushEvent(event) {
        console.log('Push event received:', event);
        
        let notificationData = {
            title: 'Geometric Development',
            body: 'You have a new notification',
            icon: '/assets/img/logo/favicon.png',
            badge: '/assets/img/logo/favicon.png',
            data: {
                url: '/',
                timestamp: Date.now()
            }
        };

        // Parse push data if available
        if (event.data) {
            try {
                const pushData = event.data.json();
                notificationData = { ...notificationData, ...pushData };
            } catch (error) {
                console.error('Failed to parse push data:', error);
            }
        }

        return notificationData;
    }

    /**
     * Handle notification click (called from service worker)
     */
    handleNotificationClick(event) {
        console.log('Notification clicked:', event);
        
        const urlToOpen = event.notification.data?.url || '/';
        
        return {
            action: 'open_url',
            url: urlToOpen,
            closeNotification: true
        };
    }

    /**
     * Send test notification (for debugging)
     */
    async sendTestNotification() {
        if (!this.subscription) {
            throw new Error('No active subscription');
        }

        try {
            const response = await fetch('/api/push/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    endpoint: this.subscription.endpoint
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            console.log('Test notification sent:', result);
            
            return result;
        } catch (error) {
            console.error('Failed to send test notification:', error);
            throw error;
        }
    }

    /**
     * Utility: Convert VAPID key to Uint8Array
     */
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    /**
     * Utility: Convert ArrayBuffer to Base64
     */
    arrayBufferToBase64(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        for (let i = 0; i < bytes.byteLength; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    }

    /**
     * Show browser notification (fallback for unsupported push)
     */
    showLocalNotification(title, options = {}) {
        if (!this.isSupported || this.permissionStatus !== 'granted') {
            console.warn('Cannot show notification: not supported or permission not granted');
            return;
        }

        const notification = new Notification(title, {
            icon: '/assets/img/logo/favicon.png',
            badge: '/assets/img/logo/favicon.png',
            ...options
        });

        // Auto-close after 5 seconds
        setTimeout(() => {
            notification.close();
        }, 5000);

        return notification;
    }
}

// Create global instance
window.notificationManager = new NotificationManager();