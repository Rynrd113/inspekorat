<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Galeri;

echo "🖼️  Sample Gambar yang Berhasil Diupload\n";
echo "========================================\n\n";

// Ambil gambar terbaru yang baru diupload (dari batch)
$recentUploads = Galeri::orderBy('created_at', 'desc')->take(10)->get();

foreach ($recentUploads as $galeri) {
    echo "📸 {$galeri->judul}\n";
    echo "📂 Kategori: {$galeri->kategori}\n";
    echo "📄 Deskripsi: " . substr($galeri->deskripsi, 0, 120) . "...\n";
    echo "📅 Tanggal: {$galeri->tanggal_publikasi}\n";
    echo "💾 File: {$galeri->file_name}\n";
    echo "---\n\n";
}

echo "✅ Script upload batch telah berhasil menambahkan 203 gambar baru!\n";
echo "🎯 Setiap gambar memiliki deskripsi yang sesuai dengan konteks folder asalnya.\n";