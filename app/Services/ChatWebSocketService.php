<?php

namespace App\Services;

use App\Events\MessageReceived;
use App\Events\MessageStatusUpdated;
use App\Events\TypingIndicator;
use App\Models\ChatMessage;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ChatWebSocketService
{
    private ?Pusher $pusher;

    public function __construct()
    {
        $this->pusher = $this->initializePusher();
    }

    /**
     * Initialize Pusher connection
     */
    private function initializePusher(): ?Pusher
    {
        try {
            $config = config('broadcasting.connections.pusher');
            
            if (!$config || !$config['key']) {
                Log::warning('Pusher not configured, WebSocket features disabled');
                return null;
            }

            return new Pusher(
                $config['key'],
                $config['secret'],
                $config['app_id'],
                $config['options'] ?? []
            );
        } catch (\Exception $e) {
            Log::error('Failed to initialize Pusher', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Broadcast message received event
     */
    public function broadcastMessageReceived(ChatMessage $message): void
    {
        if (!$this->pusher) {
            Log::debug('Pusher not available, skipping message broadcast');
            return;
        }

        try {
            event(new MessageReceived($message));
            
            Log::info('Message broadcasted via WebSocket', [
                'message_id' => $message->id,
                'conversation_id' => $message->conversation_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast message', [
                'message_id' => $message->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast message status update
     */
    public function broadcastMessageStatusUpdate(ChatMessage $message, string $oldStatus): void
    {
        if (!$this->pusher) {
            Log::debug('Pusher not available, skipping status update broadcast');
            return;
        }

        try {
            event(new MessageStatusUpdated($message, $oldStatus));
            
            Log::info('Message status update broadcasted', [
                'message_id' => $message->id,
                'old_status' => $oldStatus,
                'new_status' => $message->status
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast status update', [
                'message_id' => $message->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast typing indicator
     */
    public function broadcastTypingIndicator(
        string $conversationId,
        string $sessionId,
        string $senderType,
        ?int $senderId = null,
        bool $isTyping = true
    ): void {
        if (!$this->pusher) {
            Log::debug('Pusher not available, skipping typing indicator broadcast');
            return;
        }

        try {
            event(new TypingIndicator($conversationId, $sessionId, $senderType, $senderId, $isTyping));
            
            Log::debug('Typing indicator broadcasted', [
                'conversation_id' => $conversationId,
                'sender_type' => $senderType,
                'is_typing' => $isTyping
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast typing indicator', [
                'conversation_id' => $conversationId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast to specific conversation
     */
    public function broadcastToConversation(string $conversationId, string $event, array $data): void
    {
        if (!$this->pusher) {
            return;
        }

        try {
            $this->pusher->trigger(
                'private-chat.conversation.' . $conversationId,
                $event,
                $data
            );
            
            Log::debug('Custom event broadcasted to conversation', [
                'conversation_id' => $conversationId,
                'event' => $event
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast to conversation', [
                'conversation_id' => $conversationId,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Broadcast to specific session
     */
    public function broadcastToSession(string $sessionId, string $event, array $data): void
    {
        if (!$this->pusher) {
            return;
        }

        try {
            $this->pusher->trigger(
                'chat.session.' . $sessionId,
                $event,
                $data
            );
            
            Log::debug('Custom event broadcasted to session', [
                'session_id' => $sessionId,
                'event' => $event
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast to session', [
                'session_id' => $sessionId,
                'event' => $event,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get connection info for frontend
     */
    public function getConnectionInfo(): array
    {
        $config = config('broadcasting.connections.pusher');
        
        return [
            'enabled' => $this->pusher !== null,
            'key' => $config['key'] ?? null,
            'cluster' => $config['options']['cluster'] ?? 'mt1',
            'encrypted' => $config['options']['encrypted'] ?? true,
            'auth_endpoint' => '/broadcasting/auth'
        ];
    }

    /**
     * Check if WebSocket is available
     */
    public function isAvailable(): bool
    {
        return $this->pusher !== null;
    }

    /**
     * Get active connections count for a channel
     */
    public function getChannelInfo(string $channel): ?array
    {
        if (!$this->pusher) {
            return null;
        }

        try {
            $response = $this->pusher->getChannelInfo($channel, ['user_count']);
            return $response ? json_decode($response, true) : null;
        } catch (\Exception $e) {
            Log::error('Failed to get channel info', [
                'channel' => $channel,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Authenticate user for private channels
     */
    public function authenticateUser(string $socketId, string $channel, ?int $userId = null): array
    {
        if (!$this->pusher) {
            throw new \Exception('WebSocket not available');
        }

        try {
            // For conversation channels, check if user has access
            if (str_contains($channel, 'private-chat.conversation.')) {
                $conversationId = str_replace('private-chat.conversation.', '', $channel);
                
                // Verify user has access to this conversation
                if ($userId) {
                    $conversation = Conversation::find($conversationId);
                    if (!$conversation || $conversation->agent_id !== $userId) {
                        throw new \Exception('Unauthorized access to conversation');
                    }
                }
            }

            $auth = $this->pusher->socketAuth($channel, $socketId);
            
            return [
                'auth' => $auth,
                'channel_data' => json_encode([
                    'user_id' => $userId,
                    'user_info' => [
                        'id' => $userId
                    ]
                ])
            ];
        } catch (\Exception $e) {
            Log::error('WebSocket authentication failed', [
                'channel' => $channel,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send presence update
     */
    public function updatePresence(string $conversationId, int $userId, string $status): void
    {
        if (!$this->pusher) {
            return;
        }

        try {
            $this->broadcastToConversation($conversationId, 'presence.update', [
                'user_id' => $userId,
                'status' => $status,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update presence', [
                'conversation_id' => $conversationId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }
    }
}