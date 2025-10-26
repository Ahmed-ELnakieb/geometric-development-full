<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMessage>
 */
class ChatMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $direction = $this->faker->randomElement(['inbound', 'outbound']);
        $senderType = $direction === 'inbound' ? 'visitor' : 'agent';
        
        return [
            'conversation_id' => Conversation::factory(),
            'whatsapp_message_id' => 'wamid.' . $this->faker->uuid(),
            'direction' => $direction,
            'sender_type' => $senderType,
            'sender_id' => $senderType === 'agent' ? User::factory() : null,
            'message_type' => 'text',
            'content' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'sent', 'delivered', 'read']),
            'metadata' => [
                'timestamp' => now()->toISOString(),
            ],
        ];
    }

    public function inbound(): static
    {
        return $this->state(fn (array $attributes) => [
            'direction' => 'inbound',
            'sender_type' => 'visitor',
            'sender_id' => null,
        ]);
    }

    public function outbound(): static
    {
        return $this->state(fn (array $attributes) => [
            'direction' => 'outbound',
            'sender_type' => 'agent',
            'sender_id' => User::factory(),
        ]);
    }

    public function withMedia(): static
    {
        return $this->state(fn (array $attributes) => [
            'message_type' => 'image',
            'media_url' => $this->faker->imageUrl(),
        ]);
    }
}
