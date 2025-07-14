@extends('layouts.main')

@section('title', 'FAQ - Inspektorat Papua Tengah')
@section('description', 'Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">

    <!-- Hero Section -->
    <x-hero-section 
        title="Frequently Asked Questions"
        description="Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah"
        icon="fas fa-question-circle"
    />

    <!-- Breadcrumb -->
    <x-breadcrumb :items="['FAQ']" />

    <!-- Search & Filter Section -->
    <section class="bg-white py-8 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Box -->
            <div class="mb-6">
                <x-search-input 
                    id="searchFaq" 
                    placeholder="Cari pertanyaan..."
                    container-class="max-w-md mx-auto"
                    with-icon="true"
                    clear-button="true"
                />
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-3">
                <x-filter-button 
                    filter="" 
                    active="true" 
                    icon="fas fa-th-large" 
                    class="filter-btn"
                    data-category=""
                >
                    Semua
                </x-filter-button>
                <x-filter-button 
                    filter="umum" 
                    icon="fas fa-info-circle" 
                    class="filter-btn"
                    data-category="umum"
                >
                    Umum
                </x-filter-button>
                <x-filter-button 
                    filter="layanan" 
                    icon="fas fa-hands-helping" 
                    class="filter-btn"
                    data-category="layanan"
                >
                    Layanan
                </x-filter-button>
                <x-filter-button 
                    filter="pengaduan" 
                    icon="fas fa-exclamation-triangle" 
                    class="filter-btn"
                    data-category="pengaduan"
                >
                    Pengaduan
                </x-filter-button>
                <x-filter-button 
                    filter="audit" 
                    icon="fas fa-search" 
                    class="filter-btn"
                    data-category="audit"
                >
                    Audit
                </x-filter-button>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-4" id="faq-list">
                @if(isset($faqs) && $faqs->count() > 0)
                    @foreach($faqs as $faq)
                    <div class="faq-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" data-category="{{ strtolower($faq->kategori ?? 'umum') }}">
                        <button 
                            class="w-full px-6 py-4 text-left focus:outline-none focus:bg-gray-50 hover:bg-gray-50 transition-colors"
                            onclick="toggleFaq(this)"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <x-badge variant="primary" size="md">
                                            {{ ucfirst($faq->kategori ?? 'Umum') }}
                                        </x-badge>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 pr-4">{{ $faq->pertanyaan }}</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                                </div>
                            </div>
                        </button>
                        <div class="faq-answer hidden px-6 pb-4">
                            <div class="pt-2 text-gray-700 leading-relaxed">
                                {!! $faq->jawaban !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Default FAQ Items -->
                    <div class="faq-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" data-category="pengaduan">
                        <button 
                            class="w-full px-6 py-4 text-left focus:outline-none focus:bg-gray-50 hover:bg-gray-50 transition-colors"
                            onclick="toggleFaq(this)"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <x-badge variant="warning" size="md">
                                            Pengaduan
                                        </x-badge>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 pr-4">Bagaimana cara menyampaikan pengaduan?</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                                </div>
                            </div>
                        </button>
                        <div class="faq-answer hidden px-6 pb-4">
                            <div class="pt-2 text-gray-700 leading-relaxed">
                                <p class="mb-4">Anda dapat menyampaikan pengaduan melalui beberapa cara:</p>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    <li>Online melalui website WBS</li>
                                    <li>Datang langsung ke kantor</li>
                                    <li>Telepon atau email</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" data-category="layanan">
                        <button 
                            class="w-full px-6 py-4 text-left focus:outline-none focus:bg-gray-50 hover:bg-gray-50 transition-colors"
                            onclick="toggleFaq(this)"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <x-badge variant="primary" size="md">
                                            Layanan
                                        </x-badge>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 pr-4">Apa saja layanan yang tersedia?</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                                </div>
                            </div>
                        </button>
                        <div class="faq-answer hidden px-6 pb-4">
                            <div class="pt-2 text-gray-700 leading-relaxed">
                                <p class="mb-4">Inspektorat Papua Tengah menyediakan berbagai layanan:</p>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    <li>Audit internal</li>
                                    <li>Evaluasi kinerja</li>
                                    <li>Konsultasi tata kelola</li>
                                    <li>Bimbingan teknis</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" data-category="audit">
                        <button 
                            class="w-full px-6 py-4 text-left focus:outline-none focus:bg-gray-50 hover:bg-gray-50 transition-colors"
                            onclick="toggleFaq(this)"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <x-badge variant="success" size="md">
                                            Audit
                                        </x-badge>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 pr-4">Berapa lama proses audit internal?</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                                </div>
                            </div>
                        </button>
                        <div class="faq-answer hidden px-6 pb-4">
                            <div class="pt-2 text-gray-700 leading-relaxed">
                                <p class="mb-4">Waktu pelaksanaan audit internal bervariasi:</p>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    <li>Audit Rutin: 7-14 hari kerja</li>
                                    <li>Audit Khusus: 30-60 hari kerja</li>
                                    <li>Audit Kinerja: 14-30 hari kerja</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" data-category="umum">
                        <button 
                            class="w-full px-6 py-4 text-left focus:outline-none focus:bg-gray-50 hover:bg-gray-50 transition-colors"
                            onclick="toggleFaq(this)"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="mb-2">
                                        <x-badge variant="secondary" size="md">
                                            Umum
                                        </x-badge>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 pr-4">Apakah identitas pelapor dirahasiakan?</h3>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                                </div>
                            </div>
                        </button>
                        <div class="faq-answer hidden px-6 pb-4">
                            <div class="pt-2 text-gray-700 leading-relaxed">
                                <p class="text-gray-700">Ya, identitas pelapor akan dijamin kerahasiaannya. Kami berkomitmen penuh melindungi setiap pelapor yang beritikad baik.</p>
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Hasil Ditemukan</h3>
                <p class="text-gray-600 mb-4">Coba gunakan kata kunci yang berbeda atau pilih kategori lain.</p>
                <x-button onclick="clearSearch()" variant="primary" size="md">
                    <i class="fas fa-redo mr-2"></i>Reset Pencarian
                </x-button>
            </div>
        </div>
    </section>

    <!-- Back to Top -->
    <x-button 
        id="backToTop" 
        variant="primary" 
        class="hidden fixed bottom-6 right-6 p-3 rounded-full shadow-lg"
    >
        <i class="fas fa-arrow-up"></i>
    </x-button>
</div>

<script>
// Simple FAQ functionality with Tailwind
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchFaq');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('no-results');
    const backToTop = document.getElementById('backToTop');

    // Toggle FAQ
    window.toggleFaq = function(button) {
        const answer = button.parentElement.querySelector('.faq-answer');
        const icon = button.querySelector('i');
        
        if (answer.classList.contains('hidden')) {
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            answer.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    };

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', filterFAQs);
    }

    // Filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('bg-blue-600', 'text-white');
            
            filterFAQs();
        });
    });

    // Filter function
    function filterFAQs() {
        const searchTerm = (searchInput?.value || '').toLowerCase().trim();
        const activeCategory = document.querySelector('.filter-btn.bg-blue-600')?.dataset.category || '';
        let visibleCount = 0;

        faqItems.forEach(item => {
            const question = (item.querySelector('h3')?.textContent || '').toLowerCase();
            const answer = (item.querySelector('.faq-answer')?.textContent || '').toLowerCase();
            const category = item.dataset.category || '';

            const matchesSearch = !searchTerm || question.includes(searchTerm) || answer.includes(searchTerm);
            const matchesCategory = !activeCategory || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
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

    // Clear search
    window.clearSearch = function() {
        if (searchInput) {
            searchInput.value = '';
        }
        const defaultFilter = document.querySelector('.filter-btn[data-category=""]');
        if (defaultFilter) {
            defaultFilter.click();
        }
        filterFAQs();
    };

    // Back to top
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