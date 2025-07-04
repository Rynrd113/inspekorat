<?php

namespace Database\Seeders;

use App\Models\InfoKantor;
use Illuminate\Database\Seeder;

class InfoKantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infoKantor = [
            [
                'judul' => 'Alamat Kantor',
                'konten' => 'Jl. Trans Papua, Merauke, Papua Selatan 99611',
                'kategori' => 'alamat',
                'icon' => 'fas fa-map-marker-alt',
                'urutan' => 1,
            ],
            [
                'judul' => 'Telepon',
                'konten' => '(0971) 321234',
                'kategori' => 'kontak',
                'icon' => 'fas fa-phone',
                'urutan' => 2,
            ],
            [
                'judul' => 'Email',
                'konten' => 'inspektorat@papuaselatan.go.id',
                'kategori' => 'kontak',
                'icon' => 'fas fa-envelope',
                'urutan' => 3,
            ],
            [
                'judul' => 'Jam Operasional',
                'konten' => 'Senin - Jumat: 08:00 - 16:00 WIT<br>Sabtu - Minggu: Tutup',
                'kategori' => 'jam_operasional',
                'icon' => 'fas fa-clock',
                'urutan' => 4,
            ],
            [
                'judul' => 'Fax',
                'konten' => '(0971) 321235',
                'kategori' => 'kontak',
                'icon' => 'fas fa-fax',
                'urutan' => 5,
            ],
            [
                'judul' => 'Website Resmi',
                'konten' => 'https://inspektorat.papuaselatan.go.id',
                'kategori' => 'kontak',
                'icon' => 'fas fa-globe',
                'urutan' => 6,
            ],
        ];

        foreach ($infoKantor as $info) {
            InfoKantor::create($info);
        }
    }
}
