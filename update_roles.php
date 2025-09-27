<?php

/**
 * Script untuk mengupdate role user dari struktur lama ke struktur baru
 * Jalankan dengan: php update_roles.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "🔄 Mengupdate role user dari struktur lama ke struktur baru...\n\n";

// Mapping role lama ke role baru
$roleMapping = [
    // Admin spesifik → Content Admin
    'admin_wbs' => 'content_admin',
    'admin_berita' => 'content_admin', 
    'admin_portal_opd' => 'content_admin',
    'admin_pelayanan' => 'content_admin',
    'admin_dokumen' => 'content_admin',
    'admin_galeri' => 'content_admin',
    'admin_faq' => 'content_admin',
    
    // Manager roles → Content Admin atau Admin
    'content_manager' => 'content_admin',
    'service_manager' => 'admin',
    'opd_manager' => 'admin', 
    'wbs_manager' => 'admin',
    
    // Yang sudah benar tetap sama
    'admin' => 'admin',
    'super_admin' => 'super_admin',
    'content_admin' => 'content_admin',
    
    // Hapus user biasa - tidak digunakan lagi
    'user' => 'content_admin',
];

$updatedCount = 0;

foreach ($roleMapping as $oldRole => $newRole) {
    $users = User::where('role', $oldRole)->get();
    
    foreach ($users as $user) {
        if ($user->role !== $newRole) {
            $oldRoleValue = $user->role;
            $user->role = $newRole;
            $user->save();
            
            echo "✅ Updated: {$user->name} ({$user->email}) from '{$oldRoleValue}' to '{$newRole}'\n";
            $updatedCount++;
        }
    }
}

if ($updatedCount > 0) {
    echo "\n🎉 Successfully updated {$updatedCount} users!\n";
} else {
    echo "\n✨ No users needed updating. All roles are already current.\n";
}

echo "\n📊 Current user role distribution:\n";
$roleStats = User::select('role', \DB::raw('count(*) as count'))
    ->groupBy('role')
    ->get();

foreach ($roleStats as $stat) {
    echo "   {$stat->role}: {$stat->count} users\n";
}

echo "\n✅ Role update completed!\n";
