<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "🔧 Test Storage Disk\n";
echo "===================\n\n";

$fileName = 'galeri_1759631423_668_1000183233.jpg';

echo "Default disk: " . Storage::getDefaultDriver() . "\n";
echo "Public disk path: " . Storage::disk('public')->path('') . "\n\n";

// Test dengan disk public
$existsPublic = Storage::disk('public')->exists('galeri/' . $fileName);
echo "Public disk - galeri/{$fileName}: " . ($existsPublic ? "✅ EXISTS" : "❌ NOT FOUND") . "\n";

if ($existsPublic) {
    $url = Storage::disk('public')->url('galeri/' . $fileName);
    echo "URL: {$url}\n";
}

// Test list files
echo "\nFiles in galeri folder:\n";
$files = Storage::disk('public')->files('galeri');
$count = count($files);
echo "Total files: {$count}\n";

if ($count > 0) {
    echo "First 5 files:\n";
    foreach (array_slice($files, 0, 5) as $file) {
        echo "  - {$file}\n";
    }
}

// Manual path check
$manualPath = storage_path('app/public/galeri/' . $fileName);
echo "\nManual path check:\n";
echo "Path: {$manualPath}\n";
echo "Exists: " . (file_exists($manualPath) ? "✅ YES" : "❌ NO") . "\n";