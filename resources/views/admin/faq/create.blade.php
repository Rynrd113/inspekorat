@extends('layouts.admin')

@section('title', 'Tambah FAQ')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah FAQ</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}">FAQ</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Form Tambah FAQ
        </div>
        <div class="card-body">
            <form action="{{ route('admin.faq.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="pertanyaan" class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pertanyaan') is-invalid @enderror" 
                                   id="pertanyaan" name="pertanyaan" value="{{ old('pertanyaan') }}" required>
                            @error('pertanyaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="umum" {{ old('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="layanan" {{ old('kategori') == 'layanan' ? 'selected' : '' }}>Layanan</option>
                                <option value="pengaduan" {{ old('kategori') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                                <option value="audit" {{ old('kategori') == 'audit' ? 'selected' : '' }}>Audit</option>
                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="jawaban" class="form-label">Jawaban <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('jawaban') is-invalid @enderror" 
                              id="jawaban" name="jawaban" rows="6" required>{{ old('jawaban') }}</textarea>
                    @error('jawaban')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Gunakan formatting HTML sederhana jika diperlukan (contoh: &lt;b&gt;, &lt;i&gt;, &lt;br&gt;, &lt;ul&gt;, &lt;li&gt;).</div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="urutan" class="form-label">Urutan Tampil</label>
                            <input type="number" class="form-control @error('urutan') is-invalid @enderror" 
                                   id="urutan" name="urutan" value="{{ old('urutan', 1) }}" min="1">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Semakin kecil angka, semakin di atas posisinya.</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="is_featured" class="form-label">FAQ Unggulan</label>
                            <select class="form-select @error('is_featured') is-invalid @enderror" id="is_featured" name="is_featured">
                                <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Ya</option>
                            </select>
                            @error('is_featured')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">FAQ unggulan akan ditampilkan lebih menonjol.</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="tags" class="form-label">Tags</label>
                    <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                           id="tags" name="tags" value="{{ old('tags') }}" 
                           placeholder="Pisahkan dengan koma">
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Contoh: pengaduan, prosedur, audit</div>
                </div>

                <!-- Preview Section -->
                <div class="card bg-light mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Preview FAQ</h6>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="previewAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="previewHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#previewCollapse">
                                        <span id="previewPertanyaan">Pertanyaan akan muncul di sini...</span>
                                    </button>
                                </h2>
                                <div id="previewCollapse" class="accordion-collapse collapse" data-bs-parent="#previewAccordion">
                                    <div class="accordion-body">
                                        <span id="previewJawaban">Jawaban akan muncul di sini...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Live preview functionality
document.getElementById('pertanyaan').addEventListener('input', function() {
    const pertanyaan = this.value || 'Pertanyaan akan muncul di sini...';
    document.getElementById('previewPertanyaan').textContent = pertanyaan;
});

document.getElementById('jawaban').addEventListener('input', function() {
    const jawaban = this.value || 'Jawaban akan muncul di sini...';
    document.getElementById('previewJawaban').innerHTML = jawaban;
});

// Character counter for jawaban
document.getElementById('jawaban').addEventListener('input', function() {
    const current = this.value.length;
    const max = 1000; // Adjust as needed
    
    // Remove existing counter
    const existingCounter = this.parentNode.querySelector('.char-counter');
    if (existingCounter) {
        existingCounter.remove();
    }
    
    // Add new counter
    const counter = document.createElement('div');
    counter.className = 'char-counter form-text text-end';
    counter.textContent = `${current} karakter`;
    
    if (current > max) {
        counter.className += ' text-danger';
    }
    
    this.parentNode.appendChild(counter);
});

// Auto-generate urutan based on kategori
document.getElementById('kategori').addEventListener('change', function() {
    // Here you could make an AJAX call to get the next order number for this category
    // For now, we'll just suggest a default
    const urutanField = document.getElementById('urutan');
    if (!urutanField.value || urutanField.value == '1') {
        // You could implement logic to suggest the next available order
        urutanField.value = 1;
    }
});
</script>
@endpush
@endsection
