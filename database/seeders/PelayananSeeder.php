<?php

namespace Database\Seeders;

use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class PelayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelayanans = [
            [
                'nama' => 'Konsultasi Pengawasan Internal',
                'deskripsi' => 'Layanan konsultasi terkait pengawasan internal untuk OPD di lingkungan Pemerintah Provinsi Papua Tengah',
                'prosedur' => '1. Mengajukan permohonan konsultasi\n2. Melampirkan dokumen pendukung\n3. Menunggu jadwal konsultasi\n4. Pelaksanaan konsultasi\n5. Laporan hasil konsultasi',
                'persyaratan' => '1. Surat permohonan resmi\n2. Identitas pemohon\n3. Dokumen terkait masalah yang dikonsultasikan',
                'waktu_penyelesaian' => '7 hari kerja',
                'biaya' => 'Gratis',
                'kategori' => 'Konsultasi',
                'kontak_pic' => 'Budi Santoso',
                'email_pic' => 'konsultasi@inspektorat.papuatengah.go.id',
                'telepon_pic' => '0901-123456',
                'status' => true,
            ],
            [
                'nama' => 'Layanan Informasi Publik',
                'deskripsi' => 'Pelayanan pemberian informasi publik sesuai dengan UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik',
                'prosedur' => '1. Mengisi formulir permohonan informasi\n2. Menyerahkan formulir ke bagian informasi\n3. Verifikasi permohonan\n4. Proses penyediaan informasi\n5. Penyerahan informasi',
                'persyaratan' => '1. Formulir permohonan informasi\n2. Fotokopi KTP/identitas\n3. Dokumen pendukung (jika diperlukan)',
                'waktu_penyelesaian' => '10 hari kerja',
                'biaya' => 'Sesuai tarif fotokopi/cetak',
                'kategori' => 'Informasi',
                'kontak_pic' => 'Siti Aminah',
                'email_pic' => 'informasi@inspektorat.papuatengah.go.id',
                'telepon_pic' => '0901-123457',
                'status' => true,
            ],
            [
                'nama' => 'Audit Kinerja',
                'deskripsi' => 'Layanan audit kinerja untuk menilai efektivitas, efisiensi, dan ekonomis program/kegiatan OPD',
                'prosedur' => '1. Perencanaan audit\n2. Pembentukan tim audit\n3. Pelaksanaan audit\n4. Pelaporan hasil audit\n5. Tindak lanjut rekomendasi',
                'persyaratan' => '1. Surat penugasan audit\n2. Dokumen program/kegiatan\n3. Laporan keuangan\n4. Data pendukung lainnya',
                'waktu_penyelesaian' => '30 hari kerja',
                'biaya' => 'Gratis',
                'kategori' => 'Audit',
                'kontak_pic' => 'Ahmad Fauzi',
                'email_pic' => 'audit@inspektorat.papuatengah.go.id',
                'telepon_pic' => '0901-123458',
                'status' => true,
            ],
            [
                'nama' => 'Reviu Laporan Keuangan',
                'deskripsi' => 'Layanan reviu atas laporan keuangan OPD untuk memastikan kesesuaian dengan standar akuntansi pemerintahan',
                'prosedur' => '1. Penyerahan draft laporan keuangan\n2. Penelaahan awal\n3. Pelaksanaan reviu\n4. Pembahasan hasil reviu\n5. Finalisasi laporan',
                'persyaratan' => '1. Draft laporan keuangan\n2. Dokumen pendukung transaksi\n3. Rekonsiliasi bank\n4. Berita acara stock opname',
                'waktu_penyelesaian' => '14 hari kerja',
                'biaya' => 'Gratis',
                'kategori' => 'Reviu',
                'kontak_pic' => 'Dewi Sartika',
                'email_pic' => 'reviu@inspektorat.papuatengah.go.id',
                'telepon_pic' => '0901-123459',
                'status' => true,
            ],
            [
                'nama' => 'Evaluasi Sistem Pengendalian Internal',
                'deskripsi' => 'Layanan evaluasi untuk menilai efektivitas sistem pengendalian internal pada OPD',
                'prosedur' => '1. Penjadwalan evaluasi\n2. Pengumpulan data\n3. Analisis sistem pengendalian\n4. Identifikasi kelemahan\n5. Rekomendasi perbaikan',
                'persyaratan' => '1. Dokumen kebijakan internal\n2. Standar Operating Procedure (SOP)\n3. Struktur organisasi\n4. Job description',
                'waktu_penyelesaian' => '21 hari kerja',
                'biaya' => 'Gratis',
                'kategori' => 'Evaluasi',
                'kontak_pic' => 'Rahman Hidayat',
                'email_pic' => 'evaluasi@inspektorat.papuatengah.go.id',
                'telepon_pic' => '0901-123460',
                'status' => true,
            ],
        ];

        foreach ($pelayanans as $pelayanan) {
            Pelayanan::firstOrCreate(
                ['nama' => $pelayanan['nama']],
                $pelayanan
            );
        }
    }
}
