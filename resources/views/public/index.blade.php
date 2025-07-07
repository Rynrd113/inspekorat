@extends('layouts.app')

@section('title', 'Portal Informasi Pemerintahan - Inspektorat Papua Tengah')
@section('description', 'Portal resmi Inspektorat Provinsi Papua Tengah - Akses layanan publik, informasi pemerintahan, dan laporan WBS.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-blue-600 rounded flex items-center justify-center">
                            <span class="text-white text-sm font-bold">PT</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-lg font-semibold text-gray-900">Inspektorat Provinsi</h1>
                        <p class="text-sm text-gray-500">Papua Tengah</p>
                    </div>
                </div>
                
                <nav class="hidden md:block">
                    <div class="flex items-center space-x-8">
                        <a href="#beranda" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Beranda</a>
                        <a href="{{ route('public.berita.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Berita</a>
                        <a href="{{ route('public.portal-opd.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Portal OPD</a>
                        <a href="#pintasan-layanan" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Layanan</a>
                        <a href="#informasi" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">Kontak</a>
                        <a href="{{ route('public.wbs') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">WBS</a>
                        <a href="{{ route('admin.login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Admin</a>
                    </div>
                </nav>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="#beranda" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Beranda</a>
                <a href="{{ route('public.berita.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Berita</a>
                <a href="{{ route('public.portal-opd.index') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Portal OPD</a>
                <a href="#pintasan-layanan" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Layanan</a>
                <a href="#informasi" class="block px-3 py-2 text-gray-700 hover:text-blue-600">Kontak</a>
                <a href="{{ route('public.wbs') }}" class="block px-3 py-2 text-gray-700 hover:text-blue-600">WBS</a>
                <a href="{{ route('admin.login') }}" class="block px-3 py-2 text-blue-600 font-medium">Admin</a>
            </div>
        </div>
    </header>

    <!-- Hero Slider Section -->
    <section id="beranda" class="relative overflow-hidden">
        <div class="hero-slider relative h-[60vh] min-h-[500px] lg:h-[80vh] max-h-[700px] w-full">
            <!-- Slide 1 -->
            <div class="slide absolute inset-0 w-full h-full bg-gradient-to-r from-blue-900/90 to-blue-800/90 active">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid1" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid1)" />
                    </svg>
                </div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6">
                                Mewujudkan Pengawasan yang<br class="hidden sm:block">
                                <span class="text-blue-200">Akuntabel dan Transparan</span>
                            </h1>
                            <p class="text-lg sm:text-xl md:text-2xl text-blue-100 mb-6 sm:mb-8 max-w-4xl mx-auto">
                                Website Resmi Inspektorat Provinsi Papua Tengah
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="#layanan" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Lihat Berita
                                </a>
                                <a href="#pintasan-layanan" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Layanan Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-900/90 to-purple-800/90">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-800 via-purple-900 to-blue-900"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6">
                                Pengawasan Internal yang<br class="hidden sm:block">
                                <span class="text-purple-200">Kredibel dan Objektif</span>
                            </h1>
                            <p class="text-lg sm:text-xl md:text-2xl text-purple-100 mb-6 sm:mb-8 max-w-4xl mx-auto">
                                Membangun sistem pengawasan yang profesional dan terpercaya
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('public.wbs') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Lapor WBS
                                </a>
                                <a href="#informasi" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide absolute inset-0 w-full h-full bg-gradient-to-r from-blue-800/90 to-teal-800/90">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-teal-800 to-green-800"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6">
                                Melayani dengan<br class="hidden sm:block">
                                <span class="text-teal-200">Integritas dan Profesional</span>
                            </h1>
                            <p class="text-lg sm:text-xl md:text-2xl text-teal-100 mb-6 sm:mb-8 max-w-4xl mx-auto">
                                Berkomitmen memberikan pelayanan terbaik untuk masyarakat Papua Tengah
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="#pintasan-layanan" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Layanan Publik
                                </a>
                                <a href="#informasi" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                    Info Kontak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide absolute inset-0 bg-gradient-to-r from-blue-900/90 to-blue-800/90 active">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-800 via-blue-900 to-indigo-900"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid1" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid1)" />
                    </svg>
                </div>
                <div class="relative h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
                                Mewujudkan Pengawasan yang<br>
                                <span class="text-blue-200">Akuntabel dan Transparan</span>
                            </h1>
                            <p class="text-xl sm:text-2xl text-blue-100 mb-8 max-w-4xl mx-auto">
                                Website Resmi Inspektorat Provinsi Papua Tengah
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide absolute inset-0 bg-gradient-to-r from-indigo-900/90 to-purple-800/90">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-800 via-purple-900 to-blue-900"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
                                Pengawasan Internal yang<br>
                                <span class="text-purple-200">Kredibel dan Objektif</span>
                            </h1>
                            <p class="text-xl sm:text-2xl text-purple-100 mb-8 max-w-4xl mx-auto">
                                Membangun sistem pengawasan yang profesional dan terpercaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide absolute inset-0 bg-gradient-to-r from-blue-800/90 to-teal-800/90">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-teal-800 to-green-800"></div>
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="relative h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6">
                                Melayani dengan<br>
                                <span class="text-teal-200">Integritas dan Profesional</span>
                            </h1>
                            <p class="text-xl sm:text-2xl text-teal-100 mb-8 max-w-4xl mx-auto">
                                Berkomitmen memberikan pelayanan terbaik untuk masyarakat Papua Tengah
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Navigation -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
                <button class="slider-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white active" data-slide="0"></button>
                <button class="slider-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white" data-slide="1"></button>
                <button class="slider-dot w-3 h-3 rounded-full bg-white/50 hover:bg-white" data-slide="2"></button>
            </div>

            <!-- Slider Arrows -->
            <button class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-all duration-300" id="prevSlide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-all duration-300" id="nextSlide">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" class="w-full h-12 text-gray-50">
                <path fill="currentColor" d="M0,32L48,37.3C96,43,192,53,288,58.7C384,64,480,64,576,58.7C672,53,768,43,864,48C960,53,1056,75,1152,80C1248,85,1344,75,1392,69.3L1440,64L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Pengawasan</h3>
                    <p class="text-gray-600">Sistem pengawasan internal yang kredibel dan objektif untuk mewujudkan tata kelola yang akuntabel</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                        <i class="fas fa-users text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Pelayanan</h3>
                    <p class="text-gray-600">Memberikan pelayanan publik yang profesional, transparan, dan bertanggung jawab</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">Transparansi</h3>
                    <p class="text-gray-600">Keterbukaan informasi untuk meningkatkan akuntabilitas dan kepercayaan publik</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Inspektorat Section -->
    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Berita Inspektorat
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Dapatkan informasi terkini tentang kegiatan dan berita terbaru dari Inspektorat Provinsi Papua Tengah
                </p>
            </div>
            
            <!-- Berita List -->
            <div class="space-y-8" id="berita-list">
                <!-- Berita akan dirender dengan JavaScript -->
            </div>
            
            <!-- Navigation Tabs -->
            <div class="mt-12 flex flex-col items-center space-y-6">
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button id="btn-terbaru" onclick="filterBerita('terbaru')" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-all duration-200 hover:bg-blue-700">
                        <i class="fas fa-clock mr-2"></i>TERBARU
                    </button>
                    <button id="btn-terpopuler" onclick="filterBerita('terpopuler')" class="px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200">
                        <i class="fas fa-fire mr-2"></i>TERPOPULER
                    </button>
                </div>
                
                <!-- Lihat Semua Berita Button -->
                <a href="{{ route('public.berita.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-newspaper mr-3"></i>
                    Lihat Semua Berita
                    <i class="fas fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pintasan Layanan Section -->
    <section id="pintasan-layanan" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Pintasan Layanan
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                    Pintasan Layanan menyediakan akses cepat ke berbagai layanan penting yang tersedia di Inspektorat Provinsi Papua Tengah. Melalui menu ini, pengunjung dapat dengan mudah mengakses informasi, pengaduan, atau layanan lainnya tanpa perlu mencari lebih jauh.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- WBS Card -->
                <x-card class="hover:shadow-lg transition-shadow duration-300 group">
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        <div class="w-full h-48 bg-gradient-to-br from-red-100 to-pink-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-red-600 text-4xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-red-600 transition-colors">
                            WBS
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Anda dapat melaporkan tindak pidana korupsi yang dilakukan oleh seseorang kepada bagian Pengawasan Internal. Tidak perlu takut identitas Anda akan terungkap karena Papua Tengah akan menjamin identitas Anda. Jadilah whistleblower bagi Papua Tengah.
                        </p>
                        <x-button href="{{ route('public.wbs') }}" variant="outline" class="w-full text-red-600 border-red-600 hover:bg-red-50">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Selengkapnya
                        </x-button>
                    </div>
                </x-card>

                <!-- Portal Papua Tengah Card -->
                <x-card class="hover:shadow-lg transition-shadow duration-300 group">
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-newspaper text-blue-600 text-4xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            Portal Papua Tengah
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Temukan informasi berita terkini dan kegiatan dari Inspektorat Provinsi Papua Tengah. Akses berbagai informasi penting untuk masyarakat Papua Tengah.
                        </p>
                        <x-button href="#layanan" variant="outline" class="w-full text-blue-600 border-blue-600 hover:bg-blue-50">
                            <i class="fas fa-arrow-up mr-2"></i>
                            Lihat Berita
                        </x-button>
                    </div>
                </x-card>

                <!-- Informasi Kontak Card -->
                <x-card class="hover:shadow-lg transition-shadow duration-300 group">
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-green-600 text-4xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">
                            Informasi Kontak
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Hubungi kami melalui berbagai saluran komunikasi yang tersedia. Dapatkan informasi alamat, nomor telepon, dan email resmi Inspektorat Provinsi Papua Tengah.
                        </p>
                        <x-button href="#informasi" variant="outline" class="w-full text-green-600 border-green-600 hover:bg-green-50">
                            <i class="fas fa-arrow-down mr-2"></i>
                            Lihat Informasi
                        </x-button>
                    </div>
                </x-card>
            </div>
        </div>
    </section>

    <!-- Info Kantor Section -->
    <section id="informasi" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Informasi Kontak
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Hubungi kami melalui berbagai saluran komunikasi yang tersedia
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Alamat -->
                <x-card class="text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Alamat Kantor</h3>
                    <div class="text-gray-600">{{ $infoKantor->alamat }}</div>
                </x-card>
                
                <!-- Telepon -->
                <x-card class="text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-phone text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Telepon</h3>
                    <div class="text-gray-600">
                        <p>{{ $infoKantor->telepon }}</p>
                        @if($infoKantor->fax)
                        <p class="text-sm">Fax: {{ $infoKantor->fax }}</p>
                        @endif
                    </div>
                </x-card>
                
                <!-- Email -->
                <x-card class="text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <div class="text-gray-600">
                        <a href="mailto:{{ $infoKantor->email }}" class="text-blue-600 hover:text-blue-800">
                            {{ $infoKantor->email }}
                        </a>
                    </div>
                </x-card>
                
                <!-- Jam Operasional -->
                <x-card class="text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-clock text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Jam Operasional</h3>
                    <div class="text-gray-600">{{ $infoKantor->jam_operasional }}</div>
                </x-card>
                
                <!-- Website -->
                <x-card class="text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-globe text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Website</h3>
                    <div class="text-gray-600">
                        <a href="{{ $infoKantor->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            inspektorat.paputengah.go.id
                        </a>
                    </div>
                </x-card>
                
                <!-- Lokasi -->
                <x-card class="text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-map text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Lokasi</h3>
                    <div class="text-gray-600">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $infoKantor->koordinat }}" 
                           target="_blank" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt mr-1"></i>
                            Lihat di Google Maps
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="h-10 w-10 bg-blue-600 rounded flex items-center justify-center mr-3">
                            <span class="text-white text-sm font-bold">PT</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Inspektorat Provinsi Papua Tengah</h3>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Mewujudkan pengawasan yang akuntabel dan transparan untuk tata kelola pemerintahan yang bersih dan profesional.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#beranda" class="hover:text-white">Beranda</a></li>
                        <li><a href="#layanan" class="hover:text-white">Berita Inspektorat</a></li>
                        <li><a href="#pintasan-layanan" class="hover:text-white">Pintasan Layanan</a></li>
                        <li><a href="#informasi" class="hover:text-white">Informasi Kontak</a></li>
                        <li><a href="{{ route('public.wbs') }}" class="hover:text-white">WBS</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('public.wbs') }}" class="hover:text-white">Whistleblower System</a></li>
                        <li><a href="#layanan" class="hover:text-white">Portal Papua Tengah</a></li>
                        <li><a href="#" class="hover:text-white">Pengaduan Masyarakat</a></li>
                        <li><a href="#" class="hover:text-white">Informasi Publik</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-2 mt-1 text-blue-400"></i>
                            <span>Jl. Trans Papua, Nabire, Papua Tengah</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-blue-400"></i>
                            <span>(0984) 321234</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-blue-400"></i>
                            <span>inspektorat@papuatengah.go.id</span>
                        </li>
                    </ul>
                    
                    <div class="mt-4">
                        <h5 class="font-semibold mb-2">Sosial Media</h5>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Inspektorat Provinsi Papua Tengah. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
// Global variables
let beritaFilterInstance = null;
let beritaData = @json($portalPapuaTengah);

// Global function to filter berita
function filterBerita(filter) {
    console.log('filterBerita called with:', filter);
    if (beritaFilterInstance) {
        beritaFilterInstance.setFilter(filter);
    } else {
        console.error('BeritaFilter instance not found');
    }
}

// Event handler functions
function handleTerbaruClick(e) {
    console.log('handleTerbaruClick called');
    e.preventDefault();
    filterBerita('terbaru');
}

function handleTerpopulerClick(e) {
    console.log('handleTerpopulerClick called');
    e.preventDefault();
    filterBerita('terpopuler');
}

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

// Hero Slider Class
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.slide');
        this.dots = document.querySelectorAll('.slider-dot');
        this.prevBtn = document.getElementById('prevSlide');
        this.nextBtn = document.getElementById('nextSlide');
        this.currentSlide = 0;
        this.slideInterval = null;
        
        // Validate required elements
        if (!this.slides.length) {
            console.error('HeroSlider: No slides found');
            return;
        }
        
        if (!this.prevBtn || !this.nextBtn) {
            console.error('HeroSlider: Navigation buttons not found');
            return;
        }
        
        this.init();
    }
    
    init() {
        // Set up initial state
        this.showSlide(0);
        this.startAutoPlay();
        
        // Add event listeners
        this.prevBtn.addEventListener('click', () => {
            this.stopAutoPlay();
            this.prevSlide();
            this.startAutoPlay();
        });
        
        this.nextBtn.addEventListener('click', () => {
            this.stopAutoPlay();
            this.nextSlide();
            this.startAutoPlay();
        });
        
        // Dot navigation
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.stopAutoPlay();
                this.goToSlide(index);
                this.startAutoPlay();
            });
        });
        
        // Pause on hover
        const sliderContainer = document.querySelector('.hero-slider');
        sliderContainer.addEventListener('mouseenter', () => this.stopAutoPlay());
        sliderContainer.addEventListener('mouseleave', () => this.startAutoPlay());
    }
    
    showSlide(index) {
        // Validate index and elements
        if (!this.slides.length || index < 0 || index >= this.slides.length) {
            console.error('Invalid slide index or no slides found:', index);
            return;
        }
        
        // Hide all slides
        this.slides.forEach(slide => {
            slide.classList.remove('active');
            slide.style.opacity = '0';
            slide.style.transform = 'translateX(100%)';
        });
        
        // Update dots
        this.dots.forEach(dot => dot.classList.remove('active'));
        
        // Show current slide
        if (this.slides[index]) {
            this.slides[index].classList.add('active');
            this.slides[index].style.opacity = '1';
            this.slides[index].style.transform = 'translateX(0)';
        }
        
        if (this.dots[index]) {
            this.dots[index].classList.add('active');
        }
        
        this.currentSlide = index;
    }
    
    nextSlide() {
        const next = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(next);
    }
    
    prevSlide() {
        const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prev);
    }
    
    goToSlide(index) {
        this.showSlide(index);
    }
    
    startAutoPlay() {
        if (this.slides.length > 1) {
            this.slideInterval = setInterval(() => {
                this.nextSlide();
            }, 5000);
        }
    }
    
    stopAutoPlay() {
        if (this.slideInterval) {
            clearInterval(this.slideInterval);
            this.slideInterval = null;
        }
    }
}

// Initialize slider when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing components...');
    console.log('Berita data:', beritaData.length, 'items');
    
    // Initialize hero slider
    new HeroSlider();
    
    // Initialize berita filter
    console.log('Initializing BeritaFilter...');
    beritaFilterInstance = new BeritaFilter();
    
    // Test if buttons exist and add direct event listeners as backup
    const btnTerbaru = document.getElementById('btn-terbaru');
    const btnTerpopuler = document.getElementById('btn-terpopuler');
    
    console.log('Button test - Terbaru found:', !!btnTerbaru);
    console.log('Button test - Terpopuler found:', !!btnTerpopuler);
    
    if (btnTerbaru && btnTerpopuler) {
        console.log('Adding direct event listeners to buttons');
        
        // Remove any existing event listeners and add new ones
        btnTerbaru.removeEventListener('click', handleTerbaruClick);
        btnTerpopuler.removeEventListener('click', handleTerpopulerClick);
        
        btnTerbaru.addEventListener('click', handleTerbaruClick);
        btnTerpopuler.addEventListener('click', handleTerpopulerClick);
        
        console.log('Direct event listeners added');
    }
});

// Berita Filter Class
class BeritaFilter {
    constructor() {
        console.log('BeritaFilter: Constructor called');
        this.beritaData = beritaData; // Use global data
        this.currentFilter = 'terbaru';
        this.beritaContainer = document.getElementById('berita-list');
        this.btnTerbaru = document.getElementById('btn-terbaru');
        this.btnTerpopuler = document.getElementById('btn-terpopuler');
        
        // Debug log
        console.log('BeritaFilter: Data loaded:', this.beritaData ? this.beritaData.length : 0, 'items');
        console.log('BeritaFilter: Container found:', !!this.beritaContainer);
        console.log('BeritaFilter: Terbaru button found:', !!this.btnTerbaru);
        console.log('BeritaFilter: Terpopuler button found:', !!this.btnTerpopuler);
        
        // Check if all DOM elements exist
        if (!this.beritaContainer) {
            console.error('BeritaFilter: Container not found');
            return;
        }
        
        if (!this.beritaData || this.beritaData.length === 0) {
            console.warn('BeritaFilter: No data available');
            this.beritaContainer.innerHTML = '<p class="text-center text-gray-500 py-8">Tidak ada berita tersedia</p>';
            return;
        }
        
        console.log('BeritaFilter: All elements found, initializing...');
        this.init();
    }
    
    init() {
        console.log('BeritaFilter: Init called');
        this.renderBerita(this.getFilteredData('terbaru'));
        this.updateButtonStates();
        console.log('BeritaFilter: Initialization complete');
    }
    
    setFilter(filter) {
        console.log('setFilter called with:', filter);
        console.log('Previous filter:', this.currentFilter);
        
        this.currentFilter = filter;
        console.log('Filter set to:', this.currentFilter);
        
        // Update button states immediately
        this.updateButtonStates();
        
        // Show loading state
        this.showLoading();
        
        // Get filtered data
        const filteredData = this.getFilteredData(filter);
        console.log('Filtered data count:', filteredData.length);
        
        // Simulate slight delay for smooth UX
        setTimeout(() => {
            this.renderBerita(filteredData);
        }, 200);
    }
    
    showLoading() {
        this.beritaContainer.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Memuat berita...</span>
            </div>
        `;
    }
    
    updateButtonStates() {
        console.log('updateButtonStates called, current filter:', this.currentFilter);
        
        const btnTerbaru = document.getElementById('btn-terbaru');
        const btnTerpopuler = document.getElementById('btn-terpopuler');
        
        if (!btnTerbaru || !btnTerpopuler) {
            console.error('Buttons not found in updateButtonStates');
            return;
        }
        
        // Reset button states
        btnTerbaru.className = 'px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200';
        btnTerpopuler.className = 'px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-all duration-200';
        
        // Set active button
        if (this.currentFilter === 'terbaru') {
            btnTerbaru.className = 'px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-all duration-200 hover:bg-blue-700';
            btnTerbaru.innerHTML = '<i class="fas fa-clock mr-2"></i>TERBARU';
            btnTerpopuler.innerHTML = '<i class="fas fa-fire mr-2"></i>TERPOPULER';
        } else if (this.currentFilter === 'terpopuler') {
            btnTerpopuler.className = 'px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-all duration-200 hover:bg-blue-700';
            btnTerpopuler.innerHTML = '<i class="fas fa-fire mr-2"></i>TERPOPULER';
            btnTerbaru.innerHTML = '<i class="fas fa-clock mr-2"></i>TERBARU';
        }
        
        console.log('Button states updated');
    }
    
    getFilteredData(filter) {
        console.log('getFilteredData called with filter:', filter);
        console.log('Original data count:', this.beritaData.length);
        
        let sortedData = [...this.beritaData];
        
        if (filter === 'terbaru') {
            console.log('Sorting by newest (published_at)');
            sortedData.sort((a, b) => new Date(b.published_at) - new Date(a.published_at));
        } else if (filter === 'terpopuler') {
            console.log('Sorting by most popular (views)');
            sortedData.sort((a, b) => (b.views || 0) - (a.views || 0));
        }
        
        // Show first 3 items for debugging
        console.log(`Top 3 items after ${filter} filter:`, sortedData.slice(0, 3).map(item => ({
            judul: item.judul,
            published_at: item.published_at,
            views: item.views
        })));
        
        return sortedData;
    }
    
    renderBerita(data) {
        this.beritaContainer.innerHTML = '';
        
        data.forEach((portal, index) => {
            const beritaCard = this.createBeritaCard(portal, index, data.length);
            this.beritaContainer.appendChild(beritaCard);
        });
        
        // Smooth scroll animation for re-rendering
        this.beritaContainer.style.opacity = '0';
        setTimeout(() => {
            this.beritaContainer.style.opacity = '1';
        }, 100);
    }
    
    createBeritaCard(portal, index, total) {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden';
        
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        };
        
        const limitText = (text, limit) => {
            return text.length > limit ? text.substring(0, limit) + '...' : text;
        };
        
        const categoryClasses = index % 2 === 0 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
        
        card.innerHTML = `
            <div class="flex flex-col lg:flex-row">
                <!-- Content -->
                <div class="flex-1 p-6 lg:p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl lg:text-2xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition-colors cursor-pointer">
                                <a href="/berita/${portal.id}" class="block">${portal.judul}</a>
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>${formatDate(portal.published_at)}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-user mr-2"></i>
                                <span>${portal.penulis}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye mr-2"></i>
                                <span>${portal.views || 0} views</span>
                            </div>
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${categoryClasses}">
                                    ${portal.kategori.toUpperCase()}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-semibold text-gray-900">${index + 1} dari ${total}</span>
                        </div>
                    </div>
                    
                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                        ${limitText(portal.konten, 200)}
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <a href="/berita/${portal.id}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                            <span>Baca Selengkapnya</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Image -->
                <div class="lg:w-80 lg:flex-shrink-0">
                    <div class="h-64 lg:h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        <i class="fas fa-image text-blue-400 text-4xl"></i>
                    </div>
                </div>
            </div>
        `;
        
        return card;
    }
}
</script>

<style>
/* Hero Slider Styles */
.hero-slider {
    position: relative;
    overflow: hidden;
}

.slide {
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0;
    transform: translateX(100%);
}

.slide.active {
    opacity: 1;
    transform: translateX(0);
}

.slider-dot.active {
    background-color: white;
}

/* Berita Filter Styles */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#berita-list {
    transition: opacity 0.3s ease-in-out;
}

/* Filter button styles */
#btn-terbaru, #btn-terpopuler {
    cursor: pointer;
    position: relative;
    z-index: 10;
    pointer-events: auto;
}

#btn-terbaru:hover, #btn-terpopuler:hover {
    transform: translateY(-1px);
}

/* Hover effects */
.hero-slider button:hover {
    transform: scale(1.05);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero-slider {
        height: 60vh !important;
        min-height: 400px !important;
    }
    
    .slide h1 {
        font-size: 1.875rem !important;
        line-height: 1.2;
    }
    
    .slide p {
        font-size: 1rem !important;
    }
}

@media (max-width: 640px) {
    .hero-slider {
        height: 50vh !important;
        min-height: 350px !important;
    }
    
    .slide h1 {
        font-size: 1.5rem !important;
    }
    
    .slide p {
        font-size: 0.875rem !important;
    }
}
</style>
@endpush
@endsection
