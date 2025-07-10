@extends('layouts.admin')

@section('header', 'Profil Organisasi')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><span class="text-gray-500">Profil Organisasi</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profil Organisasi</h1>
            <p class="text-gray-600 mt-1">Kelola profil dan informasi organisasi</p>
        </div>
        <a href="{{ route('admin.profil.edit') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>Edit Profil
        </a>
    </div>

    <!-- Current Profile Display -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Organisasi</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Nama Organisasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Organisasi</label>
                        <p class="text-gray-900 text-lg font-medium">Inspektorat Provinsi Papua Tengah</p>
                    </div>

                    <!-- Visi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Visi</label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <p class="text-gray-800 italic">
                                "Terwujudnya Pengawasan Internal yang Profesional dan Akuntabel untuk Mewujudkan Good Governance di Papua Tengah"
                            </p>
                        </div>
                    </div>

                    <!-- Misi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Misi</label>
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                            <ol class="space-y-2">
                                <li class="flex items-start space-x-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 text-sm font-bold rounded-full flex items-center justify-center">1</span>
                                    <span class="text-gray-800">Melaksanakan pengawasan internal yang berkualitas</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 text-sm font-bold rounded-full flex items-center justify-center">2</span>
                                    <span class="text-gray-800">Memberikan assurance dan consulting yang optimal</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 text-sm font-bold rounded-full flex items-center justify-center">3</span>
                                    <span class="text-gray-800">Meningkatkan kapasitas pengawasan internal</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 text-sm font-bold rounded-full flex items-center justify-center">4</span>
                                    <span class="text-gray-800">Memperkuat sistem pengendalian internal pemerintah</span>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Sejarah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sejarah Singkat</label>
                        <div class="prose max-w-none">
                            <p class="text-gray-700">
                                Inspektorat Provinsi Papua Tengah dibentuk seiring dengan pembentukan provinsi Papua Tengah berdasarkan Undang-Undang Nomor 14 Tahun 2022. 
                                Sebagai unsur pengawas dalam penyelenggaraan pemerintahan daerah, Inspektorat Papua Tengah berkomitmen untuk mendukung terwujudnya 
                                tata kelola pemerintahan yang baik, bersih, dan akuntabel.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Side Information -->
        <div class="space-y-6">
            <!-- Contact Info -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Kontak</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p class="text-gray-900 text-sm">Jl. Raya Nabire No. 123, Nabire, Papua Tengah</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="text-gray-900 text-sm">(0984) 21234</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-red-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-gray-900 text-sm">inspektorat@paputengah.go.id</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-globe text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Website</p>
                            <p class="text-gray-900 text-sm">https://inspektorat.paputengah.go.id</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jam Operasional</p>
                            <p class="text-gray-900 text-sm">Senin - Jumat: 08:00 - 16:00 WIT</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Struktur Organisasi -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Struktur Organisasi</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <div class="text-center">
                                <i class="fas fa-sitemap text-gray-400 text-3xl mb-2"></i>
                                <p class="text-gray-500 text-sm">Struktur Organisasi</p>
                                <p class="text-gray-400 text-xs">Klik edit untuk mengunggah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
