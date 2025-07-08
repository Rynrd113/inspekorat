<?php

namespace Database\Seeders;

use App\Models\Wbs;
use Illuminate\Database\Seeder;

class WbsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wbsData = [
            [
                'nama_pelapor' => 'Andi Pratama',
                'email' => 'andi.pratama@email.com',
                'no_telepon' => '081234567890',
                'subjek' => 'Dugaan Penyalahgunaan Wewenang',
                'deskripsi' => 'Melaporkan adanya dugaan penyalahgunaan wewenang dalam proses tender proyek infrastruktur di Kabupaten Papua Tengah.',
                'tanggal_kejadian' => '2025-06-15',
                'lokasi_kejadian' => 'Kantor Dinas Pekerjaan Umum Papua Tengah',
                'pihak_terlibat' => 'Kepala Dinas dan Staff Pengadaan',
                'kronologi' => 'Pada tanggal 15 Juni 2025, terdapat indikasi manipulasi proses tender dimana kriteria teknis diubah secara mendadak untuk menguntungkan pihak tertentu.',
                'is_anonymous' => false,
                'status' => 'pending',
                'created_at' => now()->subDays(5),
            ],
            [
                'nama_pelapor' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'no_telepon' => '081234567891',
                'subjek' => 'Praktik Korupsi Dalam Pengadaan Barang',
                'deskripsi' => 'Terdapat indikasi praktik korupsi dalam pengadaan barang dan jasa di instansi pemerintah daerah Papua Tengah.',
                'tanggal_kejadian' => '2025-06-01',
                'lokasi_kejadian' => 'Kantor Badan Pengadaan Barang dan Jasa Papua Tengah',
                'pihak_terlibat' => 'Kepala Badan dan Vendor Terpilih',
                'kronologi' => 'Proses pengadaan dilakukan dengan tidak transparan dan terdapat mark-up harga yang signifikan.',
                'is_anonymous' => false,
                'status' => 'in_progress',
                'response' => 'Laporan sedang dalam proses verifikasi dan penyelidikan.',
                'responded_at' => now()->subDays(8),
                'created_at' => now()->subDays(10),
            ],
            [
                'nama_pelapor' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'no_telepon' => '081234567892',
                'subjek' => 'Pelanggaran Etika Pegawai',
                'deskripsi' => 'Melaporkan pelanggaran etika yang dilakukan oleh pegawai dalam pelayanan publik di Papua Tengah.',
                'tanggal_kejadian' => '2025-05-20',
                'lokasi_kejadian' => 'Kantor Pelayanan Terpadu Satu Pintu Papua Tengah',
                'pihak_terlibat' => 'Pegawai Loket Pelayanan',
                'kronologi' => 'Pegawai melakukan diskriminasi pelayanan dan meminta imbalan untuk mempercepat proses perizinan.',
                'is_anonymous' => false,
                'status' => 'resolved',
                'response' => 'Laporan telah ditindaklanjuti dan pegawai yang bersangkutan telah diberikan sanksi sesuai peraturan.',
                'responded_at' => now()->subDays(12),
                'created_at' => now()->subDays(15),
            ],
            [
                'nama_pelapor' => 'Anonymous',
                'email' => 'anonymous@wbs.com',
                'no_telepon' => null,
                'subjek' => 'Dugaan Gratifikasi',
                'deskripsi' => 'Melaporkan dugaan gratifikasi yang diterima oleh pejabat dalam proses perizinan di Papua Tengah.',
                'tanggal_kejadian' => '2025-06-28',
                'lokasi_kejadian' => 'Kantor Bupati Papua Tengah',
                'pihak_terlibat' => 'Pejabat Struktural',
                'kronologi' => 'Terdapat pemberian hadiah kepada pejabat untuk memperlancar proses perizinan perusahaan.',
                'is_anonymous' => true,
                'status' => 'pending',
                'created_at' => now()->subDays(3),
            ],
            [
                'nama_pelapor' => 'Maria Gonzales',
                'email' => 'maria.gonzales@email.com',
                'no_telepon' => '081234567893',
                'subjek' => 'Penyimpangan Anggaran Daerah',
                'deskripsi' => 'Terdapat indikasi penyimpangan dalam penggunaan anggaran daerah Papua Tengah untuk program pembangunan.',
                'tanggal_kejadian' => '2025-06-10',
                'lokasi_kejadian' => 'Kantor Dinas Pekerjaan Umum Papua Tengah',
                'pihak_terlibat' => 'Kepala Dinas dan Kontraktor',
                'kronologi' => 'Anggaran dialokasikan untuk proyek fiktif dan tidak sesuai dengan rencana pembangunan yang telah ditetapkan.',
                'is_anonymous' => false,
                'status' => 'in_progress',
                'response' => 'Tim investigasi sedang melakukan pemeriksaan terhadap laporan ini.',
                'responded_at' => now()->subDays(5),
                'created_at' => now()->subDays(7),
            ],
            [
                'nama_pelapor' => 'Rudi Hermawan',
                'email' => 'rudi.hermawan@email.com',
                'no_telepon' => '081234567894',
                'subjek' => 'Pungutan Liar',
                'deskripsi' => 'Melaporkan adanya pungutan liar dalam proses pengurusan dokumen di kantor pemerintahan Papua Tengah.',
                'tanggal_kejadian' => '2025-05-15',
                'lokasi_kejadian' => 'Kantor Camat Papua Tengah',
                'pihak_terlibat' => 'Petugas Administrasi',
                'kronologi' => 'Petugas meminta biaya tambahan diluar tarif resmi untuk pengurusan surat keterangan.',
                'is_anonymous' => false,
                'status' => 'rejected',
                'response' => 'Laporan tidak dapat diverifikasi karena kurangnya bukti pendukung.',
                'responded_at' => now()->subDays(15),
                'created_at' => now()->subDays(20),
            ],
        ];

        foreach ($wbsData as $data) {
            Wbs::updateOrCreate(
                ['email' => $data['email'], 'subjek' => $data['subjek']], 
                $data
            );
        }
    }
}
