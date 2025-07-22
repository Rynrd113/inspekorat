<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PortalPapuaTengah;

class PortalPapuaTengahSeederFixed extends Seeder
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
                'konten' => 'Inspektorat Daerah Papua Tengah meluncurkan program pengawasan terintegrasi untuk meningkatkan efektivitas pengawasan internal di seluruh OPD. Program ini mencakup pengawasan kinerja, audit ketaatan, dan evaluasi sistem pengendalian internal.',
                'kategori' => 'pengumuman',
                'author' => 'Tim Humas Inspektorat',
                'tanggal_publikasi' => now()->subDays(1),
                'status' => true,
                'views' => 150,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Sosialisasi Implementasi SPIP di Lingkungan Pemerintah Papua Tengah',
                'konten' => 'Inspektorat Papua Tengah menggelar kegiatan sosialisasi implementasi Sistem Pengendalian Intern Pemerintah (SPIP) yang dihadiri oleh seluruh pimpinan OPD di lingkungan Pemerintah Provinsi Papua Tengah.',
                'kategori' => 'kegiatan',
                'author' => 'Bagian Pembinaan dan Pengembangan',
                'tanggal_publikasi' => now()->subDays(3),
                'status' => true,
                'views' => 95,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Hasil Audit Kinerja Program Pembangunan Infrastruktur 2024',
                'konten' => 'Inspektorat telah menyelesaikan audit kinerja terhadap program pembangunan infrastruktur tahun 2024. Secara umum, pelaksanaan program telah sesuai dengan rencana yang ditetapkan dengan tingkat pencapaian 85%.',
                'kategori' => 'laporan',
                'author' => 'Tim Audit Kinerja',
                'tanggal_publikasi' => now()->subDays(5),
                'status' => true,
                'views' => 210,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Workshop Peningkatan Kapasitas Auditor Internal',
                'konten' => 'Dalam rangka meningkatkan kompetensi auditor internal, Inspektorat Papua Tengah menyelenggarakan workshop peningkatan kapasitas yang diikuti oleh seluruh auditor dan pejabat fungsional auditor.',
                'kategori' => 'pelatihan',
                'author' => 'Bagian Pelatihan dan Pengembangan SDM',
                'tanggal_publikasi' => now()->subDays(7),
                'status' => true,
                'views' => 75,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Evaluasi Sistem Pengendalian Internal Semester I Tahun 2024',
                'konten' => 'Inspektorat telah menyelesaikan evaluasi sistem pengendalian internal untuk semester pertama tahun 2024. Hasil evaluasi menunjukkan adanya peningkatan signifikan dalam implementasi SPIP di berbagai OPD.',
                'kategori' => 'evaluasi',
                'author' => 'Tim Evaluasi SPIP',
                'tanggal_publikasi' => now()->subDays(10),
                'status' => true,
                'views' => 125,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Rapat Koordinasi Pengawasan Internal Triwulan IV',
                'konten' => 'Inspektorat Papua Tengah mengadakan rapat koordinasi pengawasan internal triwulan IV yang membahas strategi pengawasan untuk tahun mendatang serta evaluasi pencapaian target pengawasan tahun berjalan.',
                'kategori' => 'rapat',
                'author' => 'Sekretariat Inspektorat',
                'tanggal_publikasi' => now()->subDays(12),
                'status' => true,
                'views' => 80,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Use DB::table()->insert() to bypass model events that may cause issues
        DB::table('portal_papua_tengahs')->insert($portalData);
    }
}
