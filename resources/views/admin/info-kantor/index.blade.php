@extends('layouts.admin')

@section('title', 'Informasi Kantor')

@section('header', 'Informasi Kantor')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Informasi Kantor</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Informasi Kantor</h1>
            <p class="text-gray-600">Kelola informasi kontak dan detail kantor</p>
        </div>
        <div class="flex items-center space-x-3">
            @if(auth()->user()->hasAnyRole(['admin', 'superadmin']))
            <a href="{{ route('admin.info-kantor.edit') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Informasi
            </a>
            @endif
        </div>
    </div>

    <!-- Office Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-address-card mr-2 text-blue-600"></i>
                    Informasi Kontak
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-building text-gray-400 mt-1"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Nama Instansi</h3>
                        <p class="text-gray-600">{{ $info->nama_instansi ?? 'Inspektorat Provinsi Papua Tengah' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Alamat</h3>
                        <p class="text-gray-600">{{ $info->alamat ?? 'JGG4+65R, Jl. Ahmad Yani, Karang Tumaritis, Distrik Nabire, Kabupaten Nabire, Papua Tengah 98811' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-phone text-gray-400 mt-1"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Telepon</h3>
                        <p class="text-gray-600">{{ $info->telepon ?? '+62 XXX-XXXX-XXXX' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-fax text-gray-400 mt-1"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Fax</h3>
                        <p class="text-gray-600">{{ $info->fax ?? '+62 XXX-XXXX-XXXX' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-envelope text-gray-400 mt-1"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Email</h3>
                        <p class="text-gray-600">{{ $info->email ?? 'info@inspektorat.papuatengah.go.id' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <i class="fas fa-globe text-gray-400 mt-1"></i>
                    <div>
                        <h3 class="font-medium text-gray-900">Website</h3>
                        <p class="text-gray-600">{{ $info->website ?? 'https://inspektorat.papuatengah.go.id' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Office Hours -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clock mr-2 text-green-600"></i>
                    Jam Operasional
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Senin - Kamis</span>
                    <span class="font-medium text-gray-900">{{ $info->jam_senin_kamis ?? '08:00 - 16:00' }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Jumat</span>
                    <span class="font-medium text-gray-900">{{ $info->jam_jumat ?? '08:00 - 16:30' }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Sabtu - Minggu</span>
                    <span class="font-medium text-gray-900">{{ $info->jam_weekend ?? 'Tutup' }}</span>
                </div>
                
                <div class="border-t pt-4">
                    <h3 class="font-medium text-gray-900 mb-2">Jam Istirahat</h3>
                    <p class="text-gray-600">{{ $info->jam_istirahat ?? '12:00 - 13:00' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leadership -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-users text-purple-600 mr-2"></i>
                Pimpinan
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $leaders = [
                    [
                        'nama' => $info->kepala_instansi ?? 'Nama Kepala Instansi',
                        'jabatan' => 'Kepala Inspektorat',
                        'foto' => $info->foto_kepala ?? null,
                        'nip' => $info->nip_kepala ?? 'NIP. XXXXXXXXXXXXXXXXX'
                    ],
                    [
                        'nama' => $info->sekretaris ?? 'Nama Sekretaris',
                        'jabatan' => 'Sekretaris',
                        'foto' => $info->foto_sekretaris ?? null,
                        'nip' => $info->nip_sekretaris ?? 'NIP. XXXXXXXXXXXXXXXXX'
                    ],
                    [
                        'nama' => $info->inspektur_1 ?? 'Nama Inspektur 1',
                        'jabatan' => 'Inspektur Pembantu Wilayah I',
                        'foto' => $info->foto_inspektur_1 ?? null,
                        'nip' => $info->nip_inspektur_1 ?? 'NIP. XXXXXXXXXXXXXXXXX'
                    ]
                ];
                @endphp
                
                @foreach($leaders as $leader)
                <div class="text-center">
                    <div class="relative inline-block mb-4">
                        @if($leader['foto'])
                        <img src="{{ asset('storage/' . $leader['foto']) }}" 
                             alt="{{ $leader['nama'] }}" 
                             class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-gray-200">
                        @else
                        <div class="w-24 h-24 rounded-full mx-auto bg-gray-200 flex items-center justify-center border-4 border-gray-300">
                            <i class="fas fa-user text-gray-400 text-2xl"></i>
                        </div>
                        @endif
                    </div>
                    <h3 class="font-medium text-gray-900">{{ $leader['nama'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $leader['jabatan'] }}</p>
                    <p class="text-xs text-gray-500">{{ $leader['nip'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Map and Additional Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Map -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-map text-red-600 mr-2"></i>
                    Lokasi Kantor
                </h2>
            </div>
            <div class="p-6">
                @if(isset($info->maps_embed))
                <div class="aspect-video">
                    {!! $info->maps_embed !!}
                </div>
                @else
                <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">Peta lokasi akan ditampilkan di sini</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Services -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-concierge-bell text-yellow-600 mr-2"></i>
                    Layanan Utama
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @php
                    $services = [
                        'Whistleblowing System (WBS)',
                        'Audit Internal',
                        'Pengawasan Keuangan',
                        'Monitoring dan Evaluasi',
                        'Konsultasi Tata Kelola',
                        'Investigasi Pengaduan'
                    ];
                    @endphp
                    
                    @foreach($services as $service)
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-gray-700">{{ $service }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informasi Tambahan
            </h2>
        </div>
        <div class="p-6">
            <div class="prose prose-sm max-w-none">
                {!! $info->informasi_tambahan ?? '<p>Informasi tambahan tentang kantor akan ditampilkan di sini.</p>' !!}
            </div>
        </div>
    </div>
</div>
@endsection
