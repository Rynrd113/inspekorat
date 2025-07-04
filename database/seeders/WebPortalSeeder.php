<?php

namespace Database\Seeders;

use App\Models\WebPortal;
use Illuminate\Database\Seeder;

class WebPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portals = [
            [
                'nama_portal' => 'SIMPEG',
                'deskripsi' => 'Sistem Informasi Manajemen Kepegawaian',
                'url_portal' => 'https://simpeg.papuaselatan.go.id',
                'kategori' => 'layanan',
                'icon' => 'fas fa-users',
                'urutan' => 1,
            ],
            [
                'nama_portal' => 'E-Office',
                'deskripsi' => 'Sistem Perkantoran Elektronik',
                'url_portal' => 'https://eoffice.papuaselatan.go.id',
                'kategori' => 'layanan',
                'icon' => 'fas fa-file-alt',
                'urutan' => 2,
            ],
            [
                'nama_portal' => 'LPSE',
                'deskripsi' => 'Layanan Pengadaan Secara Elektronik',
                'url_portal' => 'https://lpse.papuaselatan.go.id',
                'kategori' => 'layanan',
                'icon' => 'fas fa-shopping-cart',
                'urutan' => 3,
            ],
            [
                'nama_portal' => 'Portal Berita',
                'deskripsi' => 'Portal Berita Pemerintah Provinsi Papua Selatan',
                'url_portal' => 'https://berita.papuaselatan.go.id',
                'kategori' => 'informasi',
                'icon' => 'fas fa-newspaper',
                'urutan' => 4,
            ],
            [
                'nama_portal' => 'Data Center',
                'deskripsi' => 'Pusat Data Pemerintah Provinsi Papua Selatan',
                'url_portal' => 'https://data.papuaselatan.go.id',
                'kategori' => 'informasi',
                'icon' => 'fas fa-database',
                'urutan' => 5,
            ],
            [
                'nama_portal' => 'Aplikasi Mobile',
                'deskripsi' => 'Aplikasi Mobile Layanan Publik',
                'url_portal' => 'https://mobile.papuaselatan.go.id',
                'kategori' => 'aplikasi',
                'icon' => 'fas fa-mobile-alt',
                'urutan' => 6,
            ],
        ];

        foreach ($portals as $portal) {
            WebPortal::create($portal);
        }
    }
}
