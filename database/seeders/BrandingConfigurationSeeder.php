<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemConfiguration;

class BrandingConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandingConfigurations = [
            // Branding & Visual configurations
            [
                'key' => 'brand_logo_header',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Logo untuk header (ukuran: 200x60px)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_logo_footer',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Logo untuk footer (ukuran: 120x40px)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_logo_icon',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Logo icon/symbol (ukuran: 64x64px)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'branding',
                'description' => 'Favicon website (ukuran: 32x32px)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_primary_color',
                'value' => '#1e40af',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Warna utama (format: #hex)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_secondary_color',
                'value' => '#059669',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Warna sekunder (format: #hex)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_accent_color',
                'value' => '#dc2626',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Warna aksen (format: #hex)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_gradient_start',
                'value' => '#1e40af',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Warna awal gradient (format: #hex)',
                'is_public' => false,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_gradient_end',
                'value' => '#3730a3',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Warna akhir gradient (format: #hex)',
                'is_public' => false,
                'updated_by' => 1,
            ],
            [
                'key' => 'brand_theme_mode',
                'value' => 'light',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Mode tema default (light/dark)',
                'is_public' => false,
                'updated_by' => 1,
            ],

            // Enhanced Social Media configurations
            [
                'key' => 'social_facebook_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman Facebook resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_twitter_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman Twitter resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_instagram_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman Instagram resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_youtube_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL channel YouTube resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_linkedin_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman LinkedIn resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_tiktok_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL halaman TikTok resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_whatsapp_number',
                'value' => null,
                'type' => 'string',
                'group' => 'social',
                'description' => 'Nomor WhatsApp layanan (format: 628xxx)',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_telegram_url',
                'value' => null,
                'type' => 'url',
                'group' => 'social',
                'description' => 'URL channel Telegram resmi',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'social_share_enabled',
                'value' => true,
                'type' => 'boolean',
                'group' => 'social',
                'description' => 'Aktifkan tombol share sosial media',
                'is_public' => false,
                'updated_by' => 1,
            ],

            // Additional Site configurations
            [
                'key' => 'site_keywords',
                'value' => 'inspektorat, papua tengah, pemerintah, pengawasan, transparansi',
                'type' => 'string',
                'group' => 'site',
                'description' => 'Keywords SEO',
                'is_public' => true,
                'updated_by' => 1,
            ],
            [
                'key' => 'site_working_hours',
                'value' => 'Senin - Jumat: 08:00 - 16:00',
                'type' => 'string',
                'group' => 'site',
                'description' => 'Jam kerja',
                'is_public' => true,
                'updated_by' => 1,
            ],
        ];

        foreach ($brandingConfigurations as $config) {
            $config['created_at'] = now();
            $config['updated_at'] = now();
            
            SystemConfiguration::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}
