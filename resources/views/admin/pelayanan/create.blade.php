@extends('layouts.admin')

@section('header', 'Tambah Pelayanan')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li><a href="{{ route('admin.pelayanan.index') }}" class="text-blue-600 hover:text-blue-800">Pelayanan</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Tambah</li>
@endsection

@section('main-content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <i class="fas fa-tools text-6xl text-yellow-500 mb-4"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">
            Dalam Tahap Pengembangan
        </h2>
        <p class="text-gray-600 mb-6">
            Fitur tambah pelayanan sedang dalam tahap pengembangan dan akan segera tersedia.
        </p>
        <a href="{{ route('admin.pelayanan.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pelayanan
        </a>
    </div>
</div>
@endsection
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-plus mr-2 text-blue-600"></i>
                Form Tambah Pelayanan
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.pelayanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input
                            label="Nama Pelayanan"
                            name="nama_layanan"
                            id="nama_layanan"
                            type="text"
                            value="{{ old('nama_layanan') }}"
                            required="true"
                            :error="$errors->first('nama_layanan')"
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
                            <option value="audit" {{ old('kategori') == 'audit' ? 'selected' : '' }}>Audit</option>
                            <option value="konsultasi" {{ old('kategori') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                            <option value="pengaduan" {{ old('kategori') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                        required="true"
                        :error="$errors->first('deskripsi')"
                    >{{ old('deskripsi') }}</x-input>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="persyaratan" class="block text-sm font-medium text-gray-700 mb-2">
                            Syarat & Ketentuan
                        </label>
                        <textarea 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('persyaratan') border-red-500 @enderror" 
                            id="persyaratan" 
                            name="persyaratan" 
                            rows="5"
                            placeholder="Masukkan syarat, satu per baris">{{ old('persyaratan') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Tulis setiap syarat di baris baru</p>
                        @error('persyaratan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="prosedur" class="block text-sm font-medium text-gray-700 mb-2">
                            Prosedur
                        </label>
                        <textarea 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prosedur') border-red-500 @enderror" 
                            id="prosedur" 
                            name="prosedur" 
                            rows="5"
                            placeholder="Masukkan prosedur, satu per baris">{{ old('prosedur') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Tulis setiap langkah di baris baru</p>
                        @error('prosedur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Waktu Pelayanan"
                            name="waktu_pelayanan"
                            id="waktu_pelayanan"
                            type="text"
                            value="{{ old('waktu_pelayanan') }}"
                            placeholder="Contoh: 7 hari kerja"
                            :error="$errors->first('waktu_pelayanan')"
                        />
                    </div>
                    
                    <div>
                        <x-input
                            label="Biaya"
                            name="biaya"
                            id="biaya"
                            type="text"
                            value="{{ old('biaya') }}"
                            placeholder="Contoh: Gratis atau Rp 50.000"
                            :error="$errors->first('biaya')"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Kontak Penanggung Jawab"
                            name="kontak_penanggung_jawab"
                            id="kontak_penanggung_jawab"
                            type="text"
                            value="{{ old('kontak_penanggung_jawab') }}"
                            placeholder="Nomor telepon atau email"
                            :error="$errors->first('kontak_penanggung_jawab')"
                        />
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   id="status" 
                                   name="status" 
                                   value="1"
                                   {{ old('status') ? 'checked' : '' }}>
                            <label for="status" class="ml-2 block text-sm text-gray-900">
                                Aktif
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Centang untuk mengaktifkan layanan</p>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="file_formulir" class="block text-sm font-medium text-gray-700 mb-2">
                        File Formulir
                    </label>
                    <input type="file" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('file_formulir') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                           id="file_formulir" 
                           name="file_formulir" 
                           accept=".pdf,.doc,.docx">
                    @error('file_formulir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX. Maksimal 2MB.</p>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <x-button variant="secondary" href="{{ route('admin.pelayanan.index') }}">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </x-button>
                    <x-button type="submit" variant="success">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
