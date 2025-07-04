<?php

namespace Database\Seeders;

use App\Models\PortalPapuaTengah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortalPapuaTengahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portalData = [
            [
                'judul' => 'Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi',
                'slug' => 'inspektorat-papua-tengah-luncurkan-program-pengawasan-terintegrasi',
                'konten' => 'Inspektorat Provinsi Papua Tengah meluncurkan program pengawasan terintegrasi untuk meningkatkan efektivitas pengawasan internal pemerintah daerah. Program ini bertujuan untuk memastikan akuntabilitas dan transparansi dalam pengelolaan keuangan dan pelayanan publik.',
                'penulis' => 'Humas Inspektorat Papua Tengah',
                'kategori' => 'berita',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'tags' => 'pengawasan, transparansi, akuntabilitas',
                'meta_description' => 'Program pengawasan terintegrasi Inspektorat Papua Tengah untuk meningkatkan akuntabilitas pemerintahan.',
                'views' => 150,
                'created_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Pengumuman Rekrutmen Auditor Internal Papua Tengah',
                'slug' => 'pengumuman-rekrutmen-auditor-internal-papua-tengah',
                'konten' => 'Inspektorat Provinsi Papua Tengah membuka kesempatan bagi putra-putri terbaik Papua Tengah untuk bergabung sebagai auditor internal. Pendaftaran dibuka mulai tanggal 1 hingga 30 Juli 2025.',
                'penulis' => 'Bagian Kepegawaian',
                'kategori' => 'pengumuman',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'tags' => 'rekrutmen, auditor, lowongan kerja',
                'meta_description' => 'Pengumuman rekrutmen auditor internal Inspektorat Papua Tengah 2025.',
                'views' => 89,
                'created_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Kegiatan Sosialisasi Sistem Pengendalian Intern Pemerintah (SPIP)',
                'slug' => 'kegiatan-sosialisasi-sistem-pengendalian-intern-pemerintah-spip',
                'konten' => 'Inspektorat Papua Tengah menyelenggarakan kegiatan sosialisasi SPIP kepada seluruh OPD di lingkungan Pemerintah Provinsi Papua Tengah. Kegiatan ini bertujuan untuk meningkatkan pemahaman tentang implementasi SPIP.',
                'penulis' => 'Tim SPIP Inspektorat',
                'kategori' => 'kegiatan',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(7),
                'tags' => 'SPIP, sosialisasi, pengendalian intern',
                'meta_description' => 'Sosialisasi SPIP untuk OPD Papua Tengah oleh Inspektorat.',
                'views' => 67,
                'created_at' => now()->subDays(7),
            ],
            [
                'judul' => 'Peraturan Gubernur Papua Tengah Nomor 25 Tahun 2025 tentang Whistleblower System',
                'slug' => 'peraturan-gubernur-papua-tengah-nomor-25-tahun-2025-tentang-whistleblower-system',
                'konten' => 'Gubernur Papua Tengah menerbitkan Peraturan Gubernur tentang Whistleblower System sebagai upaya pencegahan dan pemberantasan korupsi di lingkungan Pemerintah Provinsi Papua Tengah.',
                'penulis' => 'Bagian Hukum',
                'kategori' => 'regulasi',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'tags' => 'peraturan, whistleblower, anti korupsi',
                'meta_description' => 'Peraturan Gubernur Papua Tengah tentang implementasi Whistleblower System.',
                'views' => 234,
                'created_at' => now()->subDays(10),
            ],
            [
                'judul' => 'Layanan Konsultasi Pengawasan Online Papua Tengah',
                'slug' => 'layanan-konsultasi-pengawasan-online-papua-tengah',
                'konten' => 'Inspektorat Papua Tengah menyediakan layanan konsultasi pengawasan secara online untuk memudahkan OPD dalam berkonsultasi terkait pelaksanaan pengawasan dan audit internal.',
                'penulis' => 'Bagian Layanan Pengawasan',
                'kategori' => 'layanan',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(12),
                'tags' => 'layanan online, konsultasi, pengawasan',
                'meta_description' => 'Layanan konsultasi pengawasan online Inspektorat Papua Tengah.',
                'views' => 45,
                'created_at' => now()->subDays(12),
            ],
            [
                'judul' => 'Rapat Koordinasi Pencegahan Korupsi di Papua Tengah',
                'slug' => 'rapat-koordinasi-pencegahan-korupsi-di-papua-tengah',
                'konten' => 'Inspektorat Papua Tengah mengadakan rapat koordinasi dengan berbagai instansi terkait untuk membahas strategi pencegahan korupsi di Papua Tengah. Rapat dihadiri oleh perwakilan dari KPK, Kejaksaan, dan Kepolisian.',
                'penulis' => 'Sekretariat Inspektorat',
                'kategori' => 'kegiatan',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'tags' => 'koordinasi, pencegahan korupsi, kerjasama',
                'meta_description' => 'Rapat koordinasi pencegahan korupsi Papua Tengah dengan instansi terkait.',
                'views' => 78,
                'created_at' => now()->subDays(15),
            ],
        ];

        foreach ($portalData as $data) {
            PortalPapuaTengah::create($data);
        }
    }
}
