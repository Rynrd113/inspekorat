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
                        <x-input
                            label="Judul Dokumen"
                            name="judul"
                            id="judul"
                            type="text"
                            value="{{ old('judul', $dokumen->judul ?? '') }}"
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
                    <x-input
                        label="Deskripsi"
                        name="deskripsi"
                        id="deskripsi"
                        type="textarea"
                        rows="4"
                        :error="$errors->first('deskripsi')"
                    >{{ old('deskripsi', $dokumen->deskripsi ?? 'Dokumen peraturan terbaru untuk Inspektorat Papua Tengah tahun 2024') }}</x-input>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Tahun"
                            name="tahun"
                            id="tahun"
                            type="number"
                            value="{{ old('tahun', $dokumen->tahun ?? date('Y')) }}"
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
                            value="{{ old('nomor_dokumen', $dokumen->nomor_dokumen ?? '') }}"
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
                            value="{{ old('tanggal_terbit', $dokumen->tanggal_terbit ? $dokumen->tanggal_terbit->format('Y-m-d') : date('Y-m-d')) }}"
                            :error="$errors->first('tanggal_terbit')"
                            required="true"
                        />
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
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('file_dokumen') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                               id="file_dokumen"
                               name="file_dokumen"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        @error('file_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB. Kosongkan jika tidak ingin mengubah file.</p>
                    </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-start">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="status"
                                       name="status"
                                       value="1"
                                       {{ old('status', $dokumen->status ?? true) ? 'checked' : '' }}
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Publikasi
                        </label>
                        <div class="flex items-start">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       id="is_public"
                                       name="is_public"
                                       value="1"
                                       {{ old('is_public', $dokumen->is_public ?? false) ? 'checked' : '' }}
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
                </div>

                <div class="mt-6">
                    <label for="file_cover" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Cover (Opsional)
                    </label>
                    @if(isset($dokumen->file_cover) && $dokumen->file_cover)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $dokumen->file_cover) }}"
                                 alt="Cover Image"
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            <p class="text-sm text-gray-500 mt-1">Cover dokumen saat ini</p>
                        </div>
                    @endif
                    <input type="file"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('file_cover') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                           id="file_cover"
                           name="file_cover"
                           accept="image/*">
                    @error('file_cover')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <x-button variant="secondary" href="{{ route('admin.dokumen.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <i class="fas fa-save mr-2"></i>Update
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
    maxSizeMB: 10,
    allowedTypes: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']
});

// Validate file cover (max 2MB)
FileValidator.attachToInput('#file_cover', {
    maxSizeMB: 2,
    allowedTypes: ['jpg', 'jpeg', 'png', 'gif', 'webp']
});
</script>
@endpush
@endsection
