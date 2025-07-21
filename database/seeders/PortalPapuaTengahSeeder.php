<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PortalPapuaTengah;

class PortalPapuaTengahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if data already exists
        if (DB::table('portal_papua_tengahs')->count() > 0) {
            return;
        }

        $portalData = [
            [
                'judul' => 'Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi',
                'konten' => 'Inspektorat Provinsi Papua Tengah meluncurkan program pengawasan terintegrasi untuk meningkatkan efektivitas pengawasan internal pemerintah daerah. Program ini bertujuan untuk memastikan akuntabilitas dan transparansi dalam pengelolaan keuangan dan pelayanan publik. Program ini akan mencakup berbagai aspek pengawasan yang terintegrasi dengan sistem informasi untuk memudahkan monitoring dan evaluasi.',
                'kategori' => 'berita',
                'author' => 'Humas Inspektorat Papua Tengah',
                'tanggal_publikasi' => now()->subDays(2)->format('Y-m-d'),
                'gambar' => 'berita/pengawasan-terintegrasi-full.jpg',
                'status' => true,
                'views' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Pengumuman Rekrutmen Auditor Internal Papua Tengah',
                'konten' => 'Inspektorat Provinsi Papua Tengah membuka kesempatan bagi putra-putri terbaik Papua Tengah untuk bergabung sebagai auditor internal. Pendaftaran dibuka mulai tanggal 1 hingga 30 Juli 2025. Persyaratan lengkap dan tata cara pendaftaran dapat dilihat di website resmi inspektorat.',
                'kategori' => 'pengumuman',
                'author' => 'Bagian Kepegawaian',
                'tanggal_publikasi' => now()->subDays(5)->format('Y-m-d'),
                'gambar' => 'berita/rekrutmen-auditor-full.jpg',
                'status' => true,
                'views' => 89,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Kegiatan Sosialisasi Sistem Pengendalian Intern Pemerintah (SPIP)',
                'konten' => 'Inspektorat Papua Tengah menyelenggarakan kegiatan sosialisasi SPIP kepada seluruh OPD di lingkungan Pemerintah Provinsi Papua Tengah. Kegiatan ini bertujuan untuk meningkatkan pemahaman tentang implementasi SPIP. Sosialisasi dilakukan secara bertahap untuk memastikan seluruh OPD memahami dan menerapkan SPIP dengan baik.',
                'kategori' => 'kegiatan',
                'author' => 'Tim SPIP Inspektorat',
                'tanggal_publikasi' => now()->subDays(7)->format('Y-m-d'),
                'gambar' => 'berita/sosialisasi-spip-full.jpg',
                'status' => true,
                'views' => 67,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Peraturan Gubernur Papua Tengah Nomor 25 Tahun 2025 tentang Whistleblower System',
                'konten' => 'Gubernur Papua Tengah menerbitkan Peraturan Gubernur tentang Whistleblower System sebagai upaya pencegahan dan pemberantasan korupsi di lingkungan Pemerintah Provinsi Papua Tengah. Peraturan ini memberikan landasan hukum yang kuat untuk implementasi sistem pelaporan pelanggaran yang efektif dan terpercaya.',
                'kategori' => 'regulasi',
                'author' => 'Bagian Hukum',
                'tanggal_publikasi' => now()->subDays(10)->format('Y-m-d'),
                'gambar' => 'berita/pergub-wbs-full.jpg',
                'status' => true,
                'views' => 234,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Layanan Konsultasi Pengawasan Online Papua Tengah',
                'konten' => 'Inspektorat Papua Tengah menyediakan layanan konsultasi pengawasan secara online untuk memudahkan OPD dalam berkonsultasi terkait pelaksanaan pengawasan dan audit internal. Layanan ini tersedia 24/7 melalui platform digital yang user-friendly.',
                'kategori' => 'layanan',
                'author' => 'Bagian Layanan Pengawasan',
                'tanggal_publikasi' => now()->subDays(12)->format('Y-m-d'),
                'gambar' => 'berita/konsultasi-online-full.jpg',
                'status' => true,
                'views' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Rapat Koordinasi Pencegahan Korupsi di Papua Tengah',
                'konten' => 'Inspektorat Papua Tengah mengadakan rapat koordinasi dengan berbagai instansi terkait untuk membahas strategi pencegahan korupsi di Papua Tengah. Rapat dihadiri oleh perwakilan dari KPK, Kejaksaan, dan Kepolisian. Hasil koordinasi akan menjadi acuan dalam penyusunan program pencegahan korupsi yang komprehensif.',
                'kategori' => 'kegiatan',
                'author' => 'Sekretariat Inspektorat',
                'tanggal_publikasi' => now()->subDays(15)->format('Y-m-d'),
                'gambar' => 'berita/rapat-koordinasi-full.jpg',
                'status' => true,
                'views' => 78,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Use DB::table()->insert() to bypass model events that may cause issues
        DB::table('portal_papua_tengahs')->insert($portalData);
    }
}
