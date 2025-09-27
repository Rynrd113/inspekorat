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
        // Array berisi semua user yang akan dibuat
        $users = [
            // Super Admin
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@inspektorat.go.id',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ],
            
            // Admin Utama
            [
                'name' => 'Administrator',
                'email' => 'admin@inspektorat.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            
            // Content Admin
            [
                'name' => 'Content Administrator',
                'email' => 'content.admin@inspektorat.go.id',
                'password' => Hash::make('contentadmin123'),
                'role' => 'content_admin',
                'email_verified_at' => now(),
            ],
        ];

        // Buat semua user
        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Tampilkan informasi credentials
        $this->command->info('All users seeded successfully!');
        $this->command->info('=================================');
        $this->command->info('LOGIN CREDENTIALS:');
        $this->command->info('=================================');
        $this->command->info('Super Admin: superadmin@inspektorat.go.id / superadmin123');
        $this->command->info('Admin: admin@inspektorat.go.id / admin123');
        $this->command->info('Content Admin: content.admin@inspektorat.go.id / contentadmin123');
        $this->command->info('=================================');
    }
}
