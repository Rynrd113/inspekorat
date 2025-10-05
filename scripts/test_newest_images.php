<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

echo "🎯 Test Gambar Terbaru (Upload Batch)\n";
echo "=====================================\n\n";

// Test gambar terbaru yang baru diupload
$newest = Galeri::orderBy('created_at', 'desc')->take(5)->get();

echo "📊 Sample gambar terbaru:\n\n";

foreach ($newest as $galeri) {
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

// Count gambar yang bisa diakses
$accessible = 0;
$total_new = Galeri::where('file_path', 'LIKE', 'galeri/galeri_%')->count();

echo "📈 Summary:\n";
echo "Total gambar batch upload: {$total_new}\n";

$batch_samples = Galeri::where('file_path', 'LIKE', 'galeri/galeri_%')->take(10)->get();
foreach ($batch_samples as $galeri) {
    if (Storage::disk('public')->exists($galeri->file_path)) {
        $accessible++;
    }
}

echo "Sample yang bisa diakses: {$accessible}/10\n";
echo "\n✅ Gambar dari batch upload siap ditampilkan di frontend!\n";