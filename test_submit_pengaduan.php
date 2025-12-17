<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pengaduan;

// Simulate a form submission
$testData = [
    'nama_pengadu' => 'Test User ' . date('Y-m-d H:i:s'),
    'email' => 'test@example.com',
    'telepon' => '081234567890',
    'subjek' => 'Test Pengaduan ' . date('H:i:s'),
    'isi_pengaduan' => 'Ini adalah test pengaduan untuk memastikan form berfungsi',
    'kategori' => 'pelayanan',
    'status' => 'pending',
    'tanggal_pengaduan' => now(),
    'is_anonymous' => false
];

try {
    $pengaduan = Pengaduan::create($testData);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Test pengaduan berhasil dibuat',
        'data' => $pengaduan,
        'total_count' => Pengaduan::count()
    ], JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
