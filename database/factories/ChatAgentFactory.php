<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatAgent>
 */
class ChatAgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['online', 'away', 'offline']),
            'max_concurrent_chats' => $this->faker->numberBetween(3, 10),
            'auto_assign' => $this->faker->boolean(80),
            'last_activity_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'online',
            'last_activity_at' => now(),
        ]);
    }

    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'offline',
            'last_activity_at' => $this->faker->dateTimeBetween('-1 day', '-1 hour'),
        ]);
    }

    public function autoAssign(): static
    {
        return $this->state(fn (array $attributes) => [
            'auto_assign' => true,
        ]);
    }
}
