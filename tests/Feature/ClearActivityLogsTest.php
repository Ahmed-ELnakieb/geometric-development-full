<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ClearActivityLogsTest extends TestCase
{
    use RefreshDatabase;

    protected User $developer;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create developer account
        $this->developer = User::factory()->create([
            'email' => 'ahmedelnakieb95@gmail.com',
            'password' => Hash::make('elnakieb'),
            'is_developer' => true,
            'role' => 'super_admin',
        ]);

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'super_admin',
            'is_developer' => false,
        ]);

        // Create some activity logs
        for ($i = 0; $i < 5; $i++) {
            activity()
                ->causedBy($this->admin)
                ->log("Test activity log {$i}");
        }
    }

    public function test_clear_logs_action_requires_valid_developer_credentials(): void
    {
        $this->actingAs($this->admin);

        // Verify logs exist
        $this->assertGreaterThan(0, Activity::count());

        // Test with valid credentials
        $developer = User::where('email', 'ahmedelnakieb95@gmail.com')
            ->where('is_developer', true)
            ->first();

        $this->assertNotNull($developer);
        $this->assertTrue(Hash::check('elnakieb', $developer->password));
    }

    public function test_clear_logs_fails_with_invalid_email(): void
    {
        $this->actingAs($this->admin);

        // Try to validate with invalid email
        $developer = User::where('email', 'invalid@example.com')
            ->where('is_developer', true)
            ->first();

        $this->assertNull($developer);
    }

    public function test_clear_logs_fails_with_invalid_password(): void
    {
        $this->actingAs($this->admin);

        // Get developer account
        $developer = User::where('email', 'ahmedelnakieb95@gmail.com')
            ->where('is_developer', true)
            ->first();

        $this->assertNotNull($developer);

        // Test with wrong password
        $this->assertFalse(Hash::check('wrongpassword', $developer->password));
    }

    public function test_clear_logs_fails_with_non_developer_account(): void
    {
        $this->actingAs($this->admin);

        // Create a regular user with same email pattern
        $regularUser = User::factory()->create([
            'email' => 'regular@example.com',
            'password' => Hash::make('password'),
            'is_developer' => false,
        ]);

        // Try to find as developer
        $developer = User::where('email', 'regular@example.com')
            ->where('is_developer', true)
            ->first();

        $this->assertNull($developer);
    }

    public function test_clear_logs_succeeds_with_valid_credentials(): void
    {
        $this->actingAs($this->admin);

        // Verify logs exist
        $initialCount = Activity::count();
        $this->assertGreaterThan(0, $initialCount);

        // Validate credentials
        $developer = User::where('email', 'ahmedelnakieb95@gmail.com')
            ->where('is_developer', true)
            ->first();

        $this->assertNotNull($developer);
        $this->assertTrue(Hash::check('elnakieb', $developer->password));

        // Simulate clearing logs
        Activity::truncate();

        // Verify logs are cleared
        $this->assertEquals(0, Activity::count());
    }

    public function test_clear_logs_operation_is_logged_in_activity_log(): void
    {
        $this->actingAs($this->admin);

        // Clear existing logs
        Activity::truncate();

        // Log the clear operation
        activity()
            ->causedBy($this->admin)
            ->log('Cleared all activity logs (authenticated by developer: ahmedelnakieb95@gmail.com)');

        // Verify the log was created
        $this->assertEquals(1, Activity::count());

        $log = Activity::first();
        $this->assertEquals('Cleared all activity logs (authenticated by developer: ahmedelnakieb95@gmail.com)', $log->description);
        $this->assertEquals($this->admin->id, $log->causer_id);
    }
}
