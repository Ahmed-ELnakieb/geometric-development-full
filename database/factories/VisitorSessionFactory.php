<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisitorSession>
 */
class VisitorSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstVisit = $this->faker->dateTimeBetween('-1 week', 'now');
        
        return [
            'id' => Str::uuid(),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'referrer' => $this->faker->optional()->url(),
            'utm_source' => $this->faker->optional()->randomElement(['google', 'facebook', 'twitter', 'email']),
            'utm_medium' => $this->faker->optional()->randomElement(['cpc', 'social', 'email', 'organic']),
            'utm_campaign' => $this->faker->optional()->words(2, true),
            'first_visit_at' => $firstVisit,
            'last_activity_at' => $this->faker->dateTimeBetween($firstVisit, 'now'),
            'page_views' => $this->faker->numberBetween(1, 20),
            'metadata' => [
                'screen_resolution' => $this->faker->randomElement(['1920x1080', '1366x768', '1440x900']),
                'timezone' => $this->faker->timezone(),
            ],
        ];
    }

    public function withUtm(): static
    {
        return $this->state(fn (array $attributes) => [
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'summer_campaign',
        ]);
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'first_visit_at' => now()->subMinutes(30),
            'last_activity_at' => now()->subMinutes(5),
        ]);
    }
}
