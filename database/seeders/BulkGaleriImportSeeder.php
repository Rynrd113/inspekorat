<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BulkGaleriImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Starting bulk galeri import from public/storage/galeri...');
        
        // Path ke folder galeri
        $galeriPath = public_path('storage/galeri');
        
        if (!File::exists($galeriPath)) {
            $this->command->error("❌ Folder {$galeriPath} tidak ditemukan!");
            return;
        }
        
        // Get all image files
        $files = File::files($galeriPath);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        
        $this->command->info("📦 Ditemukan " . count($files) . " file di folder");
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        
        // Kategori mapping berdasarkan keyword di filename
        $kategoriMap = [
            'rapat' => 'Rapat',
            'meeting' => 'Rapat',
            'audit' => 'Audit',
            'pemeriksaan' => 'Audit',
            'pelatihan' => 'Pelatihan',
            'training' => 'Pelatihan',
            'workshop' => 'Pelatihan',
            'sosialisasi' => 'Sosialisasi',
            'kunjungan' => 'Kunjungan',
            'visit' => 'Kunjungan',
            'koordinasi' => 'Koordinasi',
            'monev' => 'Monitoring',
            'monitoring' => 'Monitoring',
            'evaluasi' => 'Evaluasi',
            'upacara' => 'Upacara',
            'ceremony' => 'Upacara',
        ];
        
        foreach ($files as $file) {
            try {
                $filename = $file->getFilename();
                $extension = strtolower($file->getExtension());
                
                // Skip non-image files
                if (!in_array($extension, $allowedExtensions)) {
                    $this->command->warn("⏭️  Skip: {$filename} (bukan gambar)");
                    $skipped++;
                    continue;
                }
                
                // Check if already exists
                if (Galeri::where('file_name', $filename)->exists()) {
                    $this->command->warn("⏭️  Skip: {$filename} (sudah ada di database)");
                    $skipped++;
                    continue;
                }
                
                // Generate judul dari filename
                $judul = $this->generateJudul($filename);
                
                // Detect kategori
                $kategori = $this->detectKategori($filename, $kategoriMap);
                
                // Get file info
                $fileSize = $file->getSize();
                $fileSizeKB = number_format($fileSize / 1024, 2);
                
                // Get image dimensions
                $imagePath = $file->getPathname();
                $imageInfo = @getimagesize($imagePath);
                $dimensions = $imageInfo ? "{$imageInfo[0]}x{$imageInfo[1]}" : 'N/A';
                
                // Create galeri entry
                Galeri::create([
                    'judul' => $judul,
                    'deskripsi' => $this->generateDeskripsi($kategori, $judul),
                    'kategori' => 'foto', // Always foto karena semua image
                    'file_path' => 'galeri/' . $filename, // Relative path dari storage/app/public
                    'file_name' => $filename,
                    'file_type' => $extension,
                    'file_size' => "{$fileSizeKB} KB",
                    'tanggal_publikasi' => now()->subDays(rand(1, 365)), // Random date 1-365 hari lalu
                    'created_by' => 1,
                    'status' => true,
                ]);
                
                $number = $imported + 1;
                $this->command->info("✅ [{$number}] {$filename} → {$judul} ({$kategori}) - {$dimensions}");
                $imported++;
                
            } catch (\Exception $e) {
                $this->command->error("❌ Error: {$filename} - " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->command->newLine();
        $this->command->info("═══════════════════════════════════════");
        $this->command->info("🎉 Import Selesai!");
        $this->command->info("═══════════════════════════════════════");
        $this->command->info("✅ Berhasil diimport: {$imported} file");
        $this->command->warn("⏭️  Dilewati: {$skipped} file");
        if ($errors > 0) {
            $this->command->error("❌ Error: {$errors} file");
        }
        $this->command->info("═══════════════════════════════════════");
    }
    
    /**
     * Generate judul dari filename
     */
    private function generateJudul(string $filename): string
    {
        // Remove extension
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace separators with spaces
        $name = str_replace(['-', '_', '.'], ' ', $name);
        
        // Remove numbers at start
        $name = preg_replace('/^\d+\s*/', '', $name);
        
        // Remove year/date patterns
        $name = preg_replace('/\b(19|20)\d{2}\b/', '', $name);
        
        // Clean multiple spaces
        $name = preg_replace('/\s+/', ' ', $name);
        
        // Capitalize each word
        $name = ucwords(strtolower(trim($name)));
        
        // Limit length
        return Str::limit($name, 100);
    }
    
    /**
     * Detect kategori dari filename
     */
    private function detectKategori(string $filename, array $kategoriMap): string
    {
        $filenameLower = strtolower($filename);
        
        foreach ($kategoriMap as $keyword => $kategori) {
            if (str_contains($filenameLower, $keyword)) {
                return $kategori;
            }
        }
        
        // Default kategori
        return 'Kegiatan';
    }
    
    /**
     * Generate deskripsi
     */
    private function generateDeskripsi(string $kategori, string $judul): string
    {
        $templates = [
            'Rapat' => "Dokumentasi {$kategori} - {$judul} di Kantor Inspektorat Provinsi Papua Tengah",
            'Audit' => "Kegiatan {$kategori} - {$judul} yang dilaksanakan oleh Inspektorat Provinsi Papua Tengah",
            'Pelatihan' => "Pelaksanaan {$kategori} - {$judul} untuk meningkatkan kapasitas dan kompetensi pegawai",
            'Sosialisasi' => "Kegiatan {$kategori} - {$judul} kepada seluruh stakeholder terkait",
            'Kunjungan' => "Dokumentasi {$kategori} - {$judul} di lingkungan Pemerintah Provinsi Papua Tengah",
            'Koordinasi' => "Rapat {$kategori} - {$judul} dengan berbagai pihak terkait",
            'Monitoring' => "Kegiatan {$kategori} - {$judul} terhadap program dan kegiatan OPD",
            'Evaluasi' => "Pelaksanaan {$kategori} - {$judul} untuk perbaikan berkelanjutan",
            'Upacara' => "Pelaksanaan {$kategori} - {$judul} di Kantor Inspektorat Provinsi Papua Tengah",
        ];
        
        return $templates[$kategori] ?? "Dokumentasi kegiatan {$judul} Inspektorat Provinsi Papua Tengah";
    }
}
