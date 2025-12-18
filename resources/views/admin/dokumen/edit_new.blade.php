@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('header', 'Edit Dokumen')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.dokumen.index') }}" class="text-blue-600 hover:text-blue-800">Dokumen</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Edit</li>
@endsection

@section('main-content')
<div class="space-y-6">

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Form Edit Dokumen
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.dokumen.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('judul') border-red-500 @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul', $dokumen->judul ?? '') }}" 
                               required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror" 
                                id="kategori" 
                                name="kategori" 
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="peraturan" {{ old('kategori', $dokumen->kategori ?? '') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                            <option value="panduan" {{ old('kategori', $dokumen->kategori ?? '') == 'panduan' ? 'selected' : '' }}>Panduan</option>
                            <option value="laporan" {{ old('kategori', $dokumen->kategori ?? '') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="surat" {{ old('kategori', $dokumen->kategori ?? '') == 'surat' ? 'selected' : '' }}>Surat</option>
                            <option value="formulir" {{ old('kategori', $dokumen->kategori ?? '') == 'formulir' ? 'selected' : '' }}>Formulir</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror" 
                              id="deskripsi" 
                              name="deskripsi" 
                              rows="4">{{ old('deskripsi', $dokumen->deskripsi ?? 'Dokumen peraturan terbaru untuk Inspektorat Papua Tengah tahun 2024') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="nomor_dokumen" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Dokumen
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nomor_dokumen') border-red-500 @enderror" 
                               id="nomor_dokumen" 
                               name="nomor_dokumen" 
                               value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen ?? '') }}" 
                               placeholder="Contoh: 001/INS/2024">
                        @error('nomor_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tanggal_dokumen" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Dokumen
                        </label>
                        <input type="date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_dokumen') border-red-500 @enderror" 
                               id="tanggal_dokumen" 
                               name="tanggal_dokumen" 
                               value="{{ old('tanggal_dokumen', $dokumen->tanggal_dokumen ?? '') }}">
                        @error('tanggal_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="file_dokumen" class="block text-sm font-medium text-gray-700 mb-2">
                            File Dokumen
                        </label>
                        @if(isset($dokumen->file_dokumen) && $dokumen->file_dokumen)
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">File saat ini:</p>
                                <a href="{{ route('admin.dokumen.download', $dokumen) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-download mr-1"></i>
                                    {{ basename($dokumen->file_dokumen) }}
                                </a>
                            </div>
                        @endif
                        <input type="file" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('file_dokumen') border-red-500 @enderror" 
                               id="file_dokumen" 
                               name="file_dokumen" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        @error('file_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB. Kosongkan jika tidak ingin mengubah file.</p>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" 
                                id="status" 
                                name="status">
                            <option value="1" {{ old('status', $dokumen->status ?? '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status', $dokumen->status ?? '1') == '0' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="gambar_preview" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Preview (Opsional)
                    </label>
                    @if(isset($dokumen->gambar_preview) && $dokumen->gambar_preview)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $dokumen->gambar_preview) }}" 
                                 alt="Preview Image" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            <p class="text-sm text-gray-500 mt-1">Gambar preview saat ini</p>
                        </div>
                    @endif
                    <input type="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gambar_preview') border-red-500 @enderror" 
                           id="gambar_preview" 
                           name="gambar_preview" 
                           accept="image/*">
                    @error('gambar_preview')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.dokumen.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/file-validator.js') }}"></script>
<script>
// Validate file dokumen (max 10MB)
FileValidator.attachToInput('#file_dokumen', {
    maxSizeMB: 10,
    allowedTypes: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']
});

// Validate gambar preview (max 2MB)
FileValidator.attachToInput('#gambar_preview', {
    maxSizeMB: 2,
    allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp']
});
</script>
@endpush
@endsection
