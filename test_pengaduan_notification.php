<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pengaduan;
use App\Models\User;

echo "=== Test Pengaduan Notification System ===\n\n";

// 1. Check if admins exist
$admins = User::whereIn('role', ['admin', 'super_admin'])
    ->where('email', '!=', 'anonim@system.local')
    ->get();

echo "[INFO] Found " . $admins->count() . " admin(s):\n";
foreach ($admins as $admin) {
    echo "  - {$admin->name} ({$admin->email})\n";
}
echo "\n";

// 2. Create test pengaduan
echo "[INFO] Creating test pengaduan...\n";

$testData = [
    'nama_pengadu' => 'Test User - ' . date('Y-m-d H:i:s'),
    'email' => 'test@example.com',
    'telepon' => '081234567890',
    'subjek' => 'Test Pengaduan - ' . date('H:i:s'),
    'isi_pengaduan' => 'Ini adalah pengaduan test untuk memastikan sistem notifikasi bekerja dengan baik.',
    'kategori' => 'pelayanan',
    'status' => 'pending',
    'tanggal_pengaduan' => now(),
    'is_anonymous' => false
];

try {
    $pengaduan = Pengaduan::create($testData);
    echo "[SUCCESS] Pengaduan created with ID: {$pengaduan->id}\n";
    echo "[INFO] Pengaduan details:\n";
    echo "  - Subjek: {$pengaduan->subjek}\n";
    echo "  - Kategori: {$pengaduan->kategori}\n";
    echo "  - Status: {$pengaduan->status}\n";
    echo "\n";

    // 3. Check logs
    echo "[INFO] Checking logs...\n";
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $lines = array_slice(file($logFile), -20);
        echo "[LOG] Last 20 lines from laravel.log:\n";
        foreach ($lines as $line) {
            echo "  " . trim($line) . "\n";
        }
    } else {
        echo "[WARN] Log file not found\n";
    }

    echo "\n[SUCCESS] Test completed successfully!\n";
    echo "[INFO] Check the admin dashboard or logs to verify notifications were sent.\n";

} catch (\Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

