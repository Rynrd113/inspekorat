@extends('layouts.public')

@section('title', 'FAQ - Inspektorat Papua Tengah')
@section('description', 'Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah.')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Frequently Asked Questions</h1>
                <p class="lead">Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-question-circle fa-5x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('public.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">FAQ</li>
        </ol>
    </div>
</nav>

<!-- Search Section -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="position-relative">
                    <input type="text"
                           id="searchFaq"
                           class="form-control form-control-lg ps-5"
                           placeholder="Cari pertanyaan...">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <div id="search-loading" class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <small class="text-muted">Ketik kata kunci untuk mencari pertanyaan yang Anda butuhkan</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Filter -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h5 class="fw-semibold">Kategori Pertanyaan</h5>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <button class="btn btn-primary category-filter active" data-category="">
                <i class="fas fa-th-large me-1"></i> Semua
            </button>
            <button class="btn btn-outline-primary category-filter" data-category="umum">
                <i class="fas fa-info-circle me-1"></i> Umum
            </button>
            <button class="btn btn-outline-primary category-filter" data-category="layanan">
                <i class="fas fa-hands-helping me-1"></i> Layanan
            </button>
            <button class="btn btn-outline-primary category-filter" data-category="pengaduan">
                <i class="fas fa-exclamation-triangle me-1"></i> Pengaduan
            </button>
            <button class="btn btn-outline-primary category-filter" data-category="audit">
                <i class="fas fa-search me-1"></i> Audit
            </button>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-lg-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="display-6 text-primary mb-3">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3 class="fw-bold text-primary">25+</h3>
                        <p class="text-muted mb-0">Pertanyaan Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="display-6 text-success mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="fw-bold text-success">1000+</h3>
                        <p class="text-muted mb-0">Pengguna Terbantu</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="display-6 text-warning mb-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="fw-bold text-warning">24/7</h3>
                        <p class="text-muted mb-0">Akses Informasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular FAQ Section -->
        @if(isset($popularFaqs) && $popularFaqs->count() > 0)
        <div class="mb-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Pertanyaan Populer</h3>
                <p class="text-muted">Pertanyaan yang paling sering diajukan</p>
            </div>

            <div class="row">
                @foreach($popularFaqs as $faq)
                <div class="col-12 mb-3">
                    <div class="card border-primary faq-item" data-category="{{ strtolower($faq->kategori ?? 'umum') }}">
                        <div class="card-header bg-primary bg-opacity-10 border-primary">
                            <button class="btn btn-link w-100 text-start text-decoration-none faq-toggle p-0"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq-popular-{{ $loop->index }}"
                                    aria-expanded="false">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="mb-2">
                                            <i class="fas fa-star text-warning me-2"></i>
                                            <span class="badge bg-primary">{{ ucfirst($faq->kategori ?? 'Umum') }}</span>
                                        </div>
                                        <h5 class="mb-0 text-dark">{{ $faq->pertanyaan }}</h5>
                                    </div>
                                    <div>
                                        <i class="fas fa-chevron-down text-primary"></i>
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div id="faq-popular-{{ $loop->index }}" class="collapse">
                            <div class="card-body">
                                <div class="faq-content">
                                    {!! $faq->jawaban !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Regular FAQ Section -->
        <div id="faq-list">
            @if(isset($faqs) && $faqs->count() > 0)
                @foreach($faqs as $faq)
                <div class="card mb-3 faq-item" data-category="{{ strtolower($faq->kategori ?? 'umum') }}">
                    <div class="card-header">
                        <button class="btn btn-link w-100 text-start text-decoration-none faq-toggle p-0"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-{{ $loop->index }}"
                                aria-expanded="false">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge bg-secondary">{{ ucfirst($faq->kategori ?? 'Umum') }}</span>
                                    </div>
                                    <h5 class="mb-0 text-dark">{{ $faq->pertanyaan }}</h5>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="faq-{{ $loop->index }}" class="collapse">
                        <div class="card-body">
                            <div class="faq-content">
                                {!! $faq->jawaban !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Default FAQ Items -->
                <div class="card mb-3 faq-item" data-category="pengaduan">
                    <div class="card-header">
                        <button class="btn btn-link w-100 text-start text-decoration-none faq-toggle p-0"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-1"
                                aria-expanded="false">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge bg-warning">Pengaduan</span>
                                    </div>
                                    <h5 class="mb-0 text-dark">Bagaimana cara menyampaikan pengaduan?</h5>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="faq-1" class="collapse">
                        <div class="card-body">
                            <div class="faq-content">
                                <p>Anda dapat menyampaikan pengaduan melalui beberapa cara:</p>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <i class="fas fa-globe text-primary fa-2x mb-2"></i>
                                                <h6 class="fw-bold">Online melalui WBS</h6>
                                                <ul class="list-unstyled small">
                                                    <li>• Kunjungi halaman <a href="{{ route('public.wbs') }}" class="text-primary">WBS</a></li>
                                                    <li>• Isi formulir pengaduan</li>
                                                    <li>• Upload bukti pendukung</li>
                                                    <li>• Simpan nomor tiket</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <i class="fas fa-building text-success fa-2x mb-2"></i>
                                                <h6 class="fw-bold">Datang Langsung</h6>
                                                <ul class="list-unstyled small">
                                                    <li>• Jl. Raya Nabire No. 123</li>
                                                    <li>• Nabire, Papua Tengah</li>
                                                    <li>• Senin-Jumat</li>
                                                    <li>• 08:00-16:00 WIT</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <i class="fas fa-phone text-warning fa-2x mb-2"></i>
                                                <h6 class="fw-bold">Telepon/Email</h6>
                                                <ul class="list-unstyled small">
                                                    <li>• (0984) 21234</li>
                                                    <li>• pengaduan@inspektorat-papuatengah.go.id</li>
                                                    <li>• Senin-Jumat</li>
                                                    <li>• 08:00-16:00 WIT</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 faq-item" data-category="layanan">
                    <div class="card-header">
                        <button class="btn btn-link w-100 text-start text-decoration-none faq-toggle p-0"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-2"
                                aria-expanded="false">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge bg-info">Layanan</span>
                                    </div>
                                    <h5 class="mb-0 text-dark">Apa saja layanan yang tersedia di Inspektorat?</h5>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="faq-2" class="collapse">
                        <div class="card-body">
                            <div class="faq-content">
                                <p>Inspektorat Papua Tengah menyediakan berbagai layanan profesional:</p>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-primary">
                                                    <i class="fas fa-search me-2"></i>Layanan Audit & Pengawasan
                                                </h6>
                                                <ul class="list-unstyled">
                                                    <li>• Audit internal</li>
                                                    <li>• Evaluasi kinerja</li>
                                                    <li>• Investigasi khusus</li>
                                                    <li>• Pemantauan tindak lanjut</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-success">
                                                    <i class="fas fa-handshake me-2"></i>Layanan Konsultasi
                                                </h6>
                                                <ul class="list-unstyled">
                                                    <li>• Konsultasi tata kelola</li>
                                                    <li>• Bimbingan teknis</li>
                                                    <li>• Pelatihan SDM</li>
                                                    <li>• Asistensi implementasi</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-star me-2"></i>
                                    <strong>Informasi:</strong> Seluruh layanan ini gratis dan dapat diakses oleh OPD di lingkungan Pemerintah Papua Tengah.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 faq-item" data-category="audit">
                    <div class="card-header">
                        <button class="btn btn-link w-100 text-start text-decoration-none faq-toggle p-0"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-3"
                                aria-expanded="false">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge bg-success">Audit</span>
                                    </div>
                                    <h5 class="mb-0 text-dark">Berapa lama proses audit internal?</h5>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="faq-3" class="collapse">
                        <div class="card-body">
                            <div class="faq-content">
                                <p>Waktu pelaksanaan audit internal bervariasi tergantung pada beberapa faktor:</p>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light text-center">
                                            <div class="card-body">
                                                <i class="fas fa-file-alt text-primary fa-2x mb-2"></i>
                                                <h6 class="fw-bold">Jenis Audit</h6>
                                                <p class="small mb-0">Kinerja, keuangan, atau khusus</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light text-center">
                                            <div class="card-body">
                                                <i class="fas fa-expand-arrows-alt text-success fa-2x mb-2"></i>
                                                <h6 class="fw-bold">Ruang Lingkup</h6>
                                                <p class="small mb-0">Luas area yang diaudit</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="card bg-light text-center">
                                            <div class="card-body">
                                                <i class="fas fa-cogs text-warning fa-2x mb-2"></i>
                                                <h6 class="fw-bold">Kompleksitas</h6>
                                                <p class="small mb-0">Tingkat kerumitan sistem</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0">Estimasi Waktu Audit</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Jenis Audit</th>
                                                        <th>Estimasi Waktu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Audit Rutin</td>
                                                        <td><span class="badge bg-success">7-14 hari kerja</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Audit Khusus/Investigasi</td>
                                                        <td><span class="badge bg-warning">30-60 hari kerja</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Audit Kinerja</td>
                                                        <td><span class="badge bg-info">14-30 hari kerja</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 faq-item" data-category="umum">
                    <div class="card-header">
                        <button class="btn btn-link w-100 text-start text-decoration-none faq-toggle p-0"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-4"
                                aria-expanded="false">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge bg-secondary">Umum</span>
                                    </div>
                                    <h5 class="mb-0 text-dark">Apakah identitas pelapor akan dirahasiakan?</h5>
                                </div>
                                <div>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div id="faq-4" class="collapse">
                        <div class="card-body">
                            <div class="faq-content">
                                <div class="alert alert-success">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Ya, identitas pelapor akan dijamin kerahasiaannya.</strong>
                                    <p class="mb-0 mt-2">Kami berkomitmen penuh melindungi setiap pelapor yang beritikad baik.</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-primary">
                                                    <i class="fas fa-user-shield me-2"></i>Perlindungan yang diberikan
                                                </h6>
                                                <ul class="list-unstyled">
                                                    <li>• Kerahasiaan identitas pelapor</li>
                                                    <li>• Perlindungan dari intimidasi</li>
                                                    <li>• Komunikasi aman melalui sistem WBS</li>
                                                    <li>• Hak untuk melaporkan secara anonim</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="fw-bold text-success">
                                                    <i class="fas fa-lock me-2"></i>Sistem WBS (Whistle Blowing System)
                                                </h6>
                                                <ul class="list-unstyled">
                                                    <li>• Enkripsi data untuk keamanan</li>
                                                    <li>• Nomor tiket unik untuk komunikasi</li>
                                                    <li>• Tim khusus penanganan pengaduan</li>
                                                    <li>• Prosedur standar perlindungan</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="text-center py-5 d-none">
            <div class="display-1 text-muted mb-3">
                <i class="fas fa-search"></i>
            </div>
            <h4 class="mb-3">Tidak Ada Hasil Ditemukan</h4>
            <p class="text-muted mb-4">Coba gunakan kata kunci yang berbeda atau pilih kategori lain.</p>
            <button onclick="clearSearch()" class="btn btn-primary">
                <i class="fas fa-redo me-2"></i>Reset Pencarian
            </button>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow">
                    <div class="card-body p-5 text-center">
                        <div class="display-6 text-primary mb-4">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Pertanyaan Tidak Terjawab?</h3>
                        <p class="text-muted mb-4">
                            Jika pertanyaan Anda belum terjawab, jangan ragu untuk menghubungi kami secara langsung.
                        </p>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-phone text-primary fa-2x mb-2"></i>
                                        <h6 class="fw-bold">Telepon</h6>
                                        <p class="mb-1">(0984) 21234</p>
                                        <small class="text-muted">Senin-Jumat: 08:00-16:00 WIT</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-envelope text-primary fa-2x mb-2"></i>
                                        <h6 class="fw-bold">Email</h6>
                                        <p class="mb-1">info@inspektorat-papuatengah.go.id</p>
                                        <small class="text-muted">Respon dalam 24 jam</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-map-marker-alt text-primary fa-2x mb-2"></i>
                                        <h6 class="fw-bold">Alamat</h6>
                                        <p class="mb-1">Jl. Raya Nabire No. 123</p>
                                        <small class="text-muted">Nabire, Papua Tengah</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('public.wbs') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Laporkan Masalah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-3 rounded-circle d-none" style="z-index: 1000;">
    <i class="fas fa-arrow-up"></i>
</button>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchFaq');
    const searchLoading = document.getElementById('search-loading');
    const categoryButtons = document.querySelectorAll('.category-filter');
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('no-results');
    const backToTop = document.getElementById('backToTop');

    let searchTimeout;

    // Search functionality
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        showSearchLoading();

        searchTimeout = setTimeout(() => {
            filterFAQs();
            hideSearchLoading();
            // Fix text color after filtering
            setTimeout(fixFaqContentColor, 100);
        }, 300);
    });

    // Category filter
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            categoryButtons.forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });

            this.classList.add('btn-primary');
            this.classList.remove('btn-outline-primary');

            filterFAQs();
        });
    });

    // Filter function
    function filterFAQs() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const activeCategory = document.querySelector('.category-filter.btn-primary').dataset.category;
        let visibleCount = 0;

        faqItems.forEach(item => {
            const question = item.querySelector('h5').textContent.toLowerCase();
            const answer = item.querySelector('.faq-content').textContent.toLowerCase();
            const category = item.dataset.category;

            const matchesSearch = !searchTerm || question.includes(searchTerm) || answer.includes(searchTerm);
            const matchesCategory = !activeCategory || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                visibleCount++;

                // Highlight search terms
                if (searchTerm.length > 2) {
                    highlightSearchTerms(item, searchTerm);
                } else {
                    removeHighlights(item);
                }
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }

    // Highlight search terms
    function highlightSearchTerms(item, searchTerm) {
        const question = item.querySelector('h5');
        const content = item.querySelector('.faq-content');

        if (question && content) {
            const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');

            // Remove existing highlights
            removeHighlights(item);

            // Add highlights
            question.innerHTML = question.innerHTML.replace(regex, '<mark class="bg-warning">$1</mark>');
            content.innerHTML = content.innerHTML.replace(regex, '<mark class="bg-warning">$1</mark>');
        }
    }

    // Remove highlights
    function removeHighlights(item) {
        const question = item.querySelector('h5');
        const content = item.querySelector('.faq-content');

        if (question) {
            question.innerHTML = question.innerHTML.replace(/<mark[^>]*>([^<]+)<\/mark>/gi, '$1');
        }
        if (content) {
            content.innerHTML = content.innerHTML.replace(/<mark[^>]*>([^<]+)<\/mark>/gi, '$1');
        }
    }

    // Escape regex special characters
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Show/hide search loading
    function showSearchLoading() {
        searchLoading.classList.remove('d-none');
    }

    function hideSearchLoading() {
        searchLoading.classList.add('d-none');
    }

    // Clear search function
    window.clearSearch = function() {
        searchInput.value = '';
        document.querySelector('.category-filter[data-category=""]').click();
        filterFAQs();
    };

    // Back to top functionality
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTop.classList.remove('d-none');
        } else {
            backToTop.classList.add('d-none');
        }
    });

    backToTop.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Handle URL hash for direct FAQ access
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth' });
                // Auto-open the FAQ item
                const button = target.querySelector('.faq-toggle');
                if (button) {
                    button.click();
                }
            }, 500);
        }
    }

    // Fix FAQ content text color after collapse
    function fixFaqContentColor() {
        const faqContents = document.querySelectorAll('.faq-content');
        faqContents.forEach(content => {
            content.style.setProperty('color', '#212529', 'important');
            content.style.setProperty('background-color', 'transparent', 'important');

            // Force reflow
            content.offsetHeight;

            const allElements = content.querySelectorAll('*');
            allElements.forEach(element => {
                if (!element.classList.contains('text-primary') &&
                    !element.classList.contains('text-success') &&
                    !element.classList.contains('text-warning') &&
                    !element.classList.contains('text-danger') &&
                    !element.classList.contains('text-info') &&
                    !element.classList.contains('text-muted') &&
                    !element.classList.contains('badge')) {
                    element.style.setProperty('color', '#212529', 'important');
                    element.style.setProperty('background-color', 'transparent', 'important');

                    // Remove any white text classes
                    element.classList.remove('text-white');

                    // Force reflow on element
                    element.offsetHeight;
                }
            });
        });

        // Additional fix for specific elements that might be problematic
        const problematicElements = document.querySelectorAll('.faq-content .text-white, .faq-content [style*="color: white"], .faq-content [style*="color: #fff"]');
        problematicElements.forEach(element => {
            element.style.setProperty('color', '#212529', 'important');
            element.classList.remove('text-white');
        });
    }

    // Listen for Bootstrap collapse events
    document.addEventListener('shown.bs.collapse', function(e) {
        if (e.target.classList.contains('collapse')) {
            fixFaqContentColor();
        }
        // Additional fix for text color on page load
        setTimeout(fixFaqContentColor, 100);

        // Fix color on window resize
        window.addEventListener('resize', function() {
            setTimeout(fixFaqContentColor, 100);
        });
    });

    // Listen for Bootstrap collapse hide events
    document.addEventListener('hidden.bs.collapse', function(e) {
        if (e.target.classList.contains('collapse')) {
            fixFaqContentColor();
        }
    });

    // Initial color fix - run multiple times to ensure it sticks
    fixFaqContentColor();
    setTimeout(fixFaqContentColor, 100);
    setTimeout(fixFaqContentColor, 500);
    setTimeout(fixFaqContentColor, 1000);

    // Fix on page fully loaded
    window.addEventListener('load', function() {
        setTimeout(fixFaqContentColor, 100);
        setTimeout(fixFaqContentColor, 500);
        setTimeout(fixFaqContentColor, 1000);
    });

    // Additional script to run after DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                fixFaqContentColor();
                // Force reflow to ensure styles are applied
                document.body.offsetHeight;
                fixFaqContentColor();
            }, 50);
        });
    } else {
        // DOM is already loaded
        setTimeout(function() {
            fixFaqContentColor();
            document.body.offsetHeight;
            fixFaqContentColor();
        }, 50);
    }

    // Run fix continuously for the first few seconds
    let fixInterval = setInterval(function() {
        fixFaqContentColor();
    }, 100);

    // Stop the interval after 5 seconds
    setTimeout(function() {
        clearInterval(fixInterval);
    }, 5000);

    // MutationObserver to watch for DOM changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'attributes') {
                setTimeout(fixFaqContentColor, 50);
            }
        });
    });

    // Start observing FAQ content areas
    const faqContents = document.querySelectorAll('.faq-content');
    faqContents.forEach(content => {
        observer.observe(content, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    });

    // Also observe the main FAQ container
    const faqContainer = document.getElementById('faq-list');
    if (faqContainer) {
        observer.observe(faqContainer, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }

    // Fix color on page load and after short delay
    setTimeout(fixFaqContentColor, 100);
    setTimeout(fixFaqContentColor, 500);

    // Add event listeners for all collapse elements
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(element => {
        element.addEventListener('show.bs.collapse', function() {
            setTimeout(fixFaqContentColor, 50);
        });

        element.addEventListener('shown.bs.collapse', function() {
            setTimeout(fixFaqContentColor, 50);
            setTimeout(fixFaqContentColor, 200);
        });

        element.addEventListener('hidden.bs.collapse', function() {
            setTimeout(fixFaqContentColor, 50);
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close all open FAQs
            const openCollapses = document.querySelectorAll('.collapse.show');
            openCollapses.forEach(collapse => {
                const bsCollapse = new bootstrap.Collapse(collapse, {
                    toggle: false
                });
                bsCollapse.hide();
            });
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* FAQ specific styles */
.faq-toggle {
    color: inherit !important;
    text-decoration: none !important;
}

.faq-toggle:hover {
    color: inherit !important;
}

.faq-toggle i {
    transition: transform 0.3s ease;
}

.faq-toggle[aria-expanded="true"] i {
    transform: rotate(180deg);
}

/* CRITICAL: Force dark text color on FAQ content */
.faq-content,
.faq-content *,
.faq-content p,
.faq-content div,
.faq-content span,
.faq-content li,
.faq-content ul,
.faq-content ol,
.faq-content h1,
.faq-content h2,
.faq-content h3,
.faq-content h4,
.faq-content h5,
.faq-content h6,
.faq-content strong,
.faq-content em,
.faq-content b,
.faq-content i,
.faq-content small,
.faq-content td,
.faq-content th,
.faq-content tr {
    color: #212529 !important;
    background-color: transparent !important;
}

/* Force dark text on specific FAQ IDs */
#faq-popular-0 .faq-content,
#faq-popular-0 .faq-content *,
#faq-popular-1 .faq-content,
#faq-popular-1 .faq-content *,
#faq-popular-2 .faq-content,
#faq-popular-2 .faq-content *,
#faq-1 .faq-content,
#faq-1 .faq-content *,
#faq-2 .faq-content,
#faq-2 .faq-content *,
#faq-3 .faq-content,
#faq-3 .faq-content *,
#faq-4 .faq-content,
#faq-4 .faq-content * {
    color: #212529 !important;
    background-color: transparent !important;
}

/* Force dark text on collapse states */
.collapse .faq-content,
.collapse .faq-content *,
.collapse.show .faq-content,
.collapse.show .faq-content *,
.collapse.collapsing .faq-content,
.collapse.collapsing .faq-content * {
    color: #212529 !important;
    background-color: transparent !important;
}

/* Override any Bootstrap text utilities */
.faq-content .text-white,
.faq-content .text-white * {
    color: #212529 !important;
}

/* Override any inherited white text */
body .faq-content,
body .faq-content * {
    color: #212529 !important;
}

/* Most specific selector possible */
html body .container .faq-content,
html body .container .faq-content * {
    color: #212529 !important;
}

/* FAQ Content - Most specific selectors to override any other CSS */
.card-body .faq-content {
    line-height: 1.6 !important;
    color: #212529 !important;
    background-color: transparent !important;
}

.card-body .faq-content,
.card-body .faq-content *,
.card-body .faq-content p,
.card-body .faq-content div,
.card-body .faq-content span,
.card-body .faq-content li,
.card-body .faq-content ul,
.card-body .faq-content ol,
.card-body .faq-content h1,
.card-body .faq-content h2,
.card-body .faq-content h3,
.card-body .faq-content h4,
.card-body .faq-content h5,
.card-body .faq-content h6,
.card-body .faq-content strong,
.card-body .faq-content em,
.card-body .faq-content b,
.card-body .faq-content i,
.card-body .faq-content small,
.card-body .faq-content td,
.card-body .faq-content th,
.card-body .faq-content tr {
    color: #212529 !important;
    background-color: transparent !important;
}

.card-body .faq-content a {
    color: var(--bs-primary) !important;
    text-decoration: none;
}

.card-body .faq-content a:hover {
    text-decoration: underline;
    color: var(--bs-primary) !important;
}

/* Specific color overrides for Bootstrap utilities */
.card-body .faq-content .text-muted {
    color: #6c757d !important;
}

.card-body .faq-content .text-primary {
    color: var(--bs-primary) !important;
}

.card-body .faq-content .text-success {
    color: var(--bs-success) !important;
}

.card-body .faq-content .text-warning {
    color: var(--bs-warning) !important;
}

.card-body .faq-content .text-danger {
    color: var(--bs-danger) !important;
}

.card-body .faq-content .text-info {
    color: var(--bs-info) !important;
}

/* Collapse specific overrides */
.collapse .card-body .faq-content,
.collapse .card-body .faq-content * {
    color: #212529 !important;
    background-color: transparent !important;
}

.collapse.show .card-body .faq-content,
.collapse.show .card-body .faq-content * {
    color: #212529 !important;
    background-color: transparent !important;
}

.collapse.collapsing .card-body .faq-content,
.collapse.collapsing .card-body .faq-content * {
    color: #212529 !important;
    background-color: transparent !important;
}

/* Table and Alert specific overrides */
.card-body .faq-content .table,
.card-body .faq-content .table * {
    color: #212529 !important;
}

.card-body .faq-content .alert,
.card-body .faq-content .alert * {
    color: #212529 !important;
}

.card-body .faq-content .card,
.card-body .faq-content .card * {
    color: #212529 !important;
}

.card-body .faq-content .badge {
    color: white !important;
}

/* Override any inherited white text */
.faq-content {
    color: #212529 !important;
}

.faq-content * {
    color: #212529 !important;
}

/* Force dark text on any nested elements */
#faq-1 .faq-content,
#faq-1 .faq-content *,
#faq-2 .faq-content,
#faq-2 .faq-content *,
#faq-3 .faq-content,
#faq-3 .faq-content *,
#faq-4 .faq-content,
#faq-4 .faq-content *,
#faq-popular-0 .faq-content,
#faq-popular-0 .faq-content * {
    color: #212529 !important;
    background-color: transparent !important;
}

/* Additional critical fixes for text color */
.card-body .faq-content {
    color: #212529 !important;
}

.card-body .faq-content * {
    color: #212529 !important;
}

.category-filter {
    transition: all 0.3s ease;
}

.category-filter:hover {
    transform: translateY(-2px);
}

#backToTop {
    transition: all 0.3s ease;
}

#backToTop:hover {
    transform: scale(1.1);
}

/* Search input animations */
#searchFaq:focus {
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

/* Card hover effects */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-filter {
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .display-4 {
        font-size: 2rem;
    }
}
</style>
@endpush
