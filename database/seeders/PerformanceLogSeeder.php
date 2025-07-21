<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerformanceLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if data already exists
        if (DB::table('performance_logs')->count() > 0) {
            return;
        }

        $performanceLogs = [];
        
        // Generate sample performance logs untuk berbagai endpoint
        $urls = [
            '/admin/dashboard',
            '/admin/portal-papua-tengah',
            '/admin/wbs',
            '/admin/dokumen',
            '/admin/galeri',
            '/admin/pelayanan',
            '/admin/portal-opd',
            '/admin/faq',
            '/api/portal-papua-tengah',
            '/api/wbs',
        ];

        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $statusCodes = [200, 201, 204, 400, 404, 500];

        for ($i = 0; $i < 50; $i++) {
            $performanceLogs[] = [
                'url' => $urls[array_rand($urls)],
                'method' => $methods[array_rand($methods)],
                'execution_time' => rand(50, 2000) / 10, // 5ms - 200ms
                'memory_usage' => rand(10, 50) / 10, // 1MB - 5MB
                'peak_memory' => rand(15, 60) / 10, // 1.5MB - 6MB
                'query_count' => rand(1, 15),
                'http_status' => $statusCodes[array_rand($statusCodes)],
                'content_length' => rand(1000, 50000),
                'user_id' => rand(1, 5),
                'ip_address' => '192.168.1.' . rand(1, 254),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ];
        }

        foreach ($performanceLogs as $log) {
            DB::table('performance_logs')->insert($log);
        }
    }
}
