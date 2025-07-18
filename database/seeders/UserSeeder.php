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
            
            // Admin Modul-modul
            [
                'name' => 'Admin WBS',
                'email' => 'admin.wbs@inspektorat.go.id',
                'password' => Hash::make('adminwbs123'),
                'role' => 'admin_wbs',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Berita',
                'email' => 'admin.berita@inspektorat.go.id',
                'password' => Hash::make('adminberita123'),
                'role' => 'admin_berita',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Portal OPD',
                'email' => 'admin.opd@inspektorat.go.id',
                'password' => Hash::make('adminopd123'),
                'role' => 'admin_portal_opd',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Profil',
                'email' => 'admin.profil@inspektorat.go.id',
                'password' => Hash::make('adminprofil123'),
                'role' => 'admin_profil',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Pelayanan',
                'email' => 'admin.pelayanan@inspektorat.go.id',
                'password' => Hash::make('adminpelayanan123'),
                'role' => 'admin_pelayanan',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Dokumen',
                'email' => 'admin.dokumen@inspektorat.go.id',
                'password' => Hash::make('admindokumen123'),
                'role' => 'admin_dokumen',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin Galeri',
                'email' => 'admin.galeri@inspektorat.go.id',
                'password' => Hash::make('admingaleri123'),
                'role' => 'admin_galeri',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin FAQ',
                'email' => 'admin.faq@inspektorat.go.id',
                'password' => Hash::make('adminfaq123'),
                'role' => 'admin_faq',
                'email_verified_at' => now(),
            ],
            
            // Manager Roles
            [
                'name' => 'Content Manager',
                'email' => 'content.manager@inspektorat.go.id',
                'password' => Hash::make('contentmanager123'),
                'role' => 'content_manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Service Manager',
                'email' => 'service.manager@inspektorat.go.id',
                'password' => Hash::make('servicemanager123'),
                'role' => 'service_manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'OPD Manager',
                'email' => 'opd.manager@inspektorat.go.id',
                'password' => Hash::make('opdmanager123'),
                'role' => 'opd_manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'WBS Manager',
                'email' => 'wbs.manager@inspektorat.go.id',
                'password' => Hash::make('wbsmanager123'),
                'role' => 'wbs_manager',
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
        $this->command->info('Admin WBS: admin.wbs@inspektorat.go.id / adminwbs123');
        $this->command->info('Admin Berita: admin.berita@inspektorat.go.id / adminberita123');
        $this->command->info('Admin OPD: admin.opd@inspektorat.go.id / adminopd123');
        $this->command->info('Admin Profil: admin.profil@inspektorat.go.id / adminprofil123');
        $this->command->info('Admin Pelayanan: admin.pelayanan@inspektorat.go.id / adminpelayanan123');
        $this->command->info('Admin Dokumen: admin.dokumen@inspektorat.go.id / admindokumen123');
        $this->command->info('Admin Galeri: admin.galeri@inspektorat.go.id / admingaleri123');
        $this->command->info('Admin FAQ: admin.faq@inspektorat.go.id / adminfaq123');
        $this->command->info('Content Manager: content.manager@inspektorat.go.id / contentmanager123');
        $this->command->info('Service Manager: service.manager@inspektorat.go.id / servicemanager123');
        $this->command->info('OPD Manager: opd.manager@inspektorat.go.id / opdmanager123');
        $this->command->info('WBS Manager: wbs.manager@inspektorat.go.id / wbsmanager123');
        $this->command->info('=================================');
    }
}
