@extends('layouts.admin')

@section('title', 'Detail Pelayanan')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Pelayanan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pelayanan.index') }}">Pelayanan</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-eye me-1"></i>
                Detail Pelayanan
            </div>
            <div>
                <a href="{{ route('admin.pelayanan.edit', $pelayanan->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Nama Pelayanan</strong></td>
                            <td>: {{ $pelayanan->nama ?? 'Layanan Audit Internal' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kategori</strong></td>
                            <td>: 
                                <span class="badge bg-primary">{{ ucfirst($pelayanan->kategori ?? 'audit') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: 
                                @if(($pelayanan->status ?? 'aktif') == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Layanan</strong></td>
                            <td>: {{ $pelayanan->waktu_layanan ?? '7 hari kerja' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Biaya</strong></td>
                            <td>: {{ $pelayanan->biaya ?? 'Gratis' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kontak</strong></td>
                            <td>: {{ $pelayanan->kontak ?? '(021) 123-4567' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat</strong></td>
                            <td>: {{ isset($pelayanan->created_at) ? $pelayanan->created_at->format('d F Y H:i') : date('d F Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diperbarui</strong></td>
                            <td>: {{ isset($pelayanan->updated_at) ? $pelayanan->updated_at->format('d F Y H:i') : date('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    @if(isset($pelayanan->gambar) && $pelayanan->gambar)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $pelayanan->gambar) }}" alt="{{ $pelayanan->nama }}" 
                                 class="img-fluid rounded" style="max-width: 100%; height: auto;">
                        </div>
                    @else
                        <div class="text-center">
                            <div class="bg-light rounded p-5">
                                <i class="fas fa-image fa-3x text-muted"></i>
                                <p class="text-muted mt-2">Tidak ada gambar</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h5>Deskripsi</h5>
                    <div class="border rounded p-3 bg-light">
                        {{ $pelayanan->deskripsi ?? 'Layanan audit internal untuk memastikan tata kelola yang baik dan akuntabilitas dalam organisasi pemerintahan.' }}
                    </div>
                </div>
            </div>

            @if(isset($pelayanan->syarat) && $pelayanan->syarat)
            <div class="row mt-3">
                <div class="col-12">
                    <h5>Syarat & Ketentuan</h5>
                    <div class="border rounded p-3 bg-light">
                        {{ $pelayanan->syarat }}
                    </div>
                </div>
            </div>
            @endif

            @if(isset($pelayanan->prosedur) && $pelayanan->prosedur)
            <div class="row mt-3">
                <div class="col-12">
                    <h5>Prosedur</h5>
                    <div class="border rounded p-3 bg-light">
                        {{ $pelayanan->prosedur }}
                    </div>
                </div>
            </div>
            @endif

            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('admin.pelayanan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pelayanan "<strong>{{ $pelayanan->nama ?? 'ini' }}</strong>"?
                <br><br>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.pelayanan.destroy', $pelayanan->id ?? 1) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
