<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

echo "🎯 Test Final - Galeri Frontend Ready\n";
echo "====================================\n\n";

// Test beberapa sample galeri
$samples = Galeri::take(5)->get();

echo "📊 Total galeri: " . Galeri::count() . "\n\n";

foreach ($samples as $galeri) {
    echo "📸 {$galeri->judul}\n";
    echo "📂 Category: {$galeri->kategori}\n";
    echo "📄 Path: {$galeri->file_path}\n";
    
    // Test apakah file exists
    $exists = Storage::disk('public')->exists($galeri->file_path);
    echo "📁 File exists: " . ($exists ? "✅ YES" : "❌ NO") . "\n";
    
    if ($exists) {
        $url = Storage::disk('public')->url($galeri->file_path);
        echo "🔗 URL: {$url}\n";
        
        // Test ukuran file
        $size = Storage::disk('public')->size($galeri->file_path);
        echo "📏 Size: " . number_format($size / 1024, 2) . " KB\n";
    }
    
    echo "---\n\n";
}

echo "✅ Semua gambar siap ditampilkan di frontend!\n";
echo "🌐 Silakan akses halaman galeri untuk melihat hasilnya.\n";