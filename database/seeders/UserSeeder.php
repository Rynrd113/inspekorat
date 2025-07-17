<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create content manager
        User::create([
            'name' => 'Content Manager',
            'email' => 'content@admin.com',
            'password' => Hash::make('password'),
            'role' => 'content_manager',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create service manager
        User::create([
            'name' => 'Service Manager',
            'email' => 'service@admin.com',
            'password' => Hash::make('password'),
            'role' => 'service_manager',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create OPD manager
        User::create([
            'name' => 'OPD Manager',
            'email' => 'opd@admin.com',
            'password' => Hash::make('password'),
            'role' => 'opd_manager',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create WBS manager
        User::create([
            'name' => 'WBS Manager',
            'email' => 'wbs@admin.com',
            'password' => Hash::make('password'),
            'role' => 'wbs_manager',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create specific admin roles
        $specificRoles = [
            'admin_berita' => 'Admin Berita',
            'admin_pelayanan' => 'Admin Pelayanan',
            'admin_dokumen' => 'Admin Dokumen',
            'admin_galeri' => 'Admin Galeri',
            'admin_faq' => 'Admin FAQ',
            'admin_wbs' => 'Admin WBS',
            'admin_portal_opd' => 'Admin Portal OPD',
        ];

        foreach ($specificRoles as $role => $name) {
            User::create([
                'name' => $name,
                'email' => str_replace('admin_', '', $role) . '@admin.com',
                'password' => Hash::make('password'),
                'role' => $role,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
