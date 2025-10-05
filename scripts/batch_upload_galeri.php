<?php

/**
 * Script untuk mengupload semua gambar dari folder dokumentasi PPT
 * ke dalam database galeri secara batch dengan deskripsi otomatis
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use App\Models\Galeri;
use Carbon\Carbon;

// Boot Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class BatchGaleriUploader
{
    private $sourceFolder;
    private $publicStoragePath;
    private $uploadedCount = 0;
    private $errorCount = 0;
    private $errors = [];
    
    public function __construct()
    {
        $this->sourceFolder = __DIR__ . '/../Inspektorat PPT Dokumentasi-20251005T021251Z-1-001/Inspektorat PPT Dokumentasi';
        $this->publicStoragePath = storage_path('app/public/galeri');
        
        // Pastikan folder storage/galeri ada
        if (!file_exists($this->publicStoragePath)) {
            mkdir($this->publicStoragePath, 0755, true);
        }
    }
    
    public function run()
    {
        echo "🚀 Memulai batch upload gambar dari dokumentasi PPT...\n";
        echo "📁 Source folder: {$this->sourceFolder}\n";
        echo "📁 Target folder: {$this->publicStoragePath}\n\n";
        
        $this->processFolder($this->sourceFolder);
        
        echo "\n✅ Proses selesai!\n";
        echo "📊 Total gambar berhasil diupload: {$this->uploadedCount}\n";
        echo "❌ Total error: {$this->errorCount}\n";
        
        if (!empty($this->errors)) {
            echo "\n🔍 Detail errors:\n";
            foreach ($this->errors as $error) {
                echo "   - {$error}\n";
            }
        }
    }
    
    private function processFolder($folderPath, $parentCategory = '')
    {
        if (!is_dir($folderPath)) {
            return;
        }
        
        $items = scandir($folderPath);
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $itemPath = $folderPath . '/' . $item;
            
            if (is_dir($itemPath)) {
                // Rekursif untuk subfolder
                $category = $this->getCategoryFromFolderName($item, $parentCategory);
                $this->processFolder($itemPath, $category);
            } else if ($this->isImageFile($item)) {
                // Process image file
                $this->processImageFile($itemPath, $item, $parentCategory);
            }
        }
    }
    
    private function processImageFile($filePath, $fileName, $category)
    {
        try {
            echo "📸 Processing: {$fileName} ({$category})\n";
            
            // Generate nama file yang unik
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $newFileName = $this->generateUniqueFileName($baseName, $fileExtension);
            
            // Copy file ke storage
            $destinationPath = $this->publicStoragePath . '/' . $newFileName;
            if (!copy($filePath, $destinationPath)) {
                throw new Exception("Gagal copy file ke storage");
            }
            
            // Generate deskripsi berdasarkan konteks
            $deskripsi = $this->generateDescription($fileName, $category);
            $judul = $this->generateTitle($fileName, $category);
            
            // Simpan ke database
            $galeri = new Galeri();
            $galeri->judul = $judul;
            $galeri->deskripsi = $deskripsi;
            $galeri->kategori = $this->mapCategoryToGaleriCategory($category);
            $galeri->file_path = 'galeri/' . $newFileName;
            $galeri->file_name = $newFileName;
            $galeri->file_type = strtolower($fileExtension);
            $galeri->file_size = filesize($filePath);
            $galeri->status = true;
            $galeri->tanggal_publikasi = $this->extractDateFromPath($filePath);
            $galeri->created_by = 1; // Admin user
            $galeri->updated_by = 1;
            $galeri->save();
            
            $this->uploadedCount++;
            echo "   ✅ Berhasil: {$judul}\n";
            
        } catch (Exception $e) {
            $this->errorCount++;
            $error = "Error processing {$fileName}: " . $e->getMessage();
            $this->errors[] = $error;
            echo "   ❌ Error: {$error}\n";
        }
    }
    
    private function isImageFile($fileName)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($extension, $imageExtensions);
    }
    
    private function getCategoryFromFolderName($folderName, $parentCategory = '')
    {
        // Mapping folder ke kategori yang lebih deskriptif
        $categoryMap = [
            '3 Desain' => 'desain',
            'Foto Bersama Inspektorat PPT' => 'foto_bersama',
            'Foto PNG Pimpinan, Irban DLL' => 'pimpinan',
            'Profile Kantor' => 'profile_kantor',
            'Struktur Inspektorat PPT' => 'struktur_organisasi',
            'KPK RAKKOR WITH PPT' => 'rakkor_kpk',
            'IG' => 'instagram',
            'RAPAT' => 'rapat',
            'APEL' => 'apel',
            'apEL pagi' => 'apel_pagi'
        ];
        
        // Cek mapping eksplisit
        if (isset($categoryMap[$folderName])) {
            return $categoryMap[$folderName];
        }
        
        // Deteksi berdasarkan pattern bulan
        if (preg_match('/(\d+)\s*(JUNI|JULI|AGUS|SEPT|OCTO)/i', $folderName, $matches)) {
            $bulan = strtolower($matches[2]);
            $tanggal = $matches[1];
            return "kegiatan_{$bulan}_{$tanggal}";
        }
        
        // Deteksi pattern tanggal
        if (preg_match('/(\d+)\s*(juni|juli|agus|sept|oktober)/i', $folderName, $matches)) {
            $bulan = strtolower($matches[2]);
            $tanggal = $matches[1];
            return "kegiatan_{$bulan}_{$tanggal}";
        }
        
        // Default dengan parent category
        $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $folderName));
        return $parentCategory ? $parentCategory . '_' . $cleanName : $cleanName;
    }
    
    private function generateTitle($fileName, $category)
    {
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        
        // Mapping kategori ke prefix judul
        $titlePrefixes = [
            'desain' => 'Desain',
            'foto_bersama' => 'Foto Bersama',
            'pimpinan' => 'Foto Pimpinan',
            'profile_kantor' => 'Profile Kantor',
            'struktur_organisasi' => 'Struktur Organisasi',
            'rakkor_kpk' => 'Rakoor dengan KPK',
            'instagram' => 'Konten Instagram',
            'rapat' => 'Dokumentasi Rapat',
            'apel' => 'Apel Pagi',
            'apel_pagi' => 'Apel Pagi'
        ];
        
        // Deteksi kategori kegiatan bulanan
        if (preg_match('/kegiatan_(\w+)_(\d+)/', $category, $matches)) {
            $bulan = ucfirst($matches[1]);
            $tanggal = $matches[2];
            $prefix = "Kegiatan {$tanggal} {$bulan}";
        } else {
            $prefix = $titlePrefixes[$category] ?? 'Dokumentasi';
        }
        
        // Bersihkan nama file
        $cleanName = preg_replace('/[_\-]+/', ' ', $baseName);
        $cleanName = preg_replace('/\d+/', '', $cleanName); // Hapus nomor urut
        $cleanName = trim($cleanName);
        
        if (empty($cleanName) || strlen($cleanName) < 3) {
            return $prefix . ' Inspektorat PPT';
        }
        
        return $prefix . ' - ' . ucwords($cleanName);
    }
    
    private function generateDescription($fileName, $category)
    {
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        
        // Template deskripsi berdasarkan kategori
        $descriptionTemplates = [
            'desain' => 'Karya desain grafis untuk keperluan publikasi dan promosi kegiatan Inspektorat PPT. Desain ini dibuat untuk mendukung komunikasi visual yang efektif dalam penyampaian informasi kepada masyarakat.',
            
            'foto_bersama' => 'Dokumentasi foto bersama tim Inspektorat PPT yang menampilkan kebersamaan dan solidaritas dalam menjalankan tugas pengawasan. Foto ini menjadi bukti kolaborasi yang baik antar personel.',
            
            'pimpinan' => 'Foto resmi pimpinan dan jajaran Inspektorat PPT yang menampilkan struktur kepemimpinan organisasi. Dokumentasi ini penting untuk identitas dan representasi institusi.',
            
            'profile_kantor' => 'Dokumentasi profil dan fasilitas kantor Inspektorat PPT yang menampilkan lingkungan kerja dan infrastruktur pendukung dalam menjalankan tugas pengawasan dan pemeriksaan.',
            
            'struktur_organisasi' => 'Bagan struktur organisasi Inspektorat PPT yang menampilkan hierarki kepemimpinan dan pembagian tugas setiap unit kerja dalam mendukung fungsi pengawasan internal.',
            
            'rakkor_kpk' => 'Dokumentasi kegiatan Rapat Koordinasi (Rakkor) bersama Komisi Pemberantasan Korupsi (KPK) dalam rangka penguatan sistem pengawasan dan pencegahan korupsi.',
            
            'instagram' => 'Konten visual untuk media sosial Instagram Inspektorat PPT yang bertujuan meningkatkan engagement dan komunikasi dengan masyarakat melalui platform digital.',
            
            'rapat' => 'Dokumentasi kegiatan rapat internal Inspektorat PPT dalam rangka koordinasi, evaluasi, dan perencanaan program kerja untuk meningkatkan kualitas pengawasan.',
            
            'apel' => 'Dokumentasi kegiatan apel pagi Inspektorat PPT yang rutin dilaksanakan untuk membangun kedisiplinan, koordinasi, dan semangat kerja tim.',
            
            'apel_pagi' => 'Dokumentasi kegiatan apel pagi Inspektorat PPT yang dilaksanakan secara rutin setiap hari untuk membangun kedisiplinan dan koordinasi kerja tim.'
        ];
        
        // Untuk kategori kegiatan bulanan
        if (preg_match('/kegiatan_(\w+)_(\d+)/', $category, $matches)) {
            $bulan = ucfirst($matches[1]);
            $tanggal = $matches[2];
            
            $kegiatanDesc = [
                'juni' => 'masa penutupan semester dan persiapan evaluasi tengah tahun',
                'juli' => 'bulan penguatan kapasitas dan koordinasi lintas unit',
                'agus' => 'periode intensifikasi program pengawasan',
                'sept' => 'masa evaluasi dan perencanaan program triwulan',
                'octo' => 'periode persiapan akhir tahun dan pelaporan'
            ];
            
            $konteksBulan = $kegiatanDesc[strtolower($matches[1])] ?? 'periode kegiatan rutin';
            
            return "Dokumentasi kegiatan Inspektorat PPT tanggal {$tanggal} {$bulan} dalam {$konteksBulan}. Kegiatan ini merupakan bagian dari program kerja rutin untuk meningkatkan kualitas pengawasan dan pelayanan publik.";
        }
        
        return $descriptionTemplates[$category] ?? 'Dokumentasi kegiatan Inspektorat PPT yang menampilkan berbagai aktivitas dalam rangka menjalankan tugas pengawasan dan pelayanan kepada masyarakat.';
    }
    
    private function mapCategoryToGaleriCategory($category)
    {
        // Semua gambar akan dikategorikan sebagai 'foto'
        // Sesuai dengan kategori yang ada di model Galeri
        return 'foto';
    }
    
    private function extractDateFromPath($filePath)
    {
        // Coba ekstrak tanggal dari path
        if (preg_match('/(\d+)\s*(JUNI|JULI|AGUS|SEPT|OCTO)/i', $filePath, $matches)) {
            $tanggal = $matches[1];
            $bulanMap = [
                'JUNI' => '06',
                'JULI' => '07', 
                'AGUS' => '08',
                'SEPT' => '09',
                'OCTO' => '10'
            ];
            $bulan = $bulanMap[strtoupper($matches[2])] ?? '01';
            $tahun = '2024'; // Asumsi tahun 2024
            
            try {
                return Carbon::createFromFormat('Y-m-d', "{$tahun}-{$bulan}-{$tanggal}")->format('Y-m-d');
            } catch (Exception $e) {
                // Jika tanggal tidak valid, gunakan hari ini
                return Carbon::now()->format('Y-m-d');
            }
        }
        
        // Default ke hari ini
        return Carbon::now()->format('Y-m-d');
    }
    
    private function generateUniqueFileName($baseName, $extension)
    {
        $timestamp = time();
        $random = mt_rand(100, 999);
        $cleanBaseName = preg_replace('/[^a-zA-Z0-9]/', '_', $baseName);
        $cleanBaseName = substr($cleanBaseName, 0, 20); // Batasi panjang
        
        return "galeri_{$timestamp}_{$random}_{$cleanBaseName}.{$extension}";
    }
}

// Jalankan script
echo "🎯 Batch Upload Galeri Inspektorat PPT\n";
echo "=====================================\n\n";

try {
    $uploader = new BatchGaleriUploader();
    $uploader->run();
} catch (Exception $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🎉 Script selesai dijalankan.\n";