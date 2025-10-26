<?php

namespace Tests\Unit\Services;

use App\Services\ChatWebSocketService;
use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\VisitorSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatWebSocketServiceTest extends TestCase
{
    use RefreshDatabase;

    private ChatWebSocketService $webSocketService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->webSocketService = new ChatWebSocketService();
    }

    public function test_websocket_service_instantiation(): void
    {
        $this->assertInstanceOf(ChatWebSocketService::class, $this->webSocketService);
    }

    public function test_get_connection_info_returns_array(): void
    {
        $connectionInfo = $this->webSocketService->getConnectionInfo();
        
        $this->assertIsArray($connectionInfo);
        $this->assertArrayHasKey('enabled', $connectionInfo);
        $this->assertArrayHasKey('key', $connectionInfo);
        $this->assertArrayHasKey('cluster', $connectionInfo);
        $this->assertArrayHasKey('encrypted', $connectionInfo);
        $this->assertArrayHasKey('auth_endpoint', $connectionInfo);
    }

    public function test_is_available_returns_boolean(): void
    {
        $isAvailable = $this->webSocketService->isAvailable();
        $this->assertIsBool($isAvailable);
    }

    public function test_broadcast_message_received_without_pusher(): void
    {
        // Create test data
        $conversation = Conversation::factory()->create();
        $message = ChatMessage::factory()->create([
            'conversation_id' => $conversation->id
        ]);

        // Should not throw exception even without Pusher configured
        $this->webSocketService->broadcastMessageReceived($message);
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_broadcast_message_status_update_without_pusher(): void
    {
        // Create test data
        $conversation = Conversation::factory()->create();
        $message = ChatMessage::factory()->create([
            'conversation_id' => $conversation->id,
            'status' => 'sent'
        ]);

        // Should not throw exception even without Pusher configured
        $this->webSocketService->broadcastMessageStatusUpdate($message, 'pending');
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_broadcast_typing_indicator_without_pusher(): void
    {
        // Should not throw exception even without Pusher configured
        $this->webSocketService->broadcastTypingIndicator(
            'test-conversation-id',
            'test-session-id',
            'visitor',
            null,
            true
        );
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_broadcast_to_conversation_without_pusher(): void
    {
        // Should not throw exception even without Pusher configured
        $this->webSocketService->broadcastToConversation(
            'test-conversation-id',
            'test.event',
            ['data' => 'test']
        );
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_broadcast_to_session_without_pusher(): void
    {
        // Should not throw exception even without Pusher configured
        $this->webSocketService->broadcastToSession(
            'test-session-id',
            'test.event',
            ['data' => 'test']
        );
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_get_channel_info_returns_null_without_pusher(): void
    {
        $channelInfo = $this->webSocketService->getChannelInfo('test-channel');
        $this->assertNull($channelInfo);
    }

    public function test_authenticate_user_throws_exception_without_pusher(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('WebSocket not available');
        
        $this->webSocketService->authenticateUser(
            'test-socket-id',
            'private-chat.conversation.test',
            1
        );
    }

    public function test_update_presence_without_pusher(): void
    {
        // Should not throw exception even without Pusher configured
        $this->webSocketService->updatePresence('test-conversation-id', 1, 'online');
        
        // Test passes if no exception is thrown
        $this->assertTrue(true);
    }

    public function test_connection_info_structure(): void
    {
        $connectionInfo = $this->webSocketService->getConnectionInfo();
        
        // Test structure
        $this->assertIsArray($connectionInfo);
        $this->assertArrayHasKey('enabled', $connectionInfo);
        $this->assertArrayHasKey('key', $connectionInfo);
        $this->assertArrayHasKey('cluster', $connectionInfo);
        $this->assertArrayHasKey('encrypted', $connectionInfo);
        $this->assertArrayHasKey('auth_endpoint', $connectionInfo);
        
        // Test types
        $this->assertIsBool($connectionInfo['enabled']);
        $this->assertIsString($connectionInfo['auth_endpoint']);
        
        if ($connectionInfo['enabled']) {
            $this->assertIsString($connectionInfo['key']);
            $this->assertIsString($connectionInfo['cluster']);
            $this->assertIsBool($connectionInfo['encrypted']);
        }
    }

    public function test_websocket_service_handles_missing_conversation(): void
    {
        // Create message without proper conversation relationship
        $message = new ChatMessage([
            'conversation_id' => 'non-existent-id',
            'content' => 'Test message',
            'direction' => 'inbound',
            'sender_type' => 'visitor',
            'message_type' => 'text',
            'status' => 'delivered'
        ]);

        // Should handle gracefully without throwing exception
        try {
            $this->webSocketService->broadcastMessageReceived($message);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            // If exception is thrown, it should be handled gracefully
            $this->assertStringContainsString('conversation', strtolower($e->getMessage()));
        }
    }
}
