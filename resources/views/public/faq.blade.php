@extends('layouts.app')

@section('title', 'FAQ - Inspektorat Papua Tengah')
@section('description', 'Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Frequently Asked Questions
                </h1>
                <p class="text-xl text-purple-100 max-w-3xl mx-auto">
                    Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah
                </p>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           id="searchFaq" 
                           placeholder="Cari pertanyaan..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                </div>
                <p class="text-center text-sm text-gray-500 mt-3">
                    Ketik kata kunci untuk mencari pertanyaan yang Anda butuhkan
                </p>
            </div>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="py-6 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Kategori Pertanyaan</h3>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                <button class="category-filter active px-4 py-2 bg-purple-600 text-white rounded-full text-sm font-medium hover:bg-purple-700 transition-colors" data-category="">
                    <i class="fas fa-th-large mr-1"></i> Semua
                </button>
                <button class="category-filter px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors" data-category="umum">
                    <i class="fas fa-info-circle mr-1"></i> Umum
                </button>
                <button class="category-filter px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors" data-category="layanan">
                    <i class="fas fa-hands-helping mr-1"></i> Layanan
                </button>
                <button class="category-filter px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors" data-category="pengaduan">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Pengaduan
                </button>
                <button class="category-filter px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors" data-category="audit">
                    <i class="fas fa-search mr-1"></i> Audit
                </button>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Featured FAQ -->
            @if(isset($popularFaqs) && $popularFaqs->count() > 0)
            <div class="mb-12">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Pertanyaan Populer</h3>
                    <p class="text-gray-600">Pertanyaan yang paling sering diajukan</p>
                </div>
                
                <div class="space-y-4">
                    @foreach($popularFaqs as $faq)
                    <div class="faq-item bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-xl p-6" data-category="{{ strtolower($faq->kategori ?? 'umum') }}">
                        <button class="faq-toggle w-full flex items-start justify-between text-left group" onclick="toggleFaq(this)">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($faq->kategori ?? 'Umum') }}
                                    </span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    {{ $faq->pertanyaan }}
                                </h4>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden mt-4 pt-4 border-t border-purple-200">
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! $faq->jawaban !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Regular FAQ -->
            <div class="space-y-4" id="faq-list">
                @if(isset($faqs) && $faqs->count() > 0)
                    @foreach($faqs as $faq)
                    <div class="faq-item bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow" data-category="{{ strtolower($faq->kategori ?? 'umum') }}">
                        <button class="faq-toggle w-full flex items-start justify-between text-left group" onclick="toggleFaq(this)">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($faq->kategori ?? 'Umum') }}
                                    </span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    {{ $faq->pertanyaan }}
                                </h4>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden mt-4 pt-4 border-t border-gray-200" style="transition: all 0.3s ease;">
                            <div class="prose prose-sm max-w-none text-gray-700" style="color: #374151 !important;">
                                {!! $faq->jawaban !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Sample FAQs for demo -->
                    <div class="faq-item bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow" data-category="pengaduan">
                        <button class="faq-toggle w-full flex items-start justify-between text-left group" onclick="toggleFaq(this)">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pengaduan
                                    </span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    Bagaimana cara menyampaikan pengaduan?
                                </h4>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden mt-4 pt-4 border-t border-gray-200">
                            <div class="prose prose-sm max-w-none text-gray-700">
                                <p>Anda dapat menyampaikan pengaduan melalui beberapa cara:</p>
                                <ol>
                                    <li><strong>Online melalui WBS (Whistle Blowing System):</strong>
                                        <ul>
                                            <li>Kunjungi halaman <a href="{{ route('public.wbs') }}" class="text-purple-600 hover:text-purple-800">WBS</a></li>
                                            <li>Isi formulir pengaduan dengan lengkap</li>
                                            <li>Upload bukti pendukung jika ada</li>
                                            <li>Simpan nomor tiket untuk follow-up</li>
                                        </ul>
                                    </li>
                                    <li><strong>Datang langsung ke kantor:</strong>
                                        <ul>
                                            <li>Alamat: Jl. Raya Nabire No. 123, Nabire, Papua Tengah</li>
                                            <li>Jam kerja: Senin-Jumat, 08:00-16:00 WIT</li>
                                        </ul>
                                    </li>
                                    <li><strong>Melalui telepon/email:</strong>
                                        <ul>
                                            <li>Telepon: (0984) 21234</li>
                                            <li>Email: pengaduan@inspektorat-papuatengah.go.id</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow" data-category="layanan">
                        <button class="faq-toggle w-full flex items-start justify-between text-left group" onclick="toggleFaq(this)">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Layanan
                                    </span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    Apa saja layanan yang tersedia di Inspektorat?
                                </h4>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden mt-4 pt-4 border-t border-gray-200">
                            <div class="prose prose-sm max-w-none text-gray-700">
                                <p>Inspektorat Papua Tengah menyediakan berbagai layanan, antara lain:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <h6 class="font-semibold text-gray-900">Layanan Audit & Pengawasan:</h6>
                                        <ul>
                                            <li>Audit internal</li>
                                            <li>Evaluasi kinerja</li>
                                            <li>Investigasi khusus</li>
                                            <li>Pemantauan tindak lanjut</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold text-gray-900">Layanan Konsultasi:</h6>
                                        <ul>
                                            <li>Konsultasi tata kelola</li>
                                            <li>Bimbingan teknis</li>
                                            <li>Pelatihan SDM</li>
                                            <li>Asistensi implementasi</li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="mt-3">Seluruh layanan ini <strong>gratis</strong> dan dapat diakses oleh OPD di lingkungan Pemerintah Papua Tengah.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow" data-category="audit">
                        <button class="faq-toggle w-full flex items-start justify-between text-left group" onclick="toggleFaq(this)">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Audit
                                    </span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    Berapa lama proses audit internal?
                                </h4>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden mt-4 pt-4 border-t border-gray-200">
                            <div class="prose prose-sm max-w-none text-gray-700">
                                <p>Waktu pelaksanaan audit internal bervariasi tergantung pada:</p>
                                <ul>
                                    <li><strong>Jenis audit:</strong> Audit kinerja, audit keuangan, atau audit khusus</li>
                                    <li><strong>Ruang lingkup:</strong> Luas area yang diaudit</li>
                                    <li><strong>Kompleksitas:</strong> Tingkat kerumitan sistem dan proses</li>
                                </ul>
                                
                                <div class="overflow-x-auto mt-4">
                                    <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Audit</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Audit Rutin</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">7-14 hari kerja</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Audit Khusus/Investigasi</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">30-60 hari kerja</td>
                                            </tr>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Audit Kinerja</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">14-30 hari kerja</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow" data-category="umum">
                        <button class="faq-toggle w-full flex items-start justify-between text-left group" onclick="toggleFaq(this)">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Umum
                                    </span>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    Apakah identitas pelapor akan dirahasiakan?
                                </h4>
                            </div>
                            <div class="ml-4">
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden mt-4 pt-4 border-t border-gray-200">
                            <div class="prose prose-sm max-w-none text-gray-700">
                                <p><strong>Ya, identitas pelapor akan dijamin kerahasiaannya.</strong></p>
                                
                                <h6 class="font-semibold text-gray-900 mt-4">Perlindungan yang diberikan:</h6>
                                <ul>
                                    <li>Kerahasiaan identitas pelapor</li>
                                    <li>Perlindungan dari intimidasi atau pembalasan</li>
                                    <li>Komunikasi aman melalui sistem WBS</li>
                                    <li>Hak untuk melaporkan secara anonim</li>
                                </ul>
                                
                                <h6 class="font-semibold text-gray-900 mt-4">Sistem WBS (Whistle Blowing System):</h6>
                                <ul>
                                    <li>Enkripsi data untuk keamanan informasi</li>
                                    <li>Nomor tiket unik untuk komunikasi</li>
                                    <li>Tim khusus penanganan pengaduan</li>
                                    <li>Prosedur standar perlindungan pelapor</li>
                                </ul>
                                
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                                    <div class="flex">
                                        <i class="fas fa-shield-alt text-green-600 mr-3 mt-1"></i>
                                        <div>
                                            <p class="text-green-800 font-medium">Jaminan Keamanan</p>
                                            <p class="text-green-700 text-sm">Semua laporan ditangani dengan standar keamanan tinggi dan kerahasiaan terjamin.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="hidden text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada Hasil</h3>
                <p class="text-gray-600">Coba gunakan kata kunci yang berbeda atau pilih kategori lain.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-8">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-question-circle text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        Pertanyaan Tidak Terjawab?
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Jika pertanyaan Anda belum terjawab, jangan ragu untuk menghubungi kami secara langsung.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-phone text-purple-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Telepon</h4>
                            <p class="text-sm text-gray-600">(0984) 21234</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Email</h4>
                            <p class="text-sm text-gray-600">info@inspektorat-papuatengah.go.id</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">Alamat</h4>
                            <p class="text-sm text-gray-600">Jl. Raya Nabire No. 123</p>
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
    const searchInput = document.getElementById('searchFaq');
    const categoryButtons = document.querySelectorAll('.category-filter');
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('no-results');

    // FAQ Toggle Function
    window.toggleFaq = function(button) {
        const faqItem = button.closest('.faq-item');
        const content = faqItem.querySelector('.faq-content');
        const icon = button.querySelector('i');
        
        // Close all other FAQs first
        faqItems.forEach(item => {
            if (item !== faqItem) {
                const otherContent = item.querySelector('.faq-content');
                const otherIcon = item.querySelector('.faq-toggle i');
                if (otherContent && !otherContent.classList.contains('hidden')) {
                    otherContent.classList.add('hidden');
                    if (otherIcon) {
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                }
            }
        });
        
        // Toggle current FAQ with animation
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            content.style.opacity = '0';
            content.style.maxHeight = '0';
            content.style.overflow = 'hidden';
            
            // Force reflow
            content.offsetHeight;
            
            content.style.transition = 'all 0.3s ease';
            content.style.opacity = '1';
            content.style.maxHeight = '1000px';
            
            if (icon) {
                icon.style.transform = 'rotate(180deg)';
            }
            
            // Clean up after animation
            setTimeout(() => {
                content.style.maxHeight = 'none';
                content.style.overflow = 'visible';
            }, 300);
        } else {
            content.style.transition = 'all 0.3s ease';
            content.style.opacity = '0';
            content.style.maxHeight = '0';
            content.style.overflow = 'hidden';
            
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
            }
            
            setTimeout(() => {
                content.classList.add('hidden');
                content.style.opacity = '';
                content.style.maxHeight = '';
                content.style.overflow = '';
                content.style.transition = '';
            }, 300);
        }
    };

    // Category Filter
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            categoryButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-purple-600', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            });
            
            this.classList.add('active', 'bg-purple-600', 'text-white');
            this.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');

            filterFaqs();
        });
    });

    // Search Function
    searchInput.addEventListener('input', filterFaqs);

    function filterFaqs() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeCategory = document.querySelector('.category-filter.active').dataset.category;
        let visibleCount = 0;

        faqItems.forEach(item => {
            const question = item.querySelector('h4').textContent.toLowerCase();
            const answerElement = item.querySelector('.faq-content');
            const answer = answerElement ? answerElement.textContent.toLowerCase() : '';
            const category = item.dataset.category;

            const matchesSearch = question.includes(searchTerm) || answer.includes(searchTerm);
            const matchesCategory = !activeCategory || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }
});
</script>
@endpush
                    
                    <div class="accordion" id="featuredFaq">
                        <div class="accordion-item featured-faq">
                            <h2 class="accordion-header" id="featured1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#featuredCollapse1">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong>Apa itu Inspektorat Papua Tengah?</strong>
                                </button>
                            </h2>
                            <div id="featuredCollapse1" class="accordion-collapse collapse" data-bs-parent="#featuredFaq">
                                <div class="accordion-body">
                                    <p>Inspektorat Papua Tengah adalah lembaga pengawasan internal pemerintah yang bertugas melakukan audit, reviu, evaluasi, pemantauan, dan kegiatan pengawasan lainnya terhadap penyelenggaraan tugas dan fungsi organisasi.</p>
                                    <p>Inspektorat berperan penting dalam mewujudkan tata kelola pemerintahan yang baik (good governance) melalui:</p>
                                    <ul>
                                        <li>Pengawasan internal yang efektif</li>
                                        <li>Pemberian jaminan kualitas (quality assurance)</li>
                                        <li>Konsultasi dan pembimbingan</li>
                                        <li>Pencegahan korupsi, kolusi, dan nepotisme</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular FAQ -->
                <div class="accordion" id="faqAccordion">
                    <!-- FAQ Item 1 - Umum -->
                    <div class="accordion-item faq-item" data-category="pengaduan">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
                                <span class="badge bg-warning me-2">Pengaduan</span>
                                Bagaimana cara menyampaikan pengaduan?
                            </button>
                        </h2>
                        <div id="faqCollapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Anda dapat menyampaikan pengaduan melalui beberapa cara:</p>
                                <ol>
                                    <li><strong>Online melalui WBS (Whistle Blowing System):</strong>
                                        <ul>
                                            <li>Kunjungi halaman <a href="{{ route('public.wbs') }}" class="text-primary">WBS</a></li>
                                            <li>Isi formulir pengaduan dengan lengkap</li>
                                            <li>Upload bukti pendukung jika ada</li>
                                            <li>Simpan nomor tiket untuk follow-up</li>
                                        </ul>
                                    </li>
                                    <li><strong>Datang langsung ke kantor:</strong>
                                        <ul>
                                            <li>Alamat: Jl. Contoh No. 123, Nabire, Papua Tengah</li>
                                            <li>Jam kerja: Senin-Jumat, 08:00-16:00 WIT</li>
                                        </ul>
                                    </li>
                                    <li><strong>Melalui telepon/email:</strong>
                                        <ul>
                                            <li>Telepon: (021) 123-4567</li>
                                            <li>Email: pengaduan@inspektorat-papuatengah.go.id</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 - Layanan -->
                    <div class="accordion-item faq-item" data-category="layanan">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
                                <span class="badge bg-info me-2">Layanan</span>
                                Apa saja layanan yang tersedia di Inspektorat?
                            </button>
                        </h2>
                        <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Inspektorat Papua Tengah menyediakan berbagai layanan, antara lain:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Layanan Audit & Pengawasan:</h6>
                                        <ul>
                                            <li>Audit internal</li>
                                            <li>Evaluasi kinerja</li>
                                            <li>Investigasi khusus</li>
                                            <li>Pemantauan tindak lanjut</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Layanan Konsultasi:</h6>
                                        <ul>
                                            <li>Konsultasi tata kelola</li>
                                            <li>Bimbingan teknis</li>
                                            <li>Pelatihan SDM</li>
                                            <li>Asistensi implementasi</li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="mt-3">Seluruh layanan ini <strong>gratis</strong> dan dapat diakses oleh OPD di lingkungan Pemerintah Papua Tengah.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 - Audit -->
                    <div class="accordion-item faq-item" data-category="audit">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
                                <span class="badge bg-success me-2">Audit</span>
                                Berapa lama proses audit internal?
                            </button>
                        </h2>
                        <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Waktu pelaksanaan audit internal bervariasi tergantung pada:</p>
                                <ul>
                                    <li><strong>Jenis audit:</strong> Audit kinerja, audit keuangan, atau audit khusus</li>
                                    <li><strong>Ruang lingkup:</strong> Luas area yang diaudit</li>
                                    <li><strong>Kompleksitas:</strong> Tingkat kerumitan sistem dan proses</li>
                                </ul>
                                
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Jenis Audit</th>
                                                <th>Estimasi Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Audit Rutin</td>
                                                <td>7-14 hari kerja</td>
                                            </tr>
                                            <tr>
                                                <td>Audit Khusus/Investigasi</td>
                                                <td>30-60 hari kerja</td>
                                            </tr>
                                            <tr>
                                                <td>Audit Kinerja</td>
                                                <td>14-30 hari kerja</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 - Umum -->
                    <div class="accordion-item faq-item" data-category="umum">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4">
                                <span class="badge bg-primary me-2">Umum</span>
                                Bagaimana cara mengetahui hasil audit?
                            </button>
                        </h2>
                        <div id="faqCollapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Hasil audit dapat diketahui melalui:</p>
                                <ol>
                                    <li><strong>Laporan Resmi:</strong> Dikirim ke pimpinan OPD yang diaudit</li>
                                    <li><strong>Rapat Exit Meeting:</strong> Presentasi hasil audit kepada auditee</li>
                                    <li><strong>Portal Website:</strong> Ringkasan hasil audit (jika dipublikasikan)</li>
                                    <li><strong>Monitoring Tindak Lanjut:</strong> Update status rekomendasi</li>
                                </ol>
                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Catatan:</strong> Tidak semua hasil audit dipublikasikan. Publikasi tergantung pada jenis audit dan kebijakan organisasi.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 - Pengaduan -->
                    <div class="accordion-item faq-item" data-category="pengaduan">
                        <h2 class="accordion-header" id="faq5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5">
                                <span class="badge bg-warning me-2">Pengaduan</span>
                                Apakah identitas pelapor akan dirahasiakan?
                            </button>
                        </h2>
                        <div id="faqCollapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p><strong>Ya, identitas pelapor akan dijamin kerahasiaannya.</strong></p>
                                
                                <h6>Perlindungan yang diberikan:</h6>
                                <ul>
                                    <li>Kerahasiaan identitas pelapor</li>
                                    <li>Perlindungan dari intimidasi atau pembalasan</li>
                                    <li>Komunikasi aman melalui sistem WBS</li>
                                    <li>Hak untuk melaporkan secara anonim</li>
                                </ul>
                                
                                <h6>Sistem WBS (Whistle Blowing System):</h6>
                                <ul>
                                    <li>Enkripsi data untuk keamanan informasi</li>
                                    <li>Nomor tiket unik untuk komunikasi</li>
                                    <li>Tim khusus penanganan pengaduan</li>
                                    <li>Prosedur standar perlindungan pelapor</li>
                                </ul>
                                
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Jaminan:</strong> Inspektorat berkomitmen penuh melindungi setiap pelapor yang beritikad baik dalam menyampaikan informasi.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 6 - Layanan -->
                    <div class="accordion-item faq-item" data-category="layanan">
                        <h2 class="accordion-header" id="faq6">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6">
                                <span class="badge bg-info me-2">Layanan</span>
                                Bagaimana cara mengajukan permohonan audit?
                            </button>
                        </h2>
                        <div id="faqCollapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Untuk mengajukan permohonan audit, ikuti langkah berikut:</p>
                                
                                <h6>1. Persiapan Dokumen:</h6>
                                <ul>
                                    <li>Surat permohonan resmi dari pimpinan OPD</li>
                                    <li>Latar belakang dan tujuan audit</li>
                                    <li>Ruang lingkup yang diinginkan</li>
                                    <li>Jadwal yang diusulkan</li>
                                </ul>
                                
                                <h6>2. Proses Pengajuan:</h6>
                                <ol>
                                    <li>Kirim surat permohonan ke Inspektorat</li>
                                    <li>Tim Inspektorat akan melakukan evaluasi</li>
                                    <li>Konfirmasi penerimaan dan jadwal</li>
                                    <li>Koordinasi teknis pelaksanaan</li>
                                </ol>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6>Kontak Pengajuan:</h6>
                                                <p class="mb-1"><i class="fas fa-envelope me-2"></i> audit@inspektorat-papuatengah.go.id</p>
                                                <p class="mb-1"><i class="fas fa-phone me-2"></i> (021) 123-4567</p>
                                                <p class="mb-0"><i class="fas fa-clock me-2"></i> Senin-Jumat: 08:00-16:00 WIT</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6>Estimasi Waktu:</h6>
                                                <p class="mb-1">Evaluasi permohonan: 3-5 hari kerja</p>
                                                <p class="mb-1">Persiapan audit: 7-10 hari kerja</p>
                                                <p class="mb-0">Pelaksanaan: Sesuai ruang lingkup</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="text-center py-5" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>Tidak ditemukan hasil</h5>
                    <p class="text-muted">Coba gunakan kata kunci yang berbeda atau pilih kategori lain.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h3 class="mb-3">Tidak Menemukan Jawaban yang Anda Cari?</h3>
        <p class="lead mb-4">Tim kami siap membantu menjawab pertanyaan Anda</p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('public.kontak') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-phone me-2"></i> Hubungi Kami
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('public.wbs') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-exclamation-triangle me-2"></i> Laporkan Masalah
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="mailto:info@inspektorat-papuatengah.go.id" class="btn btn-outline-secondary btn-lg w-100">
                            <i class="fas fa-envelope me-2"></i> Email Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Search functionality
document.getElementById('searchFaq').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    filterFAQ();
});

// Category filter
document.querySelectorAll('input[name="category"]').forEach(radio => {
    radio.addEventListener('change', function() {
        filterFAQ();
    });
});

function filterFAQ() {
    const searchTerm = document.getElementById('searchFaq').value.toLowerCase();
    const selectedCategory = document.querySelector('input[name="category"]:checked').value;
    
    const faqItems = document.querySelectorAll('.faq-item');
    let visibleCount = 0;
    
    faqItems.forEach(item => {
        const category = item.dataset.category;
        const question = item.querySelector('.accordion-button').textContent.toLowerCase();
        const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
        
        let show = true;
        
        // Category filter
        if (selectedCategory && category !== selectedCategory) {
            show = false;
        }
        
        // Search filter
        if (searchTerm && !question.includes(searchTerm) && !answer.includes(searchTerm)) {
            show = false;
        }
        
        if (show) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

// Highlight search terms
function highlightSearchTerms() {
    const searchTerm = document.getElementById('searchFaq').value.toLowerCase();
    
    if (searchTerm.length > 2) {
        const faqItems = document.querySelectorAll('.faq-item:not([style*="display: none"])');
        
        faqItems.forEach(item => {
            const button = item.querySelector('.accordion-button');
            const body = item.querySelector('.accordion-body');
            
            // Remove existing highlights
            button.innerHTML = button.innerHTML.replace(/<mark[^>]*>([^<]+)<\/mark>/gi, '$1');
            body.innerHTML = body.innerHTML.replace(/<mark[^>]*>([^<]+)<\/mark>/gi, '$1');
            
            // Add new highlights
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            button.innerHTML = button.innerHTML.replace(regex, '<mark class="bg-warning">$1</mark>');
            body.innerHTML = body.innerHTML.replace(regex, '<mark class="bg-warning">$1</mark>');
        });
    }
}

// Debounced highlight function
let highlightTimeout;
document.getElementById('searchFaq').addEventListener('input', function() {
    clearTimeout(highlightTimeout);
    highlightTimeout = setTimeout(highlightSearchTerms, 500);
});

// Smooth scroll to FAQ item if coming from external link
window.addEventListener('load', function() {
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target && target.classList.contains('faq-item')) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Auto-open the FAQ item
                const button = target.querySelector('.accordion-button');
                if (button && button.classList.contains('collapsed')) {
                    button.click();
                }
            }, 500);
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.faq-content {
    transition: all 0.3s ease;
}

.faq-content.hidden {
    display: none;
}

.faq-content .prose {
    color: #374151 !important;
}

.faq-content .prose * {
    color: inherit !important;
}

.faq-content .prose p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.faq-content .prose ul {
    list-style-type: disc;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.faq-content .prose ol {
    list-style-type: decimal;
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.faq-content .prose li {
    margin-bottom: 0.5rem;
}

.faq-toggle i {
    transition: transform 0.3s ease;
}

.hero-section {
    background: linear-gradient(135deg, #9333ea 0%, #3b82f6 100%);
}

.btn-group .btn {
    border-color: #007bff;
    color: white;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-bottom: 5px;
    }
}
</style>
@endpush
