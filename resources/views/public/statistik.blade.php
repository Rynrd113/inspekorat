@extends('layouts.public')

@section('title', 'Statistik - Portal Inspektorat Papua Tengah')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">Statistik & Analitik</h1>
                <p class="text-xl text-blue-100">Data dan informasi penggunaan Portal Inspektorat Papua Tengah</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('public.index') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li class="text-gray-900 font-medium">Statistik</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Key Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Portal OPD -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-600 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Portal OPD</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['portal_opd_count'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-building text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Berita -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-green-600 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Artikel Berita</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['berita_count'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-newspaper text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-600 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Dokumen Publik</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['dokumen_count'] }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <i class="fas fa-file-pdf text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Galeri -->
            <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-600 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Foto Galeri</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['galeri_count'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-images text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Pelayanan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 rounded-full p-3 mr-4">
                        <i class="fas fa-concierge-bell text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Pelayanan</h3>
                </div>
                <p class="text-3xl font-bold text-indigo-600 mb-2">{{ $statistics['pelayanan_count'] }}</p>
                <p class="text-sm text-gray-600">Jenis layanan publik tersedia</p>
            </div>

            <!-- WBS (Whistleblowing System) -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 rounded-full p-3 mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Laporan WBS</h3>
                </div>
                <p class="text-3xl font-bold text-red-600 mb-2">{{ $statistics['wbs_count'] }}</p>
                <p class="text-sm text-gray-600">Laporan sistem pelaporan</p>
            </div>

            <!-- Pengaduan -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-100 rounded-full p-3 mr-4">
                        <i class="fas fa-message text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Pengaduan</h3>
                </div>
                <p class="text-3xl font-bold text-orange-600 mb-2">{{ $statistics['pengaduan_count'] }}</p>
                <p class="text-sm text-gray-600">Aduan masyarakat yang masuk</p>
            </div>
        </div>

        <!-- User Engagement -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <!-- Total Visitors -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Pengunjung Portal</h3>
                    <div class="bg-teal-100 rounded-full p-4">
                        <i class="fas fa-users text-teal-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-4xl font-bold text-teal-600 mb-2">{{ number_format($statistics['total_visitors']) }}</p>
                <p class="text-sm text-gray-600">Total pengunjung unik sepanjang tahun</p>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Total Tampilan</h3>
                    <div class="bg-cyan-100 rounded-full p-4">
                        <i class="fas fa-eye text-cyan-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-4xl font-bold text-cyan-600 mb-2">{{ number_format($statistics['total_views']) }}</p>
                <p class="text-sm text-gray-600">Total halaman yang dilihat</p>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
            <!-- Kategori Berita -->
            @if(count($kategoriBerita) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-chart-pie text-green-600 mr-2"></i>Distribusi Kategori Berita
                </h3>
                <div class="space-y-4">
                    @foreach($kategoriBerita as $kategori => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 font-medium">{{ ucfirst($kategori) }}</span>
                        <div class="flex items-center gap-3">
                            <div class="w-32 bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-600 h-2.5 rounded-full"
                                     style="width: {{ ($count / array_sum($kategoriBerita)) * 100 }}%"></div>
                            </div>
                            <span class="text-gray-900 font-bold min-w-max">{{ $count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Kategori Dokumen -->
            @if(count($kategoriDokumen) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-chart-pie text-yellow-600 mr-2"></i>Distribusi Kategori Dokumen
                </h3>
                <div class="space-y-4">
                    @foreach($kategoriDokumen as $kategori => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 font-medium">{{ ucfirst($kategori) }}</span>
                        <div class="flex items-center gap-3">
                            <div class="w-32 bg-gray-200 rounded-full h-2.5">
                                <div class="bg-yellow-600 h-2.5 rounded-full"
                                     style="width: {{ ($count / array_sum($kategoriDokumen)) * 100 }}%"></div>
                            </div>
                            <span class="text-gray-900 font-bold min-w-max">{{ $count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Kategori Pengaduan -->
        @if(count($kategoriPengaduan) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-12">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-chart-pie text-orange-600 mr-2"></i>Distribusi Kategori Pengaduan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($kategoriPengaduan as $kategori => $count)
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4">
                    <p class="text-gray-700 font-medium mb-2">{{ ucfirst($kategori) }}</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $count }}</p>
                    <p class="text-xs text-gray-600 mt-1">Pengaduan masuk</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
            <!-- Top Berita -->
            @if($topBerita->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-star text-yellow-600 mr-2"></i>Berita Paling Banyak Dilihat
                </h3>
                <div class="space-y-4">
                    @foreach($topBerita as $berita)
                    <div class="flex items-start gap-4 pb-4 border-b last:border-b-0">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3 w-12 h-12 flex items-center justify-center">
                            <i class="fas fa-fire text-yellow-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 truncate hover:text-blue-600 cursor-pointer">
                                <a href="{{ route('public.berita.show', $berita->id) }}">{{ $berita->judul }}</a>
                            </h4>
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="fas fa-eye text-gray-400 mr-1"></i>{{ number_format($berita->views) }} views
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Info Section -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                <h3 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>Tentang Statistik
                </h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <p>
                        <strong>📊 Statistik Real-time:</strong> Data yang ditampilkan diperbarui secara real-time dari database portal.
                    </p>
                    <p>
                        <strong>👥 Pengunjung:</strong> Jumlah pengunjung unik dihitung per IP dan hari untuk akurasi yang lebih baik.
                    </p>
                    <p>
                        <strong>📈 Tren Data:</strong> Data statistik digunakan untuk mengoptimalkan konten dan layanan portal.
                    </p>
                    <p>
                        <strong>🔐 Privasi:</strong> Semua data pengunjung dijaga kerahasiaannya dan hanya digunakan untuk analisis portal.
                    </p>
                </div>
            </div>
        </div>

        <!-- Gallery Showcase -->
        @if($topGaleri->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-images text-purple-600 mr-2"></i>Galeri Terbaru
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($topGaleri as $item)
                <a href="{{ route('public.galeri.show', $item->id) }}"
                   class="group bg-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 h-48">
                    @php
                        $imagePath = $item->file_path;
                        $imageUrl = null;
                        $isImage = in_array($item->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

                        if (file_exists(public_path('storage/' . $imagePath)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                            $imageUrl = asset('storage/' . $imagePath);
                        } elseif (file_exists(public_path('uploads/' . $imagePath))) {
                            $imageUrl = asset('uploads/' . $imagePath);
                        }
                    @endphp

                    @if($imageUrl && $isImage)
                        <img src="{{ $imageUrl }}"
                             alt="{{ $item->judul }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                             loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

