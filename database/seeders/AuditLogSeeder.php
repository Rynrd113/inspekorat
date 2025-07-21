<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if data already exists
        if (DB::table('audit_logs')->count() > 0) {
            return;
        }

        $auditLogs = [
            [
                'user_type' => 'App\\Models\\User',
                'user_id' => 1,
                'event' => 'created',
                'auditable_type' => 'App\\Models\\PortalPapuaTengah',
                'auditable_id' => 1,
                'old_values' => null,
                'new_values' => json_encode([
                    'judul' => 'Inspektorat Papua Tengah Luncurkan Program Pengawasan Terintegrasi',
                    'kategori' => 'berita',
                    'status' => true
                ]),
                'url' => '/admin/portal-papua-tengah',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'tags' => 'portal_papua_tengah,create',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'user_type' => 'App\\Models\\User',
                'user_id' => 2,
                'event' => 'updated',
                'auditable_type' => 'App\\Models\\Wbs',
                'auditable_id' => 1,
                'old_values' => json_encode(['status' => 'pending']),
                'new_values' => json_encode(['status' => 'proses']),
                'url' => '/admin/wbs/1',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'tags' => 'wbs,status_update',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'user_type' => 'App\\Models\\User',
                'user_id' => 1,
                'event' => 'created',
                'auditable_type' => 'App\\Models\\Dokumen',
                'auditable_id' => 1,
                'old_values' => null,
                'new_values' => json_encode([
                    'judul' => 'Peraturan Gubernur Papua Tengah Nomor 45 Tahun 2024',
                    'kategori' => 'Peraturan',
                    'status' => true
                ]),
                'url' => '/admin/dokumen',
                'ip_address' => '10.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
                'tags' => 'dokumen,upload',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'user_type' => 'App\\Models\\User',
                'user_id' => 3,
                'event' => 'deleted',
                'auditable_type' => 'App\\Models\\Galeri',
                'auditable_id' => 5,
                'old_values' => json_encode([
                    'judul' => 'Foto Kegiatan Lama',
                    'kategori' => 'Kegiatan',
                    'status' => true
                ]),
                'new_values' => null,
                'url' => '/admin/galeri/5',
                'ip_address' => '172.16.0.1',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X)',
                'tags' => 'galeri,delete',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_type' => 'App\\Models\\User',
                'user_id' => 1,
                'event' => 'login',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => 1,
                'old_values' => null,
                'new_values' => json_encode(['login_time' => now()]),
                'url' => '/admin/login',
                'ip_address' => '203.142.4.27',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:91.0)',
                'tags' => 'authentication,login',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
        ];

        foreach ($auditLogs as $log) {
            DB::table('audit_logs')->insert($log);
        }
    }
}
