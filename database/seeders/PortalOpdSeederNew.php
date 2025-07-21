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
                'alamat' => 'Jl. Trikora No. 1 Nabire, Papua Tengah',
                'telepon' => '(0984) 21234',
                'email' => 'setda@papuatengah.go.id',
                'website' => 'https://setda.papuatengah.go.id',
                'kepala_opd' => 'Dr. H. Ahmad Syaifudin, S.Sos., M.Si',
                'deskripsi' => 'Sekretariat Daerah Papua Tengah merupakan unsur staf yang membantu Gubernur dalam penyusunan kebijakan dan pengoordinasian administratif terhadap pelaksanaan tugas perangkat daerah.',
                'visi' => 'Terwujudnya Papua Tengah yang Mandiri, Berdaya Saing, dan Sejahtera',
                'misi' => 'Meningkatkan kualitas pelayanan publik. Mengoptimalkan pengelolaan sumber daya daerah. Memperkuat tata kelola pemerintahan yang baik. Mengembangkan kapasitas aparatur pemerintah daerah.',
                'status' => true,
                'urutan' => 1,
            ],
            [
                'nama_opd' => 'Dinas Pendidikan Papua Tengah',
                'singkatan' => 'DISDIK',
                'alamat' => 'Jl. Pendidikan No. 15 Nabire, Papua Tengah',
                'telepon' => '(0984) 21789',
                'email' => 'disdik@papuatengah.go.id',
                'website' => 'https://disdik.papuatengah.go.id',
                'kepala_opd' => 'Dr. Ir. Maria Wambrauw, M.Pd',
                'deskripsi' => 'Dinas Pendidikan Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang pendidikan berdasarkan asas otonomi dan tugas pembantuan.',
                'visi' => 'Terwujudnya Pendidikan Berkualitas dan Berkarakter di Papua Tengah',
                'misi' => 'Meningkatkan akses dan kualitas pendidikan. Mengembangkan sumber daya manusia pendidikan. Memperkuat manajemen pendidikan.',
                'status' => true,
                'urutan' => 2,
            ],
            [
                'nama_opd' => 'Dinas Kesehatan Papua Tengah',
                'singkatan' => 'DINKES',
                'alamat' => 'Jl. Kesehatan No. 20 Nabire, Papua Tengah',
                'telepon' => '(0984) 22123',
                'email' => 'dinkes@papuatengah.go.id',
                'website' => 'https://dinkes.papuatengah.go.id',
                'kepala_opd' => 'dr. John Rumbiak, M.Kes',
                'deskripsi' => 'Dinas Kesehatan Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang kesehatan.',
                'visi' => 'Terwujudnya Masyarakat Papua Tengah yang Sehat',
                'misi' => 'Meningkatkan derajat kesehatan masyarakat. Mengembangkan pelayanan kesehatan yang berkualitas. Memperkuat sistem kesehatan daerah.',
                'status' => true,
                'urutan' => 3,
            ],
            [
                'nama_opd' => 'Dinas PUPR Papua Tengah',
                'singkatan' => 'PUPR',
                'alamat' => 'Jl. Pembangunan No. 25 Nabire, Papua Tengah',
                'telepon' => '(0984) 22345',
                'email' => 'pupr@papuatengah.go.id',
                'website' => 'https://pupr.papuatengah.go.id',
                'kepala_opd' => 'Ir. Robert Mandosir, M.T',
                'deskripsi' => 'Dinas PUPR Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang pekerjaan umum dan penataan ruang.',
                'visi' => 'Terwujudnya Infrastruktur yang Berkualitas dan Penataan Ruang yang Berkelanjutan',
                'misi' => 'Mengembangkan infrastruktur yang berkualitas dan berkelanjutan. Melaksanakan penataan ruang yang optimal. Meningkatkan kualitas sumber daya manusia bidang PUPR.',
                'status' => true,
                'urutan' => 4,
            ],
            [
                'nama_opd' => 'Inspektorat Papua Tengah',
                'singkatan' => 'INSPEKTORAT',
                'alamat' => 'Jl. Trikora No. 45 Nabire, Papua Tengah',
                'telepon' => '(0984) 21567',
                'email' => 'inspektorat@papuatengah.go.id',
                'website' => 'https://inspektorat.papuatengah.go.id',
                'kepala_opd' => 'Drs. Paulus Kambu, M.Si',
                'deskripsi' => 'Inspektorat Papua Tengah merupakan unsur pengawas yang bertugas menyelenggarakan pengawasan internal di lingkungan Pemerintah Provinsi Papua Tengah.',
                'visi' => 'Terwujudnya Pengawasan Internal yang Profesional dan Akuntabel',
                'misi' => 'Melaksanakan pengawasan internal yang berkualitas. Memberikan assurance dan consulting yang optimal. Meningkatkan kapasitas pengawasan internal. Memperkuat sistem pengendalian internal pemerintah.',
                'status' => true,
                'urutan' => 5,
            ],
            [
                'nama_opd' => 'Dinas Sosial Papua Tengah',
                'singkatan' => 'DINSOS',
                'alamat' => 'Jl. Sosial No. 30 Nabire, Papua Tengah',
                'telepon' => '(0984) 22567',
                'email' => 'dinsos@papuatengah.go.id',
                'website' => 'https://dinsos.papuatengah.go.id',
                'kepala_opd' => 'Dra. Sarah Yumte, M.AP',
                'deskripsi' => 'Dinas Sosial Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang sosial.',
                'visi' => 'Terwujudnya Kesejahteraan Sosial Masyarakat Papua Tengah',
                'misi' => 'Meningkatkan kualitas pelayanan sosial. Memberdayakan masyarakat rentan. Mengembangkan program perlindungan sosial.',
                'status' => true,
                'urutan' => 6,
            ],
            [
                'nama_opd' => 'Dinas Tenaga Kerja Papua Tengah',
                'singkatan' => 'DISNAKER',
                'alamat' => 'Jl. Kerja No. 35 Nabire, Papua Tengah',
                'telepon' => '(0984) 22789',
                'email' => 'disnaker@papuatengah.go.id',
                'website' => 'https://disnaker.papuatengah.go.id',
                'kepala_opd' => 'Ir. Anton Wambrauw, M.M',
                'deskripsi' => 'Dinas Tenaga Kerja Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang ketenagakerjaan.',
                'visi' => 'Terwujudnya Tenaga Kerja Papua Tengah yang Kompeten dan Produktif',
                'misi' => 'Meningkatkan kompetensi tenaga kerja. Memperluas kesempatan kerja. Memberikan perlindungan tenaga kerja.',
                'status' => true,
                'urutan' => 7,
            ],
            [
                'nama_opd' => 'Dinas Pariwisata Papua Tengah',
                'singkatan' => 'DISPAR',
                'alamat' => 'Jl. Wisata No. 40 Nabire, Papua Tengah',
                'telepon' => '(0984) 23012',
                'email' => 'dispar@papuatengah.go.id',
                'website' => 'https://dispar.papuatengah.go.id',
                'kepala_opd' => 'Drs. Michael Kambuaya, M.Par',
                'deskripsi' => 'Dinas Pariwisata Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang pariwisata.',
                'visi' => 'Terwujudnya Papua Tengah sebagai Destinasi Wisata Berkelanjutan',
                'misi' => 'Mengembangkan destinasi wisata unggulan. Meningkatkan promosi pariwisata. Memberdayakan masyarakat di bidang pariwisata.',
                'status' => true,
                'urutan' => 8,
            ],
            [
                'nama_opd' => 'Dinas Lingkungan Hidup Papua Tengah',
                'singkatan' => 'DLH',
                'alamat' => 'Jl. Lingkungan No. 45 Nabire, Papua Tengah',
                'telepon' => '(0984) 23234',
                'email' => 'dlh@papuatengah.go.id',
                'website' => 'https://dlh.papuatengah.go.id',
                'kepala_opd' => 'Dr. Ir. Elisabeth Rumbiak, M.Si',
                'deskripsi' => 'Dinas Lingkungan Hidup Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang lingkungan hidup.',
                'visi' => 'Terwujudnya Lingkungan Hidup Papua Tengah yang Lestari',
                'misi' => 'Melindungi dan melestarikan lingkungan hidup. Mengembangkan pengelolaan lingkungan berkelanjutan. Meningkatkan kesadaran lingkungan masyarakat.',
                'status' => true,
                'urutan' => 9,
            ],
            [
                'nama_opd' => 'Badan Perencanaan Pembangunan Daerah Papua Tengah',
                'singkatan' => 'BAPPEDA',
                'alamat' => 'Jl. Perencanaan No. 50 Nabire, Papua Tengah',
                'telepon' => '(0984) 23456',
                'email' => 'bappeda@papuatengah.go.id',
                'website' => 'https://bappeda.papuatengah.go.id',
                'kepala_opd' => 'Dr. Ir. David Mandosir, M.T',
                'deskripsi' => 'Bappeda Papua Tengah bertugas melaksanakan perencanaan pembangunan daerah.',
                'visi' => 'Terwujudnya Perencanaan Pembangunan yang Terintegrasi dan Berkelanjutan',
                'misi' => 'Menyusun rencana pembangunan daerah yang berkualitas. Mengkoordinasikan perencanaan pembangunan. Melakukan monitoring dan evaluasi pembangunan.',
                'status' => true,
                'urutan' => 10,
            ]
        ];

        foreach ($opds as $opd) {
            PortalOpd::create($opd);
        }
    }
}
