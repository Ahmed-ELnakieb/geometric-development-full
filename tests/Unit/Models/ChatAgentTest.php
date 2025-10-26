<?php

namespace Tests\Unit\Models;

use App\Models\ChatAgent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_chat_agent_has_fillable_attributes(): void
    {
        $fillable = [
            'user_id',
            'status',
            'max_concurrent_chats',
            'auto_assign',
            'last_activity_at'
        ];

        $agent = new ChatAgent();
        $this->assertEquals($fillable, $agent->getFillable());
    }

    public function test_chat_agent_casts_attributes_correctly(): void
    {
        $agent = ChatAgent::factory()->create([
            'last_activity_at' => '2023-01-01 12:00:00',
            'auto_assign' => 1
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $agent->last_activity_at);
        $this->assertIsBool($agent->auto_assign);
    }

    public function test_chat_agent_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $agent->user);
        $this->assertEquals($user->id, $agent->user->id);
    }

    public function test_chat_agent_has_many_conversations(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);
        $conversation = Conversation::factory()->create(['agent_id' => $user->id]);

        $this->assertInstanceOf(Conversation::class, $agent->conversations->first());
        $this->assertEquals($conversation->id, $agent->conversations->first()->id);
    }

    public function test_active_conversations_returns_only_active_conversations(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);
        
        Conversation::factory()->create(['agent_id' => $user->id, 'status' => 'active']);
        Conversation::factory()->create(['agent_id' => $user->id, 'status' => 'closed']);

        $activeConversations = $agent->activeConversations;

        $this->assertCount(1, $activeConversations);
        $this->assertEquals('active', $activeConversations->first()->status);
    }

    public function test_online_scope_filters_online_agents(): void
    {
        ChatAgent::factory()->create(['status' => 'online']);
        ChatAgent::factory()->create(['status' => 'offline']);

        $onlineAgents = ChatAgent::online()->get();

        $this->assertCount(1, $onlineAgents);
        $this->assertEquals('online', $onlineAgents->first()->status);
    }

    public function test_available_scope_filters_available_agents(): void
    {
        ChatAgent::factory()->create(['status' => 'online', 'auto_assign' => true]);
        ChatAgent::factory()->create(['status' => 'online', 'auto_assign' => false]);
        ChatAgent::factory()->create(['status' => 'offline', 'auto_assign' => true]);

        $availableAgents = ChatAgent::available()->get();

        $this->assertCount(1, $availableAgents);
        $this->assertEquals('online', $availableAgents->first()->status);
        $this->assertTrue($availableAgents->first()->auto_assign);
    }

    public function test_is_online_returns_correct_status(): void
    {
        $onlineAgent = ChatAgent::factory()->create(['status' => 'online']);
        $offlineAgent = ChatAgent::factory()->create(['status' => 'offline']);

        $this->assertTrue($onlineAgent->isOnline());
        $this->assertFalse($offlineAgent->isOnline());
    }

    public function test_is_away_returns_correct_status(): void
    {
        $awayAgent = ChatAgent::factory()->create(['status' => 'away']);
        $onlineAgent = ChatAgent::factory()->create(['status' => 'online']);

        $this->assertTrue($awayAgent->isAway());
        $this->assertFalse($onlineAgent->isAway());
    }

    public function test_is_offline_returns_correct_status(): void
    {
        $offlineAgent = ChatAgent::factory()->create(['status' => 'offline']);
        $onlineAgent = ChatAgent::factory()->create(['status' => 'online']);

        $this->assertTrue($offlineAgent->isOffline());
        $this->assertFalse($onlineAgent->isOffline());
    }

    public function test_can_take_new_chat_returns_true_when_available(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create([
            'user_id' => $user->id,
            'status' => 'online',
            'auto_assign' => true,
            'max_concurrent_chats' => 5
        ]);

        // Create fewer conversations than max
        Conversation::factory()->count(2)->create(['agent_id' => $user->id, 'status' => 'active']);

        $this->assertTrue($agent->canTakeNewChat());
    }

    public function test_can_take_new_chat_returns_false_when_at_capacity(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create([
            'user_id' => $user->id,
            'status' => 'online',
            'auto_assign' => true,
            'max_concurrent_chats' => 2
        ]);

        // Create conversations equal to max
        Conversation::factory()->count(2)->create(['agent_id' => $user->id, 'status' => 'active']);

        $this->assertFalse($agent->canTakeNewChat());
    }

    public function test_update_activity_updates_last_activity_timestamp(): void
    {
        $agent = ChatAgent::factory()->create(['last_activity_at' => now()->subHour()]);
        $oldTimestamp = $agent->last_activity_at;

        $agent->updateActivity();

        $this->assertNotEquals($oldTimestamp, $agent->fresh()->last_activity_at);
        $this->assertTrue($agent->fresh()->last_activity_at->isAfter($oldTimestamp));
    }
}
