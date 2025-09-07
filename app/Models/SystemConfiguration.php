<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
        'is_public',
        'updated_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'value' => 'json'
    ];

    /**
     * Get the user who last updated this configuration
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get configuration by key
     */
    public static function get($key, $default = null)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    /**
     * Set configuration value
     */
    public static function set($key, $value, $type = 'string', $description = null, $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
                'group' => $group,
                'updated_by' => auth()->id()
            ]
        );
    }

    /**
     * Get configurations by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key');
    }

    /**
     * Get public configurations
     */
    public static function getPublic()
    {
        return self::where('is_public', true)->get()->pluck('value', 'key');
    }

    /**
     * Get available configuration groups
     */
    public static function getGroups()
    {
        return [
            'general' => 'Umum',
            'site' => 'Situs',
            'branding' => 'Branding & Visual',
            'email' => 'Email',
            'security' => 'Keamanan',
            'backup' => 'Backup',
            'performance' => 'Performa',
            'social' => 'Media Sosial',
            'api' => 'API',
            'maintenance' => 'Pemeliharaan'
        ];
    }

    /**
     * Get configuration types
     */
    public static function getTypes()
    {
        return [
            'string' => 'Teks',
            'text' => 'Teks Panjang',
            'number' => 'Angka',
            'boolean' => 'Ya/Tidak',
            'json' => 'JSON',
            'array' => 'Array',
            'file' => 'File',
            'image' => 'Gambar',
            'url' => 'URL',
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    /**
     * Get formatted value based on type
     */
    public function getFormattedValueAttribute()
    {
        return match($this->type) {
            'boolean' => $this->value ? 'Ya' : 'Tidak',
            'json', 'array' => json_encode($this->value, JSON_PRETTY_PRINT),
            'password' => '••••••••',
            default => $this->value
        };
    }

    /**
     * Scope for specific group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope for public configurations
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Initialize default configurations
     */
    public static function initializeDefaults()
    {
        $defaults = [
            // Site configurations
            'site_name' => ['Portal Inspektorat Papua Tengah', 'string', 'Nama situs', 'site', true],
            'site_description' => ['Portal informasi dan layanan publik resmi Inspektorat Provinsi Papua Tengah', 'text', 'Deskripsi situs', 'site', true],
            'site_keywords' => ['inspektorat, papua tengah, pemerintah, pengawasan, transparansi', 'string', 'Keywords SEO', 'site', true],
            'site_address' => [null, 'text', 'Alamat kantor', 'site', true],
            'site_phone' => [null, 'string', 'Telepon kantor', 'site', true],
            'site_email' => [null, 'email', 'Email kantor', 'site', true],
            'site_working_hours' => ['Senin - Jumat: 08:00 - 16:00', 'string', 'Jam kerja', 'site', true],
            
            // Branding & Visual configurations
            'brand_logo_header' => [null, 'image', 'Logo untuk header (ukuran: 200x60px)', 'branding', true],
            'brand_logo_footer' => [null, 'image', 'Logo untuk footer (ukuran: 120x40px)', 'branding', true],
            'brand_logo_icon' => [null, 'image', 'Logo icon/symbol (ukuran: 64x64px)', 'branding', true],
            'brand_favicon' => [null, 'image', 'Favicon website (ukuran: 32x32px)', 'branding', true],
            'brand_primary_color' => ['#1e40af', 'string', 'Warna utama (format: #hex)', 'branding', true],
            'brand_secondary_color' => ['#059669', 'string', 'Warna sekunder (format: #hex)', 'branding', true],
            'brand_accent_color' => ['#dc2626', 'string', 'Warna aksen (format: #hex)', 'branding', true],
            'brand_gradient_start' => ['#1e40af', 'string', 'Warna awal gradient (format: #hex)', 'branding', false],
            'brand_gradient_end' => ['#3730a3', 'string', 'Warna akhir gradient (format: #hex)', 'branding', false],
            'brand_theme_mode' => ['light', 'string', 'Mode tema default (light/dark)', 'branding', false],
            
            // Social Media configurations
            'social_facebook_url' => [null, 'url', 'URL halaman Facebook resmi', 'social', true],
            'social_twitter_url' => [null, 'url', 'URL halaman Twitter resmi', 'social', true],
            'social_instagram_url' => [null, 'url', 'URL halaman Instagram resmi', 'social', true],
            'social_youtube_url' => [null, 'url', 'URL channel YouTube resmi', 'social', true],
            'social_linkedin_url' => [null, 'url', 'URL halaman LinkedIn resmi', 'social', true],
            'social_tiktok_url' => [null, 'url', 'URL halaman TikTok resmi', 'social', true],
            'social_whatsapp_number' => [null, 'string', 'Nomor WhatsApp layanan (format: 628xxx)', 'social', true],
            'social_telegram_url' => [null, 'url', 'URL channel Telegram resmi', 'social', true],
            'social_share_enabled' => [true, 'boolean', 'Aktifkan tombol share sosial media', 'social', false],
            
            // Security configurations
            'max_login_attempts' => [5, 'number', 'Maksimal percobaan login', 'security', false],
            'session_timeout' => [120, 'number', 'Timeout sesi (menit)', 'security', false],
            'password_min_length' => [8, 'number', 'Panjang minimal password', 'security', false],
            'require_email_verification' => [false, 'boolean', 'Verifikasi email diperlukan', 'security', false],
            
            // Content configurations
            'content_approval_required' => [false, 'boolean', 'Persetujuan konten diperlukan', 'general', false],
            'auto_publish_news' => [true, 'boolean', 'Publikasi berita otomatis', 'general', false],
            'max_file_upload_size' => [10, 'number', 'Maksimal ukuran file upload (MB)', 'general', false],
            'allowed_file_types' => [['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'], 'array', 'Tipe file yang diizinkan', 'general', false],
            
            // Performance configurations
            'cache_enabled' => [true, 'boolean', 'Cache diaktifkan', 'performance', false],
            'cache_duration' => [60, 'number', 'Durasi cache (menit)', 'performance', false],
            
            // Maintenance
            'maintenance_mode' => [false, 'boolean', 'Mode pemeliharaan', 'maintenance', false],
            'maintenance_message' => ['Situs sedang dalam pemeliharaan. Silakan coba lagi nanti.', 'text', 'Pesan pemeliharaan', 'maintenance', false],
        ];

        foreach ($defaults as $key => [$value, $type, $description, $group, $isPublic]) {
            self::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $type,
                    'description' => $description,
                    'group' => $group,
                    'is_public' => $isPublic,
                    'updated_by' => 1 // Assuming super admin has ID 1
                ]
            );
        }
    }
}
