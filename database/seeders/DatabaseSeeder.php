<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class, // Seeder utama untuk semua user testing
            PortalPapuaTengahSeeder::class,
            InfoKantorSeeder::class,
            WbsSeeder::class,
            PortalOpdSeeder::class,
            // Seeders untuk modul baru
            PelayananSeeder::class,
            DokumenSeeder::class,
            GaleriSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
