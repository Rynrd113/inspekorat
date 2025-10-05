<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

echo "🔍 Debug Path Gambar Galeri\n";
echo "==========================\n\n";

$sample = Galeri::orderBy('created_at', 'desc')->first();

if ($sample) {
    echo "📸 Sample Galeri: {$sample->judul}\n";
    echo "📂 File Path di DB: {$sample->file_path}\n";
    echo "📁 File Name: {$sample->file_name}\n\n";
    
    // Cek berbagai kemungkinan path
    $paths_to_check = [
        "Path di DB: {$sample->file_path}",
        "Public path: public/{$sample->file_path}",
        "Galeri path: galeri/{$sample->file_name}",
        "Storage path: storage/galeri/{$sample->file_name}"
    ];
    
    foreach ($paths_to_check as $desc) {
        $path = explode(': ', $desc)[1];
        $exists = Storage::exists($path);
        $status = $exists ? "✅ ADA" : "❌ TIDAK ADA";
        echo "{$desc} -> {$status}\n";
    }
    
    echo "\n📍 File fisik di sistem:\n";
    $physical_path = public_path('storage/galeri/' . $sample->file_name);
    $file_exists = file_exists($physical_path);
    echo "File fisik: {$physical_path} -> " . ($file_exists ? "✅ ADA" : "❌ TIDAK ADA") . "\n";
    
    if ($file_exists) {
        $size = filesize($physical_path);
        echo "📏 Ukuran: " . number_format($size / 1024, 2) . " KB\n";
    }
    
    echo "\n🛠️ Storage URL yang dihasilkan:\n";
    echo "URL: " . Storage::url($sample->file_path) . "\n";
    
} else {
    echo "❌ Tidak ada data galeri ditemukan\n";
}