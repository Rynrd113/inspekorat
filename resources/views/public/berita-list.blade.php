@extends('layouts.public')

@section('title', 'Semua Berita - Portal Inspektorat Papua Tengah')
@section('description', 'Kumpulan berita dan informasi terbaru dari Inspektorat Provinsi Papua Tengah.')

@section('content')

<div class="min-h-screen bg-gray-50">

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('public.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-home"></i>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-sm font-medium text-gray-900">Semua Berita</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                    Portal Berita
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Informasi terkini dan berita resmi dari Inspektorat Provinsi Papua Tengah
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('public.berita.index') }}" class="space-y-4 lg:space-y-0 lg:flex lg:items-center lg:space-x-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari berita, penulis, atau kata kunci..."
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="lg:w-48">
                    <select name="kategori" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="lg:w-48">
                    <select name="sort" class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terpopuler" {{ request('sort') == 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="lg:w-auto">
                    <button type="submit" class="w-full lg:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <div class="mb-6">
            <p class="text-gray-600">
                Menampilkan {{ $beritaList->firstItem() ?? 0 }} - {{ $beritaList->lastItem() ?? 0 }}
                dari {{ $beritaList->total() }} berita
                @if(request('search'))
                    untuk "<strong>{{ request('search') }}</strong>"
                @endif
                @if(request('kategori'))
                    dalam kategori "<strong>{{ ucfirst(request('kategori')) }}</strong>"
                @endif
            </p>
        </div>

        <!-- News Grid -->
        @if($beritaList->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-12">
                @foreach($beritaList as $berita)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @php $imgSrc = $berita->thumbnail ?? $berita->gambar; @endphp
                            @if(!empty($imgSrc))
                                <img src="{{ filter_var($imgSrc, FILTER_VALIDATE_URL) ? $imgSrc : asset('storage/' . $imgSrc) }}"
                                     alt="{{ $berita->judul }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                     loading="lazy"
                                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100\'><i class=\'fas fa-newspaper text-blue-300 text-4xl\'></i></div>'">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100">
                                    <i class="fas fa-newspaper text-blue-300 text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <div class="p-4 sm:p-5">
                            <div class="mb-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper($berita->kategori) }}
                                </span>
                            </div>

                            <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('public.berita.show', $berita->id) }}" class="block">
                                    {{ $berita->judul }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 line-clamp-2 mb-3 leading-relaxed">
                                {{ Str::limit(strip_tags($berita->konten), 120) }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4 gap-3">
                                <span class="flex items-center min-w-0">
                                    <i class="fas fa-user mr-1"></i>
                                    <span class="truncate">{{ Str::limit($berita->author ?? 'Admin', 18) }}</span>
                                </span>
                                <span class="flex items-center whitespace-nowrap">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $berita->tanggal_publikasi ? \Carbon\Carbon::parse($berita->tanggal_publikasi)->format('d M Y') : 'Tanggal tidak tersedia' }}
                                </span>
                                <span class="flex items-center whitespace-nowrap">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ number_format($berita->views ?? 0) }}
                                </span>
                            </div>

                            <a href="{{ route('public.berita.show', $berita->id) }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 group">
                                <span>Baca Selengkapnya</span>
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $beritaList->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada berita ditemukan</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request('search') || request('kategori'))
                            Coba ubah filter pencarian atau kata kunci untuk menemukan berita yang Anda cari.
                        @else
                            Belum ada berita yang dipublikasikan saat ini.
                        @endif
                    </p>
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('public.berita.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i>
                            Lihat Semua Berita
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
