@extends('layouts.public')

@section('title', 'Layanan Publik - Inspektorat Papua Tengah')
@section('description', 'Akses berbagai layanan publik yang disediakan oleh Inspektorat Provinsi Papua Tengah.')

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Layanan Publik
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Berbagai layanan publik yang disediakan oleh Inspektorat Provinsi Papua Tengah untuk masyarakat dan OPD
                </p>
            </div>
        </div>
    </section>

    <!-- Services Grid Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Categories -->
            <div class="mb-12">
                <div class="flex flex-wrap justify-center gap-4">
                    <button class="category-filter active px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors" data-category="all">
                        Semua Layanan
                    </button>
                    <button class="category-filter px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-category="konsultasi">
                        Konsultasi
                    </button>
                    <button class="category-filter px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-category="audit">
                        Audit
                    </button>
                    <button class="category-filter px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-category="reviu">
                        Reviu
                    </button>
                    <button class="category-filter px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors" data-category="evaluasi">
                        Evaluasi
                    </button>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="services-grid">
                @forelse($pelayanans as $pelayanan)
                <div class="service-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden" 
                     data-category="{{ $pelayanan->kategori }}" 
                     style="opacity: 1; transform: translateY(0); transition: all 0.3s ease;">
                    <div class="p-6">
                        <!-- Service Icon -->
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            @if($pelayanan->kategori === 'konsultasi')
                                <i class="fas fa-comments text-blue-600 text-2xl"></i>
                            @elseif($pelayanan->kategori === 'audit')
                                <i class="fas fa-search text-blue-600 text-2xl"></i>
                            @elseif($pelayanan->kategori === 'reviu')
                                <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                            @elseif($pelayanan->kategori === 'evaluasi')
                                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                            @else
                                <i class="fas fa-concierge-bell text-blue-600 text-2xl"></i>
                            @endif
                        </div>

                        <!-- Service Info -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">
                            {{ $pelayanan->nama }}
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $pelayanan->deskripsi }}
                        </p>

                        <!-- Service Meta -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($pelayanan->kategori) }}
                            </span>
                            @if($pelayanan->waktu_penyelesaian)
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $pelayanan->waktu_penyelesaian }}
                            </span>
                            @endif
                        </div>

                        <!-- Action Button -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('public.pelayanan.show', $pelayanan->id) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                <span>Lihat Detail</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            
                            @if($pelayanan->biaya)
                            <span class="text-sm font-medium text-green-600">
                                @if($pelayanan->biaya === 'Gratis' || strtolower($pelayanan->biaya) === 'gratis')
                                    Gratis
                                @elseif(is_numeric($pelayanan->biaya))
                                    Rp {{ number_format($pelayanan->biaya) }}
                                @else
                                    {{ $pelayanan->biaya }}
                                @endif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-concierge-bell text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Layanan</h3>
                    <p class="text-gray-600">Layanan publik akan segera tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Butuh Bantuan?
                </h2>
                <p class="text-xl text-gray-600">
                    Hubungi kami untuk informasi lebih lanjut mengenai layanan yang tersedia
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Instagram -->
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-instagram text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Instagram</h3>
                    <a href="{{ config('contact.instagram.url') }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        {{ config('contact.instagram.handle') }}
                    </a>
                </div>

                <!-- Email -->
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600">{{ config('contact.email') }}</p>
                </div>

                <!-- Office Hours -->
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Jam Layanan</h3>
                    <p class="text-gray-600">{{ config('contact.jam_operasional') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Filter services by category
(function() {
    'use strict';
    
    function initServiceFilter() {
        const filterButtons = document.querySelectorAll('.category-filter');
        const serviceCards = document.querySelectorAll('.service-card');
        
        if (!filterButtons.length || !serviceCards.length) {
            console.log('No filters or cards found');
            return;
        }
        
        filterButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const category = this.getAttribute('data-category');
                console.log('Filter clicked:', category);
                
                // Update active button
                filterButtons.forEach(function(btn) {
                    btn.classList.remove('active', 'bg-blue-600', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
                });
                
                this.classList.add('active', 'bg-blue-600', 'text-white');
                this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');

                // Filter cards with animation
                let visibleCount = 0;
                serviceCards.forEach(function(card) {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (category === 'all' || cardCategory === category) {
                        visibleCount++;
                        card.style.display = 'block';
                        setTimeout(function() {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(10px)';
                        setTimeout(function() {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
                
                console.log('Visible cards:', visibleCount);
            });
        });
        
        console.log('Service filter initialized:', filterButtons.length, 'buttons,', serviceCards.length, 'cards');
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initServiceFilter);
    } else {
        initServiceFilter();
    }
})();
</script>
@endpush

@endsection
