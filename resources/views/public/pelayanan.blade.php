@extends('layouts.main')

@section('title', 'Pelayanan - Portal Inspektorat Papua Tengah')
@section('description', 'Berbagai layanan yang tersedia di Inspektorat Papua Tengah untuk mendukung tata kelola pemerintahan yang baik.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <x-hero-section 
        title="Pelayanan Kami"
        description="Berbagai layanan yang tersedia di Inspektorat Papua Tengah untuk mendukung tata kelola pemerintahan yang baik."
        icon="fas fa-hands-helping"
    />

    <!-- Breadcrumb -->
    <x-breadcrumb :items="['Pelayanan']" />

    <!-- Search & Filter Section -->
    <section class="bg-white py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search Box -->
                <div class="relative">
                    <input 
                        type="text" 
                        id="searchPelayanan" 
                        placeholder="Cari layanan..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <select 
                    id="filterKategori" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Semua Kategori</option>
                    <option value="audit">Audit</option>
                    <option value="konsultasi">Konsultasi</option>
                    <option value="pengaduan">Pengaduan</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="pelayanan-grid">
                @if(isset($pelayanan) && $pelayanan->count() > 0)
                    @foreach($pelayanan as $service)
                    <div class="service-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300" 
                         data-category="{{ strtolower($service->kategori ?? 'lainnya') }}">
                        @if($service->gambar)
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            <img src="{{ Storage::url($service->gambar) }}" 
                                 alt="{{ $service->nama }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch(strtolower($service->kategori ?? 'lainnya'))
                                        @case('audit') bg-blue-100 text-blue-800 @break
                                        @case('konsultasi') bg-green-100 text-green-800 @break
                                        @case('pengaduan') bg-yellow-100 text-yellow-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ ucfirst($service->kategori ?? 'Lainnya') }}
                                </span>
                                
                                @if($service->status === 'tersedia')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Tidak Tersedia
                                </span>
                                @endif
                            </div>
                            
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $service->nama }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($service->deskripsi, 120) }}</p>
                            
                            <div class="flex items-center justify-between">
                                @if($service->durasi)
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $service->durasi }}
                                </div>
                                @endif
                                
                                <a href="{{ route('public.pelayanan.show', $service->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Detail
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Default Services -->
                    <div class="service-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300" data-category="audit">
                        <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-search text-6xl text-white opacity-80"></i>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Audit
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Audit Internal</h3>
                            <p class="text-gray-600 mb-4">Layanan audit internal untuk memastikan tata kelola yang baik dan akuntabel di lingkungan pemerintahan.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    7-14 hari kerja
                                </div>
                                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Detail
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="service-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300" data-category="konsultasi">
                        <div class="h-48 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                            <i class="fas fa-comments text-6xl text-white opacity-80"></i>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Konsultasi
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Konsultasi Tata Kelola</h3>
                            <p class="text-gray-600 mb-4">Layanan konsultasi untuk meningkatkan sistem tata kelola dan manajemen risiko organisasi.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    1-3 hari kerja
                                </div>
                                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Detail
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="service-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300" data-category="pengaduan">
                        <div class="h-48 bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-6xl text-white opacity-80"></i>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pengaduan
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Whistleblowing System</h3>
                            <p class="text-gray-600 mb-4">Sistem pelaporan pengaduan masyarakat dengan jaminan kerahasiaan identitas pelapor.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    24/7
                                </div>
                                <a href="{{ route('public.wbs') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Detail
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="service-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300" data-category="lainnya">
                        <div class="h-48 bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-6xl text-white opacity-80"></i>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Lainnya
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">Bimbingan Teknis</h3>
                            <p class="text-gray-600 mb-4">Layanan pelatihan dan bimbingan teknis untuk meningkatkan kapasitas aparatur pemerintah.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Sesuai jadwal
                                </div>
                                <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Detail
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- No Results -->
            <div id="no-results" class="hidden text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-search text-4xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Layanan Ditemukan</h3>
                <p class="text-gray-600 mb-4">Coba gunakan kata kunci yang berbeda atau pilih kategori lain.</p>
                <button onclick="clearFilters()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </button>
            </div>
        </div>
    </section>

    <!-- Back to Top -->
    <button id="backToTop" class="hidden fixed bottom-6 right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors z-50">
        <i class="fas fa-arrow-up"></i>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPelayanan');
    const categoryFilter = document.getElementById('filterKategori');
    const serviceCards = document.querySelectorAll('.service-card');
    const noResults = document.getElementById('no-results');
    const backToTop = document.getElementById('backToTop');

    // Search and filter functionality
    function filterServices() {
        const searchTerm = (searchInput?.value || '').toLowerCase().trim();
        const selectedCategory = categoryFilter?.value || '';
        let visibleCount = 0;

        serviceCards.forEach(card => {
            const title = (card.querySelector('h3')?.textContent || '').toLowerCase();
            const description = (card.querySelector('p')?.textContent || '').toLowerCase();
            const category = card.dataset.category || '';

            const matchesSearch = !searchTerm || title.includes(searchTerm) || description.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Show/hide no results
        if (noResults) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterServices);
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterServices);
    }

    // Clear filters
    window.clearFilters = function() {
        if (searchInput) searchInput.value = '';
        if (categoryFilter) categoryFilter.value = '';
        filterServices();
    };

    // Back to top functionality
    window.addEventListener('scroll', function() {
        if (backToTop) {
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('hidden');
            } else {
                backToTop.classList.add('hidden');
            }
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>
@endsection