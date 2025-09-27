<?php

namespace App\Services;

class BrandingPresetService
{
    /**
     * Pre-defined color palettes for government websites
     * Berdasarkan standar aksesibilitas dan identitas visual pemerintah
     */
    public static function getColorPresets(): array
    {
        return [
            'pemerintah_biru' => [
                'name' => 'Pemerintah Biru (Resmi)',
                'primary' => '#1e40af',
                'secondary' => '#059669',
                'accent' => '#dc2626',
                'description' => 'Palet warna standar untuk instansi pemerintahan',
                'recommended' => true
            ],
            'inspektorat_navy' => [
                'name' => 'Inspektorat Navy',
                'primary' => '#0f172a',
                'secondary' => '#0891b2',
                'accent' => '#ea580c',
                'description' => 'Warna khusus untuk Inspektorat dengan nuansa profesional',
                'recommended' => true
            ],
            'papua_hijau' => [
                'name' => 'Papua Hijau',
                'primary' => '#166534',
                'secondary' => '#0369a1',
                'accent' => '#b91c1c',
                'description' => 'Inspirasi dari alam Papua yang hijau',
                'recommended' => true
            ],
            'netral_abu' => [
                'name' => 'Netral Abu-abu',
                'primary' => '#374151',
                'secondary' => '#6b7280',
                'accent' => '#7c2d12',
                'description' => 'Warna netral yang profesional dan mudah dibaca',
                'recommended' => true
            ],
            'merah_putih' => [
                'name' => 'Merah Putih Indonesia',
                'primary' => '#dc2626',
                'secondary' => '#1f2937',
                'accent' => '#f59e0b',
                'description' => 'Terinspirasi bendera Indonesia',
                'recommended' => false
            ],
            'biru_langit' => [
                'name' => 'Biru Langit',
                'primary' => '#0369a1',
                'secondary' => '#0d9488',
                'accent' => '#c2410c',
                'description' => 'Nuansa langit Papua yang cerah',
                'recommended' => false
            ]
        ];
    }

    /**
     * Get contrast-safe color combinations only
     */
    public static function getAccessiblePresets(): array
    {
        return array_filter(self::getColorPresets(), function($preset) {
            return $preset['recommended'];
        });
    }

    /**
     * Validate if color meets accessibility standards
     */
    public static function validateColorContrast(string $color, string $background = '#ffffff'): bool
    {
        // Simple contrast ratio check (simplified implementation)
        // In real implementation, you'd use a proper contrast ratio calculation
        $darkColors = ['#0f172a', '#166534', '#374151', '#dc2626', '#1e40af'];
        
        return in_array($color, $darkColors);
    }

    /**
     * Get logo size constraints based on government standards
     */
    public static function getLogoConstraints(): array
    {
        return [
            'header' => [
                'max_width' => 250,
                'max_height' => 80,
                'aspect_ratio' => '3:1', // max ratio
                'formats' => ['svg', 'png', 'webp'],
                'max_size' => 512 // KB
            ],
            'footer' => [
                'max_width' => 150,
                'max_height' => 50,
                'aspect_ratio' => '3:1',
                'formats' => ['svg', 'png', 'webp'],
                'max_size' => 256 // KB
            ],
            'icon' => [
                'max_width' => 64,
                'max_height' => 64,
                'aspect_ratio' => '1:1',
                'formats' => ['svg', 'png', 'ico'],
                'max_size' => 64 // KB
            ],
            'favicon' => [
                'max_width' => 32,
                'max_height' => 32,
                'aspect_ratio' => '1:1',
                'formats' => ['ico', 'png'],
                'max_size' => 32 // KB
            ]
        ];
    }

    /**
     * Get typography presets following government web standards
     */
    public static function getTypographyPresets(): array
    {
        return [
            'pemerintah_standar' => [
                'name' => 'Pemerintah Standar',
                'font_family' => 'Inter, system-ui, sans-serif',
                'description' => 'Font standar yang mudah dibaca dan aksesibel',
                'recommended' => true
            ],
            'formal_readable' => [
                'name' => 'Formal Readable',
                'font_family' => 'Source Sans Pro, system-ui, sans-serif',
                'description' => 'Font yang sangat mudah dibaca untuk dokumen resmi',
                'recommended' => true
            ],
            'system_default' => [
                'name' => 'System Default',
                'font_family' => 'system-ui, -apple-system, sans-serif',
                'description' => 'Menggunakan font sistem untuk performa optimal',
                'recommended' => true
            ]
        ];
    }

    /**
     * Get layout templates with accessibility considerations
     */
    public static function getLayoutPresets(): array
    {
        return [
            'standard' => [
                'name' => 'Layout Standar',
                'description' => 'Layout dengan header, nav, content, sidebar, footer',
                'features' => ['responsive', 'accessible', 'seo-friendly'],
                'recommended' => true
            ],
            'minimal' => [
                'name' => 'Layout Minimal',
                'description' => 'Layout sederhana dengan fokus pada konten',
                'features' => ['responsive', 'fast-loading', 'mobile-first'],
                'recommended' => true
            ],
            'portal' => [
                'name' => 'Layout Portal',
                'description' => 'Layout untuk portal dengan banyak menu dan konten',
                'features' => ['mega-menu', 'sidebar', 'breadcrumb'],
                'recommended' => false
            ]
        ];
    }

    /**
     * Validate uploaded file against security standards
     */
    public static function validateImageSecurity(string $filePath): array
    {
        $errors = [];
        
        // Check file existence
        if (!file_exists($filePath)) {
            $errors[] = 'File tidak ditemukan';
            return $errors;
        }

        // Check file size
        $fileSize = filesize($filePath) / 1024; // KB
        if ($fileSize > 2048) { // 2MB max
            $errors[] = 'Ukuran file terlalu besar (maksimal 2MB)';
        }

        // Check image dimensions
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            $errors[] = 'File bukan gambar yang valid';
            return $errors;
        }

        // Check for suspicious content (basic check)
        $fileContent = file_get_contents($filePath);
        $suspiciousPatterns = ['<script', '<?php', 'javascript:', 'data:'];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($fileContent, $pattern) !== false) {
                $errors[] = 'File mengandung konten yang tidak diizinkan';
                break;
            }
        }

        return $errors;
    }

    /**
     * Get recommended settings based on government web standards
     */
    public static function getRecommendedSettings(): array
    {
        return [
            'accessibility' => [
                'high_contrast' => true,
                'keyboard_navigation' => true,
                'screen_reader_friendly' => true,
                'minimum_font_size' => '16px'
            ],
            'performance' => [
                'optimize_images' => true,
                'lazy_loading' => true,
                'minify_css' => true,
                'cache_duration' => 3600
            ],
            'security' => [
                'validate_uploads' => true,
                'sanitize_content' => true,
                'csrf_protection' => true,
                'content_security_policy' => true
            ]
        ];
    }
}