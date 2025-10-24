@extends('layouts.app')

@section('title', 'PWA Test - Geometric Development')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-mobile-alt"></i> PWA Advanced Test Page
                    </h3>
                    <small>Test all Progressive Web App features including Background Sync and Push Notifications</small>
                </div>
                <div class="card-body">
                    
                    <!-- Core PWA Status -->
                    <div class="row">
                        <div class="col-12">
                            <h4><i class="fas fa-cogs"></i> Core PWA Status</h4>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="alert alert-info text-center">
                                <h6>Service Worker</h6>
                                <p id="sw-status" class="mb-0">Checking...</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-info text-center">
                                <h6>Install Prompt</h6>
                                <p id="install-status" class="mb-0">Checking...</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-info text-center">
                                <h6>Cache Status</h6>
                                <p id="cache-status" class="mb-0">Checking...</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-info text-center">
                                <h6>Network Status</h6>
                                <p id="network-status" class="mb-0">Online</p>
                            </div>
                        </div>
                    </div>

                    <!-- Background Sync Testing -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4><i class="fas fa-sync-alt"></i> Background Sync Testing</h4>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Sync Queue Status</h6>
                                </div>
                                <div class="card-body">
                                    <div id="sync-status">
                                        <div class="text-center">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                            <p class="mt-2 mb-0">Loading sync status...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Sync Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button id="test-sync" class="btn btn-primary btn-sm">
                                            <i class="fas fa-paper-plane"></i> Test Background Sync
                                        </button>
                                        <button id="force-sync" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-sync"></i> Force Sync Now
                                        </button>
                                        <button id="clear-sync-queue" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-trash"></i> Clear Sync Queue
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Push Notifications Testing -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4><i class="fas fa-bell"></i> Push Notifications Testing</h4>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Notification Status</h6>
                                </div>
                                <div class="card-body">
                                    <div id="notification-status">
                                        <div class="text-center">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                            <p class="mt-2 mb-0">Checking notification status...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Notification Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button id="request-permission" class="btn btn-success btn-sm">
                                            <i class="fas fa-bell"></i> Request Permission
                                        </button>
                                        <button id="subscribe-push" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Subscribe to Push
                                        </button>
                                        <button id="test-notification" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-paper-plane"></i> Send Test Notification
                                        </button>
                                        <button id="unsubscribe-push" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-minus"></i> Unsubscribe
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Sync Testing -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4><i class="fas fa-wpforms"></i> Form Sync Testing</h4>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Test Form (with Background Sync)</h6>
                                </div>
                                <div class="card-body">
                                    <form id="test-form" data-sync="true" data-sync-action="test_form_submit" action="/api/sync/process" method="POST">
                                        <div class="mb-3">
                                            <label for="test-name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="test-name" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="test-email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="test-email" name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="test-message" class="form-label">Message</label>
                                            <textarea class="form-control" id="test-message" name="message" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Submit (with Sync)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Form Sync Info</h6>
                                </div>
                                <div class="card-body">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        This form will work offline! Try:
                                    </small>
                                    <ul class="small mt-2">
                                        <li>Submit while online (immediate)</li>
                                        <li>Go offline and submit (queued)</li>
                                        <li>Come back online (auto-sync)</li>
                                        <li>Submit invalid data (validation)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4><i class="fas fa-tools"></i> Advanced Actions</h4>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Cache Management</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button id="test-cache" class="btn btn-primary btn-sm">
                                            <i class="fas fa-search"></i> Test Cache
                                        </button>
                                        <button id="clear-cache" class="btn btn-warning btn-sm">
                                            <i class="fas fa-trash"></i> Clear Cache
                                        </button>
                                        <button id="update-sw" class="btn btn-info btn-sm">
                                            <i class="fas fa-sync"></i> Update Service Worker
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Debug Tools</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button id="export-logs" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i> Export Debug Logs
                                        </button>
                                        <button id="reset-pwa" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-redo"></i> Reset PWA Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Feature Status</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0" id="feature-status">
                                        <li><i class="fas fa-spinner fa-spin"></i> Loading...</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Console -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-terminal"></i> Debug Console</h6>
                                    <button id="clear-console" class="btn btn-sm btn-outline-secondary">Clear</button>
                                </div>
                                <div class="card-body">
                                    <div id="debug-console" style="height: 200px; overflow-y: auto; background-color: #f8f9fa; padding: 10px; font-family: monospace; font-size: 12px; border: 1px solid #dee2e6;">
                                        <div class="text-muted">Debug console initialized...</div>
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

<script>
class PWATestPage {
    constructor() {
        this.syncManager = window.syncManager;
        this.notificationManager = window.notificationManager;
        this.formSync = window.formSync;
        this.debugLogs = [];
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkCoreStatus();
        this.checkSyncStatus();
        this.checkNotificationStatus();
        this.updateFeatureStatus();
        this.setupDebugConsole();
    }

    setupEventListeners() {
        // Core PWA actions
        document.getElementById('test-cache').addEventListener('click', () => this.testCache());
        document.getElementById('clear-cache').addEventListener('click', () => this.clearCache());
        document.getElementById('update-sw').addEventListener('click', () => this.updateServiceWorker());

        // Background sync actions
        document.getElementById('test-sync').addEventListener('click', () => this.testBackgroundSync());
        document.getElementById('force-sync').addEventListener('click', () => this.forceSync());
        document.getElementById('clear-sync-queue').addEventListener('click', () => this.clearSyncQueue());

        // Push notification actions
        document.getElementById('request-permission').addEventListener('click', () => this.requestNotificationPermission());
        document.getElementById('subscribe-push').addEventListener('click', () => this.subscribeToPush());
        document.getElementById('test-notification').addEventListener('click', () => this.testNotification());
        document.getElementById('unsubscribe-push').addEventListener('click', () => this.unsubscribeFromPush());

        // Debug actions
        document.getElementById('export-logs').addEventListener('click', () => this.exportLogs());
        document.getElementById('reset-pwa').addEventListener('click', () => this.resetPWAData());
        document.getElementById('clear-console').addEventListener('click', () => this.clearDebugConsole());

        // Network status
        window.addEventListener('online', () => this.updateNetworkStatus());
        window.addEventListener('offline', () => this.updateNetworkStatus());
    }

    async checkCoreStatus() {
        // Service Worker status
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.getRegistration();
                if (registration) {
                    document.getElementById('sw-status').innerHTML = '<span class="text-success">✓ Active</span>';
                    this.log('Service Worker is active');
                } else {
                    document.getElementById('sw-status').innerHTML = '<span class="text-danger">✗ Not registered</span>';
                    this.log('Service Worker not registered', 'error');
                }
            } catch (error) {
                document.getElementById('sw-status').innerHTML = '<span class="text-danger">✗ Error</span>';
                this.log('Service Worker error: ' + error.message, 'error');
            }
        } else {
            document.getElementById('sw-status').innerHTML = '<span class="text-danger">✗ Not supported</span>';
            this.log('Service Worker not supported', 'error');
        }

        // Install prompt status
        if (window.matchMedia('(display-mode: standalone)').matches) {
            document.getElementById('install-status').innerHTML = '<span class="text-success">✓ Installed</span>';
            this.log('PWA is installed');
        } else {
            document.getElementById('install-status').innerHTML = '<span class="text-warning">⚠ Not installed</span>';
            this.log('PWA not installed');
        }

        // Network status
        this.updateNetworkStatus();

        // Cache status
        this.testCache();
    }

    async checkSyncStatus() {
        if (!this.syncManager) {
            document.getElementById('sync-status').innerHTML = '<div class="text-danger">Sync Manager not available</div>';
            return;
        }

        try {
            const status = await this.syncManager.getQueueStatus();
            const html = `
                <div class="row text-center">
                    <div class="col-6">
                        <div class="text-primary">
                            <strong>${status.total}</strong>
                            <small class="d-block">Total Items</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-warning">
                            <strong>${status.pending}</strong>
                            <small class="d-block">Pending</small>
                        </div>
                    </div>
                </div>
                <div class="row text-center mt-2">
                    <div class="col-6">
                        <div class="text-info">
                            <strong>${status.processing}</strong>
                            <small class="d-block">Processing</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-danger">
                            <strong>${status.failed}</strong>
                            <small class="d-block">Failed</small>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('sync-status').innerHTML = html;
            this.log(`Sync queue: ${status.total} total, ${status.pending} pending`);
        } catch (error) {
            document.getElementById('sync-status').innerHTML = '<div class="text-danger">Error loading sync status</div>';
            this.log('Sync status error: ' + error.message, 'error');
        }
    }

    async checkNotificationStatus() {
        if (!this.notificationManager) {
            document.getElementById('notification-status').innerHTML = '<div class="text-danger">Notification Manager not available</div>';
            return;
        }

        const status = this.notificationManager.getSubscriptionStatus();
        const html = `
            <div class="mb-2">
                <strong>Support:</strong> 
                <span class="badge bg-${status.isSupported ? 'success' : 'danger'}">
                    ${status.isSupported ? 'Supported' : 'Not Supported'}
                </span>
            </div>
            <div class="mb-2">
                <strong>Permission:</strong> 
                <span class="badge bg-${this.getPermissionBadgeClass(status.permission)}">
                    ${status.permission}
                </span>
            </div>
            <div>
                <strong>Subscribed:</strong> 
                <span class="badge bg-${status.isSubscribed ? 'success' : 'secondary'}">
                    ${status.isSubscribed ? 'Yes' : 'No'}
                </span>
            </div>
        `;
        document.getElementById('notification-status').innerHTML = html;
        this.log(`Notifications: ${status.permission}, subscribed: ${status.isSubscribed}`);
    }

    getPermissionBadgeClass(permission) {
        switch (permission) {
            case 'granted': return 'success';
            case 'denied': return 'danger';
            default: return 'warning';
        }
    }

    updateNetworkStatus() {
        const isOnline = navigator.onLine;
        const statusElement = document.getElementById('network-status');
        statusElement.innerHTML = `<span class="text-${isOnline ? 'success' : 'danger'}">${isOnline ? 'Online' : 'Offline'}</span>`;
        this.log(`Network status: ${isOnline ? 'Online' : 'Offline'}`);
    }

    async testCache() {
        try {
            const cacheNames = await caches.keys();
            let totalItems = 0;
            
            for (const cacheName of cacheNames) {
                const cache = await caches.open(cacheName);
                const keys = await cache.keys();
                totalItems += keys.length;
            }
            
            document.getElementById('cache-status').innerHTML = `<span class="text-success">✓ ${totalItems} items cached</span>`;
            this.log(`Cache test: ${totalItems} items in ${cacheNames.length} caches`);
        } catch (error) {
            document.getElementById('cache-status').innerHTML = '<span class="text-danger">✗ Cache error</span>';
            this.log('Cache test error: ' + error.message, 'error');
        }
    }

    async clearCache() {
        try {
            const cacheNames = await caches.keys();
            await Promise.all(cacheNames.map(name => caches.delete(name)));
            document.getElementById('cache-status').innerHTML = '<span class="text-warning">Cache cleared</span>';
            this.log('All caches cleared');
        } catch (error) {
            this.log('Cache clear error: ' + error.message, 'error');
        }
    }

    async updateServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.getRegistration();
                if (registration) {
                    await registration.update();
                    this.log('Service worker update requested');
                    alert('Service worker update requested');
                }
            } catch (error) {
                this.log('Service worker update error: ' + error.message, 'error');
            }
        }
    }

    async testBackgroundSync() {
        if (!this.syncManager) {
            alert('Sync Manager not available');
            return;
        }

        try {
            const testData = {
                name: 'Test User',
                email: 'test@example.com',
                message: 'This is a test sync message',
                timestamp: new Date().toISOString()
            };

            const syncId = await this.syncManager.queueAction(
                'test_sync',
                '/api/sync/process',
                'POST',
                testData
            );

            this.log(`Test sync queued with ID: ${syncId}`);
            alert('Test sync queued successfully!');
            
            // Refresh sync status
            setTimeout(() => this.checkSyncStatus(), 1000);
        } catch (error) {
            this.log('Test sync error: ' + error.message, 'error');
            alert('Test sync failed: ' + error.message);
        }
    }

    async forceSync() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.ready;
                registration.active.postMessage({ type: 'FORCE_SYNC' });
                this.log('Force sync requested');
                alert('Force sync requested');
                
                // Refresh sync status
                setTimeout(() => this.checkSyncStatus(), 2000);
            } catch (error) {
                this.log('Force sync error: ' + error.message, 'error');
            }
        }
    }

    async clearSyncQueue() {
        if (!this.syncManager) {
            alert('Sync Manager not available');
            return;
        }

        if (confirm('Are you sure you want to clear the sync queue?')) {
            try {
                const deletedCount = await this.syncManager.clearQueue(true);
                this.log(`Cleared ${deletedCount} items from sync queue`);
                alert(`Cleared ${deletedCount} items from sync queue`);
                
                // Refresh sync status
                this.checkSyncStatus();
            } catch (error) {
                this.log('Clear sync queue error: ' + error.message, 'error');
            }
        }
    }

    async requestNotificationPermission() {
        if (!this.notificationManager) {
            alert('Notification Manager not available');
            return;
        }

        try {
            const granted = await this.notificationManager.requestPermission();
            this.log(`Notification permission: ${granted ? 'granted' : 'denied'}`);
            
            if (granted) {
                alert('Notification permission granted!');
            } else {
                alert('Notification permission denied');
            }
            
            this.checkNotificationStatus();
        } catch (error) {
            this.log('Request permission error: ' + error.message, 'error');
        }
    }

    async subscribeToPush() {
        if (!this.notificationManager) {
            alert('Notification Manager not available');
            return;
        }

        try {
            const subscription = await this.notificationManager.subscribe();
            this.log('Push subscription created');
            alert('Successfully subscribed to push notifications!');
            
            this.checkNotificationStatus();
        } catch (error) {
            this.log('Subscribe error: ' + error.message, 'error');
            alert('Subscribe failed: ' + error.message);
        }
    }

    async testNotification() {
        if (!this.notificationManager) {
            alert('Notification Manager not available');
            return;
        }

        try {
            const result = await this.notificationManager.sendTestNotification();
            this.log('Test notification sent');
            alert('Test notification sent!');
        } catch (error) {
            this.log('Test notification error: ' + error.message, 'error');
            alert('Test notification failed: ' + error.message);
        }
    }

    async unsubscribeFromPush() {
        if (!this.notificationManager) {
            alert('Notification Manager not available');
            return;
        }

        if (confirm('Are you sure you want to unsubscribe from push notifications?')) {
            try {
                const success = await this.notificationManager.unsubscribe();
                this.log('Push unsubscribe: ' + (success ? 'success' : 'failed'));
                
                if (success) {
                    alert('Successfully unsubscribed from push notifications');
                } else {
                    alert('Failed to unsubscribe');
                }
                
                this.checkNotificationStatus();
            } catch (error) {
                this.log('Unsubscribe error: ' + error.message, 'error');
            }
        }
    }

    updateFeatureStatus() {
        const features = [
            {
                name: 'Offline Support',
                status: 'serviceWorker' in navigator ? 'success' : 'danger',
                icon: 'fas fa-wifi'
            },
            {
                name: 'Install Prompt',
                status: window.matchMedia('(display-mode: standalone)').matches ? 'success' : 'warning',
                icon: 'fas fa-download'
            },
            {
                name: 'Background Sync',
                status: this.syncManager ? 'success' : 'danger',
                icon: 'fas fa-sync-alt'
            },
            {
                name: 'Push Notifications',
                status: this.notificationManager && this.notificationManager.isSupported ? 'success' : 'danger',
                icon: 'fas fa-bell'
            }
        ];

        const html = features.map(feature => `
            <li class="d-flex justify-content-between align-items-center">
                <span><i class="${feature.icon}"></i> ${feature.name}</span>
                <span class="badge bg-${feature.status}">
                    ${feature.status === 'success' ? '✓' : feature.status === 'warning' ? '⚠' : '✗'}
                </span>
            </li>
        `).join('');

        document.getElementById('feature-status').innerHTML = html;
    }

    setupDebugConsole() {
        // Intercept console logs
        const originalLog = console.log;
        const originalError = console.error;
        const originalWarn = console.warn;

        console.log = (...args) => {
            originalLog.apply(console, args);
            this.log(args.join(' '));
        };

        console.error = (...args) => {
            originalError.apply(console, args);
            this.log(args.join(' '), 'error');
        };

        console.warn = (...args) => {
            originalWarn.apply(console, args);
            this.log(args.join(' '), 'warn');
        };
    }

    log(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = { timestamp, message, type };
        this.debugLogs.push(logEntry);

        // Keep only last 100 logs
        if (this.debugLogs.length > 100) {
            this.debugLogs.shift();
        }

        // Update debug console
        const console = document.getElementById('debug-console');
        const colorClass = type === 'error' ? 'text-danger' : type === 'warn' ? 'text-warning' : 'text-muted';
        
        const logDiv = document.createElement('div');
        logDiv.className = colorClass;
        logDiv.innerHTML = `<small>[${timestamp}]</small> ${message}`;
        
        console.appendChild(logDiv);
        console.scrollTop = console.scrollHeight;
    }

    clearDebugConsole() {
        document.getElementById('debug-console').innerHTML = '<div class="text-muted">Debug console cleared...</div>';
        this.debugLogs = [];
    }

    exportLogs() {
        const logsText = this.debugLogs.map(log => 
            `[${log.timestamp}] ${log.type.toUpperCase()}: ${log.message}`
        ).join('\n');

        const blob = new Blob([logsText], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = `pwa-debug-logs-${new Date().toISOString().slice(0, 19)}.txt`;
        a.click();
        
        URL.revokeObjectURL(url);
        this.log('Debug logs exported');
    }

    async resetPWAData() {
        if (confirm('This will clear all PWA data including cache, sync queue, and subscriptions. Continue?')) {
            try {
                // Clear caches
                const cacheNames = await caches.keys();
                await Promise.all(cacheNames.map(name => caches.delete(name)));

                // Clear sync queue
                if (this.syncManager) {
                    await this.syncManager.clearQueue(true);
                }

                // Unsubscribe from notifications
                if (this.notificationManager) {
                    await this.notificationManager.unsubscribe();
                }

                // Clear IndexedDB
                if ('indexedDB' in window) {
                    indexedDB.deleteDatabase('GeometricPWA');
                }

                this.log('PWA data reset completed');
                alert('PWA data has been reset. Please refresh the page.');
                
                // Refresh status
                setTimeout(() => {
                    this.checkCoreStatus();
                    this.checkSyncStatus();
                    this.checkNotificationStatus();
                    this.updateFeatureStatus();
                }, 1000);
            } catch (error) {
                this.log('Reset PWA data error: ' + error.message, 'error');
                alert('Failed to reset PWA data: ' + error.message);
            }
        }
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    // Wait a bit for PWA managers to initialize
    setTimeout(() => {
        window.pwaTestPage = new PWATestPage();
    }, 1000);
});
</script>
@endsection