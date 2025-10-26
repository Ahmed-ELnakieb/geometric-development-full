<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VisitorSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visitor_session_id' => VisitorSession::factory(),
            'whatsapp_phone_number' => '+1234567890',
            'visitor_phone_number' => $this->faker->phoneNumber(),
            'agent_id' => User::factory(),
            'status' => $this->faker->randomElement(['active', 'waiting', 'closed']),
            'priority' => $this->faker->numberBetween(1, 5),
            'source' => $this->faker->randomElement(['website', 'facebook', 'google', 'direct']),
            'metadata' => [
                'browser' => $this->faker->userAgent(),
                'page' => $this->faker->url(),
            ],
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function waiting(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'waiting',
            'agent_id' => null,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }
}
