<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create PROTECTED developer account - CANNOT BE DELETED OR EDITED
        if (!User::where('email', 'ahmedelnakieb95@gmail.com')->exists()) {
            User::create([
                'name' => 'Developer',
                'email' => 'ahmedelnakieb95@gmail.com',
                'password' => Hash::make('elnakieb'),
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create default super admin user
        // IMPORTANT: Change the password after first login for security!
        if (!User::where('email', 'admin@geometric-development.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@geometric-development.com',
                'password' => Hash::make('admin'), // Change this password after first login
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create default admin user
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123'),
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
