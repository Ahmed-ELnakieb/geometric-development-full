<?php

namespace Tests\Unit\Models;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_message_has_fillable_attributes(): void
    {
        $fillable = [
            'conversation_id',
            'whatsapp_message_id',
            'direction',
            'sender_type',
            'sender_id',
            'message_type',
            'content',
            'media_url',
            'status',
            'metadata'
        ];

        $message = new ChatMessage();
        $this->assertEquals($fillable, $message->getFillable());
    }

    public function test_chat_message_casts_metadata_to_array(): void
    {
        $message = ChatMessage::factory()->create([
            'metadata' => ['timestamp' => '2023-01-01']
        ]);

        $this->assertIsArray($message->metadata);
        $this->assertEquals(['timestamp' => '2023-01-01'], $message->metadata);
    }

    public function test_chat_message_belongs_to_conversation(): void
    {
        $conversation = Conversation::factory()->create();
        $message = ChatMessage::factory()->create(['conversation_id' => $conversation->id]);

        $this->assertInstanceOf(Conversation::class, $message->conversation);
        $this->assertEquals($conversation->id, $message->conversation->id);
    }

    public function test_inbound_scope_filters_inbound_messages(): void
    {
        ChatMessage::factory()->create(['direction' => 'inbound']);
        ChatMessage::factory()->create(['direction' => 'outbound']);

        $inboundMessages = ChatMessage::inbound()->get();

        $this->assertCount(1, $inboundMessages);
        $this->assertEquals('inbound', $inboundMessages->first()->direction);
    }

    public function test_outbound_scope_filters_outbound_messages(): void
    {
        ChatMessage::factory()->create(['direction' => 'outbound']);
        ChatMessage::factory()->create(['direction' => 'inbound']);

        $outboundMessages = ChatMessage::outbound()->get();

        $this->assertCount(1, $outboundMessages);
        $this->assertEquals('outbound', $outboundMessages->first()->direction);
    }

    public function test_by_status_scope_filters_by_status(): void
    {
        ChatMessage::factory()->create(['status' => 'delivered']);
        ChatMessage::factory()->create(['status' => 'pending']);

        $deliveredMessages = ChatMessage::byStatus('delivered')->get();

        $this->assertCount(1, $deliveredMessages);
        $this->assertEquals('delivered', $deliveredMessages->first()->status);
    }

    public function test_pending_scope_filters_pending_messages(): void
    {
        ChatMessage::factory()->create(['status' => 'pending']);
        ChatMessage::factory()->create(['status' => 'sent']);

        $pendingMessages = ChatMessage::pending()->get();

        $this->assertCount(1, $pendingMessages);
        $this->assertEquals('pending', $pendingMessages->first()->status);
    }

    public function test_delivered_scope_filters_delivered_messages(): void
    {
        ChatMessage::factory()->create(['status' => 'delivered']);
        ChatMessage::factory()->create(['status' => 'pending']);

        $deliveredMessages = ChatMessage::delivered()->get();

        $this->assertCount(1, $deliveredMessages);
        $this->assertEquals('delivered', $deliveredMessages->first()->status);
    }

    public function test_is_from_visitor_returns_true_for_visitor_messages(): void
    {
        $message = ChatMessage::factory()->create(['sender_type' => 'visitor']);

        $this->assertTrue($message->isFromVisitor());
        $this->assertFalse($message->isFromAgent());
        $this->assertFalse($message->isFromSystem());
    }

    public function test_is_from_agent_returns_true_for_agent_messages(): void
    {
        $message = ChatMessage::factory()->create(['sender_type' => 'agent']);

        $this->assertTrue($message->isFromAgent());
        $this->assertFalse($message->isFromVisitor());
        $this->assertFalse($message->isFromSystem());
    }

    public function test_is_from_system_returns_true_for_system_messages(): void
    {
        $message = ChatMessage::factory()->create(['sender_type' => 'system']);

        $this->assertTrue($message->isFromSystem());
        $this->assertFalse($message->isFromVisitor());
        $this->assertFalse($message->isFromAgent());
    }
}
