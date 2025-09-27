<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTING API & FORM ENDPOINTS ===\n\n";

// Determine base URL
$baseUrl = 'http://127.0.0.1:8000'; // Adjust this to your local server URL
echo "Base URL: $baseUrl\n\n";

// Test 1: Public Routes
echo "1. Testing Public Routes...\n";
$publicRoutes = [
    '/' => 'Homepage',
    '/berita' => 'Berita Index',
    '/wbs' => 'WBS Form',
    '/profil' => 'Profil',
    '/pelayanan' => 'Pelayanan Index',
    '/dokumen' => 'Dokumen Index',
    '/galeri' => 'Galeri Index',
    '/faq' => 'FAQ',
    '/kontak' => 'Kontak',
    '/pengaduan' => 'Pengaduan Form',
];

foreach ($publicRoutes as $route => $name) {
    try {
        $response = Http::timeout(5)->get($baseUrl . $route);
        if ($response->successful()) {
            echo "   ✅ $name ($route): HTTP {$response->status()}\n";
        } else {
            echo "   ❌ $name ($route): HTTP {$response->status()}\n";
        }
    } catch (Exception $e) {
        echo "   ❌ $name ($route): Connection failed - " . $e->getMessage() . "\n";
    }
}

// Test 2: Form Data Submission
echo "\n2. Testing Form Submissions...\n";

// Test WBS Form Submission
echo "   Testing WBS Form...\n";
try {
    $wbsData = [
        'nama_pelapor' => 'Test Reporter API',
        'email' => 'wbs_test_' . time() . '@example.com',
        'no_telepon' => '081234567890',
        'subjek' => 'Test WBS via API - ' . date('Y-m-d H:i:s'),
        'deskripsi' => 'Test submission melalui API untuk memastikan endpoint berfungsi',
    ];
    
    $response = Http::timeout(10)->post($baseUrl . '/wbs', $wbsData);
    if ($response->successful() || $response->status() == 302) {
        echo "     ✅ WBS submission successful: HTTP {$response->status()}\n";
    } else {
        echo "     ❌ WBS submission failed: HTTP {$response->status()}\n";
    }
} catch (Exception $e) {
    echo "     ❌ WBS submission error: " . $e->getMessage() . "\n";
}

// Test Pengaduan Form Submission
echo "   Testing Pengaduan Form...\n";
try {
    $pengaduanData = [
        'nama_pengadu' => 'Test Reporter Pengaduan API',
        'email' => 'pengaduan_test_' . time() . '@example.com',
        'telepon' => '081234567891',
        'subjek' => 'Test Pengaduan via API - ' . date('Y-m-d H:i:s'),
        'isi_pengaduan' => 'Test submission pengaduan melalui API',
    ];
    
    $response = Http::timeout(10)->post($baseUrl . '/pengaduan', $pengaduanData);
    if ($response->successful() || $response->status() == 302) {
        echo "     ✅ Pengaduan submission successful: HTTP {$response->status()}\n";
    } else {
        echo "     ❌ Pengaduan submission failed: HTTP {$response->status()}\n";
    }
} catch (Exception $e) {
    echo "     ❌ Pengaduan submission error: " . $e->getMessage() . "\n";
}

// Test Kontak Form Submission
echo "   Testing Kontak Form...\n";
try {
    $kontakData = [
        'nama' => 'Test Contact API',
        'email' => 'contact_test_' . time() . '@example.com',
        'subjek' => 'Test Kontak via API',
        'pesan' => 'Test message melalui API untuk memastikan form kontak berfungsi',
    ];
    
    $response = Http::timeout(10)->post($baseUrl . '/kontak', $kontakData);
    if ($response->successful() || $response->status() == 302) {
        echo "     ✅ Kontak submission successful: HTTP {$response->status()}\n";
    } else {
        echo "     ❌ Kontak submission failed: HTTP {$response->status()}\n";
    }
} catch (Exception $e) {
    echo "     ❌ Kontak submission error: " . $e->getMessage() . "\n";
}

// Test 3: Dynamic Content
echo "\n3. Testing Dynamic Content...\n";

// Test if berita/news show properly with existing data
try {
    $portalNews = \App\Models\PortalPapuaTengah::where('status', true)->first();
    if ($portalNews) {
        $response = Http::timeout(5)->get($baseUrl . "/berita/{$portalNews->id}");
        if ($response->successful()) {
            echo "   ✅ Berita detail page (ID: {$portalNews->id}): HTTP {$response->status()}\n";
        } else {
            echo "   ❌ Berita detail page failed: HTTP {$response->status()}\n";
        }
    } else {
        echo "   ⚠️  No published berita found for testing\n";
    }
} catch (Exception $e) {
    echo "   ❌ Berita detail test error: " . $e->getMessage() . "\n";
}

// Test pelayanan detail
try {
    $pelayanan = \App\Models\Pelayanan::where('status', true)->first();
    if ($pelayanan) {
        $response = Http::timeout(5)->get($baseUrl . "/pelayanan/{$pelayanan->id}");
        if ($response->successful()) {
            echo "   ✅ Pelayanan detail page (ID: {$pelayanan->id}): HTTP {$response->status()}\n";
        } else {
            echo "   ❌ Pelayanan detail page failed: HTTP {$response->status()}\n";
        }
    } else {
        echo "   ⚠️  No active pelayanan found for testing\n";
    }
} catch (Exception $e) {
    echo "   ❌ Pelayanan detail test error: " . $e->getMessage() . "\n";
}

// Test 4: Admin Routes (without authentication - should redirect)
echo "\n4. Testing Admin Routes (should redirect to login)...\n";
$adminRoutes = [
    '/admin/dashboard' => 'Admin Dashboard',
    '/admin/wbs' => 'Admin WBS',
    '/admin/pelayanan' => 'Admin Pelayanan',
    '/admin/pengaduan' => 'Admin Pengaduan',
];

foreach ($adminRoutes as $route => $name) {
    try {
        $response = Http::timeout(5)->get($baseUrl . $route);
        if ($response->status() == 302) {
            echo "   ✅ $name ($route): Redirected to login (HTTP 302) ✓\n";
        } elseif ($response->status() == 401) {
            echo "   ✅ $name ($route): Unauthorized access blocked (HTTP 401) ✓\n";
        } else {
            echo "   ❌ $name ($route): Unexpected response HTTP {$response->status()}\n";
        }
    } catch (Exception $e) {
        echo "   ❌ $name ($route): Connection failed - " . $e->getMessage() . "\n";
    }
}

// Test 5: File Downloads
echo "\n5. Testing File Downloads...\n";
try {
    $dokumen = \App\Models\Dokumen::where('status', true)->where('file_path', '!=', null)->first();
    if ($dokumen) {
        $response = Http::timeout(5)->get($baseUrl . "/dokumen/{$dokumen->id}/preview");
        if ($response->successful()) {
            echo "   ✅ Document preview (ID: {$dokumen->id}): HTTP {$response->status()}\n";
        } else {
            echo "   ❌ Document preview failed: HTTP {$response->status()}\n";
        }
    } else {
        echo "   ⚠️  No documents with files found for testing\n";
    }
} catch (Exception $e) {
    echo "   ❌ Document preview test error: " . $e->getMessage() . "\n";
}

echo "\n=== E2E API & FORM TESTING COMPLETED ===\n";
echo "Hasil testing menunjukkan status koneksi antara frontend dan backend.\n";
echo "Jika ada error HTTP 500, periksa log aplikasi untuk detail lebih lanjut.\n";
echo "Jika ada connection failed, pastikan server development sedang berjalan.\n";
echo "\nUntuk menjalankan server development:\n";
echo "php artisan serve\n";