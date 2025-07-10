@extends('layouts.app')

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
                @forelse($pelayanans ?? [] as $pelayanan)
                <div class="service-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden" data-category="{{ $pelayanan->kategori ?? 'umum' }}">
                    <div class="p-6">
                        <!-- Service Icon -->
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            @if(($pelayanan->kategori ?? '') === 'konsultasi')
                                <i class="fas fa-comments text-blue-600 text-2xl"></i>
                            @elseif(($pelayanan->kategori ?? '') === 'audit')
                                <i class="fas fa-search text-blue-600 text-2xl"></i>
                            @elseif(($pelayanan->kategori ?? '') === 'reviu')
                                <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                            @elseif(($pelayanan->kategori ?? '') === 'evaluasi')
                                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                            @else
                                <i class="fas fa-concierge-bell text-blue-600 text-2xl"></i>
                            @endif
                        </div>

                        <!-- Service Info -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">
                            {{ $pelayanan->nama_layanan ?? $pelayanan->nama ?? 'Layanan' }}
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $pelayanan->deskripsi ?? 'Deskripsi layanan tidak tersedia.' }}
                        </p>

                        <!-- Service Meta -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($pelayanan->kategori ?? 'Umum') }}
                            </span>
                            @if(isset($pelayanan->waktu_pelayanan) || isset($pelayanan->waktu_penyelesaian))
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $pelayanan->waktu_pelayanan ?? $pelayanan->waktu_penyelesaian }}
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
                            
                            @if(isset($pelayanan->biaya))
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
                <!-- Phone -->
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Telepon</h3>
                    <p class="text-gray-600">(0984) 21234</p>
                </div>

                <!-- Email -->
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                    <p class="text-gray-600">inspektorat@papuatengah.go.id</p>
                </div>

                <!-- Office Hours -->
                <div class="text-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Jam Layanan</h3>
                    <p class="text-gray-600">Senin - Jumat<br>08:00 - 16:00 WIT</p>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Category filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.category-filter');
    const serviceCards = document.querySelectorAll('.service-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            
            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');

            // Filter cards
            serviceCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush
@endsection
