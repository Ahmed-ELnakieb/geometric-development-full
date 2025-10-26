<!-- WhatsApp Chat Widget -->
<div class="wa-chat-widget" id="whatsappChatWidget">
    <!-- Chat Button -->
    <div class="wa-chat-button" id="chatButton">
        <span class="wa-chat-button-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516" fill="currentColor"/>
            </svg>
        </span>
        <span class="wa-chat-notification-badge" id="notificationBadge" style="display: none;">0</span>
    </div>

    <!-- Chat Interface -->
    <div class="wa-chat-interface" id="chatInterface">
        <!-- Chat Header -->
        <div class="wa-chat-header">
            <div class="wa-chat-header-info">
                <div class="wa-chat-avatar">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516" fill="currentColor"/>
                    </svg>
                </div>
                <div class="wa-chat-header-text">
                    <div class="wa-chat-title">Chat with us</div>
                    <div class="wa-chat-status" id="chatStatus">Online</div>
                </div>
            </div>
            <button class="wa-chat-close" id="chatClose" aria-label="Close chat">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <!-- Chat Messages -->
        <div class="wa-chat-messages" id="chatMessages">
            <div class="wa-chat-welcome-message">
                <div class="wa-chat-message wa-chat-message-received">
                    <div class="wa-chat-message-content">
                        <div class="wa-chat-message-text">
                            Hi! 👋 How can we help you today?
                        </div>
                        <div class="wa-chat-message-time">
                            <span id="welcomeTime"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div class="wa-chat-typing" id="typingIndicator" style="display: none;">
            <div class="wa-chat-typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <span class="wa-chat-typing-text">Typing...</span>
        </div>

        <!-- Chat Input -->
        <div class="wa-chat-input-container">
            <div class="wa-chat-input-wrapper">
                <textarea 
                    class="wa-chat-input" 
                    id="chatInput" 
                    placeholder="Type your message..."
                    rows="1"
                    maxlength="1000"
                    aria-label="Type your message"
                ></textarea>
                <button class="wa-chat-send" id="chatSend" aria-label="Send message" disabled>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="wa-chat-connection-status" id="connectionStatus" style="display: none;">
                <span class="wa-chat-connection-indicator"></span>
                <span class="wa-chat-connection-text">Connecting...</span>
            </div>
        </div>
    </div>
</div>

<!-- Chat Widget Styles -->
<style>
/* WhatsApp Chat Widget Styles */
.wa-chat-widget {
    position: fixed;
    right: 1.5%;
    bottom: 8%; /* Position above back-to-top button */
    z-index: 1000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

@media (max-width: 767px) {
    .wa-chat-widget {
        right: 3%;
        bottom: 10%;
    }
}

/* Chat Button */
.wa-chat-button {
    position: relative;
    width: 60px;
    height: 60px;
    background: #25D366;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    transition: all 0.3s ease;
    transform: scale(1);
}

.wa-chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
}

.wa-chat-button-icon {
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.wa-chat-notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Chat Interface */
.wa-chat-interface {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    display: none;
    flex-direction: column;
    overflow: hidden;
    transform: scale(0.8) translateY(20px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.wa-chat-interface.active {
    display: flex;
    transform: scale(1) translateY(0);
    opacity: 1;
}

@media (max-width: 767px) {
    .wa-chat-interface {
        width: 320px;
        height: 450px;
        right: -10px;
    }
}

/* Chat Header */
.wa-chat-header {
    background: #25D366;
    color: white;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.wa-chat-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.wa-chat-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.wa-chat-title {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 2px;
}

.wa-chat-status {
    font-size: 12px;
    opacity: 0.9;
}

.wa-chat-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.wa-chat-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Chat Messages */
.wa-chat-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    background: #f0f0f0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e5ddd5' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.wa-chat-message {
    margin-bottom: 12px;
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.wa-chat-message-received {
    justify-content: flex-start;
}

.wa-chat-message-sent {
    justify-content: flex-end;
}

.wa-chat-message-content {
    max-width: 80%;
    position: relative;
}

.wa-chat-message-received .wa-chat-message-content {
    background: white;
    border-radius: 18px 18px 18px 4px;
    padding: 8px 12px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.wa-chat-message-sent .wa-chat-message-content {
    background: #dcf8c6;
    border-radius: 18px 18px 4px 18px;
    padding: 8px 12px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.wa-chat-message-text {
    font-size: 14px;
    line-height: 1.4;
    word-wrap: break-word;
    margin-bottom: 4px;
}

.wa-chat-message-time {
    font-size: 11px;
    color: #667781;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
}

.wa-chat-message-status {
    display: flex;
    align-items: center;
}

.wa-chat-message-status svg {
    width: 12px;
    height: 12px;
}

.wa-chat-message-status.sending {
    color: #667781;
}

.wa-chat-message-status.sent {
    color: #667781;
}

.wa-chat-message-status.delivered {
    color: #4fc3f7;
}

.wa-chat-message-status.read {
    color: #4fc3f7;
}

/* Typing Indicator */
.wa-chat-typing {
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f0f0f0;
    border-top: 1px solid #e0e0e0;
}

.wa-chat-typing-dots {
    display: flex;
    gap: 2px;
}

.wa-chat-typing-dots span {
    width: 6px;
    height: 6px;
    background: #667781;
    border-radius: 50%;
    animation: typing-dots 1.4s infinite ease-in-out;
}

.wa-chat-typing-dots span:nth-child(1) {
    animation-delay: -0.32s;
}

.wa-chat-typing-dots span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing-dots {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

.wa-chat-typing-text {
    font-size: 12px;
    color: #667781;
}

/* Chat Input */
.wa-chat-input-container {
    background: white;
    border-top: 1px solid #e0e0e0;
}

.wa-chat-input-wrapper {
    display: flex;
    align-items: flex-end;
    padding: 12px 16px;
    gap: 8px;
}

.wa-chat-input {
    flex: 1;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    padding: 8px 16px;
    font-size: 14px;
    font-family: inherit;
    resize: none;
    outline: none;
    max-height: 100px;
    min-height: 36px;
    transition: border-color 0.2s;
}

.wa-chat-input:focus {
    border-color: #25D366;
}

.wa-chat-send {
    width: 36px;
    height: 36px;
    background: #25D366;
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    opacity: 0.5;
}

.wa-chat-send:enabled {
    opacity: 1;
}

.wa-chat-send:enabled:hover {
    background: #128C7E;
    transform: scale(1.05);
}

.wa-chat-send:disabled {
    cursor: not-allowed;
}

/* Connection Status */
.wa-chat-connection-status {
    padding: 8px 16px;
    background: #fff3cd;
    border-top: 1px solid #ffeaa7;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #856404;
}

.wa-chat-connection-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ffc107;
    animation: pulse 2s infinite;
}

.wa-chat-connection-status.connected .wa-chat-connection-indicator {
    background: #28a745;
    animation: none;
}

.wa-chat-connection-status.disconnected .wa-chat-connection-indicator {
    background: #dc3545;
    animation: none;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Scrollbar Styling */
.wa-chat-messages::-webkit-scrollbar {
    width: 6px;
}

.wa-chat-messages::-webkit-scrollbar-track {
    background: transparent;
}

.wa-chat-messages::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

.wa-chat-messages::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

/* Accessibility */
.wa-chat-widget [aria-label] {
    outline: none;
}

.wa-chat-widget [aria-label]:focus-visible {
    outline: 2px solid #25D366;
    outline-offset: 2px;
}

/* Animation Classes */
.wa-chat-message-enter {
    animation: messageSlideIn 0.3s ease-out;
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.wa-chat-button-pulse {
    animation: buttonPulse 2s infinite;
}

@keyframes buttonPulse {
    0% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    }
    50% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 0 0 10px rgba(37, 211, 102, 0.1);
    }
    100% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    }
}
</style>

<script>
// WhatsApp Chat Widget JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize welcome time
    const welcomeTime = document.getElementById('welcomeTime');
    if (welcomeTime) {
        welcomeTime.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
});
</script>