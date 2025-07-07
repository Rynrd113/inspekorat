@extends('layouts.app')

@section('title', $portalOpd->nama_opd . ' - Portal OPD Papua Tengah')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header with Banner -->
    <div class="relative h-64 md:h-80 overflow-hidden">
        <img src="{{ $portalOpd->banner_url }}" alt="{{ $portalOpd->nama_opd }}" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 to-indigo-900/60"></div>
        
        <!-- Breadcrumb -->
        <div class="absolute top-4 left-0 right-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center text-sm">
                    <a href="{{ route('public.index') }}" class="text-blue-200 hover:text-white">Beranda</a>
                    <i class="fas fa-chevron-right mx-2 text-blue-300"></i>
                    <a href="{{ route('public.portal-opd.index') }}" class="text-blue-200 hover:text-white">Portal OPD</a>
                    <i class="fas fa-chevron-right mx-2 text-blue-300"></i>
                    <span class="text-white">{{ $portalOpd->nama_opd }}</span>
                </nav>
            </div>
        </div>
        
        <!-- OPD Header Info -->
        <div class="absolute bottom-0 left-0 right-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
                <div class="flex items-end space-x-4">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                            <img src="{{ $portalOpd->logo_url }}" alt="{{ $portalOpd->nama_opd }}" 
                                 class="w-16 h-16 object-cover rounded-full">
                        </div>
                    </div>
                    
                    <!-- OPD Info -->
                    <div class="text-white">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $portalOpd->nama_opd }}</h1>
                        @if($portalOpd->singkatan)
                        <p class="text-lg text-blue-200 font-medium">{{ $portalOpd->singkatan }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Description -->
                @if($portalOpd->deskripsi)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Tentang {{ $portalOpd->nama_opd }}
                    </h2>
                    <div class="prose max-w-none text-gray-600">
                        {!! nl2br(e($portalOpd->deskripsi)) !!}
                    </div>
                </div>
                @endif

                <!-- Visi Misi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Visi -->
                    @if($portalOpd->visi)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-eye text-green-600 mr-2"></i>
                            Visi
                        </h3>
                        <p class="text-gray-600 italic">{{ $portalOpd->visi }}</p>
                    </div>
                    @endif

                    <!-- Misi -->
                    @if($portalOpd->misi && count($portalOpd->misi) > 0)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-target text-orange-600 mr-2"></i>
                            Misi
                        </h3>
                        <ol class="space-y-2">
                            @foreach($portalOpd->misi as $index => $misiItem)
                            @if($misiItem)
                            <li class="flex items-start space-x-2">
                                <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 text-sm font-bold rounded-full flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-gray-600">{{ $misiItem }}</span>
                            </li>
                            @endif
                            @endforeach
                        </ol>
                    </div>
                    @endif
                </div>

                <!-- Kepala OPD -->
                @if($portalOpd->kepala_opd)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                        Pimpinan
                    </h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-purple-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $portalOpd->kepala_opd }}</h4>
                            <p class="text-gray-600">Kepala {{ $portalOpd->singkatan ?: $portalOpd->nama_opd }}</p>
                            @if($portalOpd->nip_kepala)
                            <p class="text-sm text-gray-500">NIP: {{ $portalOpd->nip_kepala }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Alamat -->
                @if($portalOpd->alamat)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                        Lokasi
                    </h3>
                    <div class="text-gray-600">
                        {!! nl2br(e($portalOpd->alamat)) !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-address-book text-blue-600 mr-2"></i>
                        Informasi Kontak
                    </h3>
                    <div class="space-y-4">
                        @if($portalOpd->telepon)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-phone text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Telepon</p>
                                <p class="text-gray-900 font-medium">
                                    <a href="tel:{{ $portalOpd->telepon }}" class="hover:text-blue-600">
                                        {{ $portalOpd->telepon }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($portalOpd->email)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-envelope text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-gray-900 font-medium">
                                    <a href="mailto:{{ $portalOpd->email }}" class="hover:text-green-600">
                                        {{ $portalOpd->email }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($portalOpd->website)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-globe text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Website</p>
                                <p class="text-gray-900 font-medium">
                                    <a href="{{ $portalOpd->website }}" target="_blank" class="hover:text-purple-600">
                                        Website Resmi
                                        <i class="fas fa-external-link-alt text-xs ml-1"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-tools text-orange-600 mr-2"></i>
                        Aksi Cepat
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('public.portal-opd.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Portal OPD
                        </a>
                        
                        @if($portalOpd->website)
                        <a href="{{ $portalOpd->website }}" target="_blank"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Kunjungi Website
                        </a>
                        @endif

                        @if($portalOpd->email)
                        <a href="mailto:{{ $portalOpd->email }}"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>
                            Kirim Email
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Related Links -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-link text-indigo-600 mr-2"></i>
                        Link Terkait
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('public.berita.index') }}" 
                           class="block text-sm text-gray-600 hover:text-indigo-600 hover:underline">
                            <i class="fas fa-newspaper mr-2"></i>
                            Berita Terbaru
                        </a>
                        <a href="{{ route('public.wbs') }}" 
                           class="block text-sm text-gray-600 hover:text-indigo-600 hover:underline">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Whistleblowing System
                        </a>
                        <a href="{{ route('public.index') }}#informasi" 
                           class="block text-sm text-gray-600 hover:text-indigo-600 hover:underline">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Lainnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
