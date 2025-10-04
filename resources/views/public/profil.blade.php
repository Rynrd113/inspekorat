@extends('layouts.public')

@section('title', 'Profil Inspektorat Papua Tengah')

@section('content')

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-blue-800 to-blue-900 py-20">
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Profil Organisasi
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Mengenal lebih dekat Inspektorat Provinsi Papua Tengah
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Organization Overview -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <div class="text-center mb-12">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-blue-600 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $profil['nama_organisasi'] }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Lembaga pengawasan internal yang berkomitmen untuk mewujudkan tata kelola pemerintahan yang baik, 
                    bersih, dan akuntabel di Provinsi Papua Tengah.
                </p>
            </div>

            <!-- Visi & Misi -->
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <!-- Visi -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-eye text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Visi</h3>
                    </div>
                    <p class="text-gray-700 italic leading-relaxed">
                        "{{ $profil['visi'] }}"
                    </p>
                </div>

                <!-- Misi -->
                <div class="bg-green-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-bullseye text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Misi</h3>
                    </div>
                    <ol class="space-y-3">
                        @foreach($profil['misi'] as $index => $misi)
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 text-sm font-bold rounded-full flex items-center justify-center mr-3">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-gray-700">{{ $misi }}</span>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <!-- Sejarah -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-history text-gray-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Sejarah Singkat</h3>
                </div>
                <div class="prose max-w-none text-gray-700">
                    <p>{{ $profil['sejarah'] }}</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Contact Details -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-address-book text-blue-600 mr-3"></i>
                    Informasi Kontak
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mt-1">
                            <i class="fas fa-map-marker-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Alamat</p>
                            <p class="text-gray-600">Jl. Raya Nabire No. 123, Nabire, Papua Tengah</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Telepon</p>
                            <p class="text-gray-600">(0984) 21234</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-red-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email</p>
                            <p class="text-gray-600">inspektorat@papuatengah.go.id</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-globe text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Website</p>
                            <p class="text-gray-600">https://inspektorat.papuatengah.go.id</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Jam Operasional</p>
                            <p class="text-gray-600">Senin - Jumat: 08:00 - 16:00 WIT</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Struktur Organisasi -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-sitemap text-blue-600 mr-3"></i>
                    Struktur Organisasi
                </h3>
                
                <div class="text-center">
                    <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                        <div class="text-center">
                            <i class="fas fa-sitemap text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">Struktur Organisasi</p>
                            <p class="text-gray-400 text-sm">Inspektorat Provinsi Papua Tengah</p>
                        </div>
                    </div>
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download Struktur Organisasi
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-12">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Layanan Kami</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    <a href="{{ route('public.pelayanan.index') }}" 
                       class="group p-6 text-center border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-lg transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors">
                            <i class="fas fa-concierge-bell text-blue-600 group-hover:text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Layanan Publik</h4>
                        <p class="text-gray-600 text-sm">Akses berbagai layanan yang tersedia</p>
                    </a>

                    <a href="{{ route('public.wbs') }}" 
                       class="group p-6 text-center border-2 border-gray-200 rounded-lg hover:border-green-500 hover:shadow-lg transition-all duration-200">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500 transition-colors">
                            <i class="fas fa-shield-alt text-green-600 group-hover:text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Whistleblowing</h4>
                        <p class="text-gray-600 text-sm">Laporkan dugaan pelanggaran</p>
                    </a>

                    <a href="{{ route('public.portal-opd.index') }}" 
                       class="group p-6 text-center border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:shadow-lg transition-all duration-200">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-500 transition-colors">
                            <i class="fas fa-building text-purple-600 group-hover:text-white"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Portal OPD</h4>
                        <p class="text-gray-600 text-sm">Direktori Organisasi Perangkat Daerah</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
