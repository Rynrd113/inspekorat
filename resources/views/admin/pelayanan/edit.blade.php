@extends('layouts.admin')

@section('title', 'Edit Pelayanan')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Pelayanan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pelayanan.index') }}">Pelayanan</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Form Edit Pelayanan
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pelayanan.update', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pelayanan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $pelayanan->nama ?? '') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="audit" {{ old('kategori', $pelayanan->kategori ?? '') == 'audit' ? 'selected' : '' }}>Audit</option>
                                <option value="konsultasi" {{ old('kategori', $pelayanan->kategori ?? '') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                                <option value="pengaduan" {{ old('kategori', $pelayanan->kategori ?? '') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                                <option value="lainnya" {{ old('kategori', $pelayanan->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $pelayanan->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="syarat" class="form-label">Syarat & Ketentuan</label>
                            <textarea class="form-control @error('syarat') is-invalid @enderror" 
                                      id="syarat" name="syarat" rows="3">{{ old('syarat', $pelayanan->syarat ?? '') }}</textarea>
                            @error('syarat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="prosedur" class="form-label">Prosedur</label>
                            <textarea class="form-control @error('prosedur') is-invalid @enderror" 
                                      id="prosedur" name="prosedur" rows="3">{{ old('prosedur', $pelayanan->prosedur ?? '') }}</textarea>
                            @error('prosedur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="waktu_layanan" class="form-label">Waktu Layanan</label>
                            <input type="text" class="form-control @error('waktu_layanan') is-invalid @enderror" 
                                   id="waktu_layanan" name="waktu_layanan" value="{{ old('waktu_layanan', $pelayanan->waktu_layanan ?? '') }}" 
                                   placeholder="Contoh: 7 hari kerja">
                            @error('waktu_layanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="biaya" class="form-label">Biaya</label>
                            <input type="text" class="form-control @error('biaya') is-invalid @enderror" 
                                   id="biaya" name="biaya" value="{{ old('biaya', $pelayanan->biaya ?? '') }}" 
                                   placeholder="Contoh: Gratis atau Rp 50.000">
                            @error('biaya')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kontak" class="form-label">Kontak</label>
                            <input type="text" class="form-control @error('kontak') is-invalid @enderror" 
                                   id="kontak" name="kontak" value="{{ old('kontak', $pelayanan->kontak ?? '') }}" 
                                   placeholder="Nomor telepon atau email">
                            @error('kontak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="aktif" {{ old('status', $pelayanan->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $pelayanan->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar</label>
                    @if(isset($pelayanan->gambar) && $pelayanan->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $pelayanan->gambar) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                            <p class="text-muted mt-1">Gambar saat ini</p>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                           id="gambar" name="gambar" accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pelayanan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
