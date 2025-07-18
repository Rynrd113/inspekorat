<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SimpleDuskSeeder extends Seeder
{
    /**
     * Run simple user seeds for Dusk testing.
     */
    public function run(): void
    {
        // Delete existing test users first
        User::where('email', 'like', '%@test.com')->delete();

        // Create test users with different roles
        User::create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Content Manager Test',
            'email' => 'content@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Use admin role for now
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'WBS Manager Test',
            'email' => 'wbs@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Use admin role for now
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'OPD Manager Test',
            'email' => 'opd@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Use admin role for now
            'email_verified_at' => now(),
        ]);
    }
}