/**
 * SyncManager - Handles background synchronization for PWA
 */
class SyncManager {
    constructor() {
        this.dbName = 'GeometricPWA';
        this.dbVersion = 1;
        this.storeName = 'syncQueue';
        this.db = null;
        this.maxRetries = 3;
        this.retryDelay = 1000;
        this.queueLimit = 100;
        
        this.initDB();
    }

    /**
     * Initialize IndexedDB database
     */
    async initDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);
            
            request.onerror = () => reject(request.error);
            request.onsuccess = () => {
                this.db = request.result;
                resolve(this.db);
            };
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Create sync queue store
                if (!db.objectStoreNames.contains(this.storeName)) {
                    const store = db.createObjectStore(this.storeName, { keyPath: 'id' });
                    store.createIndex('timestamp', 'timestamp', { unique: false });
                    store.createIndex('status', 'status', { unique: false });
                    store.createIndex('action', 'action', { unique: false });
                }
            };
        });
    }

    /**
     * Generate unique ID for sync items
     */
    generateId() {
        return 'sync_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Queue an action for background sync
     */
    async queueAction(action, endpoint, method = 'POST', data = {}, headers = {}) {
        if (!this.db) {
            await this.initDB();
        }

        // Check queue limit
        const queueSize = await this.getQueueSize();
        if (queueSize >= this.queueLimit) {
            throw new Error('Sync queue is full. Please try again later.');
        }

        const syncItem = {
            id: this.generateId(),
            action: action,
            endpoint: endpoint,
            method: method,
            data: data,
            headers: headers,
            timestamp: Date.now(),
            status: 'pending',
            retryCount: 0,
            maxRetries: this.maxRetries,
            lastError: null
        };

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.add(syncItem);
            request.onsuccess = () => {
                console.log('Action queued for sync:', action);
                this.registerBackgroundSync();
                resolve(syncItem.id);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get current queue size
     */
    async getQueueSize() {
        if (!this.db) return 0;
        
        const transaction = this.db.transaction([this.storeName], 'readonly');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.count();
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get all pending sync items
     */
    async getPendingItems() {
        if (!this.db) {
            await this.initDB();
        }

        const transaction = this.db.transaction([this.storeName], 'readonly');
        const store = transaction.objectStore(this.storeName);
        const index = store.index('status');
        
        return new Promise((resolve, reject) => {
            const request = index.getAll('pending');
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Process sync queue
     */
    async processQueue() {
        const pendingItems = await this.getPendingItems();
        console.log(`Processing ${pendingItems.length} pending sync items`);

        for (const item of pendingItems) {
            try {
                await this.processSyncItem(item);
            } catch (error) {
                console.error('Failed to process sync item:', item.id, error);
                await this.handleSyncError(item, error);
            }
        }
    }

    /**
     * Process individual sync item
     */
    async processSyncItem(item) {
        // Update status to processing
        await this.updateItemStatus(item.id, 'processing');

        // Prepare request
        const requestOptions = {
            method: item.method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...item.headers
            }
        };

        // Add CSRF token if needed
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            requestOptions.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }

        // Add body for POST/PUT requests
        if (item.method !== 'GET' && item.data) {
            requestOptions.body = JSON.stringify(item.data);
        }

        // Make request
        const response = await fetch(item.endpoint, requestOptions);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const result = await response.json();
        
        // Check if sync was successful
        if (result.success === false) {
            // Handle validation errors or other failures
            if (result.type === 'validation_error' && result.requires_user_action) {
                await this.handleValidationError(item, result);
                return result;
            } else {
                throw new Error(result.message || 'Sync failed');
            }
        }
        
        // Mark as completed and remove from queue
        await this.removeItem(item.id);
        console.log('Sync completed for:', item.action);
        
        return result;
    }

    /**
     * Handle sync errors with retry logic
     */
    async handleSyncError(item, error) {
        item.retryCount++;
        item.lastError = error.message;

        if (item.retryCount >= item.maxRetries) {
            // Max retries reached, mark as failed
            await this.updateItemStatus(item.id, 'failed', error.message);
            console.error('Sync failed permanently:', item.action, error);
        } else {
            // Schedule retry
            await this.updateItemStatus(item.id, 'pending', error.message);
            console.log(`Sync retry ${item.retryCount}/${item.maxRetries} for:`, item.action);
            
            // Exponential backoff
            const delay = this.retryDelay * Math.pow(2, item.retryCount - 1);
            setTimeout(() => {
                this.registerBackgroundSync();
            }, delay);
        }
    }

    /**
     * Update sync item status
     */
    async updateItemStatus(id, status, error = null) {
        if (!this.db) return;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const getRequest = store.get(id);
            getRequest.onsuccess = () => {
                const item = getRequest.result;
                if (item) {
                    item.status = status;
                    if (error) item.lastError = error;
                    
                    const putRequest = store.put(item);
                    putRequest.onsuccess = () => resolve();
                    putRequest.onerror = () => reject(putRequest.error);
                } else {
                    resolve();
                }
            };
            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    /**
     * Remove completed sync item
     */
    async removeItem(id) {
        if (!this.db) return;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.delete(id);
            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Register background sync with service worker
     */
    registerBackgroundSync() {
        if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
            navigator.serviceWorker.ready.then(registration => {
                return registration.sync.register('background-sync');
            }).catch(error => {
                console.error('Background sync registration failed:', error);
                // Fallback to immediate sync
                this.processQueue();
            });
        } else {
            // Fallback for browsers without background sync
            this.processQueue();
        }
    }

    /**
     * Handle background sync event (called from service worker)
     */
    async handleBackgroundSync(event) {
        console.log('Background sync event triggered');
        await this.processQueue();
    }

    /**
     * Get sync queue status for debugging
     */
    async getQueueStatus() {
        if (!this.db) {
            await this.initDB();
        }

        const transaction = this.db.transaction([this.storeName], 'readonly');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.getAll();
            request.onsuccess = () => {
                const items = request.result;
                const status = {
                    total: items.length,
                    pending: items.filter(item => item.status === 'pending').length,
                    processing: items.filter(item => item.status === 'processing').length,
                    failed: items.filter(item => item.status === 'failed').length,
                    items: items
                };
                resolve(status);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Handle validation errors that require user action
     */
    async handleValidationError(item, result) {
        // Update item with validation error details
        item.status = 'validation_error';
        item.validationErrors = result.errors;
        item.originalData = result.data;
        item.lastError = result.message;

        // Update in database
        await this.updateItem(item);

        // Notify user about validation error
        this.notifyValidationError(item, result);
        
        console.log('Validation error for sync item:', item.id, result.errors);
    }

    /**
     * Notify user about validation errors
     */
    notifyValidationError(item, result) {
        // Create notification for user
        const notification = {
            type: 'validation_error',
            syncId: item.id,
            action: item.action,
            message: result.message,
            errors: result.errors,
            data: result.data
        };

        // Dispatch custom event for form sync to handle
        window.dispatchEvent(new CustomEvent('syncValidationError', {
            detail: notification
        }));

        // Show browser notification if available
        if (window.notificationManager && window.notificationManager.permissionStatus === 'granted') {
            window.notificationManager.showLocalNotification(
                'Form Submission Error',
                {
                    body: 'A form submission failed validation. Please review and resubmit.',
                    data: { syncId: item.id, action: item.action }
                }
            );
        }
    }

    /**
     * Get items with validation errors
     */
    async getValidationErrorItems() {
        if (!this.db) {
            await this.initDB();
        }

        const transaction = this.db.transaction([this.storeName], 'readonly');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.getAll();
            request.onsuccess = () => {
                const items = request.result.filter(item => item.status === 'validation_error');
                resolve(items);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Update sync item data after user correction
     */
    async updateSyncItemData(syncId, newData) {
        if (!this.db) return false;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const getRequest = store.get(syncId);
            getRequest.onsuccess = () => {
                const item = getRequest.result;
                if (item && item.status === 'validation_error') {
                    // Update data and reset status
                    item.data = newData;
                    item.status = 'pending';
                    item.retryCount = 0;
                    item.validationErrors = null;
                    item.lastError = null;
                    
                    const putRequest = store.put(item);
                    putRequest.onsuccess = () => {
                        console.log('Sync item data updated:', syncId);
                        // Trigger sync
                        this.registerBackgroundSync();
                        resolve(true);
                    };
                    putRequest.onerror = () => reject(putRequest.error);
                } else {
                    resolve(false);
                }
            };
            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    /**
     * Update entire sync item
     */
    async updateItem(item) {
        if (!this.db) return;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.put(item);
            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Remove sync item with validation error
     */
    async removeValidationErrorItem(syncId) {
        return await this.removeItem(syncId);
    }

    /**
     * Clear completed and failed items from queue
     */
    async clearQueue(includeFailedItems = false) {
        if (!this.db) return;

        const transaction = this.db.transaction([this.storeName], 'readwrite');
        const store = transaction.objectStore(this.storeName);
        
        return new Promise((resolve, reject) => {
            const request = store.getAll();
            request.onsuccess = () => {
                const items = request.result;
                const itemsToDelete = items.filter(item => 
                    item.status === 'completed' || 
                    (includeFailedItems && (item.status === 'failed' || item.status === 'validation_error'))
                );
                
                let deletedCount = 0;
                itemsToDelete.forEach(item => {
                    const deleteRequest = store.delete(item.id);
                    deleteRequest.onsuccess = () => {
                        deletedCount++;
                        if (deletedCount === itemsToDelete.length) {
                            resolve(deletedCount);
                        }
                    };
                });
                
                if (itemsToDelete.length === 0) {
                    resolve(0);
                }
            };
            request.onerror = () => reject(request.error);
        });
    }
}

// Create global instance
window.syncManager = new SyncManager();