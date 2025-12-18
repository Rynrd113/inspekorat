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
        // Check if data already exists
        if (InfoKantor::count() > 0) {
            return;
        }

        $infoKantorData = [
            [
                'nama' => 'Inspektorat Daerah Provinsi Papua Tengah',
                'alamat' => config('contact.alamat'),
                'telepon' => null,
                'email' => config('contact.email'),
                'website' => config('contact.website.url'),
                'deskripsi' => 'Inspektorat Daerah Provinsi Papua Tengah adalah unsur pengawas yang bertugas menyelenggarakan pengawasan internal di lingkungan Pemerintah Provinsi Papua Tengah.',
                'jam_operasional' => config('contact.jam_operasional'),
                'latitude' => -3.3744146,
                'longitude' => 135.5052575,
                'status' => true,
            ],
            [
                'nama' => 'Kantor Cabang Inspektorat - Timika',
                'alamat' => 'Jl. Yos Sudarso No. 12, Timika, Papua Tengah',
                'telepon' => '(0901) 321456',
                'email' => 'cabang.timika@papuatengah.go.id',
                'website' => null,
                'deskripsi' => 'Kantor cabang Inspektorat yang melayani wilayah Timika dan sekitarnya.',
                'jam_operasional' => 'Senin - Jumat: 08:00 - 15:00 WIT',
                'latitude' => -4.5333,
                'longitude' => 136.8833,
                'status' => true,
            ],
            [
                'nama' => 'Kantor Cabang Inspektorat - Paniai',
                'alamat' => 'Jl. Danau Paniai No. 8, Enarotali, Papua Tengah',
                'telepon' => '(0968) 41234',
                'email' => 'cabang.paniai@papuatengah.go.id',
                'website' => null,
                'deskripsi' => 'Kantor cabang Inspektorat yang melayani wilayah Paniai dan sekitarnya.',
                'jam_operasional' => 'Senin - Jumat: 08:00 - 15:00 WIT',
                'latitude' => -3.8667,
                'longitude' => 136.35,
                'status' => true,
            ],
        ];

        foreach ($infoKantorData as $data) {
            InfoKantor::create($data);
        }
    }
}
