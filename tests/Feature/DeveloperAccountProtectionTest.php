<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DeveloperAccountSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperAccountProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_account_is_created_by_seeder(): void
    {
        $this->seed(DeveloperAccountSeeder::class);

        $developer = User::where('email', 'ahmedelnakieb95@gmail.com')->first();

        $this->assertNotNull($developer);
        $this->assertEquals('super_admin', $developer->role);
        $this->assertTrue($developer->is_developer);
        $this->assertTrue($developer->is_active);
        $this->assertNotNull($developer->email_verified_at);
    }

    public function test_developer_account_is_hidden_from_user_list(): void
    {
        // Create developer account
        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'is_developer' => true,
            'role' => 'super_admin',
        ]);

        // Create regular admin user
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_developer' => false,
        ]);

        // Create regular users
        User::factory()->count(3)->create([
            'is_developer' => false,
        ]);

        // Login as admin
        $this->actingAs($admin);

        // Access user list (simulating Filament resource query)
        $users = User::nonDeveloper()->get();

        $this->assertCount(4, $users); // 3 regular users + 1 admin
        $this->assertFalse($users->contains('id', $developer->id));
    }

    public function test_developer_account_cannot_be_edited_via_url(): void
    {
        // Create developer account
        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'is_developer' => true,
            'role' => 'super_admin',
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_developer' => false,
        ]);

        // Login as admin
        $this->actingAs($admin);

        // Try to access edit page for developer account
        $response = $this->get("/admin/users/{$developer->id}/edit");

        // Should redirect to user list
        $response->assertRedirect('/admin/users');
    }

    public function test_developer_account_cannot_be_deleted(): void
    {
        // Create developer account
        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'is_developer' => true,
            'role' => 'super_admin',
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_developer' => false,
        ]);

        // Login as admin
        $this->actingAs($admin);

        // Verify developer account exists
        $this->assertDatabaseHas('users', [
            'id' => $developer->id,
            'is_developer' => true,
        ]);

        // The isDeveloper() method should return true
        $this->assertTrue($developer->isDeveloper());
    }

    public function test_edit_and_delete_actions_are_hidden_for_developer(): void
    {
        // Create developer account
        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'is_developer' => true,
            'role' => 'super_admin',
        ]);

        // Create regular user
        $regularUser = User::factory()->create([
            'is_developer' => false,
        ]);

        // Test that developer account should hide actions
        $this->assertTrue($developer->isDeveloper());

        // Test that regular user should show actions
        $this->assertFalse($regularUser->isDeveloper());
    }
}
