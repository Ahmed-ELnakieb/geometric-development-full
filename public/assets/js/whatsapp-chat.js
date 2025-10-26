/**
 * WhatsApp Chat Widget
 * Real-time chat integration with WhatsApp Cloud API
 */

class WhatsAppChatWidget {
    constructor(config = {}) {
        this.config = {
            apiUrl: '/api/whatsapp/chat',
            wsUrl: null, // Will be set when WebSocket is implemented
            sessionId: this.generateSessionId(),
            maxRetries: 3,
            retryDelay: 1000,
            ...config
        };

        this.isOpen = false;
        this.isConnected = false;
        this.messageQueue = new MessageQueue();
        this.websocket = null;
        this.pusher = null;
        this.channels = {};
        this.retryCount = 0;

        this.init();
    }

    init() {
        this.bindEvents();
        this.loadSession();
        this.initializeWebSocket();
        this.startHeartbeat();
        
        // Show pulse animation on first visit
        if (!localStorage.getItem('wa_chat_visited')) {
            this.showPulseAnimation();
            localStorage.setItem('wa_chat_visited', 'true');
        }
    }

    /**
     * Initialize WebSocket connection
     */
    async initializeWebSocket() {
        try {
            // Get WebSocket configuration from server
            const response = await fetch(this.config.apiUrl + '/websocket-info');
            const data = await response.json();
            
            if (!data.success || !data.websocket.enabled) {
                console.log('WebSocket not available, using polling mode');
                return;
            }
            
            // Load Pusher library dynamically
            await this.loadPusherLibrary();
            
            // Initialize Pusher connection
            this.pusher = new Pusher(data.websocket.key, {
                cluster: data.websocket.cluster,
                encrypted: data.websocket.encrypted,
                authEndpoint: data.websocket.auth_endpoint,
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Session-ID': this.config.sessionId
                    }
                }
            });
            
            // Subscribe to session channel
            this.subscribeToSessionChannel();
            
            // Handle connection events
            this.pusher.connection.bind('connected', () => {
                console.log('WebSocket connected');
                this.isConnected = true;
                this.showConnectionStatus('connected', 'Connected');
                this.processMessageQueue();
            });
            
            this.pusher.connection.bind('disconnected', () => {
                console.log('WebSocket disconnected');
                this.isConnected = false;
                this.showConnectionStatus('disconnected', 'Disconnected');
            });
            
            this.pusher.connection.bind('error', (error) => {
                console.error('WebSocket error:', error);
                this.isConnected = false;
                this.showConnectionStatus('disconnected', 'Connection error');
            });
            
        } catch (error) {
            console.error('Failed to initialize WebSocket:', error);
        }
    }

    /**
     * Load Pusher library dynamically
     */
    async loadPusherLibrary() {
        return new Promise((resolve, reject) => {
            if (window.Pusher) {
                resolve();
                return;
            }
            
            const script = document.createElement('script');
            script.src = 'https://js.pusher.com/8.2.0/pusher.min.js';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    /**
     * Subscribe to session channel for receiving messages
     */
    subscribeToSessionChannel() {
        if (!this.pusher) return;
        
        const channelName = `chat.session.${this.config.sessionId}`;
        const channel = this.pusher.subscribe(channelName);
        this.channels.session = channel;
        
        // Handle incoming messages
        channel.bind('message.received', (data) => {
            console.log('Message received via WebSocket:', data);
            
            if (data.message && data.message.type === 'received') {
                this.receiveMessage(data.message);
            }
        });
        
        // Handle message status updates
        channel.bind('message.status.updated', (data) => {
            console.log('Message status updated via WebSocket:', data);
            this.updateMessageStatus(data.message_id, data.new_status);
        });
        
        // Handle typing indicators
        channel.bind('typing.indicator', (data) => {
            console.log('Typing indicator via WebSocket:', data);
            
            if (data.sender_type === 'agent' || data.sender_type === 'system') {
                if (data.is_typing) {
                    this.showTypingIndicator();
                } else {
                    this.hideTypingIndicator();
                }
            }
        });
    }

    bindEvents() {
        const chatButton = document.getElementById('chatButton');
        const chatClose = document.getElementById('chatClose');
        const chatInput = document.getElementById('chatInput');
        const chatSend = document.getElementById('chatSend');

        if (chatButton) {
            chatButton.addEventListener('click', () => this.toggleChat());
        }

        if (chatClose) {
            chatClose.addEventListener('click', () => this.closeChat());
        }

        if (chatInput) {
            chatInput.addEventListener('input', (e) => this.handleInputChange(e));
            chatInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
            chatInput.addEventListener('paste', (e) => this.handlePaste(e));
        }

        if (chatSend) {
            chatSend.addEventListener('click', () => this.sendMessage());
        }

        // Handle clicks outside chat to close
        document.addEventListener('click', (e) => {
            if (this.isOpen && !e.target.closest('.wa-chat-widget')) {
                this.closeChat();
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeChat();
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());

        // Handle page visibility change
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.reconnectIfNeeded();
            }
        });
    }

    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    openChat() {
        const chatInterface = document.getElementById('chatInterface');
        const chatButton = document.getElementById('chatButton');
        
        if (chatInterface && chatButton) {
            this.isOpen = true;
            chatInterface.classList.add('active');
            chatButton.classList.remove('wa-chat-button-pulse');
            
            // Focus on input
            setTimeout(() => {
                const chatInput = document.getElementById('chatInput');
                if (chatInput) {
                    chatInput.focus();
                }
            }, 300);

            // Mark as read
            this.markMessagesAsRead();
            
            // Track analytics
            this.trackEvent('chat_opened');
        }
    }

    closeChat() {
        const chatInterface = document.getElementById('chatInterface');
        
        if (chatInterface) {
            this.isOpen = false;
            chatInterface.classList.remove('active');
            
            // Track analytics
            this.trackEvent('chat_closed');
        }
    }

    handleInputChange(e) {
        const input = e.target;
        const sendButton = document.getElementById('chatSend');
        
        // Auto-resize textarea
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 100) + 'px';
        
        // Enable/disable send button
        if (sendButton) {
            sendButton.disabled = !input.value.trim();
        }
        
        // Show typing indicator (will be implemented with WebSocket)
        this.handleTyping();
    }

    handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.sendMessage();
        }
    }

    handlePaste(e) {
        // Handle pasted content
        setTimeout(() => {
            const input = e.target;
            if (input.value.length > 1000) {
                input.value = input.value.substring(0, 1000);
                this.showNotification('Message too long. Maximum 1000 characters allowed.', 'warning');
            }
        }, 0);
    }

    async sendMessage() {
        const chatInput = document.getElementById('chatInput');
        const sendButton = document.getElementById('chatSend');
        
        if (!chatInput || !chatInput.value.trim()) return;

        const messageText = chatInput.value.trim();
        const messageId = this.generateMessageId();
        
        // Clear input
        chatInput.value = '';
        chatInput.style.height = 'auto';
        if (sendButton) sendButton.disabled = true;

        // Add message to UI immediately
        this.addMessageToUI({
            id: messageId,
            text: messageText,
            type: 'sent',
            status: 'sending',
            timestamp: new Date()
        });

        try {
            // Send message via API
            const response = await this.sendMessageToAPI(messageText, messageId);
            
            if (response.success) {
                this.updateMessageStatus(messageId, 'sent');
                this.trackEvent('message_sent');
            } else {
                throw new Error(response.error || 'Failed to send message');
            }
        } catch (error) {
            console.error('Failed to send message:', error);
            
            // Queue message for retry if offline
            if (!this.isConnected) {
                this.messageQueue.add({
                    id: messageId,
                    text: messageText,
                    timestamp: new Date()
                });
                this.updateMessageStatus(messageId, 'queued');
                this.showConnectionStatus('disconnected', 'Message queued. Will send when connected.');
            } else {
                this.updateMessageStatus(messageId, 'failed');
                this.showNotification('Failed to send message. Please try again.', 'error');
            }
        }
    }

    async sendMessageToAPI(text, messageId) {
        const response = await fetch(this.config.apiUrl + '/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Session-ID': this.config.sessionId
            },
            body: JSON.stringify({
                message: text,
                message_id: messageId,
                session_id: this.config.sessionId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return await response.json();
    }

    addMessageToUI(message) {
        const messagesContainer = document.getElementById('chatMessages');
        if (!messagesContainer) return;

        const messageElement = this.createMessageElement(message);
        messagesContainer.appendChild(messageElement);
        
        // Scroll to bottom
        this.scrollToBottom();
        
        // Add entrance animation
        messageElement.classList.add('wa-chat-message-enter');
        
        // Update notification badge if chat is closed
        if (!this.isOpen && message.type === 'received') {
            this.updateNotificationBadge();
        }
    }

    createMessageElement(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `wa-chat-message wa-chat-message-${message.type}`;
        messageDiv.setAttribute('data-message-id', message.id);

        const contentDiv = document.createElement('div');
        contentDiv.className = 'wa-chat-message-content';

        const textDiv = document.createElement('div');
        textDiv.className = 'wa-chat-message-text';
        textDiv.textContent = message.text;

        const timeDiv = document.createElement('div');
        timeDiv.className = 'wa-chat-message-time';
        
        const timeSpan = document.createElement('span');
        timeSpan.textContent = this.formatTime(message.timestamp);
        timeDiv.appendChild(timeSpan);

        if (message.type === 'sent') {
            const statusSpan = document.createElement('span');
            statusSpan.className = `wa-chat-message-status ${message.status}`;
            statusSpan.innerHTML = this.getStatusIcon(message.status);
            timeDiv.appendChild(statusSpan);
        }

        contentDiv.appendChild(textDiv);
        contentDiv.appendChild(timeDiv);
        messageDiv.appendChild(contentDiv);

        return messageDiv;
    }

    updateMessageStatus(messageId, status) {
        const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
        if (!messageElement) return;

        const statusElement = messageElement.querySelector('.wa-chat-message-status');
        if (statusElement) {
            statusElement.className = `wa-chat-message-status ${status}`;
            statusElement.innerHTML = this.getStatusIcon(status);
        }
    }

    getStatusIcon(status) {
        const icons = {
            sending: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" fill="currentColor"><animate attributeName="opacity" values="1;0.5;1" dur="1s" repeatCount="indefinite"/></circle></svg>',
            sent: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            delivered: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4m-6-2l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            read: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4m-6-2l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            failed: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2"/><line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2"/></svg>',
            queued: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><polyline points="12,6 12,12 16,14" stroke="currentColor" stroke-width="2"/></svg>'
        };
        return icons[status] || icons.sent;
    }

    receiveMessage(message) {
        this.addMessageToUI({
            id: message.id || this.generateMessageId(),
            text: message.text,
            type: 'received',
            timestamp: new Date(message.timestamp || Date.now())
        });

        // Show notification if chat is closed
        if (!this.isOpen) {
            this.showNotification(`New message: ${message.text.substring(0, 50)}${message.text.length > 50 ? '...' : ''}`, 'info');
        }

        this.trackEvent('message_received');
    }

    updateNotificationBadge() {
        const badge = document.getElementById('notificationBadge');
        if (!badge) return;

        const unreadCount = this.getUnreadMessageCount();
        if (unreadCount > 0) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount.toString();
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    markMessagesAsRead() {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.style.display = 'none';
        }
        
        // Mark messages as read in storage
        const session = this.getSession();
        if (session.messages) {
            session.messages.forEach(msg => {
                if (msg.type === 'received') {
                    msg.read = true;
                }
            });
            this.saveSession(session);
        }
    }

    getUnreadMessageCount() {
        const session = this.getSession();
        if (!session.messages) return 0;
        
        return session.messages.filter(msg => 
            msg.type === 'received' && !msg.read
        ).length;
    }

    showTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.style.display = 'flex';
            this.scrollToBottom();
        }
    }

    hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.style.display = 'none';
        }
    }

    showConnectionStatus(status, message) {
        const connectionStatus = document.getElementById('connectionStatus');
        const connectionText = connectionStatus?.querySelector('.wa-chat-connection-text');
        
        if (connectionStatus && connectionText) {
            connectionStatus.className = `wa-chat-connection-status ${status}`;
            connectionText.textContent = message;
            connectionStatus.style.display = 'flex';
            
            if (status === 'connected') {
                setTimeout(() => {
                    connectionStatus.style.display = 'none';
                }, 3000);
            }
        }
    }

    showNotification(message, type = 'info') {
        // Simple notification system
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('WhatsApp Chat', {
                body: message,
                icon: '/favicon.ico',
                tag: 'whatsapp-chat'
            });
        }
        
        console.log(`[WhatsApp Chat ${type.toUpperCase()}]:`, message);
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chatMessages');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    formatTime(date) {
        return new Date(date).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    generateSessionId() {
        return 'wa_session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    generateMessageId() {
        return 'msg_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    loadSession() {
        const session = this.getSession();
        
        // Load previous messages
        if (session.messages && session.messages.length > 0) {
            const messagesContainer = document.getElementById('chatMessages');
            if (messagesContainer) {
                // Clear welcome message
                messagesContainer.innerHTML = '';
                
                // Add previous messages
                session.messages.forEach(message => {
                    this.addMessageToUI(message);
                });
            }
        }
        
        // Update notification badge
        this.updateNotificationBadge();
    }

    getSession() {
        try {
            const session = localStorage.getItem('wa_chat_session');
            return session ? JSON.parse(session) : { messages: [] };
        } catch (error) {
            console.error('Failed to load session:', error);
            return { messages: [] };
        }
    }

    saveSession(session) {
        try {
            localStorage.setItem('wa_chat_session', JSON.stringify(session));
        } catch (error) {
            console.error('Failed to save session:', error);
        }
    }

    /**
     * Enhanced typing handler with WebSocket support
     */
    handleTyping() {
        // Clear existing timeout
        clearTimeout(this.typingTimeout);
        
        // Send typing started
        this.sendTypingIndicator(true);
        
        // Set timeout to send typing stopped
        this.typingTimeout = setTimeout(() => {
            this.sendTypingIndicator(false);
        }, 1000);
    }

    /**
     * Send typing indicator via WebSocket
     */
    async sendTypingIndicator(isTyping) {
        if (!this.pusher || !this.isConnected) {
            return;
        }
        
        try {
            await fetch(this.config.apiUrl + '/typing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Session-ID': this.config.sessionId
                },
                body: JSON.stringify({
                    session_id: this.config.sessionId,
                    is_typing: isTyping
                })
            });
        } catch (error) {
            console.error('Failed to send typing indicator:', error);
        }
    }

    handleResize() {
        // Adjust chat position on mobile
        const chatInterface = document.getElementById('chatInterface');
        if (chatInterface && window.innerWidth <= 767) {
            // Mobile adjustments
        }
    }

    showPulseAnimation() {
        const chatButton = document.getElementById('chatButton');
        if (chatButton) {
            chatButton.classList.add('wa-chat-button-pulse');
            
            // Remove after 10 seconds
            setTimeout(() => {
                chatButton.classList.remove('wa-chat-button-pulse');
            }, 10000);
        }
    }

    startHeartbeat() {
        // Check connection status periodically
        setInterval(() => {
            this.checkConnection();
        }, 30000); // Every 30 seconds
    }

    async checkConnection() {
        try {
            const response = await fetch(this.config.apiUrl + '/ping', {
                method: 'GET',
                headers: {
                    'X-Session-ID': this.config.sessionId
                }
            });
            
            if (response.ok) {
                if (!this.isConnected) {
                    this.isConnected = true;
                    this.showConnectionStatus('connected', 'Connected');
                    this.processMessageQueue();
                }
                this.retryCount = 0;
            } else {
                throw new Error('Connection check failed');
            }
        } catch (error) {
            if (this.isConnected) {
                this.isConnected = false;
                this.showConnectionStatus('disconnected', 'Connection lost. Retrying...');
            }
        }
    }

    reconnectIfNeeded() {
        if (!this.isConnected) {
            this.checkConnection();
        }
    }

    async processMessageQueue() {
        const queuedMessages = this.messageQueue.getAll();
        
        for (const message of queuedMessages) {
            try {
                const response = await this.sendMessageToAPI(message.text, message.id);
                if (response.success) {
                    this.updateMessageStatus(message.id, 'sent');
                    this.messageQueue.remove(message.id);
                }
            } catch (error) {
                console.error('Failed to send queued message:', error);
                break; // Stop processing if one fails
            }
        }
    }

    trackEvent(eventName, data = {}) {
        // Analytics tracking
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, {
                event_category: 'whatsapp_chat',
                ...data
            });
        }
        
        console.log(`[WhatsApp Chat Event]: ${eventName}`, data);
    }

    /**
     * Disconnect WebSocket
     */
    disconnectWebSocket() {
        if (this.pusher) {
            // Unsubscribe from all channels
            Object.values(this.channels).forEach(channel => {
                if (channel) {
                    this.pusher.unsubscribe(channel.name);
                }
            });
            
            // Disconnect
            this.pusher.disconnect();
            this.pusher = null;
            this.channels = {};
            this.isConnected = false;
        }
    }

    // Public API methods
    open() {
        this.openChat();
    }

    close() {
        this.closeChat();
    }

    sendTextMessage(text) {
        const chatInput = document.getElementById('chatInput');
        if (chatInput) {
            chatInput.value = text;
            this.sendMessage();
        }
    }

    updateConnectionStatus(status) {
        this.isConnected = status === 'connected';
        const statusElement = document.getElementById('chatStatus');
        if (statusElement) {
            statusElement.textContent = status === 'connected' ? 'Online' : 'Offline';
        }
    }
}

// Message Queue for offline handling
class MessageQueue {
    constructor() {
        this.queue = this.loadQueue();
    }

    add(message) {
        this.queue.push(message);
        this.saveQueue();
    }

    remove(messageId) {
        this.queue = this.queue.filter(msg => msg.id !== messageId);
        this.saveQueue();
    }

    getAll() {
        return [...this.queue];
    }

    clear() {
        this.queue = [];
        this.saveQueue();
    }

    loadQueue() {
        try {
            const queue = localStorage.getItem('wa_chat_queue');
            return queue ? JSON.parse(queue) : [];
        } catch (error) {
            console.error('Failed to load message queue:', error);
            return [];
        }
    }

    saveQueue() {
        try {
            localStorage.setItem('wa_chat_queue', JSON.stringify(this.queue));
        } catch (error) {
            console.error('Failed to save message queue:', error);
        }
    }
}

// Initialize chat widget when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if chat widget exists on page
    if (document.getElementById('whatsappChatWidget')) {
        window.whatsappChat = new WhatsAppChatWidget({
            apiUrl: '/api/whatsapp/chat'
        });
        
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { WhatsAppChatWidget, MessageQueue };
}