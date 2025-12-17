<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pengaduan;

$count = Pengaduan::count();
$latest = Pengaduan::latest()->first();

header('Content-Type: application/json');
echo json_encode([
    'count' => $count,
    'latest' => $latest,
    'all' => Pengaduan::latest()->take(5)->get()
], JSON_PRETTY_PRINT);
