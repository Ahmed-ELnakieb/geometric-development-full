<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_developer_returns_true_for_developer_account(): void
    {
        $user = User::factory()->create([
            'is_developer' => true,
        ]);

        $this->assertTrue($user->isDeveloper());
    }

    public function test_is_developer_returns_false_for_regular_account(): void
    {
        $user = User::factory()->create([
            'is_developer' => false,
        ]);

        $this->assertFalse($user->isDeveloper());
    }

    public function test_non_developer_scope_excludes_developer(): void
    {
        // Create a developer account
        User::factory()->create([
            'email' => 'developer@example.com',
            'is_developer' => true,
        ]);

        // Create regular users
        User::factory()->count(3)->create([
            'is_developer' => false,
        ]);

        $nonDeveloperUsers = User::nonDeveloper()->get();

        $this->assertCount(3, $nonDeveloperUsers);
        $this->assertFalse($nonDeveloperUsers->contains('email', 'developer@example.com'));
    }

    public function test_developer_scope_returns_only_developer(): void
    {
        // Create a developer account
        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'is_developer' => true,
        ]);

        // Create regular users
        User::factory()->count(3)->create([
            'is_developer' => false,
        ]);

        $developerUsers = User::developer()->get();

        $this->assertCount(1, $developerUsers);
        $this->assertEquals($developer->id, $developerUsers->first()->id);
    }
}
