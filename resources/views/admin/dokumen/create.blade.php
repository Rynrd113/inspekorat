@extends('layouts.admin')

@section('title', 'Tambah Dokumen')

@section('header', 'Tambah Dokumen')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.dokumen.index') }}" class="text-blue-600 hover:text-blue-800">Dokumen</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Tambah</li>
@endsection

@section('main-content')

<div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-plus mr-2 text-blue-600"></i>
                Form Tambah Dokumen
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input
                            label="Judul Dokumen"
                            name="judul"
                            id="judul"
                            type="text"
                            value="{{ old('judul') }}"
                            required="true"
                            :error="$errors->first('judul')"
                        />
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('kategori') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                id="kategori"
                                name="kategori"
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="peraturan" {{ old('kategori') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                            <option value="panduan" {{ old('kategori') == 'panduan' ? 'selected' : '' }}>Panduan</option>
                            <option value="laporan" {{ old('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="surat" {{ old('kategori') == 'surat' ? 'selected' : '' }}>Surat</option>
                            <option value="formulir" {{ old('kategori') == 'formulir' ? 'selected' : '' }}>Formulir</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <x-input
                        label="Deskripsi"
                        name="deskripsi"
                        id="deskripsi"
                        type="textarea"
                        rows="4"
                        :error="$errors->first('deskripsi')"
                    >{{ old('deskripsi') }}</x-input>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Tahun"
                            name="tahun"
                            id="tahun"
                            type="number"
                            value="{{ old('tahun', date('Y')) }}"
                            min="2020"
                            max="{{ date('Y') + 1 }}"
                            :error="$errors->first('tahun')"
                            required="true"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Nomor Dokumen"
                            name="nomor_dokumen"
                            id="nomor_dokumen"
                            type="text"
                            value="{{ old('nomor_dokumen') }}"
                            placeholder="Contoh: 001/INS/2024"
                            :error="$errors->first('nomor_dokumen')"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Tanggal Terbit"
                            name="tanggal_terbit"
                            id="tanggal_terbit"
                            type="date"
                            value="{{ old('tanggal_terbit', date('Y-m-d')) }}"
                            :error="$errors->first('tanggal_terbit')"
                            required="true"
                        />
                    </div>
                </div>

                <div class="mt-6">
                    <label for="file_dokumen" class="block text-sm font-medium text-gray-700 mb-2">
                        File Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="file"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('file_dokumen') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                           id="file_dokumen"
                           name="file_dokumen"
                           required>
                    @error('file_dokumen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Semua format file diizinkan (PDF, Word, Excel, PowerPoint, dll). Maksimal 10MB.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <div class="flex items-start">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="status"
                                       name="status"
                                       value="1"
                                       {{ old('status', '1') ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Dokumen Aktif
                                </span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-start h-full">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="is_public"
                                       name="is_public"
                                       value="1"
                                       {{ old('is_public') ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Dokumen Publik
                                </span>
                            </label>
                        </div>
                        @error('is_public')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="file_cover" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Cover (Opsional)
                    </label>
                    <input type="file"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('file_cover') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                           id="file_cover"
                           name="file_cover"
                           accept="image/*">
                    @error('file_cover')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Untuk cover dokumen di halaman publik.</p>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <x-button variant="secondary" href="{{ route('admin.dokumen.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </x-button>
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </x-button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script src="{{ asset('js/file-validator.js') }}"></script>
<script>
// Validate file dokumen (max 10MB)
FileValidator.attachToInput('#file_dokumen', {
    maxSizeMB: 10
});

// Validate file cover (max 2MB)
FileValidator.attachToInput('#file_cover', {
    maxSizeMB: 2,
    allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp']
});
</script>
@endpush
@endsection
