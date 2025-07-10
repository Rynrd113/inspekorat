<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galeris = [
            [
                'judul' => 'Rapat Koordinasi Inspektorat dengan OPD',
                'deskripsi' => 'Kegiatan rapat koordinasi antara Inspektorat dengan seluruh OPD di lingkungan Pemerintah Provinsi Papua Tengah',
                'kategori' => 'Rapat',
                'type' => 'foto',
                'file_path' => 'galeri/rapat-koordinasi-opd.jpg',
                'thumbnail_path' => 'galeri/thumbs/rapat-koordinasi-opd-thumb.jpg',
                'file_name' => 'rapat-koordinasi-opd.jpg',
                'file_size' => '2.8 MB',
                'tanggal_event' => '2024-12-20',
                'lokasi_event' => 'Aula Inspektorat Papua Tengah',
                'urutan' => 1,
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Sosialisasi Whistleblowing System',
                'deskripsi' => 'Kegiatan sosialisasi sistem pelaporan pelanggaran kepada seluruh ASN di Papua Tengah',
                'kategori' => 'Sosialisasi',
                'type' => 'foto',
                'file_path' => 'galeri/sosialisasi-wbs.jpg',
                'thumbnail_path' => 'galeri/thumbs/sosialisasi-wbs-thumb.jpg',
                'file_name' => 'sosialisasi-wbs.jpg',
                'file_size' => '3.1 MB',
                'tanggal_event' => '2024-11-15',
                'lokasi_event' => 'Hotel Grand Papua Nabire',
                'urutan' => 2,
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Audit Lapangan di Dinas Pendidikan',
                'deskripsi' => 'Pelaksanaan audit lapangan untuk mengevaluasi program pendidikan gratis',
                'kategori' => 'Audit',
                'type' => 'foto',
                'file_path' => 'galeri/audit-dinas-pendidikan.jpg',
                'thumbnail_path' => 'galeri/thumbs/audit-dinas-pendidikan-thumb.jpg',
                'file_name' => 'audit-dinas-pendidikan.jpg',
                'file_size' => '2.5 MB',
                'tanggal_event' => '2024-10-08',
                'lokasi_event' => 'Dinas Pendidikan Papua Tengah',
                'urutan' => 3,
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Pelatihan Sistem Pengendalian Internal',
                'deskripsi' => 'Pelatihan untuk meningkatkan pemahaman ASN tentang sistem pengendalian internal pemerintah',
                'kategori' => 'Pelatihan',
                'type' => 'foto',
                'file_path' => 'galeri/pelatihan-spip.jpg',
                'thumbnail_path' => 'galeri/thumbs/pelatihan-spip-thumb.jpg',
                'file_name' => 'pelatihan-spip.jpg',
                'file_size' => '2.9 MB',
                'tanggal_event' => '2024-09-25',
                'lokasi_event' => 'Bappeda Papua Tengah',
                'urutan' => 4,
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Video Profil Inspektorat Papua Tengah',
                'deskripsi' => 'Video profil yang menggambarkan tugas, fungsi, dan peran Inspektorat Provinsi Papua Tengah',
                'kategori' => 'Profil',
                'type' => 'video',
                'file_path' => 'galeri/video-profil-inspektorat.mp4',
                'thumbnail_path' => 'galeri/thumbs/video-profil-thumb.jpg',
                'file_name' => 'video-profil-inspektorat.mp4',
                'file_size' => '45.2 MB',
                'tanggal_event' => '2024-08-17',
                'lokasi_event' => 'Kantor Inspektorat Papua Tengah',
                'urutan' => 5,
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Monitoring dan Evaluasi Program Kerja',
                'deskripsi' => 'Kegiatan monitoring dan evaluasi pelaksanaan program kerja semester I tahun 2024',
                'kategori' => 'Monitoring',
                'type' => 'foto',
                'file_path' => 'galeri/monev-program-kerja.jpg',
                'thumbnail_path' => 'galeri/thumbs/monev-program-kerja-thumb.jpg',
                'file_name' => 'monev-program-kerja.jpg',
                'file_size' => '2.6 MB',
                'tanggal_event' => '2024-07-12',
                'lokasi_event' => 'Ruang Rapat Inspektorat',
                'urutan' => 6,
                'created_by' => 1,
                'status' => true,
            ],
        ];

        foreach ($galeris as $galeri) {
            Galeri::firstOrCreate(
                ['judul' => $galeri['judul']],
                $galeri
            );
        }
    }
}
