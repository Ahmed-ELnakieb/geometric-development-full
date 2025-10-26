<?php

namespace Tests\Unit\Models;

use App\Models\Conversation;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\VisitorSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversation_has_fillable_attributes(): void
    {
        $fillable = [
            'visitor_session_id',
            'whatsapp_phone_number',
            'visitor_phone_number',
            'agent_id',
            'status',
            'priority',
            'source',
            'metadata'
        ];

        $conversation = new Conversation();
        $this->assertEquals($fillable, $conversation->getFillable());
    }

    public function test_conversation_casts_metadata_to_array(): void
    {
        $conversation = Conversation::factory()->create([
            'metadata' => ['key' => 'value']
        ]);

        $this->assertIsArray($conversation->metadata);
        $this->assertEquals(['key' => 'value'], $conversation->metadata);
    }

    public function test_conversation_belongs_to_agent(): void
    {
        $user = User::factory()->create();
        $conversation = Conversation::factory()->create(['agent_id' => $user->id]);

        $this->assertInstanceOf(User::class, $conversation->agent);
        $this->assertEquals($user->id, $conversation->agent->id);
    }

    public function test_conversation_belongs_to_visitor_session(): void
    {
        $session = VisitorSession::factory()->create();
        $conversation = Conversation::factory()->create(['visitor_session_id' => $session->id]);

        $this->assertInstanceOf(VisitorSession::class, $conversation->visitorSession);
        $this->assertEquals($session->id, $conversation->visitorSession->id);
    }

    public function test_conversation_has_many_messages(): void
    {
        $conversation = Conversation::factory()->create();
        $message = ChatMessage::factory()->create(['conversation_id' => $conversation->id]);

        $this->assertInstanceOf(ChatMessage::class, $conversation->messages->first());
        $this->assertEquals($message->id, $conversation->messages->first()->id);
    }

    public function test_active_scope_filters_active_conversations(): void
    {
        Conversation::factory()->create(['status' => 'active']);
        Conversation::factory()->create(['status' => 'closed']);

        $activeConversations = Conversation::active()->get();

        $this->assertCount(1, $activeConversations);
        $this->assertEquals('active', $activeConversations->first()->status);
    }

    public function test_waiting_scope_filters_waiting_conversations(): void
    {
        Conversation::factory()->create(['status' => 'waiting']);
        Conversation::factory()->create(['status' => 'active']);

        $waitingConversations = Conversation::waiting()->get();

        $this->assertCount(1, $waitingConversations);
        $this->assertEquals('waiting', $waitingConversations->first()->status);
    }

    public function test_by_agent_scope_filters_by_agent_id(): void
    {
        $agent = User::factory()->create();
        Conversation::factory()->create(['agent_id' => $agent->id]);
        Conversation::factory()->create(['agent_id' => User::factory()->create()->id]);

        $agentConversations = Conversation::byAgent($agent->id)->get();

        $this->assertCount(1, $agentConversations);
        $this->assertEquals($agent->id, $agentConversations->first()->agent_id);
    }

    public function test_conversation_uses_uuid_primary_key(): void
    {
        $conversation = Conversation::factory()->create();

        $this->assertIsString($conversation->id);
        $this->assertEquals(36, strlen($conversation->id)); // UUID length
    }
}
