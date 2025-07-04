<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaduanData = [
            [
                'nama_pengadu' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'telepon' => '081234567890',
                'subjek' => 'Pelayanan publik yang kurang baik',
                'isi_pengaduan' => 'Saya ingin mengadukan pelayanan di kantor yang sangat lambat dan tidak profesional.',
                'status' => 'pending'
            ],
            [
                'nama_pengadu' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'telepon' => '082345678901',
                'subjek' => 'Pungutan liar di daerah',
                'isi_pengaduan' => 'Ada oknum yang melakukan pungutan liar di area pasar tradisional.',
                'status' => 'proses'
            ],
            [
                'nama_pengadu' => 'Ahmad Wijaya',
                'email' => 'ahmad@example.com',
                'telepon' => '083456789012',
                'subjek' => 'Nepotisme dalam rekrutmen',
                'isi_pengaduan' => 'Proses rekrutmen pegawai terindikasi adanya nepotisme dan tidak transparan.',
                'status' => 'selesai',
                'tanggapan' => 'Telah ditindaklanjuti ke bagian kepegawaian untuk evaluasi.'
            ],
            [
                'nama_pengadu' => 'Maria Dewi',
                'email' => 'maria@example.com',
                'telepon' => '084567890123',
                'subjek' => 'Keluhan umum fasilitas',
                'isi_pengaduan' => 'Fasilitas umum di kantor perlu diperbaiki, terutama toilet dan tempat parkir.',
                'status' => 'pending'
            ],
            [
                'nama_pengadu' => 'Robert Chen',
                'email' => 'robert@example.com',
                'telepon' => null,
                'subjek' => 'Transparansi anggaran',
                'isi_pengaduan' => 'Mohon adanya transparansi dalam penggunaan anggaran daerah.',
                'status' => 'selesai',
                'tanggapan' => 'Informasi anggaran telah tersedia di website resmi.'
            ]
        ];

        foreach ($pengaduanData as $data) {
            Pengaduan::create($data);
        }
    }
}
