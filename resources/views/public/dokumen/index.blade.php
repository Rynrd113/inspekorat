@extends('layouts.app')

@section('title', 'Dokumen Publik - Inspektorat Papua Tengah')
@section('description', 'Download dokumen resmi, peraturan, dan laporan dari Inspektorat Provinsi Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-orange-600 to-red-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Dokumen Publik
                </h1>
                <p class="text-xl text-orange-100 max-w-3xl mx-auto">
                    Download dokumen resmi, peraturan, panduan, dan laporan dari Inspektorat Provinsi Papua Tengah
                </p>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               id="search-docs" 
                               placeholder="Cari dokumen..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="md:w-64">
                    <select id="category-filter" class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Kategori</option>
                        <option value="peraturan">Peraturan</option>
                        <option value="sop">SOP</option>
                        <option value="laporan">Laporan</option>
                        <option value="panduan">Panduan</option>
                        <option value="formulir">Formulir</option>
                        <option value="perencanaan">Perencanaan</option>
                        <option value="pedoman">Pedoman</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Documents Grid Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="documents-grid">
                @forelse($dokumens ?? [] as $dokumen)
                <div class="document-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group"
                     data-category="{{ strtolower($dokumen->kategori ?? 'umum') }}"
                     data-title="{{ strtolower($dokumen->judul ?? $dokumen->nama ?? '') }}"
                     data-description="{{ strtolower($dokumen->deskripsi ?? '') }}">
                    
                    <!-- Document Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <!-- File Icon -->
                            <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                @if(str_contains(strtolower($dokumen->file_type ?? 'pdf'), 'pdf'))
                                    <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                                @elseif(str_contains(strtolower($dokumen->file_type ?? ''), 'word'))
                                    <i class="fas fa-file-word text-blue-600 text-2xl"></i>
                                @elseif(str_contains(strtolower($dokumen->file_type ?? ''), 'excel'))
                                    <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                                @else
                                    <i class="fas fa-file-alt text-gray-600 text-2xl"></i>
                                @endif
                            </div>

                            <!-- Category Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ ucfirst($dokumen->kategori ?? 'Dokumen') }}
                            </span>
                        </div>

                        <!-- Document Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-orange-600 transition-colors">
                            {{ $dokumen->judul ?? $dokumen->nama ?? 'Dokumen' }}
                        </h3>

                        <!-- Description -->
                        @if(isset($dokumen->deskripsi))
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            {{ $dokumen->deskripsi }}
                        </p>
                        @endif
                    </div>

                    <!-- Document Meta -->
                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <!-- File Size -->
                            @if(isset($dokumen->file_size))
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-hdd w-4 mr-2"></i>
                                <span>{{ $dokumen->file_size }}</span>
                            </div>
                            @endif

                            <!-- Publication Date -->
                            @if(isset($dokumen->tanggal_publikasi))
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar w-4 mr-2"></i>
                                <span>{{ \Carbon\Carbon::parse($dokumen->tanggal_publikasi)->format('d F Y') }}</span>
                            </div>
                            @endif

                            <!-- Download Count -->
                            @if(isset($dokumen->download_count))
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-download w-4 mr-2"></i>
                                <span>{{ number_format($dokumen->download_count) }} download</span>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('public.dokumen.download', $dokumen->id) }}" 
                               class="flex-1 bg-orange-600 hover:bg-orange-700 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                            
                            @if(isset($dokumen->file_path) && str_contains($dokumen->file_type ?? 'pdf', 'pdf'))
                            <a href="{{ route('public.dokumen.preview', $dokumen->id) }}" 
                               target="_blank"
                               class="px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                               title="Preview Dokumen">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-folder-open text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-medium text-gray-900 mb-4">Belum Ada Dokumen</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Dokumen publik akan segera tersedia untuk diunduh. Silakan periksa kembali nanti.
                    </p>
                </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            @if(isset($dokumens) && $dokumens->count() >= 9)
            <div class="text-center mt-12">
                <button id="load-more" class="bg-white border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Tampilkan Lebih Banyak
                </button>
            </div>
            @endif
        </div>
    </section>

    <!-- Information Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-8">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-info-circle text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Informasi Dokumen
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Semua dokumen yang tersedia di portal ini merupakan dokumen resmi Inspektorat Provinsi Papua Tengah. 
                        Dokumen dapat diunduh secara gratis dan digunakan sesuai dengan ketentuan yang berlaku.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-shield-alt text-orange-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Dokumen Resmi</h4>
                            <p class="text-sm text-gray-600">Semua dokumen terverifikasi</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-download text-orange-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Download Gratis</h4>
                            <p class="text-sm text-gray-600">Tidak ada biaya unduhan</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-sync-alt text-orange-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Update Berkala</h4>
                            <p class="text-sm text-gray-600">Dokumen selalu terbaru</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-docs');
    const categoryFilter = document.getElementById('category-filter');
    const documentCards = document.querySelectorAll('.document-card');

    // Search and filter function
    function filterDocuments() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value.toLowerCase();

        documentCards.forEach(card => {
            const title = card.dataset.title || '';
            const description = card.dataset.description || '';
            const category = card.dataset.category || '';

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterDocuments);
    categoryFilter.addEventListener('change', filterDocuments);

    // Load more functionality
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // This would load more documents via AJAX
            alert('Fitur load more akan segera tersedia');
        });
    }
});
</script>
@endpush
@endsection
