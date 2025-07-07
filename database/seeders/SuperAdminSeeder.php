<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@inspektorat.id'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
            ]
        );

        // Create Admin for each module
        User::firstOrCreate(
            ['email' => 'admin@inspektorat.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.wbs@inspektorat.id'],
            [
                'name' => 'Admin WBS',
                'password' => Hash::make('adminwbs123'),
                'role' => 'admin_wbs',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.berita@inspektorat.id'],
            [
                'name' => 'Admin Berita',
                'password' => Hash::make('adminberita123'),
                'role' => 'admin_berita',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.opd@inspektorat.id'],
            [
                'name' => 'Admin Portal OPD',
                'password' => Hash::make('adminopd123'),
                'role' => 'admin_portal_opd',
            ]
        );

        // Update existing admin user if exists
        User::where('email', 'admin@example.com')
            ->update([
                'role' => 'admin'
            ]);
    }
}
