@extends('layouts.public')

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
    
    /* Slider styles */
    .slide {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    
    .slide.active {
        opacity: 1;
    }
    
    .slider-dot.active {
        background-color: rgba(255, 255, 255, 1);
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
        <div class="hero-slider relative h-[70vh] min-h-[600px] lg:h-[85vh] max-h-[800px] w-full">
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
                            <div class="mb-6">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                                    <i class="fas fa-shield-alt text-white text-3xl"></i>
                                </div>
                            </div>
                            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                                Pengawasan yang<br class="hidden sm:block">
                                <span class="text-blue-200">Akuntabel & Transparan</span>
                            </h1>
                            <p class="text-xl sm:text-2xl md:text-3xl text-blue-100 mb-8 max-w-4xl mx-auto font-light">
                                Portal Resmi Inspektorat Provinsi Papua Tengah
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="#layanan" class="bg-white text-blue-700 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <i class="fas fa-newspaper mr-2"></i>
                                    Lihat Berita Terbaru
                                </a>
                                <a href="#pintasan-layanan" class="bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
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
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
                <button class="slider-dot w-4 h-4 rounded-full bg-white/50 hover:bg-white active transition-all duration-300 shadow-lg" data-slide="0"></button>
                <button class="slider-dot w-4 h-4 rounded-full bg-white/50 hover:bg-white transition-all duration-300 shadow-lg" data-slide="1"></button>
                <button class="slider-dot w-4 h-4 rounded-full bg-white/50 hover:bg-white transition-all duration-300 shadow-lg" data-slide="2"></button>
            </div>

            <!-- Slider Arrows -->
            <button class="absolute left-6 top-1/2 transform -translate-y-1/2 w-14 h-14 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20 border border-white/20 hover:border-white/40" id="prevSlide">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <button class="absolute right-6 top-1/2 transform -translate-y-1/2 w-14 h-14 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 z-20 border border-white/20 hover:border-white/40" id="nextSlide">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>
        </div>
        
    </section>

    <!-- Stats Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Values Section -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                    <i class="fas fa-award text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">
                    Nilai & Komitmen Kami
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Tiga pilar utama yang menjadi fondasi dalam menjalankan tugas pengawasan dan pelayanan publik
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 mb-20">
                <div class="text-center group">
                    <div class="relative inline-block mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center group-hover:from-blue-600 group-hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-shield-alt text-white text-3xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-blue-600 text-xs"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pengawasan</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Sistem pengawasan internal yang kredibel dan objektif untuk mewujudkan tata kelola yang akuntabel
                    </p>
                </div>

                <div class="text-center group">
                    <div class="relative inline-block mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center group-hover:from-indigo-600 group-hover:to-indigo-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-users text-white text-3xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-indigo-600 text-xs"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pelayanan</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Memberikan pelayanan publik yang profesional, transparan, dan bertanggung jawab
                    </p>
                </div>

                <div class="text-center group">
                    <div class="relative inline-block mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center group-hover:from-purple-600 group-hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            <i class="fas fa-chart-line text-white text-3xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-purple-600 text-xs"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Transparansi</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Keterbukaan informasi untuk meningkatkan akuntabilitas dan kepercayaan publik
                    </p>
                </div>
            </div>
            
            <!-- Statistics Numbers -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-3xl shadow-2xl p-8 lg:p-12">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Statistik Portal Papua Tengah</h3>
                    <p class="text-gray-600 text-lg">Data terkini layanan dan informasi yang tersedia di portal</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-2xl mb-6">
                            <i class="fas fa-building text-emerald-600 text-2xl"></i>
                        </div>
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2" id="stat-opd">{{ $stats['portal_opd'] ?? '10' }}</div>
                        <div class="text-sm text-gray-600 font-medium">OPD Terdaftar</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                            <i class="fas fa-newspaper text-blue-600 text-2xl"></i>
                        </div>
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2" id="stat-berita">{{ $stats['berita'] ?? '25' }}</div>
                        <div class="text-sm text-gray-600 font-medium">Berita Aktif</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-2xl mb-6">
                            <i class="fas fa-shield-alt text-red-600 text-2xl"></i>
                        </div>
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2" id="stat-wbs">{{ $stats['wbs'] ?? '50' }}</div>
                        <div class="text-sm text-gray-600 font-medium">Laporan WBS</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-2xl mb-6">
                            <i class="fas fa-eye text-purple-600 text-2xl"></i>
                        </div>
                        <div class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2" id="stat-views">{{ $stats['total_views'] ?? '1,250' }}</div>
                        <div class="text-sm text-gray-600 font-medium">Total Kunjungan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Inspektorat Section -->
    <section id="layanan" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                    <i class="fas fa-newspaper text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">
                    Berita Inspektorat
                </h2>
                <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Dapatkan informasi terkini tentang kegiatan dan berita terbaru dari Inspektorat Provinsi Papua Tengah
                </p>
            </div>
            
            <!-- Berita List -->
            <div class="space-y-8 mb-16" id="berita-list">
                @if($portalPapuaTengah && $portalPapuaTengah->count() > 0)
                    @foreach($portalPapuaTengah as $berita)
                        <article class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                            <div class="md:flex">
                                <!-- Image -->
                                <div class="md:w-1/3">
                                    <div class="h-48 md:h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center group-hover:from-blue-200 group-hover:to-indigo-200 transition-colors">
                                        @if($berita->gambar)
                                            <img src="{{ asset('storage/' . $berita->gambar) }}" 
                                                 alt="{{ $berita->judul }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-blue-400 text-3xl"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="md:w-2/3 p-8">
                                    <!-- Category -->
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ strtoupper($berita->kategori) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors line-clamp-2">
                                        {{ $berita->judul }}
                                    </h3>
                                    
                                    <!-- Content -->
                                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                        {{ Str::limit(strip_tags($berita->konten), 200) }}
                                    </p>
                                    
                                    <!-- Meta -->
                                    <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 mb-6">
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-2"></i>
                                            <span>{{ $berita->author ?? 'Admin' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span>{{ $berita->tanggal_publikasi ? $berita->tanggal_publikasi->format('d M Y') : 'Hari ini' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-eye mr-2"></i>
                                            <span>{{ number_format($berita->views ?? 0) }} views</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('public.berita.show', $berita->id) }}" class="inline-flex items-center px-6 py-3 text-blue-600 hover:text-white border-2 border-blue-600 hover:bg-blue-600 rounded-xl transition-all duration-300 font-semibold group">
                                        <span>Baca Selengkapnya</span>
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada berita tersedia</h3>
                            <p class="text-gray-500 mb-6">Berita akan segera dipublikasikan.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Navigation Tabs -->
            <div class="flex flex-col items-center space-y-8">
                <div class="bg-white rounded-2xl p-2 shadow-lg">
                    <button id="btn-terbaru" onclick="filterBerita('terbaru')" class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl transition-all duration-200 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg">
                        <i class="fas fa-clock mr-2"></i>TERBARU
                    </button>
                    <button id="btn-terpopuler" onclick="filterBerita('terpopuler')" class="px-8 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-all duration-200">
                        <i class="fas fa-fire mr-2"></i>TERPOPULER
                    </button>
                </div>
                
                <!-- Lihat Semua Berita Button -->
                <a href="{{ route('public.berita.index') }}" class="group inline-flex items-center px-10 py-4 text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    <i class="fas fa-newspaper mr-3 text-lg"></i>
                    Lihat Semua Berita
                    <i class="fas fa-arrow-right ml-3 text-lg group-hover:translate-x-1 transition-transform duration-300"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pintasan Layanan Section -->
    <section id="pintasan-layanan" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                    <i class="fas fa-rocket text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">
                    Pintasan Layanan
                </h2>
                <p class="text-xl text-gray-600 max-w-5xl mx-auto leading-relaxed">
                    Pintasan Layanan menyediakan akses cepat ke berbagai layanan penting yang tersedia di Inspektorat Provinsi Papua Tengah. Melalui menu ini, pengunjung dapat dengan mudah mengakses informasi, pengaduan, atau layanan lainnya tanpa perlu mencari lebih jauh.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- Profil Card -->
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group transform hover:-translate-y-2 border border-gray-100">
                    <div class="p-6">
                        <div class="w-full h-48 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-university text-indigo-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">
                            Profil Inspektorat
                        </h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Pelajari profil lengkap, visi, misi, dan struktur organisasi Inspektorat Papua Tengah.
                        </p>
                        <a href="{{ route('public.profil') }}" class="inline-flex items-center justify-center w-full px-6 py-3 text-indigo-600 border-2 border-indigo-600 rounded-xl hover:bg-indigo-50 transition-all duration-300 font-semibold">
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
document.addEventListener('DOMContentLoaded', function() {
    // Initialize page components  
    initHeroSlider();
    initBackToTop();
    animateStats();
    
    // Initialize filter buttons with default active state
    updateFilterButtons('terbaru');
});

// Global variables
let currentFilter = 'terbaru';

// Filter berita function - For now just updates button state
// Future enhancement: could make AJAX calls for dynamic filtering
function filterBerita(filter) {
    updateFilterButtons(filter);
    currentFilter = filter;
    
    console.log('Filter changed to:', filter);
    // Note: Currently showing server-rendered data from database
    // Could enhance with AJAX filtering later
}

// Note: Fallback data function removed to ensure data comes from database

// Filter berita function (currently shows server-rendered data)
function filterBerita(filter) {
    updateFilterButtons(filter);
    currentFilter = filter;
    
    console.log('Filter changed to:', filter);
    // Note: Currently showing server-rendered data from database
    // Dynamic filtering could be enhanced with AJAX calls later
}

// Note: renderBeritaList function removed to prevent overriding server-rendered content
// The berita content is now properly rendered from database via Blade templates

// Update filter buttons
function updateFilterButtons(activeFilter) {
    const btnTerbaru = document.getElementById('btn-terbaru');
    const btnTerpopuler = document.getElementById('btn-terpopuler');
    
    // Reset all buttons
    btnTerbaru.className = 'px-8 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-all duration-200';
    btnTerpopuler.className = 'px-8 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-all duration-200';
    
    // Set active button
    if (activeFilter === 'terbaru') {
        btnTerbaru.className = 'px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl transition-all duration-200 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg';
    } else if (activeFilter === 'terpopuler') {
        btnTerpopuler.className = 'px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl transition-all duration-200 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg';
    }
}

// Hero slider functionality
function initHeroSlider() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');
    
    let currentSlide = 0;
    
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }
    
    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
    
    // Auto-play slider
    setInterval(nextSlide, 5000);
}

// Back to top functionality
function initBackToTop() {
    const backToTopBtn = document.getElementById('backToTop');
    
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 100) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// Animate stats counter
function animateStats() {
    const stats = document.querySelectorAll('#stat-opd, #stat-berita, #stat-wbs, #stat-views');
    
    stats.forEach(stat => {
        const target = parseInt(stat.textContent.replace(/,/g, ''));
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
}

// Mobile menu toggle
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu) {
        mobileMenu.classList.toggle('hidden');
    }
}

// Add mobile menu event listener
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('[onclick="toggleMobileMenu()"]');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    }
});
</script>
@endpush

@endsection
