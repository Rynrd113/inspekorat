<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use Illuminate\Database\Seeder;

class HeroSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'judul' => 'Whistleblower System',
                'subjudul' => 'Laporkan Dugaan Pelanggaran Secara Aman',
                'deskripsi' => 'Sistem pelaporan pelanggaran yang aman dan terjamin kerahasiaannya. Kami menjamin perlindungan identitas pelapor.',
                'link_url' => route('public.wbs'),
                'link_text' => 'Lapor Sekarang',
                'prioritas' => 'urgent',
                'kategori' => 'layanan',
                'status' => 'published',
                'urutan' => 1,
                'is_active' => true,
            ],
            [
                'judul' => 'Transparansi & Akuntabilitas',
                'subjudul' => 'Pengawasan Internal Pemerintah Provinsi Papua Tengah',
                'deskripsi' => 'Mewujudkan tata kelola pemerintahan yang bersih, transparan, dan akuntabel melalui pengawasan internal yang profesional.',
                'link_url' => route('public.profil'),
                'link_text' => 'Selengkapnya',
                'prioritas' => 'tinggi',
                'kategori' => 'pengumuman',
                'status' => 'published',
                'urutan' => 2,
                'is_active' => true,
            ],
            [
                'judul' => 'Portal OPD Papua Tengah',
                'subjudul' => 'Informasi Organisasi Perangkat Daerah',
                'deskripsi' => 'Akses informasi lengkap tentang OPD di lingkungan Pemerintah Provinsi Papua Tengah secara digital dan terintegrasi.',
                'link_url' => route('public.portal-opd.index'),
                'link_text' => 'Lihat Portal OPD',
                'prioritas' => 'normal',
                'kategori' => 'layanan',
                'status' => 'published',
                'urutan' => 3,
                'is_active' => true,
            ],
            [
                'judul' => 'Layanan Pengaduan Masyarakat',
                'subjudul' => 'Sampaikan Keluhan & Saran Anda',
                'deskripsi' => 'Kami siap mendengarkan keluhan, saran, dan masukan Anda untuk perbaikan pelayanan publik yang lebih baik.',
                'link_url' => route('public.pengaduan'),
                'link_text' => 'Buat Pengaduan',
                'prioritas' => 'normal',
                'kategori' => 'layanan',
                'status' => 'published',
                'urutan' => 4,
                'is_active' => true,
            ],
            [
                'judul' => 'Berita & Kegiatan Terkini',
                'subjudul' => 'Informasi Terbaru Inspektorat Papua Tengah',
                'deskripsi' => 'Ikuti update berita, kegiatan, dan program-program pengawasan yang dilaksanakan oleh Inspektorat Provinsi Papua Tengah.',
                'link_url' => route('public.berita.index'),
                'link_text' => 'Lihat Berita',
                'prioritas' => 'normal',
                'kategori' => 'berita',
                'status' => 'published',
                'urutan' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::create($slider);
        }

        $this->command->info('✅ Hero Sliders seeded successfully!');
    }
}
