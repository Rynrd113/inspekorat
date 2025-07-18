<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use App\Models\Wbs;
use App\Models\PortalOpd;
use App\Models\Pelayanan;
use App\Models\Galeri;
use App\Models\Dokumen;
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;

class DuskTestSeeder extends Seeder
{
    /**
     * Run the database seeds for Dusk testing.
     */
    public function run(): void
    {
        // Create test users with different roles
        $superAdmin = User::create([
            'name' => 'Super Admin Test',
            'email' => 'superadmin@test.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $contentManager = User::create([
            'name' => 'Content Manager Test',
            'email' => 'content@test.com',
            'password' => Hash::make('password'),
            'role' => 'content_manager',
            'email_verified_at' => now(),
        ]);

        $wbsManager = User::create([
            'name' => 'WBS Manager Test',
            'email' => 'wbs@test.com',
            'password' => Hash::make('password'),
            'role' => 'wbs_manager',
            'email_verified_at' => now(),
        ]);

        $opdManager = User::create([
            'name' => 'OPD Manager Test',
            'email' => 'opd@test.com',
            'password' => Hash::make('password'),
            'role' => 'opd_manager',
            'email_verified_at' => now(),
        ]);

        // Create test news articles
        if ($this->schema()->hasTable('portal_papua_tengahs')) {
            PortalPapuaTengah::create([
                'judul' => 'Berita Test Published',
                'isi' => 'Konten berita yang sudah dipublish untuk testing Dusk.',
                'status' => 'published',
                'is_featured' => true,
                'created_by' => $contentManager->id,
                'created_at' => now()->subDays(1),
            ]);

            PortalPapuaTengah::create([
                'judul' => 'Berita Test Draft',
                'isi' => 'Konten berita yang masih draft untuk testing.',
                'status' => 'draft',
                'is_featured' => false,
                'created_by' => $contentManager->id,
                'created_at' => now(),
            ]);
        }

        // Create test WBS reports
        if ($this->schema()->hasTable('wbs')) {
            Wbs::create([
                'judul_laporan' => 'Test WBS Report Anonymous',
                'isi_laporan' => 'Laporan test untuk WBS anonymous.',
                'lokasi_kejadian' => 'Kantor Test',
                'waktu_kejadian' => now()->subDays(2),
                'is_anonymous' => true,
                'status' => 'pending',
                'nomor_tiket' => 'WBS-TEST-001',
                'created_at' => now()->subDays(2),
            ]);

            Wbs::create([
                'judul_laporan' => 'Test WBS Report Named',
                'isi_laporan' => 'Laporan test untuk WBS dengan identitas.',
                'lokasi_kejadian' => 'Kantor Test 2',
                'waktu_kejadian' => now()->subDays(1),
                'is_anonymous' => false,
                'nama_pelapor' => 'John Doe Test',
                'email_pelapor' => 'john@test.com',
                'telepon_pelapor' => '08123456789',
                'status' => 'proses',
                'nomor_tiket' => 'WBS-TEST-002',
                'tanggapan_admin' => 'Laporan sedang dalam proses investigasi.',
                'created_at' => now()->subDays(1),
            ]);
        }

        // Create test services
        if ($this->schema()->hasTable('pelayanans')) {
            Pelayanan::create([
                'nama_layanan' => 'Layanan Test Aktif',
                'deskripsi' => 'Deskripsi layanan test yang aktif.',
                'syarat' => 'Syarat dan ketentuan layanan test.',
                'waktu_pelayanan' => '3 hari kerja',
                'biaya' => 'Gratis',
                'status' => 'active',
                'created_by' => $admin->id,
            ]);
        }

        // Create test OPD portals
        if ($this->schema()->hasTable('portal_opds')) {
            PortalOpd::create([
                'nama_opd' => 'Dinas Test Papua Tengah',
                'deskripsi' => 'Deskripsi dinas test untuk testing.',
                'alamat' => 'Jl. Test No. 123',
                'telepon' => '021-12345678',
                'email' => 'dinas@test.com',
                'website' => 'https://test.com',
                'kepala_opd' => 'Kepala Dinas Test',
                'status' => 'active',
                'created_by' => $opdManager->id,
            ]);
        }

        // Create test galleries
        if ($this->schema()->hasTable('galeris')) {
            Galeri::create([
                'judul' => 'Galeri Test Published',
                'deskripsi' => 'Galeri test yang sudah dipublish.',
                'gambar' => json_encode(['test1.jpg', 'test2.jpg']),
                'status' => 'published',
                'created_by' => $contentManager->id,
            ]);
        }

        // Create test documents
        if ($this->schema()->hasTable('dokumens')) {
            Dokumen::create([
                'judul' => 'Dokumen Test PDF',
                'deskripsi' => 'Dokumen test dalam format PDF.',
                'kategori' => 'peraturan',
                'file_path' => 'documents/test.pdf',
                'file_name' => 'test.pdf',
                'file_size' => 1024000,
                'mime_type' => 'application/pdf',
                'status' => 'published',
                'created_by' => $contentManager->id,
            ]);
        }

        // Create test FAQs
        if ($this->schema()->hasTable('faqs')) {
            Faq::create([
                'pertanyaan' => 'Bagaimana cara test FAQ?',
                'jawaban' => 'Ini adalah jawaban test untuk FAQ testing.',
                'kategori' => 'umum',
                'status' => 'published',
                'urutan' => 1,
                'is_featured' => true,
                'created_by' => $contentManager->id,
            ]);
        }
    }

    /**
     * Helper function to check if table exists.
     */
    private function schema()
    {
        return app('db')->getSchemaBuilder();
    }
}