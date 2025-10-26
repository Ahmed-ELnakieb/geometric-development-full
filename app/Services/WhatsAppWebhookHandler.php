<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Models\VisitorSession;
use App\Models\WebhookEvent;
use App\Exceptions\WhatsAppApiException;
use App\Services\ChatWebSocketService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WhatsAppWebhookHandler
{
    private WhatsAppCloudService $whatsappService;

    public function __construct(WhatsAppCloudService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Process incoming webhook event from WhatsApp
     */
    public function processWebhookEvent(Request $request): Response
    {
        try {
            // Handle webhook verification (GET request)
            if ($request->has('hub_mode') && $request->get('hub_mode') === 'subscribe') {
                return $this->handleWebhookVerification($request);
            }

            // For POST requests, verify webhook signature
            $payload = $request->getContent();
            $signature = $request->header('X-Hub-Signature-256');
            
            if (!$signature || !$this->whatsappService->validateWebhookSignature($payload, $signature)) {
                Log::warning('Invalid webhook signature', [
                    'signature' => $signature,
                    'payload_length' => strlen($payload)
                ]);
                return response('Unauthorized', 401);
            }

            // Parse webhook data
            $data = $request->json()->all();

            // Log webhook event
            $webhookEvent = WebhookEvent::create([
                'event_type' => $this->determineEventType($data),
                'payload' => $data,
                'signature' => $signature,
                'processed' => false
            ]);

            // Process the webhook data
            $this->processWebhookData($data, $webhookEvent);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->getContent()
            ]);

            return response('Internal Server Error', 500);
        }
    }

    /**
     * Handle webhook verification challenge
     */
    private function handleWebhookVerification(Request $request): Response
    {
        $mode = $request->get('hub_mode');
        $token = $request->get('hub_verify_token');
        $challenge = $request->get('hub_challenge');

        $expectedToken = $this->whatsappService->getWebhookVerifyToken();

        if ($mode === 'subscribe' && $token === $expectedToken) {
            Log::info('Webhook verification successful');
            return response($challenge, 200);
        }

        Log::warning('Webhook verification failed', [
            'mode' => $mode,
            'token' => $token,
            'expected_token' => $expectedToken
        ]);

        return response('Forbidden', 403);
    }

    /**
     * Process webhook data based on type
     */
    private function processWebhookData(array $data, WebhookEvent $webhookEvent): void
    {
        try {
            if (!isset($data['entry'])) {
                throw new \Exception('Invalid webhook data: missing entry');
            }

            foreach ($data['entry'] as $entry) {
                if (!isset($entry['changes'])) {
                    continue;
                }

                foreach ($entry['changes'] as $change) {
                    if ($change['field'] !== 'messages') {
                        continue;
                    }

                    $value = $change['value'];

                    // Process messages
                    if (isset($value['messages'])) {
                        foreach ($value['messages'] as $message) {
                            $this->handleIncomingMessage($message, $value);
                        }
                    }

                    // Process status updates
                    if (isset($value['statuses'])) {
                        foreach ($value['statuses'] as $status) {
                            $this->handleStatusUpdate($status);
                        }
                    }
                }
            }

            $webhookEvent->markAsProcessed();

        } catch (\Exception $e) {
            $webhookEvent->markAsFailed($e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle incoming message from WhatsApp
     */
    public function handleIncomingMessage(array $messageData, array $contextData): void
    {
        DB::transaction(function () use ($messageData, $contextData) {
            $phoneNumber = $messageData['from'];
            $whatsappMessageId = $messageData['id'];
            $timestamp = $messageData['timestamp'];

            // Check if message already exists
            if (ChatMessage::where('whatsapp_message_id', $whatsappMessageId)->exists()) {
                Log::info('Duplicate message received', ['message_id' => $whatsappMessageId]);
                return;
            }

            // Find or create conversation
            $conversation = $this->findOrCreateConversation($phoneNumber, $contextData);

            // Extract message content
            $content = $this->extractMessageContent($messageData);
            $messageType = $this->determineMessageType($messageData);
            $mediaUrl = $this->extractMediaUrl($messageData);

            // Create message record
            $message = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'whatsapp_message_id' => $whatsappMessageId,
                'direction' => 'inbound',
                'sender_type' => 'visitor',
                'sender_id' => null,
                'message_type' => $messageType,
                'content' => $content,
                'media_url' => $mediaUrl,
                'status' => 'delivered',
                'metadata' => [
                    'timestamp' => $timestamp,
                    'phone_number' => $phoneNumber,
                    'context' => $contextData['contacts'] ?? null
                ]
            ]);

            // Update conversation status
            $conversation->update([
                'status' => 'active',
                'updated_at' => now()
            ]);

            // Mark message as read on WhatsApp
            try {
                $this->whatsappService->markMessageAsRead($whatsappMessageId);
            } catch (\Exception $e) {
                Log::warning('Failed to mark message as read', [
                    'message_id' => $whatsappMessageId,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Incoming message processed', [
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'whatsapp_message_id' => $whatsappMessageId
            ]);

            // Broadcast to WebSocket clients
            $webSocketService = app(ChatWebSocketService::class);
            $webSocketService->broadcastMessageReceived($message);
        });
    }

    /**
     * Handle message status updates
     */
    public function handleStatusUpdate(array $statusData): void
    {
        $whatsappMessageId = $statusData['id'];
        $status = $statusData['status'];
        $timestamp = $statusData['timestamp'];

        $message = ChatMessage::where('whatsapp_message_id', $whatsappMessageId)->first();

        if (!$message) {
            Log::warning('Status update for unknown message', [
                'whatsapp_message_id' => $whatsappMessageId,
                'status' => $status
            ]);
            return;
        }

        // Update message status
        $message->update([
            'status' => $status,
            'metadata' => array_merge($message->metadata ?? [], [
                'status_timestamp' => $timestamp,
                'status_updated_at' => now()->toISOString()
            ])
        ]);

        $oldStatus = $message->getOriginal('status');

        Log::info('Message status updated', [
            'message_id' => $message->id,
            'whatsapp_message_id' => $whatsappMessageId,
            'old_status' => $oldStatus,
            'new_status' => $status
        ]);

        // Broadcast status update to WebSocket clients
        $webSocketService = app(ChatWebSocketService::class);
        $webSocketService->broadcastMessageStatusUpdate($message, $oldStatus);
    }

    /**
     * Handle delivery receipts
     */
    public function handleDeliveryReceipt(array $receiptData): void
    {
        // This is typically handled by status updates
        $this->handleStatusUpdate($receiptData);
    }

    /**
     * Find or create conversation for phone number
     */
    private function findOrCreateConversation(string $phoneNumber, array $contextData): Conversation
    {
        // Try to find existing active conversation
        $conversation = Conversation::where('visitor_phone_number', $phoneNumber)
            ->where('status', '!=', 'closed')
            ->first();

        if ($conversation) {
            return $conversation;
        }

        // Create new conversation
        $visitorSession = $this->createVisitorSessionFromContext($contextData);

        return Conversation::create([
            'visitor_session_id' => $visitorSession->id,
            'whatsapp_phone_number' => $contextData['metadata']['phone_number_id'] ?? null,
            'visitor_phone_number' => $phoneNumber,
            'agent_id' => null, // Will be assigned by routing logic
            'status' => 'waiting',
            'priority' => 1,
            'source' => 'whatsapp',
            'metadata' => [
                'created_from_webhook' => true,
                'context' => $contextData
            ]
        ]);
    }

    /**
     * Create visitor session from webhook context
     */
    private function createVisitorSessionFromContext(array $contextData): VisitorSession
    {
        return VisitorSession::create([
            'ip_address' => '0.0.0.0', // Not available from WhatsApp
            'user_agent' => 'WhatsApp',
            'referrer' => null,
            'utm_source' => 'whatsapp',
            'utm_medium' => 'messaging',
            'utm_campaign' => null,
            'first_visit_at' => now(),
            'last_activity_at' => now(),
            'page_views' => 1,
            'metadata' => [
                'platform' => 'whatsapp',
                'context' => $contextData
            ]
        ]);
    }

    /**
     * Extract message content from WhatsApp message data
     */
    private function extractMessageContent(array $messageData): string
    {
        if (isset($messageData['text'])) {
            return $messageData['text']['body'];
        }

        if (isset($messageData['image'])) {
            return $messageData['image']['caption'] ?? '[Image]';
        }

        if (isset($messageData['document'])) {
            return $messageData['document']['caption'] ?? '[Document]';
        }

        if (isset($messageData['audio'])) {
            return '[Audio Message]';
        }

        if (isset($messageData['video'])) {
            return $messageData['video']['caption'] ?? '[Video]';
        }

        if (isset($messageData['location'])) {
            $location = $messageData['location'];
            return "Location: {$location['latitude']}, {$location['longitude']}";
        }

        if (isset($messageData['contacts'])) {
            return '[Contact Card]';
        }

        return '[Unsupported Message Type]';
    }

    /**
     * Determine message type from WhatsApp message data
     */
    private function determineMessageType(array $messageData): string
    {
        if (isset($messageData['text'])) return 'text';
        if (isset($messageData['image'])) return 'image';
        if (isset($messageData['document'])) return 'document';
        if (isset($messageData['audio'])) return 'audio';
        if (isset($messageData['video'])) return 'video';
        if (isset($messageData['location'])) return 'location';
        if (isset($messageData['contacts'])) return 'contact';

        return 'text';
    }

    /**
     * Extract media URL from message data
     */
    private function extractMediaUrl(array $messageData): ?string
    {
        $mediaTypes = ['image', 'document', 'audio', 'video'];

        foreach ($mediaTypes as $type) {
            if (isset($messageData[$type]['id'])) {
                try {
                    return $this->whatsappService->getMediaUrl($messageData[$type]['id']);
                } catch (\Exception $e) {
                    Log::warning('Failed to get media URL', [
                        'media_id' => $messageData[$type]['id'],
                        'type' => $type,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return null;
    }

    /**
     * Determine event type from webhook data
     */
    private function determineEventType(array $data): string
    {
        if (isset($data['entry'][0]['changes'][0]['value']['messages'])) {
            return 'message';
        }

        if (isset($data['entry'][0]['changes'][0]['value']['statuses'])) {
            return 'status';
        }

        return 'unknown';
    }

    /**
     * Validate and sanitize webhook payload
     */
    public function validateAndSanitizePayload(array $payload): array
    {
        // Remove any potentially dangerous content
        $sanitized = $this->recursiveSanitize($payload);

        // Validate required fields
        if (!isset($sanitized['entry']) || !is_array($sanitized['entry'])) {
            throw new \InvalidArgumentException('Invalid webhook payload: missing or invalid entry field');
        }

        return $sanitized;
    }

    /**
     * Recursively sanitize array data
     */
    private function recursiveSanitize(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->recursiveSanitize($value);
            } elseif (is_string($value)) {
                // Basic sanitization - remove potential XSS
                $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }
}