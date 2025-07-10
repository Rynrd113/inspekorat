@extends('layouts.public')

@section('title', 'Kontak Kami - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Kontak Kami</h1>
                <p class="lead">Hubungi kami untuk informasi lebih lanjut atau sampaikan masukan Anda.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-envelope fa-5x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-2">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('public.index') }}">Beranda</a></li>
            <li class="breadcrumb-item active">Kontak</li>
        </ol>
    </div>
</nav>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-paper-plane text-primary me-2"></i>
                        Kirim Pesan
                    </h4>
                    
                    <form method="POST" action="{{ route('kontak.kirim') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="pesan" class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('pesan') is-invalid @enderror" 
                                      id="pesan" 
                                      name="pesan" 
                                      rows="6" 
                                      placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."
                                      required>{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Informasi Kontak
                    </h4>
                    
                    <div class="contact-info">
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Alamat</h6>
                                    <p class="text-muted mb-0">{{ $kontak->alamat ?? 'Jl. Raya Nabire No. 123, Nabire, Papua Tengah' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-phone text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Telepon</h6>
                                    <p class="text-muted mb-0">{{ $kontak->telepon ?? '(0984) 21234' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-envelope text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Email</h6>
                                    <p class="text-muted mb-0">{{ $kontak->email ?? 'inspektorat@paputengah.go.id' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-clock text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-1">Jam Operasional</h6>
                                    <p class="text-muted mb-0">{{ $kontak->jam_operasional ?? 'Senin - Jumat: 08:00 - 16:00 WIT' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-question-circle text-primary me-2"></i>
                        Butuh Bantuan Cepat?
                    </h5>
                    <p class="text-muted mb-3">Lihat halaman FAQ untuk jawaban atas pertanyaan yang sering diajukan.</p>
                    <a href="{{ route('public.faq') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Lihat FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
