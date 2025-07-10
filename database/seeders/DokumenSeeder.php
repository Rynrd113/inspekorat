<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use Illuminate\Database\Seeder;

class DokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokumens = [
            [
                'judul' => 'Peraturan Gubernur Papua Tengah Nomor 45 Tahun 2024',
                'deskripsi' => 'Peraturan tentang Organisasi dan Tata Kerja Inspektorat Provinsi Papua Tengah',
                'kategori' => 'Peraturan',
                'file_path' => 'dokumen/pergub-45-2024.pdf',
                'file_name' => 'pergub-45-2024.pdf',
                'file_size' => '2.5 MB',
                'file_type' => 'pdf',
                'tanggal_publikasi' => '2024-12-15',
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Standar Operating Procedure (SOP) Audit Internal',
                'deskripsi' => 'SOP pelaksanaan audit internal di lingkungan Pemerintah Provinsi Papua Tengah',
                'kategori' => 'SOP',
                'file_path' => 'dokumen/sop-audit-internal.pdf',
                'file_name' => 'sop-audit-internal.pdf',
                'file_size' => '1.8 MB',
                'file_type' => 'pdf',
                'tanggal_publikasi' => '2024-11-20',
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Laporan Hasil Pengawasan Semester I Tahun 2024',
                'deskripsi' => 'Laporan hasil kegiatan pengawasan yang telah dilaksanakan pada semester pertama tahun 2024',
                'kategori' => 'Laporan',
                'file_path' => 'dokumen/lhp-semester-1-2024.pdf',
                'file_name' => 'lhp-semester-1-2024.pdf',
                'file_size' => '3.2 MB',
                'file_type' => 'pdf',
                'tanggal_publikasi' => '2024-07-30',
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Panduan Whistleblowing System (WBS)',
                'deskripsi' => 'Panduan lengkap penggunaan sistem pelaporan pelanggaran melalui WBS',
                'kategori' => 'Panduan',
                'file_path' => 'dokumen/panduan-wbs.pdf',
                'file_name' => 'panduan-wbs.pdf',
                'file_size' => '1.5 MB',
                'file_type' => 'pdf',
                'tanggal_publikasi' => '2024-06-10',
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Formulir Permohonan Informasi Publik',
                'deskripsi' => 'Formulir standar untuk permohonan informasi publik sesuai UU KIP',
                'kategori' => 'Formulir',
                'file_path' => 'dokumen/formulir-informasi-publik.docx',
                'file_name' => 'formulir-informasi-publik.docx',
                'file_size' => '0.8 MB',
                'file_type' => 'docx',
                'tanggal_publikasi' => '2024-05-15',
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Rencana Strategis Inspektorat 2024-2029',
                'deskripsi' => 'Dokumen perencanaan strategis Inspektorat Provinsi Papua Tengah periode 2024-2029',
                'kategori' => 'Perencanaan',
                'file_path' => 'dokumen/renstra-2024-2029.pdf',
                'file_name' => 'renstra-2024-2029.pdf',
                'file_size' => '4.1 MB',
                'file_type' => 'pdf',
                'tanggal_publikasi' => '2024-04-01',
                'created_by' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Pedoman Evaluasi Kinerja OPD',
                'deskripsi' => 'Pedoman pelaksanaan evaluasi kinerja Organisasi Perangkat Daerah',
                'kategori' => 'Pedoman',
                'file_path' => 'dokumen/pedoman-evaluasi-kinerja.pdf',
                'file_name' => 'pedoman-evaluasi-kinerja.pdf',
                'file_size' => '2.7 MB',
                'file_type' => 'pdf',
                'tanggal_publikasi' => '2024-03-12',
                'created_by' => 1,
                'status' => true,
            ],
        ];

        foreach ($dokumens as $dokumen) {
            Dokumen::firstOrCreate(
                ['judul' => $dokumen['judul']],
                $dokumen
            );
        }
    }
}
