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
                'role' => 'super_admin',
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

        // Admin untuk modul baru
        User::firstOrCreate(
            ['email' => 'admin.profil@inspektorat.id'],
            [
                'name' => 'Admin Profil',
                'password' => Hash::make('adminprofil123'),
                'role' => 'admin_profil',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.pelayanan@inspektorat.id'],
            [
                'name' => 'Admin Pelayanan',
                'password' => Hash::make('adminpelayanan123'),
                'role' => 'admin_pelayanan',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.dokumen@inspektorat.id'],
            [
                'name' => 'Admin Dokumen',
                'password' => Hash::make('admindokumen123'),
                'role' => 'admin_dokumen',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.galeri@inspektorat.id'],
            [
                'name' => 'Admin Galeri',
                'password' => Hash::make('admingaleri123'),
                'role' => 'admin_galeri',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin.faq@inspektorat.id'],
            [
                'name' => 'Admin FAQ',
                'password' => Hash::make('adminfaq123'),
                'role' => 'admin_faq',
            ]
        );
    }
}
