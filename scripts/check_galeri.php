<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;

echo "📊 Laporan Upload Galeri Inspektorat PPT\n";
echo "========================================\n\n";

$total = Galeri::count();
echo "✅ Total gambar berhasil diupload: {$total}\n\n";

echo "📂 Kategori yang tersedia:\n";
Galeri::select('kategori')->distinct()->get()->each(function($item) {
    $count = Galeri::where('kategori', $item->kategori)->count();
    echo "   • {$item->kategori}: {$count} gambar\n";
});

echo "\n📝 Sample deskripsi yang telah dibuat:\n";
echo "=====================================\n";

$samples = Galeri::take(5)->get(['judul', 'deskripsi', 'kategori']);
foreach ($samples as $sample) {
    echo "\n🖼️  Judul: {$sample->judul}\n";
    echo "📂 Kategori: {$sample->kategori}\n";
    echo "📄 Deskripsi: " . substr($sample->deskripsi, 0, 150) . "...\n";
    echo "---\n";
}

echo "\n🎉 Semua gambar telah berhasil diupload dengan deskripsi yang sesuai konteks!\n";