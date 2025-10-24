/**
 * FormSync - Handles offline form submissions with background sync
 */
class FormSync {
    constructor() {
        this.syncManager = window.syncManager;
        this.forms = new Map();
        this.isOnline = navigator.onLine;
        
        this.init();
    }

    /**
     * Initialize form sync functionality
     */
    init() {
        // Listen for online/offline events
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.showConnectionStatus('online');
            this.processPendingForms();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.showConnectionStatus('offline');
        });

        // Listen for validation errors from sync manager
        window.addEventListener('syncValidationError', (event) => {
            this.handleSyncValidationError(event.detail);
        });

        // Auto-discover forms with sync attributes
        this.discoverSyncForms();
        
        // Show initial connection status
        this.showConnectionStatus(this.isOnline ? 'online' : 'offline');
        
        // Check for existing validation errors
        this.checkForValidationErrors();
    }

    /**
     * Discover and register forms with data-sync attributes
     */
    discoverSyncForms() {
        const syncForms = document.querySelectorAll('form[data-sync="true"]');
        syncForms.forEach(form => {
            this.registerForm(form);
        });
    }

    /**
     * Register a form for sync functionality
     */
    registerForm(form) {
        if (!form.id) {
            form.id = 'sync-form-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        }

        const formConfig = {
            element: form,
            action: form.action || window.location.href,
            method: form.method || 'POST',
            syncAction: form.dataset.syncAction || 'form_submit',
            showStatus: form.dataset.showStatus !== 'false'
        };

        this.forms.set(form.id, formConfig);

        // Add event listener
        form.addEventListener('submit', (e) => this.handleFormSubmit(e, formConfig));

        // Add sync status indicator if enabled
        if (formConfig.showStatus) {
            this.addStatusIndicator(form);
        }

        console.log('Form registered for sync:', form.id);
    }

    /**
     * Handle form submission
     */
    async handleFormSubmit(event, formConfig) {
        event.preventDefault();

        const form = formConfig.element;
        const formData = new FormData(form);
        const data = this.formDataToObject(formData);

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            data._token = csrfToken.getAttribute('content');
        }

        // Show loading state
        this.setFormLoading(form, true);

        try {
            if (this.isOnline) {
                // Try immediate submission
                await this.submitFormDirectly(formConfig, data);
            } else {
                // Queue for background sync
                await this.queueFormForSync(formConfig, data);
            }
        } catch (error) {
            console.error('Form submission failed:', error);
            
            if (this.isOnline) {
                // If online submission failed, queue for retry
                await this.queueFormForSync(formConfig, data);
            } else {
                this.showFormError(form, 'Form will be submitted when connection is restored.');
            }
        } finally {
            this.setFormLoading(form, false);
        }
    }

    /**
     * Submit form directly (when online)
     */
    async submitFormDirectly(formConfig, data) {
        const response = await fetch(formConfig.action, {
            method: formConfig.method,
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': data._token || ''
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const result = await response.json();
        this.handleFormSuccess(formConfig.element, result);
        
        return result;
    }

    /**
     * Queue form for background sync
     */
    async queueFormForSync(formConfig, data) {
        if (!this.syncManager) {
            throw new Error('Sync manager not available');
        }

        const headers = {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        const syncId = await this.syncManager.queueAction(
            formConfig.syncAction,
            formConfig.action,
            formConfig.method,
            data,
            headers
        );

        this.showFormQueued(formConfig.element, syncId);
        
        return syncId;
    }

    /**
     * Convert FormData to plain object
     */
    formDataToObject(formData) {
        const obj = {};
        
        for (let [key, value] of formData.entries()) {
            if (obj[key]) {
                // Handle multiple values (checkboxes, multiple selects)
                if (Array.isArray(obj[key])) {
                    obj[key].push(value);
                } else {
                    obj[key] = [obj[key], value];
                }
            } else {
                obj[key] = value;
            }
        }
        
        return obj;
    }

    /**
     * Add sync status indicator to form
     */
    addStatusIndicator(form) {
        // Check if indicator already exists
        if (form.querySelector('.sync-status-indicator')) {
            return;
        }

        const indicator = document.createElement('div');
        indicator.className = 'sync-status-indicator';
        indicator.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="sync-status-icon me-2">
                    <i class="fas fa-wifi text-success"></i>
                </div>
                <small class="sync-status-text text-muted">Ready to submit</small>
            </div>
        `;

        // Insert at the beginning of the form
        form.insertBefore(indicator, form.firstChild);
    }

    /**
     * Update form status indicator
     */
    updateStatusIndicator(form, status, message, icon = null) {
        const indicator = form.querySelector('.sync-status-indicator');
        if (!indicator) return;

        const iconElement = indicator.querySelector('.sync-status-icon i');
        const textElement = indicator.querySelector('.sync-status-text');

        if (iconElement && icon) {
            iconElement.className = icon;
        }

        if (textElement) {
            textElement.textContent = message;
            textElement.className = `sync-status-text text-${status}`;
        }
    }

    /**
     * Set form loading state
     */
    setFormLoading(form, loading) {
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        
        if (loading) {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            }
            this.updateStatusIndicator(form, 'info', 'Submitting...', 'fas fa-spinner fa-spin text-info');
        } else {
            if (submitBtn) {
                submitBtn.disabled = false;
                if (submitBtn.dataset.originalText) {
                    submitBtn.textContent = submitBtn.dataset.originalText;
                    delete submitBtn.dataset.originalText;
                }
            }
        }
    }

    /**
     * Handle successful form submission
     */
    handleFormSuccess(form, result) {
        this.updateStatusIndicator(form, 'success', 'Submitted successfully!', 'fas fa-check-circle text-success');
        
        // Show success message
        this.showAlert(form, 'Form submitted successfully!', 'success');
        
        // Reset form if configured
        if (form.dataset.resetOnSuccess !== 'false') {
            form.reset();
        }

        // Redirect if specified
        if (result.redirect) {
            setTimeout(() => {
                window.location.href = result.redirect;
            }, 1500);
        }

        // Reset status after delay
        setTimeout(() => {
            this.updateStatusIndicator(form, 'muted', 'Ready to submit', 'fas fa-wifi text-success');
        }, 3000);
    }

    /**
     * Show form queued for sync
     */
    showFormQueued(form, syncId) {
        this.updateStatusIndicator(form, 'warning', 'Queued for sync', 'fas fa-clock text-warning');
        this.showAlert(form, 'Form queued for submission when connection is restored.', 'info');
        
        // Store sync ID for tracking
        form.dataset.syncId = syncId;
    }

    /**
     * Show form error
     */
    showFormError(form, message) {
        this.updateStatusIndicator(form, 'danger', 'Submission failed', 'fas fa-exclamation-triangle text-danger');
        this.showAlert(form, message, 'warning');
    }

    /**
     * Show connection status
     */
    showConnectionStatus(status) {
        // Update all form indicators
        this.forms.forEach((config) => {
            const form = config.element;
            if (status === 'online') {
                this.updateStatusIndicator(form, 'success', 'Online - Ready to submit', 'fas fa-wifi text-success');
            } else {
                this.updateStatusIndicator(form, 'warning', 'Offline - Will sync later', 'fas fa-wifi-slash text-warning');
            }
        });

        // Show global status notification
        const statusMessage = status === 'online' 
            ? 'Connection restored. Syncing pending forms...' 
            : 'You are offline. Forms will be submitted when connection is restored.';
        
        const alertType = status === 'online' ? 'success' : 'info';
        this.showGlobalAlert(statusMessage, alertType);
    }

    /**
     * Process pending forms when coming back online
     */
    async processPendingForms() {
        if (!this.syncManager) return;

        try {
            const queueStatus = await this.syncManager.getQueueStatus();
            const pendingForms = queueStatus.items.filter(item => 
                item.status === 'pending' && item.action.includes('form')
            );

            if (pendingForms.length > 0) {
                console.log(`Processing ${pendingForms.length} pending forms`);
                // The sync manager will handle the actual processing
                this.syncManager.registerBackgroundSync();
            }
        } catch (error) {
            console.error('Failed to process pending forms:', error);
        }
    }

    /**
     * Show alert message near form
     */
    showAlert(form, message, type) {
        // Remove existing alerts
        const existingAlert = form.querySelector('.form-sync-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show form-sync-alert mt-2`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert after form
        form.parentNode.insertBefore(alert, form.nextSibling);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    /**
     * Show global alert
     */
    showGlobalAlert(message, type) {
        // Check if global alert already exists
        const existingAlert = document.querySelector('.global-sync-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show global-sync-alert position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alert.innerHTML = `
            <i class="fas fa-sync-alt"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alert);

        // Auto-dismiss after 4 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 4000);
    }

    /**
     * Get sync status for a form
     */
    async getFormSyncStatus(formId) {
        const formConfig = this.forms.get(formId);
        if (!formConfig || !this.syncManager) {
            return null;
        }

        const syncId = formConfig.element.dataset.syncId;
        if (!syncId) {
            return null;
        }

        try {
            const queueStatus = await this.syncManager.getQueueStatus();
            const syncItem = queueStatus.items.find(item => item.id === syncId);
            return syncItem;
        } catch (error) {
            console.error('Failed to get sync status:', error);
            return null;
        }
    }

    /**
     * Handle validation errors from sync manager
     */
    handleSyncValidationError(errorData) {
        console.log('Handling sync validation error:', errorData);
        
        // Find the form that caused this error
        const form = this.findFormBySyncId(errorData.syncId);
        if (form) {
            this.showValidationErrorModal(form, errorData);
        } else {
            // Show generic notification if form not found
            this.showGlobalAlert(
                'A form submission failed validation. Please check your forms and resubmit.',
                'warning'
            );
        }
    }

    /**
     * Find form by sync ID
     */
    findFormBySyncId(syncId) {
        for (let [formId, config] of this.forms) {
            if (config.element.dataset.syncId === syncId) {
                return config.element;
            }
        }
        return null;
    }

    /**
     * Show validation error modal
     */
    showValidationErrorModal(form, errorData) {
        // Remove existing modal
        const existingModal = document.getElementById('validationErrorModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal HTML
        const modalHtml = `
            <div class="modal fade" id="validationErrorModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                Form Validation Error
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">${errorData.message}</p>
                            <div class="validation-errors">
                                ${this.formatValidationErrors(errorData.errors)}
                            </div>
                            <div class="mt-3">
                                <p class="text-muted small">
                                    <i class="fas fa-info-circle"></i>
                                    Please correct the errors above and resubmit the form.
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Dismiss
                            </button>
                            <button type="button" class="btn btn-primary" onclick="formSync.scrollToForm('${form.id}')">
                                Go to Form
                            </button>
                            <button type="button" class="btn btn-danger" onclick="formSync.discardFailedSubmission('${errorData.syncId}')">
                                Discard
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to page
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('validationErrorModal'));
        modal.show();

        // Update form status
        this.updateStatusIndicator(form, 'danger', 'Validation error - needs correction', 'fas fa-exclamation-triangle text-danger');
        
        // Populate form with original data
        this.populateFormWithData(form, errorData.data);
        
        // Highlight validation errors
        this.highlightValidationErrors(form, errorData.errors);
    }

    /**
     * Format validation errors for display
     */
    formatValidationErrors(errors) {
        let html = '<ul class="list-unstyled mb-0">';
        
        for (let field in errors) {
            const fieldErrors = Array.isArray(errors[field]) ? errors[field] : [errors[field]];
            fieldErrors.forEach(error => {
                html += `<li class="text-danger"><i class="fas fa-times-circle"></i> <strong>${field}:</strong> ${error}</li>`;
            });
        }
        
        html += '</ul>';
        return html;
    }

    /**
     * Populate form with data
     */
    populateFormWithData(form, data) {
        for (let field in data) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = input.value === data[field];
                } else {
                    input.value = data[field];
                }
            }
        }
    }

    /**
     * Highlight validation errors in form
     */
    highlightValidationErrors(form, errors) {
        // Clear existing error highlights
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // Add error highlights
        for (let field in errors) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const fieldErrors = Array.isArray(errors[field]) ? errors[field] : [errors[field]];
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = fieldErrors.join(', ');
                
                input.parentNode.appendChild(errorDiv);
            }
        }
    }

    /**
     * Scroll to form
     */
    scrollToForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('validationErrorModal'));
            if (modal) {
                modal.hide();
            }
        }
    }

    /**
     * Discard failed submission
     */
    async discardFailedSubmission(syncId) {
        if (!this.syncManager) return;

        try {
            await this.syncManager.removeValidationErrorItem(syncId);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('validationErrorModal'));
            if (modal) {
                modal.hide();
            }
            
            this.showGlobalAlert('Failed submission discarded.', 'info');
        } catch (error) {
            console.error('Failed to discard submission:', error);
            this.showGlobalAlert('Failed to discard submission.', 'danger');
        }
    }

    /**
     * Check for existing validation errors on page load
     */
    async checkForValidationErrors() {
        if (!this.syncManager) return;

        try {
            // Wait a bit for sync manager to initialize
            setTimeout(async () => {
                const errorItems = await this.syncManager.getValidationErrorItems();
                
                if (errorItems.length > 0) {
                    console.log(`Found ${errorItems.length} validation errors`);
                    
                    // Show notification about pending errors
                    this.showGlobalAlert(
                        `You have ${errorItems.length} form submission(s) that need correction.`,
                        'warning'
                    );
                    
                    // Handle the first error
                    if (errorItems[0]) {
                        this.handleSyncValidationError({
                            syncId: errorItems[0].id,
                            action: errorItems[0].action,
                            message: errorItems[0].lastError,
                            errors: errorItems[0].validationErrors,
                            data: errorItems[0].originalData
                        });
                    }
                }
            }, 2000);
        } catch (error) {
            console.error('Failed to check validation errors:', error);
        }
    }

    /**
     * Manually register a form for sync
     */
    enableSyncForForm(formSelector, options = {}) {
        const form = document.querySelector(formSelector);
        if (!form) {
            console.error('Form not found:', formSelector);
            return;
        }

        // Set sync attributes
        form.dataset.sync = 'true';
        form.dataset.syncAction = options.syncAction || 'form_submit';
        form.dataset.showStatus = options.showStatus !== false ? 'true' : 'false';
        form.dataset.resetOnSuccess = options.resetOnSuccess !== false ? 'true' : 'false';

        // Register the form
        this.registerForm(form);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.formSync = new FormSync();
});

// Export for manual usage
window.FormSync = FormSync;