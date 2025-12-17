<?php
// Direct database query untuk cek data pengaduan
$host = 'localhost';
$dbname = 'u953792975_portal';
$username = 'u953792975_root';
$password = 'wgq^oB$A8';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get count
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pengaduans WHERE deleted_at IS NULL");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Get latest 5
    $stmt = $pdo->query("SELECT id, nama_pengadu, email, subjek, kategori, status, created_at FROM pengaduans WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5");
    $latest = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get status breakdown
    $stmt = $pdo->query("SELECT status, COUNT(*) as jumlah FROM pengaduans WHERE deleted_at IS NULL GROUP BY status");
    $statusBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'total_pengaduan' => $count,
        'status_breakdown' => $statusBreakdown,
        'latest_5' => $latest
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT);
}
