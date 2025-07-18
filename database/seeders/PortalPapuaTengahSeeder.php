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
                'slug' => 'inspektorat-papua-tengah-luncurkan-program-pengawasan-terintegrasi',
                'konten' => 'Inspektorat Provinsi Papua Tengah meluncurkan program pengawasan terintegrasi untuk meningkatkan efektivitas pengawasan internal pemerintah daerah. Program ini bertujuan untuk memastikan akuntabilitas dan transparansi dalam pengelolaan keuangan dan pelayanan publik.',
                'isi' => 'Inspektorat Provinsi Papua Tengah meluncurkan program pengawasan terintegrasi untuk meningkatkan efektivitas pengawasan internal pemerintah daerah. Program ini bertujuan untuk memastikan akuntabilitas dan transparansi dalam pengelolaan keuangan dan pelayanan publik. Program ini akan mencakup berbagai aspek pengawasan yang terintegrasi dengan sistem informasi untuk memudahkan monitoring dan evaluasi.',
                'penulis' => 'Humas Inspektorat Papua Tengah',
                'kategori' => 'berita',
                'thumbnail' => 'berita/pengawasan-terintegrasi.jpg',
                'gambar' => 'berita/pengawasan-terintegrasi-full.jpg',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'tags' => 'pengawasan, transparansi, akuntabilitas',
                'meta_description' => 'Program pengawasan terintegrasi Inspektorat Papua Tengah untuk meningkatkan akuntabilitas pemerintahan.',
                'status' => 'published',
                'views' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Pengumuman Rekrutmen Auditor Internal Papua Tengah',
                'slug' => 'pengumuman-rekrutmen-auditor-internal-papua-tengah',
                'konten' => 'Inspektorat Provinsi Papua Tengah membuka kesempatan bagi putra-putri terbaik Papua Tengah untuk bergabung sebagai auditor internal. Pendaftaran dibuka mulai tanggal 1 hingga 30 Juli 2025.',
                'isi' => 'Inspektorat Provinsi Papua Tengah membuka kesempatan bagi putra-putri terbaik Papua Tengah untuk bergabung sebagai auditor internal. Pendaftaran dibuka mulai tanggal 1 hingga 30 Juli 2025. Persyaratan lengkap dan tata cara pendaftaran dapat dilihat di website resmi inspektorat.',
                'penulis' => 'Bagian Kepegawaian',
                'kategori' => 'pengumuman',
                'thumbnail' => 'berita/rekrutmen-auditor.jpg',
                'gambar' => 'berita/rekrutmen-auditor-full.jpg',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'tags' => 'rekrutmen, auditor, lowongan kerja',
                'meta_description' => 'Pengumuman rekrutmen auditor internal Inspektorat Papua Tengah 2025.',
                'status' => 'published',
                'views' => 89,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Kegiatan Sosialisasi Sistem Pengendalian Intern Pemerintah (SPIP)',
                'slug' => 'kegiatan-sosialisasi-sistem-pengendalian-intern-pemerintah-spip',
                'konten' => 'Inspektorat Papua Tengah menyelenggarakan kegiatan sosialisasi SPIP kepada seluruh OPD di lingkungan Pemerintah Provinsi Papua Tengah. Kegiatan ini bertujuan untuk meningkatkan pemahaman tentang implementasi SPIP.',
                'isi' => 'Inspektorat Papua Tengah menyelenggarakan kegiatan sosialisasi SPIP kepada seluruh OPD di lingkungan Pemerintah Provinsi Papua Tengah. Kegiatan ini bertujuan untuk meningkatkan pemahaman tentang implementasi SPIP. Sosialisasi dilakukan secara bertahap untuk memastikan seluruh OPD memahami dan menerapkan SPIP dengan baik.',
                'penulis' => 'Tim SPIP Inspektorat',
                'kategori' => 'kegiatan',
                'thumbnail' => 'berita/sosialisasi-spip.jpg',
                'gambar' => 'berita/sosialisasi-spip-full.jpg',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(7),
                'tags' => 'SPIP, sosialisasi, pengendalian intern',
                'meta_description' => 'Sosialisasi SPIP untuk OPD Papua Tengah oleh Inspektorat.',
                'status' => 'published',
                'views' => 67,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Peraturan Gubernur Papua Tengah Nomor 25 Tahun 2025 tentang Whistleblower System',
                'slug' => 'peraturan-gubernur-papua-tengah-nomor-25-tahun-2025-tentang-whistleblower-system',
                'konten' => 'Gubernur Papua Tengah menerbitkan Peraturan Gubernur tentang Whistleblower System sebagai upaya pencegahan dan pemberantasan korupsi di lingkungan Pemerintah Provinsi Papua Tengah.',
                'isi' => 'Gubernur Papua Tengah menerbitkan Peraturan Gubernur tentang Whistleblower System sebagai upaya pencegahan dan pemberantasan korupsi di lingkungan Pemerintah Provinsi Papua Tengah. Peraturan ini memberikan landasan hukum yang kuat untuk implementasi sistem pelaporan pelanggaran yang efektif dan terpercaya.',
                'penulis' => 'Bagian Hukum',
                'kategori' => 'regulasi',
                'thumbnail' => 'berita/pergub-wbs.jpg',
                'gambar' => 'berita/pergub-wbs-full.jpg',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'tags' => 'peraturan, whistleblower, anti korupsi',
                'meta_description' => 'Peraturan Gubernur Papua Tengah tentang implementasi Whistleblower System.',
                'status' => 'published',
                'views' => 234,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Layanan Konsultasi Pengawasan Online Papua Tengah',
                'slug' => 'layanan-konsultasi-pengawasan-online-papua-tengah',
                'konten' => 'Inspektorat Papua Tengah menyediakan layanan konsultasi pengawasan secara online untuk memudahkan OPD dalam berkonsultasi terkait pelaksanaan pengawasan dan audit internal.',
                'isi' => 'Inspektorat Papua Tengah menyediakan layanan konsultasi pengawasan secara online untuk memudahkan OPD dalam berkonsultasi terkait pelaksanaan pengawasan dan audit internal. Layanan ini tersedia 24/7 melalui platform digital yang user-friendly.',
                'penulis' => 'Bagian Layanan Pengawasan',
                'kategori' => 'layanan',
                'thumbnail' => 'berita/konsultasi-online.jpg',
                'gambar' => 'berita/konsultasi-online-full.jpg',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(12),
                'tags' => 'layanan online, konsultasi, pengawasan',
                'meta_description' => 'Layanan konsultasi pengawasan online Inspektorat Papua Tengah.',
                'status' => 'published',
                'views' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Rapat Koordinasi Pencegahan Korupsi di Papua Tengah',
                'slug' => 'rapat-koordinasi-pencegahan-korupsi-di-papua-tengah',
                'konten' => 'Inspektorat Papua Tengah mengadakan rapat koordinasi dengan berbagai instansi terkait untuk membahas strategi pencegahan korupsi di Papua Tengah. Rapat dihadiri oleh perwakilan dari KPK, Kejaksaan, dan Kepolisian.',
                'isi' => 'Inspektorat Papua Tengah mengadakan rapat koordinasi dengan berbagai instansi terkait untuk membahas strategi pencegahan korupsi di Papua Tengah. Rapat dihadiri oleh perwakilan dari KPK, Kejaksaan, dan Kepolisian. Hasil koordinasi akan menjadi acuan dalam penyusunan program pencegahan korupsi yang komprehensif.',
                'penulis' => 'Sekretariat Inspektorat',
                'kategori' => 'kegiatan',
                'thumbnail' => 'berita/rapat-koordinasi.jpg',
                'gambar' => 'berita/rapat-koordinasi-full.jpg',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'tags' => 'koordinasi, pencegahan korupsi, kerjasama',
                'meta_description' => 'Rapat koordinasi pencegahan korupsi Papua Tengah dengan instansi terkait.',
                'status' => 'published',
                'views' => 78,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Use DB::table()->insert() to bypass model events that may cause issues
        DB::table('portal_papua_tengahs')->insert($portalData);
    }
}
