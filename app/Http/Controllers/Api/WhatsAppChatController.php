<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppCloudService;
use App\Services\ChatWebSocketService;
use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Models\VisitorSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WhatsAppChatController extends Controller
{
    private WhatsAppCloudService $whatsappService;
    private ChatWebSocketService $webSocketService;

    public function __construct(WhatsAppCloudService $whatsappService, ChatWebSocketService $webSocketService)
    {
        $this->whatsappService = $whatsappService;
        $this->webSocketService = $webSocketService;
    }

    /**
     * Send a message from the chat widget
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000',
            'message_id' => 'required|string|max:255',
            'session_id' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid input data',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $sessionId = $request->input('session_id');
            $messageText = $request->input('message');
            $messageId = $request->input('message_id');

            // Find or create visitor session
            $visitorSession = $this->findOrCreateVisitorSession($sessionId, $request);

            // Find or create conversation
            $conversation = $this->findOrCreateConversation($visitorSession);

            // Create message record
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'whatsapp_message_id' => null, // Will be set after WhatsApp API response
                'direction' => 'outbound',
                'sender_type' => 'visitor',
                'sender_id' => null,
                'message_type' => 'text',
                'content' => $messageText,
                'status' => 'pending',
                'metadata' => [
                    'frontend_message_id' => $messageId,
                    'session_id' => $sessionId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            ]);

            // Send to WhatsApp (if configured)
            if ($this->whatsappService->isConfigured()) {
                try {
                    $whatsappResponse = $this->whatsappService->sendMessage(
                        $conversation->whatsapp_phone_number ?? '+1234567890', // Default for testing
                        $messageText
                    );

                    if (isset($whatsappResponse['messages'][0]['id'])) {
                        $message->update([
                            'whatsapp_message_id' => $whatsappResponse['messages'][0]['id'],
                            'status' => 'sent'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send WhatsApp message', [
                        'message_id' => $message->id,
                        'error' => $e->getMessage()
                    ]);
                    
                    $message->update(['status' => 'failed']);
                    
                    return response()->json([
                        'success' => false,
                        'error' => 'Failed to send message to WhatsApp'
                    ], 500);
                }
            } else {
                // For testing without WhatsApp API
                $message->update(['status' => 'sent']);
                
                // Simulate auto-reply for testing
                $this->simulateAutoReply($conversation, $messageText);
            }

            // Broadcast message via WebSocket
            $this->webSocketService->broadcastMessageReceived($message);

            return response()->json([
                'success' => true,
                'message_id' => $message->id,
                'whatsapp_message_id' => $message->whatsapp_message_id,
                'status' => $message->status
            ]);

        } catch (\Exception $e) {
            Log::error('Chat send message error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Ping endpoint for connection checking
     */
    public function ping(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'timestamp' => now()->toISOString(),
            'session_id' => $request->header('X-Session-ID')
        ]);
    }

    /**
     * Get chat history for a session
     */
    public function getHistory(Request $request): JsonResponse
    {
        $sessionId = $request->header('X-Session-ID');
        
        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'error' => 'Session ID required'
            ], 400);
        }

        try {
            $visitorSession = VisitorSession::find($sessionId);
            
            if (!$visitorSession) {
                return response()->json([
                    'success' => true,
                    'messages' => []
                ]);
            }

            $conversation = Conversation::where('visitor_session_id', $visitorSession->id)
                ->where('status', '!=', 'closed')
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => true,
                    'messages' => []
                ]);
            }

            $messages = ChatMessage::where('conversation_id', $conversation->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'text' => $message->content,
                        'type' => $message->direction === 'inbound' ? 'received' : 'sent',
                        'status' => $message->status,
                        'timestamp' => $message->created_at->toISOString()
                    ];
                });

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);

        } catch (\Exception $e) {
            Log::error('Chat history error', [
                'session_id' => $sessionId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to load chat history'
            ], 500);
        }
    }

    /**
     * Find or create visitor session
     */
    private function findOrCreateVisitorSession(string $sessionId, Request $request): VisitorSession
    {
        $session = VisitorSession::find($sessionId);

        if (!$session) {
            $session = VisitorSession::create([
                'id' => $sessionId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'utm_source' => $request->get('utm_source'),
                'utm_medium' => $request->get('utm_medium'),
                'utm_campaign' => $request->get('utm_campaign'),
                'first_visit_at' => now(),
                'last_activity_at' => now(),
                'page_views' => 1,
                'metadata' => [
                    'created_via' => 'chat_widget'
                ]
            ]);
        } else {
            $session->updateActivity();
        }

        return $session;
    }

    /**
     * Find or create conversation for visitor session
     */
    private function findOrCreateConversation(VisitorSession $visitorSession): Conversation
    {
        $conversation = Conversation::where('visitor_session_id', $visitorSession->id)
            ->where('status', '!=', 'closed')
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'visitor_session_id' => $visitorSession->id,
                'whatsapp_phone_number' => '+1234567890', // Default for testing
                'visitor_phone_number' => null,
                'agent_id' => null,
                'status' => 'waiting',
                'priority' => 1,
                'source' => 'website_chat',
                'metadata' => [
                    'created_via' => 'chat_widget',
                    'visitor_session' => $visitorSession->toArray()
                ]
            ]);
        }

        return $conversation;
    }

    /**
     * Simulate auto-reply for testing (when WhatsApp API is not configured)
     */
    private function simulateAutoReply(Conversation $conversation, string $userMessage): void
    {
        // Simple auto-reply logic for testing
        $replies = [
            'hello' => 'Hi there! 👋 How can I help you today?',
            'help' => 'I\'m here to assist you! What do you need help with?',
            'price' => 'For pricing information, please contact our sales team at sales@example.com',
            'contact' => 'You can reach us at:\n📞 Phone: +1 (555) 123-4567\n📧 Email: info@example.com',
            'hours' => 'Our business hours are:\nMonday - Friday: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed',
            'default' => 'Thank you for your message! Our team will get back to you shortly. 😊'
        ];

        $lowerMessage = strtolower($userMessage);
        $replyText = $replies['default'];

        foreach ($replies as $keyword => $reply) {
            if ($keyword !== 'default' && str_contains($lowerMessage, $keyword)) {
                $replyText = $reply;
                break;
            }
        }

        // Create auto-reply message
        $autoReply = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => 'auto_' . time(),
            'direction' => 'inbound',
            'sender_type' => 'system',
            'sender_id' => null,
            'message_type' => 'text',
            'content' => $replyText,
            'status' => 'delivered',
            'metadata' => [
                'auto_reply' => true,
                'trigger_message' => $userMessage
            ]
        ]);

        // Broadcast auto-reply via WebSocket
        $this->webSocketService->broadcastMessageReceived($autoReply);
    }

    /**
     * Send typing indicator
     */
    public function sendTypingIndicator(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string|max:255',
            'is_typing' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid input data',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $sessionId = $request->input('session_id');
            $isTyping = $request->input('is_typing');

            // Find visitor session and conversation
            $visitorSession = VisitorSession::find($sessionId);
            if (!$visitorSession) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session not found'
                ], 404);
            }

            $conversation = Conversation::where('visitor_session_id', $visitorSession->id)
                ->where('status', '!=', 'closed')
                ->first();

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'error' => 'Conversation not found'
                ], 404);
            }

            // Broadcast typing indicator
            $this->webSocketService->broadcastTypingIndicator(
                $conversation->id,
                $sessionId,
                'visitor',
                null,
                $isTyping
            );

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id
            ]);

        } catch (\Exception $e) {
            Log::error('Typing indicator error', [
                'session_id' => $request->input('session_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get WebSocket connection information
     */
    public function getWebSocketInfo(Request $request): JsonResponse
    {
        try {
            $connectionInfo = $this->webSocketService->getConnectionInfo();
            
            return response()->json([
                'success' => true,
                'websocket' => $connectionInfo
            ]);

        } catch (\Exception $e) {
            Log::error('WebSocket info error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to get WebSocket info'
            ], 500);
        }
    }
}
