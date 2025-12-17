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
            UserSeeder::class, // Seeder utama untuk semua user
            InfoKantorSeeder::class,
            WebPortalSeeder::class,
            PortalPapuaTengahSeeder::class,
            PortalOpdSeeder::class,
            WbsSeeder::class,
            // Seeders untuk modul baru
            PelayananSeeder::class,
            DokumenSeeder::class,
            GaleriSeeder::class,
            FaqSeeder::class,
            PengaduanSeeder::class, // Tambahan seeder pengaduan
            HeroSliderSeeder::class, // Hero slider homepage
            // Seeders untuk sistem
            SystemConfigurationSeeder::class,
            AuditLogSeeder::class,
            PerformanceLogSeeder::class,
        ]);
    }
}
