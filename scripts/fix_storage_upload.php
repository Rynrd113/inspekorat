<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

echo "🔧 Re-upload Gambar ke Storage yang Benar\n";
echo "=========================================\n\n";

// Lokasi source folder asli
$sourceFolder = __DIR__ . '/../Inspektorat PPT Dokumentasi-20251005T021251Z-1-001/Inspektorat PPT Dokumentasi';

// Ambil data galeri yang baru diupload
$galeris = Galeri::where('file_path', 'LIKE', 'galeri/galeri_%')->get();

echo "📊 Total gambar yang perlu dipindahkan: {$galeris->count()}\n\n";

$success = 0;
$failed = 0;

foreach ($galeris as $galeri) {
    try {
        // Cari file asli berdasarkan nama file
        $originalFileName = str_replace('galeri_' . time() . '_', '', $galeri->file_name);
        $originalFileName = preg_replace('/^galeri_\d+_\d+_/', '', $galeri->file_name);
        
        // Cari file di folder source
        $foundFile = null;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceFolder));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === $galeri->file_type) {
                $cleanFileName = str_replace([' ', '-', '_'], '', strtolower($file->getFilename()));
                $cleanOriginal = str_replace([' ', '-', '_'], '', strtolower($originalFileName));
                
                if (strpos($cleanFileName, $cleanOriginal) !== false || 
                    strpos($cleanOriginal, pathinfo($cleanFileName, PATHINFO_FILENAME)) !== false) {
                    $foundFile = $file->getPathname();
                    break;
                }
            }
        }
        
        if ($foundFile && file_exists($foundFile)) {
            // Upload ke storage yang benar menggunakan Storage::put
            $fileContent = file_get_contents($foundFile);
            $storagePath = $galeri->file_path;
            
            Storage::disk('public')->put($storagePath, $fileContent);
            
            echo "✅ {$galeri->judul} -> {$storagePath}\n";
            $success++;
        } else {
            echo "❌ File tidak ditemukan untuk: {$galeri->judul}\n";
            $failed++;
        }
        
    } catch (Exception $e) {
        echo "❌ Error: {$galeri->judul} - " . $e->getMessage() . "\n";
        $failed++;
    }
}

echo "\n📈 Hasil:\n";
echo "✅ Berhasil: {$success}\n";
echo "❌ Gagal: {$failed}\n";

// Test sample
if ($success > 0) {
    echo "\n🧪 Test Sample:\n";
    $sample = Galeri::where('file_path', 'LIKE', 'galeri/galeri_%')->first();
    if ($sample && Storage::disk('public')->exists($sample->file_path)) {
        echo "📸 {$sample->judul} -> ✅ DAPAT DIAKSES\n";
        echo "🔗 URL: " . Storage::disk('public')->url($sample->file_path) . "\n";
    }
}

echo "\n🎉 Proses selesai!\n";