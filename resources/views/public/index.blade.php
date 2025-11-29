@extends('layouts.public')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'Portal Informasi Pemerintahan - Inspektorat Papua Tengah')
@section('description', 'Portal resmi Inspektorat Provinsi Papua Tengah - Akses layanan publik, informasi pemerintahan, dan laporan WBS.')

@push('styles')
<style>
    /* Back to top button */
    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 50;
        display: none;
        padding: 0.75rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        border-radius: 50%;
        width: 3rem;
        height: 3rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.3);
    }
    
    .back-to-top:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.4);
    }
    
    /* Slider styles - CRITICAL */
    .slide {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.6s ease-in-out;
        pointer-events: none;
        z-index: 1;
    }
    
    .slide.active {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto;
        z-index: 10;
    }
    
    /* Slider dots */
    .slider-dot {
        cursor: pointer;
        background-color: rgba(255, 255, 255, 0.5);
    }
    
    .slider-dot.active {
        background-color: rgba(255, 255, 255, 1);
        transform: scale(1.2);
    }
    
    /* Slider buttons */
    #prevSlide, #nextSlide {
        cursor: pointer;
        user-select: none;
    }
    
    #prevSlide:active, #nextSlide:active {
        transform: translateY(-50%) scale(0.95);
    }
    
    /* Animation styles */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    /* Line clamp utility */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white">

    <!-- Hero Slider Section -->
    <section id="beranda" class="relative overflow-hidden">
        <div class="hero-slider relative h-[60vh] min-h-[500px] sm:h-[70vh] sm:min-h-[600px] lg:h-[85vh] max-h-[800px] w-full">
            <!-- Slide 1 -->
            <div class="slide absolute inset-0 w-full h-full active">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800"></div>
                <div class="absolute inset-0 bg-black/10"></div>
                <!-- Geometric Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-10 right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 left-20 w-96 h-96 bg-blue-300/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-indigo-300/20 rounded-full blur-2xl"></div>
                </div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <div class="mb-4 sm:mb-6">
                                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl mb-4 sm:mb-6">
                                    <i class="fas fa-shield-alt text-white text-2xl sm:text-3xl"></i>
                                </div>
                            </div>
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 leading-tight px-4">
                                Pengawasan yang<br class="hidden sm:block">
                                <span class="text-blue-200">Akuntabel & Transparan</span>
                            </h1>
                            <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-blue-100 mb-6 sm:mb-8 max-w-4xl mx-auto font-light px-4">
                                Portal Resmi Inspektorat Provinsi Papua Tengah
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                                <a href="#layanan" class="bg-white text-blue-700 px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-sm sm:text-base">
                                    <i class="fas fa-newspaper mr-2"></i>
                                    Lihat Berita Terbaru
                                </a>
                                <a href="#pintasan-layanan" class="bg-white/10 backdrop-blur-sm text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40 text-sm sm:text-base">
                                    <i class="fas fa-concierge-bell mr-2"></i>
                                    Layanan Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide absolute inset-0 w-full h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-700 to-pink-800"></div>
                <div class="absolute inset-0 bg-black/10"></div>
                <!-- Geometric Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-80 h-80 bg-purple-300/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/3 right-1/4 w-40 h-40 bg-pink-300/20 rounded-full blur-2xl"></div>
                </div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                                    <i class="fas fa-balance-scale text-white text-3xl"></i>
                                </div>
                            </div>
                            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                                Pengawasan Internal<br class="hidden sm:block">
                                <span class="text-purple-200">Kredibel & Objektif</span>
                            </h1>
                            <p class="text-xl sm:text-2xl md:text-3xl text-purple-100 mb-8 max-w-4xl mx-auto font-light">
                                Membangun sistem pengawasan yang profesional dan terpercaya
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('public.wbs') }}" class="bg-white text-purple-700 px-8 py-4 rounded-xl font-semibold hover:bg-purple-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Lapor WBS
                                </a>
                                <a href="#informasi" class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
                                    <i class="fas fa-phone mr-2"></i>
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide absolute inset-0 w-full h-full">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-800"></div>
                <div class="absolute inset-0 bg-black/10"></div>
                <!-- Geometric Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-16 right-16 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-24 left-24 w-88 h-88 bg-teal-300/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 w-36 h-36 bg-cyan-300/20 rounded-full blur-2xl"></div>
                </div>
                <div class="relative h-full flex items-center justify-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="text-center text-white">
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                                    <i class="fas fa-handshake text-white text-3xl"></i>
                                </div>
                            </div>
                            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                                Melayani dengan<br class="hidden sm:block">
                                <span class="text-teal-200">Integritas & Profesional</span>
                            </h1>
                            <p class="text-xl sm:text-2xl md:text-3xl text-teal-100 mb-8 max-w-4xl mx-auto font-light">
                                Berkomitmen memberikan pelayanan terbaik untuk Papua Tengah
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="#pintasan-layanan" class="bg-white text-teal-700 px-8 py-4 rounded-xl font-semibold hover:bg-teal-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-hands-helping mr-2"></i>
                                    Layanan Publik
                                </a>
                                <a href="#informasi" class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Info Kontak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Navigation -->
            <div class="absolute bottom-4 sm:bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2 sm:space-x-3 z-20">
                <button class="slider-dot w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white/50 hover:bg-white active transition-all duration-300 shadow-lg" data-slide="0"></button>
                <button class="slider-dot w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white/50 hover:bg-white transition-all duration-300 shadow-lg" data-slide="1"></button>
                <button class="slider-dot w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-white/50 hover:bg-white transition-all duration-300 shadow-lg" data-slide="2"></button>
            </div>

            <!-- Slider Arrows -->
            <button class="absolute left-2 sm:left-4 lg:left-6 top-1/2 transform -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20 border border-white/20 hover:border-white/40" id="prevSlide">
                <i class="fas fa-chevron-left text-sm sm:text-base lg:text-lg"></i>
            </button>
            <button class="absolute right-2 sm:right-4 lg:right-6 top-1/2 transform -translate-y-1/2 w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20 border border-white/20 hover:border-white/40" id="nextSlide">
                <i class="fas fa-chevron-right text-sm sm:text-base lg:text-lg"></i>
            </button>
        </div>
        
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Values Section -->
            <div class="text-center mb-12 sm:mb-16">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-blue-100 rounded-xl sm:rounded-2xl mb-4 sm:mb-6">
                    <i class="fas fa-award text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 px-4">
                    Nilai & Komitmen Kami
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed px-4">
                    Tiga pilar utama yang menjadi fondasi dalam menjalankan tugas pengawasan dan pelayanan publik
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-12 mb-12 sm:mb-16 lg:mb-20">
                <div class="text-center group px-4">
                    <div class="relative inline-block mb-6 sm:mb-8">
                        <div class="w-20 h-20 sm:w-22 sm:h-22 lg:w-24 lg:h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-shield-alt text-white text-2xl sm:text-3xl"></i>
                        </div>
                        <div class="absolute -top-1 sm:-top-2 -right-1 sm:-right-2 w-5 h-5 sm:w-6 sm:h-6 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-blue-600 text-xs"></i>
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Pengawasan</h3>
                    <p class="text-gray-600 leading-relaxed text-base sm:text-lg">
                        Sistem pengawasan internal yang kredibel dan objektif untuk mewujudkan tata kelola yang akuntabel
                    </p>
                </div>

                <div class="text-center group px-4">
                    <div class="relative inline-block mb-6 sm:mb-8">
                        <div class="w-20 h-20 sm:w-22 sm:h-22 lg:w-24 lg:h-24 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl sm:rounded-2xl flex items-center justify-center group-hover:from-indigo-600 group-hover:to-indigo-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-users text-white text-2xl sm:text-3xl"></i>
                        </div>
                        <div class="absolute -top-1 sm:-top-2 -right-1 sm:-right-2 w-5 h-5 sm:w-6 sm:h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-indigo-600 text-xs"></i>
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Pelayanan</h3>
                    <p class="text-gray-600 leading-relaxed text-base sm:text-lg">
                        Memberikan pelayanan publik yang profesional, transparan, dan bertanggung jawab
                    </p>
                </div>

                <div class="text-center group px-4 sm:col-span-2 lg:col-span-1">
                    <div class="relative inline-block mb-6 sm:mb-8">
                        <div class="w-20 h-20 sm:w-22 sm:h-22 lg:w-24 lg:h-24 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl sm:rounded-2xl flex items-center justify-center group-hover:from-purple-600 group-hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-chart-line text-white text-2xl sm:text-3xl"></i>
                        </div>
                        <div class="absolute -top-1 sm:-top-2 -right-1 sm:-right-2 w-5 h-5 sm:w-6 sm:h-6 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-purple-600 text-xs"></i>
                        </div>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Transparansi</h3>
                    <p class="text-gray-600 leading-relaxed text-base sm:text-lg">
                        Keterbukaan informasi untuk meningkatkan akuntabilitas dan kepercayaan publik
                    </p>
                </div>
            </div>
            
            <!-- Statistics Numbers -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl sm:rounded-3xl shadow-xl sm:shadow-2xl p-6 sm:p-8 lg:p-12">
                <div class="text-center mb-8 sm:mb-12">
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3 sm:mb-4 px-4">Statistik Portal Provinsi Papua Tengah</h3>
                    <p class="text-gray-600 text-base sm:text-lg px-4">Data terkini layanan dan informasi yang tersedia di portal</p>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 xl:gap-12">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-emerald-100 rounded-xl sm:rounded-2xl mb-3 sm:mb-4 lg:mb-6">
                            <i class="fas fa-building text-emerald-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2" id="stat-opd">{{ number_format($stats['portal_opd'] ?? 0) }}</div>
                        <div class="text-xs sm:text-sm text-gray-600 font-medium px-2">OPD Terdaftar</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-blue-100 rounded-xl sm:rounded-2xl mb-3 sm:mb-4 lg:mb-6">
                            <i class="fas fa-newspaper text-blue-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2" id="stat-berita">{{ number_format($stats['berita'] ?? 0) }}</div>
                        <div class="text-xs sm:text-sm text-gray-600 font-medium px-2">Berita Aktif</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-red-100 rounded-xl sm:rounded-2xl mb-3 sm:mb-4 lg:mb-6">
                            <i class="fas fa-shield-alt text-red-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2" id="stat-wbs">{{ number_format($stats['wbs'] ?? 0) }}</div>
                        <div class="text-xs sm:text-sm text-gray-600 font-medium px-2">Laporan WBS</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-purple-100 rounded-xl sm:rounded-2xl mb-3 sm:mb-4 lg:mb-6">
                            <i class="fas fa-eye text-purple-600 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                        <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-1 sm:mb-2" id="stat-views">{{ number_format($stats['total_views'] ?? 0) }}</div>
                        <div class="text-xs sm:text-sm text-gray-600 font-medium px-2">Total Kunjungan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Gallery Section -->
    @if($latestGallery && $latestGallery->count() > 0)
    <section class="py-16 bg-gradient-to-br from-purple-50 to-pink-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-pink-100 rounded-2xl mb-4">
                    <i class="fas fa-camera text-pink-600 text-2xl"></i>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Galeri Foto Terbaru
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Dokumentasi kegiatan dan momen penting Inspektorat Provinsi Papua Tengah
                </p>
            </div>

            <!-- Simple Gallery Carousel -->
            <div class="relative">
                <div class="gallery-carousel overflow-hidden">
                    <div class="gallery-track flex transition-transform duration-500 ease-out">
                        @foreach($latestGallery as $galeri)
                        <div class="gallery-slide min-w-full sm:min-w-[50%] lg:min-w-[33.333%] xl:min-w-[25%] px-3">
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                                <div class="relative h-64 bg-gray-200 overflow-hidden">
                                    @if($galeri->file_path)
                                        <img src="{{ asset('uploads/' . $galeri->file_path) }}" 
                                             alt="{{ $galeri->judul }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full items-center justify-center bg-gradient-to-br from-pink-100 to-purple-100 hidden">
                                            <i class="fas fa-image text-pink-300 text-4xl"></i>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-100 to-purple-100">
                                            <i class="fas fa-image text-pink-300 text-4xl"></i>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2">{{ $galeri->judul }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $galeri->deskripsi }}</p>
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span class="bg-pink-100 text-pink-700 px-2 py-1 rounded">{{ $galeri->kategori }}</span>
                                        <span>{{ $galeri->tanggal_publikasi ? \Carbon\Carbon::parse($galeri->tanggal_publikasi)->format('d M Y') : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <button type="button" class="gallery-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-800 hover:bg-pink-600 hover:text-white transition-colors z-10">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="gallery-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-800 hover:bg-pink-600 hover:text-white transition-colors z-10">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- View All Button -->
            <div class="text-center mt-12">
                <a href="{{ route('public.galeri.index') }}" class="inline-flex items-center px-8 py-3 bg-pink-600 text-white rounded-xl hover:bg-pink-700 transition-colors font-semibold shadow-lg hover:shadow-xl">
                    <i class="fas fa-images mr-2"></i>
                    Lihat Semua Galeri
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Berita Inspektorat Section -->
    <section id="layanan" class="py-12 sm:py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-blue-100 rounded-xl sm:rounded-2xl mb-4 sm:mb-6">
                    <i class="fas fa-newspaper text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 px-4">
                    Berita Inspektorat
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed px-4">
                    Dapatkan informasi terkini tentang kegiatan dan berita terbaru dari Inspektorat Provinsi Papua Tengah
                </p>
            </div>
            
            <!-- Berita List -->
            <div class="space-y-6 sm:space-y-8 mb-12 sm:mb-16" id="berita-list">
                @if($portalPapuaTengah && $portalPapuaTengah->count() > 0)
                    @foreach($portalPapuaTengah as $berita)
                        <article class="bg-white rounded-xl sm:rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                            <div class="md:flex">
                                <!-- Image -->
                                <div class="md:w-1/3">
                                    <div class="h-48 sm:h-56 md:h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-indigo-200 transition-colors">
                                        @if($berita->gambar)
                                            <img src="{{ asset('storage/' . $berita->gambar) }}" 
                                                 alt="{{ $berita->judul }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-blue-400 text-2xl sm:text-3xl"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="md:w-2/3 p-4 sm:p-6 lg:p-8">
                                    <!-- Category -->
                                    <div class="mb-3 sm:mb-4">
                                        <span class="inline-flex items-center px-2.5 py-1 sm:px-3 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ strtoupper($berita->kategori) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-blue-600 transition-colors line-clamp-2">
                                        {{ $berita->judul }}
                                    </h3>
                                    
                                    <!-- Content -->
                                    <p class="text-gray-600 mb-4 sm:mb-6 line-clamp-3 leading-relaxed text-sm sm:text-base">
                                        {{ Str::limit(strip_tags($berita->konten), 200) }}
                                    </p>
                                    
                                    <!-- Meta -->
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 lg:gap-6 text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-1.5 sm:mr-2"></i>
                                            <span class="truncate">{{ Str::limit($berita->author ?? 'Admin', 20) }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-1.5 sm:mr-2"></i>
                                            <span>{{ $berita->tanggal_publikasi ? $berita->tanggal_publikasi->format('d M Y') : 'Hari ini' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-eye mr-1.5 sm:mr-2"></i>
                                            <span>{{ number_format($berita->views ?? 0) }}</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('public.berita.show', $berita->id) }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base text-blue-600 hover:text-white border-2 border-blue-600 hover:bg-blue-600 rounded-lg sm:rounded-xl transition-all duration-300 font-semibold group">
                                        <span>Baca Selengkapnya</span>
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="text-center py-12 sm:py-16">
                        <div class="max-w-md mx-auto px-4">
                            <i class="fas fa-newspaper text-gray-300 text-5xl sm:text-6xl mb-4"></i>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum ada berita tersedia</h3>
                            <p class="text-sm sm:text-base text-gray-500 mb-6">Berita akan segera dipublikasikan.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Navigation Tabs -->
            <div class="flex flex-col items-center space-y-6 sm:space-y-8">
                <div class="bg-white rounded-xl sm:rounded-2xl p-1.5 sm:p-2 shadow-lg">
                    <button id="btn-terbaru" onclick="filterBerita('terbaru')" class="px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg sm:rounded-xl transition-all duration-200 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg">
                        <i class="fas fa-clock mr-1 sm:mr-2"></i>TERBARU
                    </button>
                    <button id="btn-terpopuler" onclick="filterBerita('terpopuler')" class="px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg sm:rounded-xl transition-all duration-200">
                        <i class="fas fa-fire mr-1 sm:mr-2"></i>TERPOPULER
                    </button>
                </div>
                
                <!-- Lihat Semua Berita Button -->
                <a href="{{ route('public.berita.index') }}" class="group inline-flex items-center px-6 sm:px-8 lg:px-10 py-3 sm:py-4 text-sm sm:text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl sm:rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    <i class="fas fa-newspaper mr-2 sm:mr-3 text-base sm:text-lg"></i>
                    Lihat Semua Berita
                    <i class="fas fa-arrow-right ml-2 sm:ml-3 text-base sm:text-lg group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pintasan Layanan Section -->
    <section id="pintasan-layanan" class="py-12 sm:py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-blue-100 rounded-xl sm:rounded-2xl mb-4 sm:mb-6">
                    <i class="fas fa-rocket text-blue-600 text-xl sm:text-2xl"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6 px-4">
                    Pintasan Layanan
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-5xl mx-auto leading-relaxed px-4">
                    Pintasan Layanan menyediakan akses cepat ke berbagai layanan penting yang tersedia di Inspektorat Provinsi Papua Tengah. Melalui menu ini, pengunjung dapat dengan mudah mengakses informasi, pengaduan, atau layanan lainnya tanpa perlu mencari lebih jauh.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                <!-- Profil Card -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-4 sm:p-6">
                        <div class="w-full h-36 sm:h-40 lg:h-48 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                            <i class="fas fa-university text-indigo-600 text-3xl sm:text-4xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3 group-hover:text-indigo-600 transition-colors">
                            Profil Inspektorat
                        </h3>
                        <p class="text-gray-600 mb-4 sm:mb-6 leading-relaxed text-sm sm:text-base">
                            Pelajari profil lengkap, visi, misi, dan struktur organisasi Inspektorat Papua Tengah.
                        </p>
                        <a href="{{ route('public.profil') }}" class="inline-flex items-center justify-center w-full px-4 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base text-indigo-600 border-2 border-indigo-600 rounded-lg sm:rounded-xl hover:bg-indigo-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-info-circle mr-2"></i>
                            Lihat Profil
                        </a>
                    </div>
                </div>

                <!-- Pelayanan Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-teal-100 to-green-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-concierge-bell text-teal-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-teal-600 transition-colors">
                            Layanan Publik
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Akses berbagai layanan publik yang disediakan oleh Inspektorat Papua Tengah.
                        </p>
                        <a href="{{ route('public.pelayanan.index') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-teal-600 border-2 border-teal-600 rounded-xl hover:bg-teal-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-hands-helping mr-2"></i>
                            Lihat Layanan
                        </a>
                    </div>
                </div>

                <!-- Dokumen Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-orange-100 to-red-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-file-pdf text-orange-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">
                            Dokumen Publik
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Download dokumen resmi, peraturan, dan laporan dari Inspektorat Papua Tengah.
                        </p>
                        <a href="{{ route('public.dokumen.index') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-orange-600 border-2 border-orange-600 rounded-xl hover:bg-orange-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-download mr-2"></i>
                            Lihat Dokumen
                        </a>
                    </div>
                </div>

                <!-- Galeri Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-pink-100 to-purple-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-images text-pink-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-pink-600 transition-colors">
                            Galeri Kegiatan
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Lihat dokumentasi foto dan video kegiatan Inspektorat Papua Tengah.
                        </p>
                        <a href="{{ route('public.galeri.index') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-pink-600 border-2 border-pink-600 rounded-xl hover:bg-pink-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-camera mr-2"></i>
                            Lihat Galeri
                        </a>
                    </div>
                </div>

                <!-- FAQ Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-question-circle text-cyan-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-cyan-600 transition-colors">
                            FAQ
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Temukan jawaban atas pertanyaan yang sering diajukan tentang layanan inspektorat.
                        </p>
                        <a href="{{ route('public.faq') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-cyan-600 border-2 border-cyan-600 rounded-xl hover:bg-cyan-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-comments mr-2"></i>
                            Lihat FAQ
                        </a>
                    </div>
                </div>

                <!-- WBS Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-red-100 to-pink-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-shield-alt text-red-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors">
                            WBS
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Laporkan dugaan pelanggaran atau korupsi melalui sistem pelaporan yang aman.
                        </p>
                        <a href="{{ route('public.wbs') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-red-600 border-2 border-red-600 rounded-xl hover:bg-red-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Buat Laporan
                        </a>
                    </div>
                </div>

                <!-- Pengaduan Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-comment-alt text-green-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">
                            Pengaduan Masyarakat
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Sampaikan pengaduan, kritik, dan saran kepada Inspektorat Papua Tengah.
                        </p>
                        <a href="{{ route('public.pengaduan') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-green-600 border-2 border-green-600 rounded-xl hover:bg-green-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Buat Pengaduan
                        </a>
                    </div>
                </div>

                <!-- Portal OPD Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-green-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-building text-emerald-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors">
                            Portal OPD
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Jelajahi informasi lengkap OPD di Papua Tengah beserta profil dan kontaknya.
                        </p>
                        <a href="{{ route('public.portal-opd.index') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-emerald-600 border-2 border-emerald-600 rounded-xl hover:bg-emerald-50 transition-all duration-300 font-semibold">
                            <i class="fas fa-building mr-2"></i>
                            Lihat Portal OPD
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portal OPD Showcase Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-2xl mb-6">
                    <i class="fas fa-building text-emerald-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">
                    Portal Organisasi Perangkat Daerah
                </h2>
                <p class="text-xl text-gray-600 max-w-5xl mx-auto leading-relaxed">
                    Jelajahi informasi lengkap tentang OPD di Papua Tengah. Akses profil, visi-misi, kontak, dan layanan dari setiap Organisasi Perangkat Daerah dengan mudah dan terpercaya.
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-16">
                <div>
                    <div class="bg-white rounded-3xl p-8 shadow-xl">
                        <div class="flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-2xl mb-8">
                            <i class="fas fa-building text-emerald-600 text-3xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">Informasi OPD Terlengkap</h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Temukan profil lengkap setiap OPD mulai dari visi-misi, struktur organisasi, kepala OPD, hingga informasi kontak yang dapat dihubungi langsung.
                        </p>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-lg">Profil dan Visi-Misi OPD</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-lg">Informasi Kepala OPD</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-lg">Kontak dan Alamat Lengkap</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-emerald-600 text-sm"></i>
                                </div>
                                <span class="text-lg">Website Resmi OPD</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Mudah Diakses</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Interface yang user-friendly memudahkan masyarakat mengakses informasi OPD yang dibutuhkan.
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="flex items-center justify-center w-16 h-16 bg-purple-100 rounded-2xl mb-6">
                            <i class="fas fa-sync-alt text-purple-600 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Selalu Update</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Informasi OPD selalu diperbaharui untuk memberikan data yang akurat dan terkini.
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-2xl mb-6">
                            <i class="fas fa-search text-indigo-600 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Pencarian Cepat</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Fitur pencarian membantu menemukan OPD yang dicari dengan cepat dan efisien.
                        </p>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                        <div class="flex items-center justify-center w-16 h-16 bg-rose-100 rounded-2xl mb-6">
                            <i class="fas fa-phone text-rose-600 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-3">Kontak Langsung</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Informasi kontak lengkap memungkinkan komunikasi langsung dengan OPD terkait.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('public.portal-opd.index') }}" 
                   class="group inline-flex items-center px-10 py-4 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white text-lg font-semibold rounded-2xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    <i class="fas fa-building mr-3 text-xl"></i>
                    Jelajahi Portal OPD
                    <i class="fas fa-arrow-right ml-3 text-xl group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Info Kantor Section -->
    <section id="informasi" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                    <i class="fas fa-phone text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">
                    Informasi Kontak
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Hubungi kami melalui berbagai saluran komunikasi yang tersedia
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Alamat -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-6">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Alamat Kantor</h3>
                    <div class="text-gray-600 text-lg leading-relaxed">{{ $infoKantor->alamat }}</div>
                </div>
                
                <!-- Telepon -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-6">
                        <i class="fas fa-phone text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Telepon</h3>
                    <div class="text-gray-600 text-lg">
                        <p>{{ $infoKantor->telepon }}</p>
                        @if($infoKantor->fax)
                        <p class="text-sm mt-2">Fax: {{ $infoKantor->fax }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Email -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl mb-6">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Email</h3>
                    <div class="text-gray-600 text-lg">
                        <a href="mailto:{{ $infoKantor->email }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                            {{ $infoKantor->email }}
                        </a>
                    </div>
                </div>
                
                <!-- Jam Operasional -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl mb-6">
                        <i class="fas fa-clock text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Jam Operasional</h3>
                    <div class="text-gray-600 text-lg leading-relaxed">{{ $infoKantor->jam_operasional }}</div>
                </div>
                
                <!-- Website -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl mb-6">
                        <i class="fas fa-globe text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Website</h3>
                    <div class="text-gray-600 text-lg">
                        <a href="{{ $infoKantor->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                            inspektorat.papuatengah.go.id
                        </a>
                    </div>
                </div>
                
                <!-- Lokasi -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mb-6">
                        <i class="fas fa-map text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Lokasi</h3>
                    <div class="text-gray-600 text-lg">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $infoKantor->koordinat }}" 
                           target="_blank" 
                           class="text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat di Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- Back to Top Button -->
<button id="backToTop" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
</button>

@push('scripts')
<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing page components...');
    
    // Initialize filter buttons with default active state
    updateFilterButtons('terbaru');
    
    // Animate stats if visible
    animateStats();
    
    console.log('All components initialized successfully!');
});

// Global variables
let currentFilter = 'terbaru';

// Filter berita function
function filterBerita(filter) {
    updateFilterButtons(filter);
    currentFilter = filter;
    console.log('Filter changed to:', filter);
}

// Update filter buttons
function updateFilterButtons(activeFilter) {
    const btnTerbaru = document.getElementById('btn-terbaru');
    const btnTerpopuler = document.getElementById('btn-terpopuler');
    
    if (!btnTerbaru || !btnTerpopuler) return;
    
    // Reset all buttons
    btnTerbaru.className = 'px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg sm:rounded-xl transition-all duration-200';
    btnTerpopuler.className = 'px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-lg sm:rounded-xl transition-all duration-200';
    
    // Set active button
    if (activeFilter === 'terbaru') {
        btnTerbaru.className = 'px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg sm:rounded-xl transition-all duration-200 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg';
    } else if (activeFilter === 'terpopuler') {
        btnTerpopuler.className = 'px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg sm:rounded-xl transition-all duration-200 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg';
    }
}

// Animate stats counter
function animateStats() {
    const stats = document.querySelectorAll('#stat-opd, #stat-berita, #stat-wbs, #stat-views');
    
    if (stats.length === 0) return;
    
    stats.forEach(stat => {
        const target = parseInt(stat.textContent.replace(/,/g, ''));
        if (isNaN(target)) return;
        
        let current = 0;
        const increment = target / 100;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(current).toLocaleString();
        }, 20);
    });
    
    console.log('Stats animation initialized');
}

// Simple Gallery Carousel - Fixed Version
(function() {
    let currentSlide = 0;
    let slidesToShow = 1;
    let totalSlides = 0;
    let autoplayTimer = null;
    
    function initCarousel() {
        const carousel = document.querySelector('.gallery-carousel');
        const track = document.querySelector('.gallery-track');
        const slides = document.querySelectorAll('.gallery-slide');
        const prevBtn = document.querySelector('.gallery-prev');
        const nextBtn = document.querySelector('.gallery-next');
        
        if (!carousel || !track || slides.length === 0) {
            console.log('Gallery carousel elements not found');
            return;
        }
        
        totalSlides = slides.length;
        console.log('Gallery carousel found with', totalSlides, 'slides');
        
        function updateSlidesToShow() {
            const width = window.innerWidth;
            if (width >= 1280) slidesToShow = 4;
            else if (width >= 1024) slidesToShow = 3;
            else if (width >= 640) slidesToShow = 2;
            else slidesToShow = 1;
        }
        
        function moveSlide() {
            updateSlidesToShow();
            const maxSlide = Math.max(0, totalSlides - slidesToShow);
            
            // Keep currentSlide in bounds
            if (currentSlide > maxSlide) currentSlide = maxSlide;
            if (currentSlide < 0) currentSlide = 0;
            
            const percentage = -(currentSlide * (100 / slidesToShow));
            track.style.transform = `translateX(${percentage}%)`;
            
            // Update buttons
            if (prevBtn) {
                if (currentSlide === 0) {
                    prevBtn.style.opacity = '0.3';
                    prevBtn.style.cursor = 'not-allowed';
                } else {
                    prevBtn.style.opacity = '1';
                    prevBtn.style.cursor = 'pointer';
                }
            }
            
            if (nextBtn) {
                if (currentSlide >= maxSlide) {
                    nextBtn.style.opacity = '0.3';
                    nextBtn.style.cursor = 'not-allowed';
                } else {
                    nextBtn.style.opacity = '1';
                    nextBtn.style.cursor = 'pointer';
                }
            }
            
            console.log('Moved to slide', currentSlide, 'of', maxSlide);
        }
        
        function nextSlide() {
            updateSlidesToShow();
            const maxSlide = Math.max(0, totalSlides - slidesToShow);
            if (currentSlide < maxSlide) {
                currentSlide++;
                moveSlide();
                resetAutoplay();
            }
        }
        
        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                moveSlide();
                resetAutoplay();
            }
        }
        
        function autoplay() {
            updateSlidesToShow();
            const maxSlide = Math.max(0, totalSlides - slidesToShow);
            if (currentSlide >= maxSlide) {
                currentSlide = 0;
            } else {
                currentSlide++;
            }
            moveSlide();
        }
        
        function startAutoplay() {
            stopAutoplay();
            autoplayTimer = setInterval(autoplay, 5000);
        }
        
        function stopAutoplay() {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        }
        
        function resetAutoplay() {
            startAutoplay();
        }
        
        // Event listeners
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Prev button clicked');
                prevSlide();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next button clicked');
                nextSlide();
            });
        }
        
        // Window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                moveSlide();
            }, 200);
        });
        
        // Initialize
        updateSlidesToShow();
        moveSlide();
        startAutoplay();
        
        console.log('Gallery carousel initialized successfully');
    }
    
    // Wait for DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousel);
    } else {
        initCarousel();
    }
})();
</script>
@endpush

@endsection
