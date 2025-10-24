const CACHE_NAME = 'geometric-development-v{{ config("app.version", "1.0") }}';
const OFFLINE_CACHE = 'offline-v1';

// Files to cache immediately on install
const filesToCache = [
@foreach($cacheAssets as $asset)
    '{{ $asset }}',
@endforeach
@foreach($cacheRoutes as $route)
    '{{ $route }}',
@endforeach
    '/offline.html'
];

// Routes to cache when visited
const routesToCache = [
@foreach($cacheRoutes as $route)
    '{{ $route }}',
@endforeach
];

// Patterns to exclude from caching
const excludePatterns = [
@foreach($excludePatterns as $pattern)
    /{{ str_replace('*', '.*', $pattern) }}/,
@endforeach
];

// Check if URL should be excluded from caching
const shouldExclude = function(url) {
    return excludePatterns.some(pattern => pattern.test(url));
};

const preLoad = function () {
    return caches.open(CACHE_NAME).then(function (cache) {
        console.log('Caching essential files...');
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    console.log('Service Worker installing...');
    event.waitUntil(preLoad());
    self.skipWaiting();
});

const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    const url = new URL(request.url);
    
    // Only cache http(s) requests and exclude admin routes
    if (!request.url.startsWith('http') || shouldExclude(url.pathname)) {
        return Promise.resolve();
    }
    
    return caches.open(CACHE_NAME).then(function (cache) {
        return fetch(request).then(function (response) {
            // Only cache successful responses
            if (response.status === 200 && response.type === 'basic') {
                return cache.put(request, response.clone());
            }
            return response;
        }).catch(function() {
            // If fetch fails, don't cache
            return Promise.resolve();
        });
    });
};

const returnFromCache = function (request) {
    return caches.open(CACHE_NAME).then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match("/offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener("fetch", function (event) {
    const url = new URL(event.request.url);
    
    // Skip admin, filament routes and non-GET requests from PWA caching
    if (shouldExclude(url.pathname) || event.request.method !== 'GET') {
        return;
    }
    
    event.respondWith(
        caches.match(event.request).then(function(response) {
            // Return cached version or fetch from network
            if (response) {
                console.log('Serving from cache:', event.request.url);
                return response;
            }
            
            return checkResponse(event.request).catch(function () {
                console.log('Network failed, serving offline page for:', event.request.url);
                return returnFromCache(event.request);
            });
        })
    );
    
    // Cache successful requests in the background
    if (event.request.url.startsWith('http') && event.request.method === 'GET' && !shouldExclude(url.pathname)) {
        event.waitUntil(addToCache(event.request));
    }
});

// Handle cache updates and cleanup
self.addEventListener('activate', function(event) {
    console.log('Service Worker activating...');
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== CACHE_NAME && cacheName !== OFFLINE_CACHE) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(function() {
            console.log('Service Worker activated');
            return self.clients.claim();
        })
    );
});

// Handle background sync events
self.addEventListener('sync', function(event) {
    console.log('Background sync event:', event.tag);
    
    if (event.tag === 'background-sync') {
        event.waitUntil(handleBackgroundSync());
    }
});

// Handle background sync processing
async function handleBackgroundSync() {
    try {
        console.log('Processing background sync...');
        
        // Process sync queue directly in service worker
        await processOfflineQueue();
        
    } catch (error) {
        console.error('Background sync failed:', error);
        throw error; // This will cause the sync to be retried
    }
}

// Process offline sync queue
async function processOfflineQueue() {
    try {
        // Open IndexedDB
        const db = await openSyncDatabase();
        const transaction = db.transaction(['syncQueue'], 'readwrite');
        const store = transaction.objectStore('syncQueue');
        
        // Get all pending items
        const pendingItems = await getAllPendingItems(store);
        console.log(`Found ${pendingItems.length} pending sync items`);
        
        for (const item of pendingItems) {
            try {
                await processSyncItem(item, store);
            } catch (error) {
                console.error('Failed to process sync item:', item.id, error);
                await handleSyncItemError(item, error, store);
            }
        }
        
    } catch (error) {
        console.error('Failed to process offline queue:', error);
        throw error;
    }
}

// Open sync database
function openSyncDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('GeometricPWA', 1);
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// Get all pending sync items
function getAllPendingItems(store) {
    return new Promise((resolve, reject) => {
        const index = store.index('status');
        const request = index.getAll('pending');
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

// Process individual sync item
async function processSyncItem(item, store) {
    console.log('Processing sync item:', item.id, item.action);
    
    // Update status to processing
    item.status = 'processing';
    await updateSyncItem(store, item);
    
    // Prepare request
    const requestOptions = {
        method: item.method,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...item.headers
        }
    };
    
    // Add body for POST/PUT requests
    if (item.method !== 'GET' && item.data) {
        requestOptions.body = JSON.stringify(item.data);
    }
    
    // Make request to sync API
    const syncEndpoint = '/api/sync/process';
    const syncData = {
        action: item.action,
        endpoint: item.endpoint,
        method: item.method,
        data: item.data,
        sync_id: item.id
    };
    
    const response = await fetch(syncEndpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(syncData)
    });
    
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    
    const result = await response.json();
    
    if (result.success) {
        // Remove completed item
        await removeSyncItem(store, item.id);
        console.log('Sync completed for:', item.action);
        
        // Notify clients of successful sync
        await notifyClients('syncSuccess', {
            syncId: item.id,
            action: item.action,
            result: result
        });
    } else {
        // Handle sync failure
        throw new Error(result.message || 'Sync failed');
    }
}

// Handle sync item error
async function handleSyncItemError(item, error, store) {
    item.retryCount = (item.retryCount || 0) + 1;
    item.lastError = error.message;
    
    if (item.retryCount >= (item.maxRetries || 3)) {
        // Max retries reached
        item.status = 'failed';
        await updateSyncItem(store, item);
        
        // Notify clients of failed sync
        await notifyClients('syncFailed', {
            syncId: item.id,
            action: item.action,
            error: error.message
        });
    } else {
        // Reset to pending for retry
        item.status = 'pending';
        await updateSyncItem(store, item);
        
        console.log(`Sync retry ${item.retryCount}/${item.maxRetries} for:`, item.action);
    }
}

// Update sync item in database
function updateSyncItem(store, item) {
    return new Promise((resolve, reject) => {
        const request = store.put(item);
        request.onsuccess = () => resolve();
        request.onerror = () => reject(request.error);
    });
}

// Remove sync item from database
function removeSyncItem(store, itemId) {
    return new Promise((resolve, reject) => {
        const request = store.delete(itemId);
        request.onsuccess = () => resolve();
        request.onerror = () => reject(request.error);
    });
}

// Notify all clients
async function notifyClients(type, data) {
    const clients = await self.clients.matchAll();
    clients.forEach(client => {
        client.postMessage({ type, data });
    });
}

// Handle push notification events
self.addEventListener('push', function(event) {
    console.log('Push notification received:', event);
    
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
    
    event.waitUntil(
        self.registration.showNotification(notificationData.title, {
            body: notificationData.body,
            icon: notificationData.icon,
            badge: notificationData.badge,
            data: notificationData.data,
            actions: notificationData.actions || [],
            requireInteraction: notificationData.requireInteraction || false,
            silent: notificationData.silent || false
        })
    );
});

// Handle notification click events
self.addEventListener('notificationclick', function(event) {
    console.log('Notification clicked:', event);
    
    event.notification.close();
    
    const urlToOpen = event.notification.data?.url || '/';
    
    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clients => {
            // Check if there's already a window/tab open with the target URL
            for (let client of clients) {
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            
            // If no existing window/tab, open a new one
            if (self.clients.openWindow) {
                return self.clients.openWindow(urlToOpen);
            }
        })
    );
});

// Handle messages from the main thread
self.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    } else if (event.data && event.data.type === 'FORCE_SYNC') {
        // Force background sync
        handleBackgroundSync().catch(error => {
            console.error('Forced sync failed:', error);
        });
    } else if (event.data && event.data.type === 'CLEANUP_CACHE') {
        // Cleanup old cache entries
        cleanupOldCaches().catch(error => {
            console.error('Cache cleanup failed:', error);
        });
    }
});

// Cleanup old cache entries
async function cleanupOldCaches() {
    try {
        const cacheNames = await caches.keys();
        const oldCaches = cacheNames.filter(name => 
            name !== CACHE_NAME && name !== OFFLINE_CACHE
        );
        
        await Promise.all(
            oldCaches.map(cacheName => {
                console.log('Deleting old cache:', cacheName);
                return caches.delete(cacheName);
            })
        );
        
        console.log(`Cleaned up ${oldCaches.length} old caches`);
    } catch (error) {
        console.error('Failed to cleanup caches:', error);
    }
}

// Enhanced error handling and logging
self.addEventListener('error', function(event) {
    console.error('Service Worker error:', event.error);
});

self.addEventListener('unhandledrejection', function(event) {
    console.error('Service Worker unhandled rejection:', event.reason);
});