<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

echo "🔧 Memperbaiki Path Gambar Galeri\n";
echo "=================================\n\n";

// Ambil semua galeri yang path-nya salah
$galeris = Galeri::where('file_path', 'LIKE', 'storage/galeri/%')->get();

echo "📊 Total gambar yang perlu diperbaiki: {$galeris->count()}\n\n";

$fixed = 0;
$errors = 0;

foreach ($galeris as $galeri) {
    try {
        // Ubah dari "storage/galeri/filename.jpg" menjadi "galeri/filename.jpg"
        $oldPath = $galeri->file_path;
        $newPath = str_replace('storage/', '', $oldPath);
        
        $galeri->file_path = $newPath;
        $galeri->save();
        
        $fixed++;
        echo "✅ Fixed: {$galeri->judul} ({$oldPath} -> {$newPath})\n";
        
    } catch (Exception $e) {
        $errors++;
        echo "❌ Error: {$galeri->judul} - " . $e->getMessage() . "\n";
    }
}

echo "\n📈 Hasil Perbaikan:\n";
echo "✅ Berhasil diperbaiki: {$fixed} gambar\n";
echo "❌ Error: {$errors} gambar\n";

// Test sample
echo "\n🧪 Test Sample:\n";
$sample = Galeri::orderBy('updated_at', 'desc')->first();
if ($sample) {
    echo "📸 Sample: {$sample->judul}\n";
    echo "📂 Path baru: {$sample->file_path}\n";
    echo "🔗 URL: " . asset('storage/' . $sample->file_path) . "\n";
    
    // Cek apakah Storage dapat mengaksesnya sekarang
    $exists = Storage::exists('public/' . $sample->file_path);
    echo "📍 Storage exists: " . ($exists ? "✅ YA" : "❌ TIDAK") . "\n";
}

echo "\n🎉 Perbaikan selesai!\n";