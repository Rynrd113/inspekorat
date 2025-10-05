<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

echo "🧪 Test Storage Access Final\n";
echo "============================\n\n";

$sample = Galeri::orderBy('created_at', 'desc')->first();

if ($sample) {
    echo "📸 Sample: {$sample->judul}\n";
    echo "📂 Path di DB: {$sample->file_path}\n\n";
    
    // Test berbagai path
    $tests = [
        'Direct path' => $sample->file_path,
        'Public path' => 'public/' . $sample->file_path,
    ];
    
    foreach ($tests as $desc => $path) {
        $exists = Storage::exists($path);
        $status = $exists ? "✅ DITEMUKAN" : "❌ TIDAK ADA";
        echo "{$desc}: {$path} -> {$status}\n";
        
        if ($exists) {
            $url = Storage::url($path);
            echo "   🔗 URL: {$url}\n";
        }
    }
    
    echo "\n📁 File fisik check:\n";
    $storage_path = storage_path('app/public/' . $sample->file_path);
    $exists = file_exists($storage_path);
    echo "Storage path: {$storage_path} -> " . ($exists ? "✅ ADA" : "❌ TIDAK ADA") . "\n";
    
    if ($exists) {
        echo "📏 Size: " . number_format(filesize($storage_path) / 1024, 2) . " KB\n";
    }
    
} else {
    echo "❌ Tidak ada data galeri\n";
}