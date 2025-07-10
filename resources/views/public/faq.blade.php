@extends('layouts.public')

@section('title', 'FAQ - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Frequently Asked Questions</h1>
                <p class="lead">Temukan jawaban atas pertanyaan yang sering diajukan tentang Inspektorat Papua Tengah.</p>
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
<section class="py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" id="searchFaq" placeholder="Cari pertanyaan...">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Ketik kata kunci untuk mencari pertanyaan yang Anda butuhkan</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Categories -->
<section class="py-4">
    <div class="container">
        <div class="text-center mb-4">
            <h5>Kategori Pertanyaan</h5>
        </div>
        <div class="row justify-content-center">
            <div class="col-auto">
                <div class="btn-group flex-wrap" role="group" id="categoryButtons">
                    <input type="radio" class="btn-check" name="category" id="cat-semua" value="" checked>
                    <label class="btn btn-outline-primary" for="cat-semua">
                        <i class="fas fa-th-large me-1"></i> Semua
                    </label>

                    <input type="radio" class="btn-check" name="category" id="cat-umum" value="umum">
                    <label class="btn btn-outline-primary" for="cat-umum">
                        <i class="fas fa-info-circle me-1"></i> Umum
                    </label>

                    <input type="radio" class="btn-check" name="category" id="cat-layanan" value="layanan">
                    <label class="btn btn-outline-primary" for="cat-layanan">
                        <i class="fas fa-hands-helping me-1"></i> Layanan
                    </label>

                    <input type="radio" class="btn-check" name="category" id="cat-pengaduan" value="pengaduan">
                    <label class="btn btn-outline-primary" for="cat-pengaduan">
                        <i class="fas fa-exclamation-triangle me-1"></i> Pengaduan
                    </label>

                    <input type="radio" class="btn-check" name="category" id="cat-audit" value="audit">
                    <label class="btn btn-outline-primary" for="cat-audit">
                        <i class="fas fa-search me-1"></i> Audit
                    </label>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Featured FAQ -->
                <div class="mb-5">
                    <div class="text-center mb-4">
                        <h3>Pertanyaan Populer</h3>
                        <p class="text-muted">Pertanyaan yang paling sering diajukan</p>
                    </div>
                    
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

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.featured-faq {
    border: 2px solid #ffc107;
    border-radius: 10px;
    overflow: hidden;
}

.featured-faq .accordion-button {
    background-color: #fff8e1;
    font-weight: 600;
}

.faq-item .accordion-button:not(.collapsed) {
    background-color: #e3f2fd;
    border-bottom: 1px solid #dee2e6;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn-group .btn-check:checked + .btn {
    background-color: #007bff;
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
