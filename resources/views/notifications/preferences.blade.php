@extends('layouts.app')

@section('title', 'Notification Preferences')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-bell"></i> Notification Preferences
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Browser Support Check -->
                    <div id="browser-support" class="alert alert-info" style="display: none;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Push Notifications:</strong> <span id="support-message">Checking browser support...</span>
                    </div>

                    <!-- Permission Status -->
                    <div id="permission-status" class="alert" style="display: none;">
                        <i class="fas fa-shield-alt"></i>
                        <strong>Permission Status:</strong> <span id="permission-message">Checking permissions...</span>
                    </div>

                    <!-- Subscription Management -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Push Notifications</h5>
                            <p class="text-muted">
                                Enable push notifications to receive updates about new projects, blog posts, and important announcements.
                            </p>
                            
                            <div id="subscription-controls">
                                <button id="enable-notifications" class="btn btn-success" style="display: none;">
                                    <i class="fas fa-bell"></i> Enable Notifications
                                </button>
                                <button id="disable-notifications" class="btn btn-outline-danger" style="display: none;">
                                    <i class="fas fa-bell-slash"></i> Disable Notifications
                                </button>
                                <button id="test-notification" class="btn btn-outline-info" style="display: none;">
                                    <i class="fas fa-paper-plane"></i> Send Test
                                </button>
                            </div>

                            <div id="subscription-status" class="mt-3" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <div class="status-indicator me-2"></div>
                                    <span id="status-text">Checking status...</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5>Active Subscriptions</h5>
                            <div id="subscriptions-list">
                                <div class="text-center">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 mb-0">Loading subscriptions...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Categories (Future Enhancement) -->
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5>Notification Categories</h5>
                            <p class="text-muted">Choose what types of notifications you'd like to receive:</p>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="category-projects" checked disabled>
                                <label class="form-check-label" for="category-projects">
                                    <strong>New Projects</strong>
                                    <small class="d-block text-muted">Get notified when we launch new real estate projects</small>
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="category-blog" checked disabled>
                                <label class="form-check-label" for="category-blog">
                                    <strong>Blog Updates</strong>
                                    <small class="d-block text-muted">Receive notifications for new blog posts and articles</small>
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="category-careers" checked disabled>
                                <label class="form-check-label" for="category-careers">
                                    <strong>Career Opportunities</strong>
                                    <small class="d-block text-muted">Be the first to know about new job openings</small>
                                </label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="category-promotions" checked disabled>
                                <label class="form-check-label" for="category-promotions">
                                    <strong>Special Offers</strong>
                                    <small class="d-block text-muted">Get notified about exclusive deals and promotions</small>
                                </label>
                            </div>

                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Category preferences will be available in a future update. Currently, all notification types are enabled.
                            </small>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5>Need Help?</h5>
                            <div class="accordion" id="helpAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            How do push notifications work?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            Push notifications allow us to send you updates even when you're not actively browsing our website. 
                                            They appear as small pop-ups on your device and can be clicked to navigate directly to relevant content.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            How do I disable notifications?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            You can disable notifications at any time by clicking the "Disable Notifications" button above, 
                                            or by managing notification permissions in your browser settings.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            Are my notifications private and secure?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                        <div class="accordion-body">
                                            Yes! We use industry-standard encryption (VAPID) to ensure your notification subscriptions are secure. 
                                            We never share your notification data with third parties and you can unsubscribe at any time.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #6c757d;
}
.status-indicator.enabled {
    background-color: #28a745;
}
.status-indicator.disabled {
    background-color: #dc3545;
}
.subscription-item {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
}
</style>

<script>
class NotificationPreferences {
    constructor() {
        this.notificationManager = window.notificationManager;
        this.init();
    }

    async init() {
        await this.checkBrowserSupport();
        await this.checkPermissionStatus();
        await this.loadSubscriptions();
        this.setupEventListeners();
        this.updateUI();
    }

    checkBrowserSupport() {
        const supportDiv = document.getElementById('browser-support');
        const messageSpan = document.getElementById('support-message');
        
        if (this.notificationManager && this.notificationManager.isSupported) {
            supportDiv.className = 'alert alert-success';
            messageSpan.textContent = 'Your browser supports push notifications!';
        } else {
            supportDiv.className = 'alert alert-warning';
            messageSpan.textContent = 'Your browser does not support push notifications.';
        }
        
        supportDiv.style.display = 'block';
    }

    async checkPermissionStatus() {
        const statusDiv = document.getElementById('permission-status');
        const messageSpan = document.getElementById('permission-message');
        
        if (!this.notificationManager) {
            statusDiv.className = 'alert alert-warning';
            messageSpan.textContent = 'Notification manager not available';
            statusDiv.style.display = 'block';
            return;
        }

        const status = this.notificationManager.getSubscriptionStatus();
        
        switch (status.permission) {
            case 'granted':
                statusDiv.className = 'alert alert-success';
                messageSpan.textContent = 'Notifications are enabled';
                break;
            case 'denied':
                statusDiv.className = 'alert alert-danger';
                messageSpan.textContent = 'Notifications are blocked. Please enable them in your browser settings.';
                break;
            default:
                statusDiv.className = 'alert alert-info';
                messageSpan.textContent = 'Click "Enable Notifications" to get started';
        }
        
        statusDiv.style.display = 'block';
    }

    async loadSubscriptions() {
        try {
            const response = await fetch('/notifications/status');
            const data = await response.json();
            
            if (data.success) {
                this.renderSubscriptions(data.subscriptions);
            } else {
                this.renderSubscriptions([]);
            }
        } catch (error) {
            console.error('Failed to load subscriptions:', error);
            this.renderSubscriptions([]);
        }
    }

    renderSubscriptions(subscriptions) {
        const listDiv = document.getElementById('subscriptions-list');
        
        if (subscriptions.length === 0) {
            listDiv.innerHTML = '<p class="text-muted">No active subscriptions</p>';
            return;
        }

        let html = '';
        subscriptions.forEach(subscription => {
            html += `
                <div class="subscription-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">${subscription.endpoint}</small>
                            <small class="text-muted">
                                Created: ${subscription.created_at} | 
                                Last used: ${subscription.last_used_at}
                            </small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="notificationPrefs.removeSubscription(${subscription.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        listDiv.innerHTML = html;
    }

    setupEventListeners() {
        document.getElementById('enable-notifications').addEventListener('click', () => {
            this.enableNotifications();
        });

        document.getElementById('disable-notifications').addEventListener('click', () => {
            this.disableNotifications();
        });

        document.getElementById('test-notification').addEventListener('click', () => {
            this.sendTestNotification();
        });
    }

    updateUI() {
        if (!this.notificationManager) return;

        const status = this.notificationManager.getSubscriptionStatus();
        const enableBtn = document.getElementById('enable-notifications');
        const disableBtn = document.getElementById('disable-notifications');
        const testBtn = document.getElementById('test-notification');
        const statusDiv = document.getElementById('subscription-status');
        const statusText = document.getElementById('status-text');
        const indicator = document.querySelector('.status-indicator');

        if (status.isSubscribed) {
            enableBtn.style.display = 'none';
            disableBtn.style.display = 'inline-block';
            testBtn.style.display = 'inline-block';
            statusText.textContent = 'Notifications are enabled';
            indicator.className = 'status-indicator enabled';
        } else if (status.permission === 'granted') {
            enableBtn.style.display = 'inline-block';
            disableBtn.style.display = 'none';
            testBtn.style.display = 'none';
            statusText.textContent = 'Notifications are available but not enabled';
            indicator.className = 'status-indicator disabled';
        } else {
            enableBtn.style.display = 'inline-block';
            disableBtn.style.display = 'none';
            testBtn.style.display = 'none';
            statusText.textContent = 'Notifications are not enabled';
            indicator.className = 'status-indicator disabled';
        }

        statusDiv.style.display = 'block';
    }

    async enableNotifications() {
        try {
            await this.notificationManager.subscribe();
            await this.loadSubscriptions();
            this.updateUI();
            this.checkPermissionStatus();
            this.showAlert('Notifications enabled successfully!', 'success');
        } catch (error) {
            console.error('Failed to enable notifications:', error);
            this.showAlert('Failed to enable notifications: ' + error.message, 'danger');
        }
    }

    async disableNotifications() {
        try {
            await this.notificationManager.unsubscribe();
            await this.loadSubscriptions();
            this.updateUI();
            this.checkPermissionStatus();
            this.showAlert('Notifications disabled successfully!', 'info');
        } catch (error) {
            console.error('Failed to disable notifications:', error);
            this.showAlert('Failed to disable notifications: ' + error.message, 'danger');
        }
    }

    async sendTestNotification() {
        try {
            const response = await fetch('/notifications/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.showAlert(`Test notification sent! Delivered to ${data.sent} device(s).`, 'success');
            } else {
                this.showAlert('Failed to send test notification: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Failed to send test notification:', error);
            this.showAlert('Failed to send test notification', 'danger');
        }
    }

    async removeSubscription(subscriptionId) {
        if (!confirm('Are you sure you want to remove this subscription?')) {
            return;
        }

        try {
            const response = await fetch('/notifications/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ subscription_id: subscriptionId })
            });

            const data = await response.json();
            
            if (data.success) {
                await this.loadSubscriptions();
                this.updateUI();
                this.showAlert('Subscription removed successfully!', 'info');
            } else {
                this.showAlert('Failed to remove subscription: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Failed to remove subscription:', error);
            this.showAlert('Failed to remove subscription', 'danger');
        }
    }

    showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    window.notificationPrefs = new NotificationPreferences();
});
</script>
@endsection