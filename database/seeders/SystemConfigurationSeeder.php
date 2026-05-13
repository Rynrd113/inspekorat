<?php

namespace Database\Seeders;

use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SystemConfigurationSeeder extends Seeder
{
    private const PROTECTED_GROUPS = ['mail', 'technical'];

    public function run(): void
    {
        $configs = $this->definitions();

        foreach ($configs as $cfg) {
            $record = SystemConfiguration::updateOrCreate(
                ['key' => $cfg['key']],
                [
                    'type'        => $cfg['type'],
                    'group'       => $cfg['group'],
                    'description' => $cfg['description'],
                    'is_public'   => $cfg['is_public'],
                    'updated_by'  => 1,
                ]
            );

            if ($record->wasRecentlyCreated || $record->value === null) {
                $record->update(['value' => $cfg['default_value']]);
            }

            Cache::forget('sys_config_' . $cfg['key']);
        }

        $this->command->info('SystemConfigurationSeeder: ' . count($configs) . ' konfigurasi disinkronisasi.');
    }

    private function definitions(): array
    {
        return [
            // ── IDENTITY ────────────────────────────────────────────────────
            [
                'key'           => 'site_name',
                'default_value' => 'Portal Inspektorat Papua Tengah',
                'type'          => 'string',
                'group'         => 'identity',
                'description'   => 'Nama resmi portal/website yang tampil di tab browser dan header',
                'is_public'     => true,
            ],
            [
                'key'           => 'site_tagline',
                'default_value' => 'Pengawasan yang Profesional dan Berintegritas',
                'type'          => 'string',
                'group'         => 'identity',
                'description'   => 'Tagline atau slogan singkat di bawah nama website',
                'is_public'     => true,
            ],
            [
                'key'           => 'site_logo',
                'default_value' => null,
                'type'          => 'image',
                'group'         => 'identity',
                'description'   => 'Logo utama website — format PNG/SVG, ukuran ideal 200×60 px',
                'is_public'     => true,
            ],
            [
                'key'           => 'favicon',
                'default_value' => null,
                'type'          => 'image',
                'group'         => 'identity',
                'description'   => 'Favicon browser — format ICO/PNG, ukuran 32×32 px',
                'is_public'     => true,
            ],

            // ── CONTACT ─────────────────────────────────────────────────────
            [
                'key'           => 'office_address',
                'default_value' => 'Jl. Ahmad Yani, Karang Tumaritis, Distrik Nabire, Kabupaten Nabire, Papua Tengah 98811',
                'type'          => 'text',
                'group'         => 'contact',
                'description'   => 'Alamat kantor lengkap yang tampil di halaman Kontak',
                'is_public'     => true,
            ],
            [
                'key'           => 'contact_email',
                'default_value' => 'inspektoratprovpt@gmail.com',
                'type'          => 'email',
                'group'         => 'contact',
                'description'   => 'Alamat email resmi kantor yang bisa dihubungi publik',
                'is_public'     => true,
            ],
            [
                'key'           => 'contact_whatsapp',
                'default_value' => null,
                'type'          => 'string',
                'group'         => 'contact',
                'description'   => 'Nomor WhatsApp layanan — format internasional tanpa + (contoh: 6281234567890)',
                'is_public'     => true,
            ],
            [
                'key'           => 'working_hours',
                'default_value' => 'Senin – Jumat: 08:00 – 16:00 WIT',
                'type'          => 'string',
                'group'         => 'contact',
                'description'   => 'Jam operasional kantor yang tampil di halaman Kontak',
                'is_public'     => true,
            ],
            [
                'key'           => 'map_embed_code',
                'default_value' => null,
                'type'          => 'text',
                'group'         => 'contact',
                'description'   => 'Kode embed iframe Google Maps untuk halaman Kontak (salin dari Google Maps → Share → Embed a map)',
                'is_public'     => true,
            ],

            // ── SOCIAL MEDIA ─────────────────────────────────────────────────
            [
                'key'           => 'facebook_url',
                'default_value' => null,
                'type'          => 'url',
                'group'         => 'social',
                'description'   => 'URL lengkap halaman Facebook resmi (contoh: https://facebook.com/inspektorat)',
                'is_public'     => true,
            ],
            [
                'key'           => 'instagram_url',
                'default_value' => 'https://www.instagram.com/inspektoratpapuatengah',
                'type'          => 'url',
                'group'         => 'social',
                'description'   => 'URL lengkap akun Instagram resmi',
                'is_public'     => true,
            ],
            [
                'key'           => 'youtube_url',
                'default_value' => null,
                'type'          => 'url',
                'group'         => 'social',
                'description'   => 'URL lengkap channel YouTube resmi',
                'is_public'     => true,
            ],
            [
                'key'           => 'twitter_url',
                'default_value' => null,
                'type'          => 'url',
                'group'         => 'social',
                'description'   => 'URL lengkap akun Twitter/X resmi',
                'is_public'     => true,
            ],

            // ── MAIL (SMTP) — super_admin only ───────────────────────────────
            [
                'key'           => 'mail_host',
                'default_value' => 'smtp.gmail.com',
                'type'          => 'string',
                'group'         => 'mail',
                'description'   => 'Host server SMTP untuk pengiriman notifikasi email',
                'is_public'     => false,
            ],
            [
                'key'           => 'mail_port',
                'default_value' => '587',
                'type'          => 'integer',
                'group'         => 'mail',
                'description'   => 'Port server SMTP — 587 untuk TLS, 465 untuk SSL',
                'is_public'     => false,
            ],
            [
                'key'           => 'mail_username',
                'default_value' => null,
                'type'          => 'string',
                'group'         => 'mail',
                'description'   => 'Username/alamat email akun SMTP (biasanya sama dengan mail_from_address)',
                'is_public'     => false,
            ],
            [
                'key'           => 'mail_password',
                'default_value' => null,
                'type'          => 'password',
                'group'         => 'mail',
                'description'   => 'Password atau App Password akun SMTP — simpan dengan aman',
                'is_public'     => false,
            ],
            [
                'key'           => 'mail_encryption',
                'default_value' => 'tls',
                'type'          => 'string',
                'group'         => 'mail',
                'description'   => 'Protokol enkripsi SMTP: tls (port 587) atau ssl (port 465)',
                'is_public'     => false,
            ],
            [
                'key'           => 'mail_from_address',
                'default_value' => null,
                'type'          => 'email',
                'group'         => 'mail',
                'description'   => 'Alamat email pengirim yang terlihat oleh penerima (From address)',
                'is_public'     => false,
            ],

            // ── SEO & ANALYTICS ──────────────────────────────────────────────
            [
                'key'           => 'meta_description',
                'default_value' => 'Portal resmi Inspektorat Provinsi Papua Tengah untuk transparansi dan pelayanan publik pengawasan pemerintahan daerah.',
                'type'          => 'text',
                'group'         => 'seo',
                'description'   => 'Meta description untuk SEO — tampil di hasil pencarian Google (maks 160 karakter)',
                'is_public'     => true,
            ],
            [
                'key'           => 'meta_keywords',
                'default_value' => 'inspektorat, papua tengah, pengawasan, pemerintah, transparansi, akuntabilitas',
                'type'          => 'string',
                'group'         => 'seo',
                'description'   => 'Meta keywords untuk SEO — pisahkan setiap kata kunci dengan koma',
                'is_public'     => true,
            ],
            [
                'key'           => 'google_analytics_id',
                'default_value' => null,
                'type'          => 'string',
                'group'         => 'seo',
                'description'   => 'Google Analytics Measurement ID — format: G-XXXXXXXXXX (kosongkan jika belum ada)',
                'is_public'     => false,
            ],

            // ── TECHNICAL — super_admin only ─────────────────────────────────
            [
                'key'           => 'maintenance_mode',
                'default_value' => 'false',
                'type'          => 'boolean',
                'group'         => 'technical',
                'description'   => 'Mode pemeliharaan — aktifkan (true) untuk menampilkan halaman maintenance ke pengunjung',
                'is_public'     => false,
            ],
            [
                'key'           => 'max_upload_size',
                'default_value' => '10',
                'type'          => 'integer',
                'group'         => 'technical',
                'description'   => 'Batas maksimal ukuran file yang dapat diunggah admin dalam satuan MB',
                'is_public'     => false,
            ],
            [
                'key'           => 'footer_copyright_text',
                'default_value' => '© ' . date('Y') . ' Inspektorat Provinsi Papua Tengah. Hak Cipta Dilindungi.',
                'type'          => 'string',
                'group'         => 'technical',
                'description'   => 'Teks hak cipta yang ditampilkan di bagian bawah (footer) setiap halaman website',
                'is_public'     => true,
            ],
        ];
    }
}
