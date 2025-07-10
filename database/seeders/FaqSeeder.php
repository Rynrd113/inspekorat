<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'pertanyaan' => 'Apa tugas dan fungsi Inspektorat Provinsi Papua Tengah?',
                'jawaban' => 'Inspektorat Provinsi Papua Tengah bertugas melaksanakan pengawasan internal di lingkungan Pemerintah Provinsi Papua Tengah yang meliputi audit, reviu, evaluasi, pemantauan, dan kegiatan pengawasan lainnya sesuai dengan peraturan perundang-undangan.',
                'kategori' => 'Umum',
                'urutan' => 1,
                'is_popular' => true,
                'view_count' => 150,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana cara melaporkan dugaan pelanggaran melalui WBS?',
                'jawaban' => 'Anda dapat melaporkan dugaan pelanggaran melalui Whistleblowing System (WBS) dengan mengakses menu WBS di website ini, mengisi formulir laporan dengan lengkap, dan mengirimkan laporan. Identitas pelapor akan dijaga kerahasiaannya.',
                'kategori' => 'WBS',
                'urutan' => 2,
                'is_popular' => true,
                'view_count' => 98,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana cara mengajukan permohonan informasi publik?',
                'jawaban' => 'Permohonan informasi publik dapat diajukan dengan mengisi formulir yang tersedia di bagian informasi dan dokumentasi atau mendownload formulir di website ini. Penyampaian dapat dilakukan secara langsung, melalui pos, faksimile, atau email.',
                'kategori' => 'Informasi Publik',
                'urutan' => 3,
                'is_popular' => false,
                'view_count' => 67,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Berapa lama waktu yang diperlukan untuk menyelesaikan audit?',
                'jawaban' => 'Waktu penyelesaian audit bervariasi tergantung jenis dan ruang lingkup audit. Umumnya audit kinerja memerlukan waktu 30 hari kerja, sedangkan audit khusus dapat disesuaikan dengan kebutuhan.',
                'kategori' => 'Audit',
                'urutan' => 4,
                'is_popular' => false,
                'view_count' => 45,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Apakah layanan konsultasi pengawasan berbayar?',
                'jawaban' => 'Tidak, layanan konsultasi pengawasan yang diberikan oleh Inspektorat Provinsi Papua Tengah kepada OPD di lingkungan Pemerintah Provinsi Papua Tengah tidak dipungut biaya alias gratis.',
                'kategori' => 'Layanan',
                'urutan' => 5,
                'is_popular' => true,
                'view_count' => 123,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Apa saja dokumen yang diperlukan untuk reviu laporan keuangan?',
                'jawaban' => 'Dokumen yang diperlukan antara lain: draft laporan keuangan, dokumen pendukung transaksi, rekonsiliasi bank, berita acara stock opname, dan dokumen pendukung lainnya sesuai dengan kebutuhan reviu.',
                'kategori' => 'Reviu',
                'urutan' => 6,
                'is_popular' => false,
                'view_count' => 34,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana sistem pengendalian internal yang baik?',
                'jawaban' => 'Sistem pengendalian internal yang baik harus memenuhi unsur-unsur SPIP (Sistem Pengendalian Intern Pemerintah) yaitu: lingkungan pengendalian, penilaian risiko, kegiatan pengendalian, informasi dan komunikasi, serta pemantauan.',
                'kategori' => 'SPIP',
                'urutan' => 7,
                'is_popular' => false,
                'view_count' => 78,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Kapan jadwal audit rutin dilaksanakan?',
                'jawaban' => 'Jadwal audit rutin disusun dalam Program Kerja Pengawasan Tahunan (PKPT) yang ditetapkan setiap awal tahun. Pelaksanaannya disesuaikan dengan prioritas dan ketersediaan sumber daya.',
                'kategori' => 'Audit',
                'urutan' => 8,
                'is_popular' => false,
                'view_count' => 56,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Bagaimana cara mengunduh dokumen dari website?',
                'jawaban' => 'Untuk mengunduh dokumen, kunjungi halaman Dokumen, pilih dokumen yang diinginkan, kemudian klik tombol download. Pastikan browser Anda mendukung download file PDF atau format lainnya.',
                'kategori' => 'Website',
                'urutan' => 9,
                'is_popular' => false,
                'view_count' => 89,
                'status' => true,
            ],
            [
                'pertanyaan' => 'Siapa yang dapat mengakses layanan Inspektorat?',
                'jawaban' => 'Layanan Inspektorat dapat diakses oleh seluruh OPD di lingkungan Pemerintah Provinsi Papua Tengah, ASN, dan masyarakat umum sesuai dengan jenis layanan yang tersedia.',
                'kategori' => 'Layanan',
                'urutan' => 10,
                'is_popular' => true,
                'view_count' => 112,
                'status' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['pertanyaan' => $faq['pertanyaan']],
                $faq
            );
        }
    }
}
