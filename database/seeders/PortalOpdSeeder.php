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
        $admin = User::where('role', 'superadmin')->first();
        
        $opds = [
            [
                'nama_opd' => 'Sekretariat Daerah Papua Tengah',
                'singkatan' => 'SETDA',
                'alamat' => 'Jl. Trikora No. 1 Nabire, Papua Tengah',
                'telepon' => '(0984) 21234',
                'email' => 'setda@papuatengah.go.id',
                'website' => 'https://setda.papuatengah.go.id',
                'kepala_opd' => 'Dr. H. Ahmad Syaifudin, S.Sos., M.Si',
                'nip_kepala' => '196805101990031002',
                'deskripsi' => 'Sekretariat Daerah Papua Tengah merupakan unsur staf yang membantu Gubernur dalam penyusunan kebijakan dan pengoordinasian administratif terhadap pelaksanaan tugas perangkat daerah.',
                'visi' => 'Terwujudnya Papua Tengah yang Mandiri, Berdaya Saing, dan Sejahtera',
                'misi' => [
                    'Meningkatkan kualitas pelayanan publik',
                    'Mengoptimalkan pengelolaan sumber daya daerah',
                    'Memperkuat tata kelola pemerintahan yang baik',
                    'Mengembangkan kapasitas aparatur pemerintah daerah'
                ],
                'status' => true,
                'created_by' => $admin?->id,
            ],
            [
                'nama_opd' => 'Dinas Pendidikan Papua Tengah',
                'singkatan' => 'DISDIK',
                'alamat' => 'Jl. Pendidikan No. 15 Nabire, Papua Tengah',
                'telepon' => '(0984) 21567',
                'email' => 'disdik@papuatengah.go.id',
                'website' => 'https://disdik.papuatengah.go.id',
                'kepala_opd' => 'Prof. Dr. Maria Josephine, M.Pd',
                'nip_kepala' => '197203151995032001',
                'deskripsi' => 'Dinas Pendidikan Papua Tengah bertugas melaksanakan urusan pemerintahan daerah di bidang pendidikan berdasarkan asas otonomi dan tugas pembantuan.',
                'visi' => 'Terwujudnya Pendidikan Berkualitas, Berkarakter, dan Berdaya Saing',
                'misi' => [
                    'Menyediakan layanan pendidikan yang berkualitas dan merata',
                    'Mengembangkan tenaga pendidik dan kependidikan yang profesional',
                    'Memperkuat pendidikan karakter dan budaya lokal',
                    'Meningkatkan infrastruktur dan fasilitas pendidikan'
                ],
                'status' => true,
                'created_by' => $admin?->id,
            ],
            [
                'nama_opd' => 'Dinas Kesehatan Papua Tengah',
                'singkatan' => 'DINKES',
                'alamat' => 'Jl. Kesehatan No. 8 Nabire, Papua Tengah',
                'telepon' => '(0984) 21890',
                'email' => 'dinkes@papuatengah.go.id',
                'website' => 'https://dinkes.papuatengah.go.id',
                'kepala_opd' => 'dr. Michael Tanggela, Sp.PD., M.Kes',
                'nip_kepala' => '198006151998031003',
                'deskripsi' => 'Dinas Kesehatan Papua Tengah bertanggung jawab dalam penyelenggaraan urusan pemerintahan daerah bidang kesehatan.',
                'visi' => 'Terwujudnya Masyarakat Papua Tengah yang Sehat, Mandiri, dan Berkeadilan',
                'misi' => [
                    'Meningkatkan akses dan kualitas pelayanan kesehatan',
                    'Mengembangkan sistem kesehatan yang berkelanjutan',
                    'Memperkuat promosi kesehatan dan pencegahan penyakit',
                    'Meningkatkan kapasitas sumber daya kesehatan'
                ],
                'status' => true,
                'created_by' => $admin?->id,
            ],
            [
                'nama_opd' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'singkatan' => 'PUPR',
                'alamat' => 'Jl. Pembangunan No. 25 Nabire, Papua Tengah',
                'telepon' => '(0984) 22345',
                'email' => 'pupr@papuatengah.go.id',
                'website' => 'https://pupr.papuatengah.go.id',
                'kepala_opd' => 'Ir. Robert Mandosir, M.T',
                'nip_kepala' => '197509201998031004',
                'deskripsi' => 'Dinas PUPR Papua Tengah bertugas melaksanakan urusan pemerintahan daerah bidang pekerjaan umum dan penataan ruang.',
                'visi' => 'Terwujudnya Infrastruktur yang Berkualitas dan Penataan Ruang yang Berkelanjutan',
                'misi' => [
                    'Mengembangkan infrastruktur yang berkualitas dan berkelanjutan',
                    'Melaksanakan penataan ruang yang optimal',
                    'Meningkatkan akses dan konektivitas wilayah',
                    'Memperkuat kapasitas teknis bidang PUPR'
                ],
                'status' => true,
                'created_by' => $admin?->id,
            ],
            [
                'nama_opd' => 'Inspektorat Papua Tengah',
                'singkatan' => 'INSPEKTORAT',
                'alamat' => 'Jl. Pengawasan No. 10 Nabire, Papua Tengah',
                'telepon' => '(0984) 21111',
                'email' => 'inspektorat@papuatengah.go.id',
                'website' => 'https://inspektorat.papuatengah.go.id',
                'kepala_opd' => 'Drs. Samuel Waromi, M.AP',
                'nip_kepala' => '196512251990031005',
                'deskripsi' => 'Inspektorat Papua Tengah merupakan unsur pengawas yang bertugas menyelenggarakan pengawasan internal di lingkungan Pemerintah Provinsi Papua Tengah.',
                'visi' => 'Terwujudnya Pengawasan Internal yang Profesional dan Akuntabel',
                'misi' => [
                    'Melaksanakan pengawasan internal yang berkualitas',
                    'Memberikan assurance dan consulting yang optimal',
                    'Meningkatkan kapasitas pengawasan internal',
                    'Memperkuat sistem pengendalian internal pemerintah'
                ],
                'status' => true,
                'created_by' => $admin?->id,
            ]
        ];

        foreach ($opds as $opd) {
            PortalOpd::updateOrCreate(
                ['nama_opd' => $opd['nama_opd']], 
                $opd
            );
        }
    }
}
