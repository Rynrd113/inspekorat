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
                'nama' => 'SIMPEG',
                'deskripsi' => 'Sistem Informasi Manajemen Kepegawaian',
                'url' => 'https://simpeg.papuaselatan.go.id',
                'kategori' => 'layanan',
                'status' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'E-Office',
                'deskripsi' => 'Sistem Perkantoran Elektronik',
                'url' => 'https://eoffice.papuaselatan.go.id',
                'kategori' => 'layanan',
                'status' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'LPSE',
                'deskripsi' => 'Layanan Pengadaan Secara Elektronik',
                'url' => 'https://lpse.papuaselatan.go.id',
                'kategori' => 'layanan',
                'status' => true,
                'urutan' => 3,
            ],
            [
                'nama' => 'Portal Berita',
                'deskripsi' => 'Portal Berita Pemerintah Provinsi Papua Selatan',
                'url' => 'https://berita.papuaselatan.go.id',
                'kategori' => 'informasi',
                'status' => true,
                'urutan' => 4,
            ],
            [
                'nama' => 'Data Center',
                'deskripsi' => 'Pusat Data Pemerintah Provinsi Papua Selatan',
                'url' => 'https://data.papuaselatan.go.id',
                'kategori' => 'informasi',
                'status' => true,
                'urutan' => 5,
            ],
            [
                'nama' => 'Aplikasi Mobile',
                'deskripsi' => 'Aplikasi Mobile Layanan Publik',
                'url' => 'https://mobile.papuaselatan.go.id',
                'kategori' => 'aplikasi',
                'status' => true,
                'urutan' => 6,
            ],
        ];

        foreach ($portals as $portal) {
            WebPortal::create($portal);
        }
    }
}
