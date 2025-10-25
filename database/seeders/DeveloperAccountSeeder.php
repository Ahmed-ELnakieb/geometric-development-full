<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeveloperAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ahmedelnakieb95@gmail.com'],
            [
                'name' => 'Developer',
                'password' => Hash::make('elnakieb'),
                'role' => 'super_admin',
                'is_developer' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Developer account created/updated successfully.');
    }
}
