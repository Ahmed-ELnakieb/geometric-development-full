<?php

namespace Tests\Feature;

use App\Models\ChatAgent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_can_be_created(): void
    {
        $user = User::factory()->create();
        
        $agent = ChatAgent::create([
            'user_id' => $user->id,
            'status' => 'online',
            'max_concurrent_chats' => 5,
            'auto_assign' => true
        ]);

        $this->assertDatabaseHas('chat_agents', [
            'user_id' => $user->id,
            'status' => 'online',
            'max_concurrent_chats' => 5,
            'auto_assign' => true
        ]);
    }

    public function test_agent_status_can_be_updated(): void
    {
        $agent = ChatAgent::factory()->create(['status' => 'offline']);
        
        $agent->update(['status' => 'online', 'last_activity_at' => now()]);
        
        $this->assertEquals('online', $agent->fresh()->status);
        $this->assertNotNull($agent->fresh()->last_activity_at);
    }

    public function test_agent_can_take_new_chat_when_available(): void
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

    public function test_agent_cannot_take_new_chat_when_at_capacity(): void
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

    public function test_agent_cannot_take_new_chat_when_offline(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create([
            'user_id' => $user->id,
            'status' => 'offline',
            'auto_assign' => true,
            'max_concurrent_chats' => 5
        ]);

        $this->assertFalse($agent->canTakeNewChat());
    }

    public function test_agent_cannot_take_new_chat_when_auto_assign_disabled(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create([
            'user_id' => $user->id,
            'status' => 'online',
            'auto_assign' => false,
            'max_concurrent_chats' => 5
        ]);

        $this->assertFalse($agent->canTakeNewChat());
    }

    public function test_online_scope_filters_online_agents(): void
    {
        ChatAgent::factory()->create(['status' => 'online']);
        ChatAgent::factory()->create(['status' => 'offline']);
        ChatAgent::factory()->create(['status' => 'away']);

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

    public function test_agent_activity_can_be_updated(): void
    {
        $agent = ChatAgent::factory()->create(['last_activity_at' => now()->subHour()]);
        $oldTimestamp = $agent->last_activity_at;

        $agent->updateActivity();

        $this->assertNotEquals($oldTimestamp, $agent->fresh()->last_activity_at);
        $this->assertTrue($agent->fresh()->last_activity_at->isAfter($oldTimestamp));
    }

    public function test_agent_active_conversations_relationship(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);
        
        Conversation::factory()->create(['agent_id' => $user->id, 'status' => 'active']);
        Conversation::factory()->create(['agent_id' => $user->id, 'status' => 'closed']);

        $activeConversations = $agent->activeConversations;

        $this->assertCount(1, $activeConversations);
        $this->assertEquals('active', $activeConversations->first()->status);
    }

    public function test_agent_status_methods(): void
    {
        $onlineAgent = ChatAgent::factory()->create(['status' => 'online']);
        $awayAgent = ChatAgent::factory()->create(['status' => 'away']);
        $offlineAgent = ChatAgent::factory()->create(['status' => 'offline']);

        $this->assertTrue($onlineAgent->isOnline());
        $this->assertFalse($onlineAgent->isAway());
        $this->assertFalse($onlineAgent->isOffline());

        $this->assertFalse($awayAgent->isOnline());
        $this->assertTrue($awayAgent->isAway());
        $this->assertFalse($awayAgent->isOffline());

        $this->assertFalse($offlineAgent->isOnline());
        $this->assertFalse($offlineAgent->isAway());
        $this->assertTrue($offlineAgent->isOffline());
    }

    public function test_agent_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $agent->user);
        $this->assertEquals($user->id, $agent->user->id);
    }

    public function test_agent_has_many_conversations(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);
        $conversation = Conversation::factory()->create(['agent_id' => $user->id]);

        $this->assertInstanceOf(Conversation::class, $agent->conversations->first());
        $this->assertEquals($conversation->id, $agent->conversations->first()->id);
    }

    public function test_conversation_assignment_logic(): void
    {
        // Create multiple agents with different availability
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        
        $availableAgent = ChatAgent::factory()->create([
            'user_id' => $user1->id,
            'status' => 'online',
            'auto_assign' => true,
            'max_concurrent_chats' => 5
        ]);
        
        $busyAgent = ChatAgent::factory()->create([
            'user_id' => $user2->id,
            'status' => 'online',
            'auto_assign' => true,
            'max_concurrent_chats' => 1
        ]);
        
        $offlineAgent = ChatAgent::factory()->create([
            'user_id' => $user3->id,
            'status' => 'offline',
            'auto_assign' => true,
            'max_concurrent_chats' => 5
        ]);

        // Make busy agent at capacity
        Conversation::factory()->create(['agent_id' => $user2->id, 'status' => 'active']);

        // Test which agents can take new chats
        $this->assertTrue($availableAgent->canTakeNewChat());
        $this->assertFalse($busyAgent->canTakeNewChat());
        $this->assertFalse($offlineAgent->canTakeNewChat());

        // Test finding available agents
        $availableAgents = ChatAgent::available()
            ->get()
            ->filter(fn($agent) => $agent->canTakeNewChat());

        $this->assertCount(1, $availableAgents);
        $this->assertEquals($availableAgent->id, $availableAgents->first()->id);
    }

    public function test_agent_performance_metrics(): void
    {
        $user = User::factory()->create();
        $agent = ChatAgent::factory()->create(['user_id' => $user->id]);
        
        // Create conversations with different statuses
        Conversation::factory()->count(3)->create(['agent_id' => $user->id, 'status' => 'active']);
        Conversation::factory()->count(2)->create(['agent_id' => $user->id, 'status' => 'closed']);
        Conversation::factory()->count(1)->create(['agent_id' => $user->id, 'status' => 'waiting']);

        $totalConversations = $agent->conversations()->count();
        $activeConversations = $agent->activeConversations()->count();

        $this->assertEquals(6, $totalConversations);
        $this->assertEquals(3, $activeConversations);
    }
}
