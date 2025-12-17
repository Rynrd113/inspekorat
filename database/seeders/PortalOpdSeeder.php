<?php

namespace Database\Seeders;

use App\Models\PortalOpd;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortalOpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if data already exists
        if (PortalOpd::count() > 0) {
            return;
        }

        $admin = User::select(['id', 'name', 'email', 'role'])
            ->where('role', 'super_admin')
            ->first();
        
                $opds = [
            [
                'nama_opd' => 'Sekretariat Daerah Papua Tengah',
                'singkatan' => 'SETDA',
                'deskripsi' => 'Sekretariat Daerah Papua Tengah adalah unsur staf yang membantu Gubernur dalam penyusunan kebijakan dan koordinasi yang dipimpin oleh Sekretaris Daerah.',
                'alamat' => 'Jl. Pemuda No. 1, Nabire, Papua Tengah',
                'telepon' => '0984-21001',
                'email' => 'setda@papuatengah.go.id',
                'website' => 'https://setda.papuatengah.go.id',
                'kepala_opd' => 'Drs. Yan Adi Panai, M.AP',
                'visi' => 'Terwujudnya Papua Tengah yang Maju, Mandiri dan Bermartabat',
                'misi' => [
                    'Meningkatkan kualitas pelayanan publik yang profesional dan berintegritas',
                    'Memperkuat koordinasi antar OPD untuk sinergi pembangunan'
                ],
                'tugas_fungsi' => 'Membantu Gubernur dalam penyelenggaraan pemerintahan daerah',
                'status' => true,
                'urutan' => 1,
            ],
            [
                'nama_opd' => 'Dinas Pendidikan dan Kebudayaan',
                'singkatan' => 'DISDIKBUD',
                'deskripsi' => 'Dinas Pendidikan dan Kebudayaan Papua Tengah bertugas menyelenggarakan urusan pemerintahan daerah bidang pendidikan dan kebudayaan.',
                'alamat' => 'Jl. Pendidikan No. 15, Nabire, Papua Tengah',
                'telepon' => '0984-21015',
                'email' => 'disdikbud@papuatengah.go.id',
                'website' => 'https://disdikbud.papuatengah.go.id',
                'kepala_opd' => 'Dr. Maria Yolanda, M.Pd',
                'visi' => 'Terwujudnya pendidikan bermutu dan berkarakter',
                'misi' => [
                    'Meningkatkan akses dan mutu pendidikan untuk semua',
                    'Mengembangkan pendidikan karakter dan budaya lokal',
                    'Meningkatkan kompetensi tenaga pendidik'
                ],
                'tugas_fungsi' => 'Menyelenggarakan pendidikan dasar, menengah, dan kebudayaan',
                'status' => true,
                'urutan' => 2,
            ],
            [
                'nama_opd' => 'Dinas Kesehatan',
                'singkatan' => 'DINKES',
                'deskripsi' => 'Dinas Kesehatan Papua Tengah bertugas menyelenggarakan urusan pemerintahan daerah bidang kesehatan.',
                'alamat' => 'Jl. Kesehatan No. 20, Nabire, Papua Tengah',
                'telepon' => '0984-21020',
                'email' => 'dinkes@papuatengah.go.id',
                'website' => 'https://dinkes.papuatengah.go.id',
                'kepala_opd' => 'dr. Michael Kareth, M.Kes',
                'visi' => 'Papua Tengah Sehat dan Mandiri',
                'misi' => [
                    'Meningkatkan derajat kesehatan masyarakat Papua Tengah',
                    'Memperkuat sistem pelayanan kesehatan dasar',
                    'Mengembangkan pencegahan dan penanggulangan penyakit'
                ],
                'tugas_fungsi' => 'Menyelenggarakan upaya kesehatan masyarakat',
                'status' => true,
                'urutan' => 3,
            ],
            [
                'nama_opd' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'singkatan' => 'DPUPR',
                'deskripsi' => 'Dinas Pekerjaan Umum dan Penataan Ruang bertugas menyelenggarakan urusan pemerintahan bidang pekerjaan umum dan penataan ruang.',
                'alamat' => 'Jl. Pembangunan No. 25, Nabire, Papua Tengah',
                'telepon' => '0984-21025',
                'email' => 'dpupr@papuatengah.go.id',
                'website' => 'https://dpupr.papuatengah.go.id',
                'kepala_opd' => 'Ir. Robertus Daby, M.T',
                'visi' => 'Infrastruktur yang Berkualitas untuk Papua Tengah',
                'misi' => [
                    'Membangun infrastruktur yang berkelanjutan',
                    'Meningkatkan kualitas jalan dan jembatan',
                    'Memperkuat penataan ruang wilayah'
                ],
                'tugas_fungsi' => 'Menyelenggarakan pembangunan infrastruktur wilayah',
                'status' => true,
                'urutan' => 4,
            ],
            [
                'nama_opd' => 'Dinas Sosial',
                'singkatan' => 'DINSOS',
                'deskripsi' => 'Dinas Sosial Papua Tengah bertugas menyelenggarakan urusan pemerintahan daerah bidang sosial.',
                'alamat' => 'Jl. Sosial No. 30, Nabire, Papua Tengah',
                'telepon' => '0984-21030',
                'email' => 'dinsos@papuatengah.go.id',
                'website' => 'https://dinsos.papuatengah.go.id',
                'kepala_opd' => 'Dra. Elisabeth Rumbiak, M.AP',
                'visi' => 'Kesejahteraan Sosial untuk Seluruh Masyarakat',
                'misi' => [
                    'Meningkatkan kesejahteraan sosial masyarakat',
                    'Melindungi kelompok rentan dan disabilitas',
                    'Mengembangkan program pemberdayaan sosial'
                ],
                'tugas_fungsi' => 'Menyelenggarakan rehabilitasi dan bantuan sosial',
                'status' => true,
                'urutan' => 5,
            ],
            [
                'nama_opd' => 'Dinas Pertanian dan Ketahanan Pangan',
                'singkatan' => 'DPKP',
                'deskripsi' => 'Dinas Pertanian dan Ketahanan Pangan bertugas menyelenggarakan urusan pemerintahan bidang pertanian dan ketahanan pangan.',
                'alamat' => 'Jl. Pertanian No. 35, Nabire, Papua Tengah',
                'telepon' => '0984-21035',
                'email' => 'dpkp@papuatengah.go.id',
                'website' => 'https://dpkp.papuatengah.go.id',
                'kepala_opd' => 'Ir. Yohanis Imbiri, M.P',
                'visi' => 'Papua Tengah Mandiri Pangan',
                'misi' => [
                    'Meningkatkan produktivitas dan ketahanan pangan',
                    'Mengembangkan pertanian organik dan berkelanjutan',
                    'Memperkuat sistem distribusi pangan lokal'
                ],
                'tugas_fungsi' => 'Mengembangkan sektor pertanian berkelanjutan',
                'status' => true,
                'urutan' => 6,
            ],
            [
                'nama_opd' => 'Dinas Pariwisata dan Ekonomi Kreatif',
                'singkatan' => 'DISPAREKRAF',
                'deskripsi' => 'Dinas Pariwisata dan Ekonomi Kreatif bertugas mengembangkan potensi pariwisata dan ekonomi kreatif Papua Tengah.',
                'alamat' => 'Jl. Wisata No. 40, Nabire, Papua Tengah',
                'telepon' => '0984-21040',
                'email' => 'disparekraf@papuatengah.go.id',
                'website' => 'https://disparekraf.papuatengah.go.id',
                'kepala_opd' => 'Drs. Tommy Kambu, M.Par',
                'visi' => 'Papua Tengah Destinasi Wisata Unggulan',
                'misi' => [
                    'Mengembangkan pariwisata berkelanjutan',
                    'Mempromosikan destinasi wisata lokal',
                    'Memberdayakan ekonomi kreatif masyarakat'
                ],
                'tugas_fungsi' => 'Mengembangkan destinasi dan industri pariwisata',
                'status' => true,
                'urutan' => 7,
            ],
            [
                'nama_opd' => 'Dinas Lingkungan Hidup',
                'singkatan' => 'DLH',
                'deskripsi' => 'Dinas Lingkungan Hidup Papua Tengah bertugas menyelenggarakan urusan pemerintahan bidang lingkungan hidup.',
                'alamat' => 'Jl. Lingkungan No. 45, Nabire, Papua Tengah',
                'telepon' => '0984-21045',
                'email' => 'dlh@papuatengah.go.id',
                'website' => 'https://dlh.papuatengah.go.id',
                'kepala_opd' => 'Ir. Agustina Murip, M.Si',
                'visi' => 'Lingkungan Hidup Papua Tengah yang Lestari',
                'misi' => [
                    'Menjaga kelestarian lingkungan hidup',
                    'Mencegah pencemaran dan kerusakan lingkungan',
                    'Memulihkan kualitas lingkungan yang rusak'
                ],
                'tugas_fungsi' => 'Menyelenggarakan perlindungan dan pengelolaan lingkungan',
                'status' => true,
                'urutan' => 8,
            ],
            [
                'nama_opd' => 'Dinas Komunikasi dan Informatika',
                'singkatan' => 'DISKOMINFO',
                'deskripsi' => 'Dinas Komunikasi dan Informatika bertugas menyelenggarakan urusan pemerintahan bidang komunikasi dan informatika.',
                'alamat' => 'Jl. Teknologi No. 50, Nabire, Papua Tengah',
                'telepon' => '0984-21050',
                'email' => 'diskominfo@papuatengah.go.id',
                'website' => 'https://diskominfo.papuatengah.go.id',
                'kepala_opd' => 'Dr. Eng. Freddy Numberi, M.Kom',
                'visi' => 'Smart Government Papua Tengah',
                'misi' => [
                    'Mewujudkan pemerintahan digital',
                    'Meningkatkan literasi digital masyarakat',
                    'Mengembangkan infrastruktur TIK daerah'
                ],
                'tugas_fungsi' => 'Mengembangkan sistem informasi pemerintahan',
                'status' => true,
                'urutan' => 9,
            ],
            [
                'nama_opd' => 'Inspektorat Daerah',
                'singkatan' => 'INSPEKTORAT',
                'deskripsi' => 'Inspektorat Daerah Papua Tengah adalah unsur pengawas penyelenggaraan pemerintahan daerah.',
                'alamat' => 'JGG4+65R, Jl. Ahmad Yani, Karang Tumaritis, Distrik Nabire, Kabupaten Nabire, Papua Tengah 98811',
                'telepon' => '0984-21055',
                'email' => 'inspektorat@papuatengah.go.id',
                'website' => 'https://inspektorat.papuatengah.go.id',
                'kepala_opd' => 'Dr. Alexander Rumbiak, M.AP',
                'visi' => 'Pengawasan yang Akuntabel dan Transparan',
                'misi' => [
                    'Mewujudkan pengawasan yang efektif',
                    'Meningkatkan akuntabilitas penyelenggaraan pemerintahan',
                    'Mencegah dan memberantas korupsi'
                ],
                'tugas_fungsi' => 'Melaksanakan pengawasan intern pemerintah daerah',
                'status' => true,
                'urutan' => 10,
            ]
        ];

        foreach ($opds as $opd) {
            PortalOpd::create($opd);
        }
    }
}
