@extends('layouts.admin')

@section('title', 'Edit Pelayanan')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Pelayanan</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li><a href="{{ route('admin.pelayanan.index') }}" class="text-blue-600 hover:text-blue-800">Pelayanan</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
                <li class="text-gray-600">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-edit mr-2 text-blue-600"></i>
                Form Edit Pelayanan
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.pelayanan.update', $pelayanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pelayanan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $pelayanan->nama ?? '') }}" 
                               required>
                        @error('nama')
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
                            <option value="audit" {{ old('kategori', $pelayanan->kategori ?? '') == 'audit' ? 'selected' : '' }}>Audit</option>
                            <option value="konsultasi" {{ old('kategori', $pelayanan->kategori ?? '') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                            <option value="pengaduan" {{ old('kategori', $pelayanan->kategori ?? '') == 'pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                            <option value="lainnya" {{ old('kategori', $pelayanan->kategori ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror" 
                              id="deskripsi" 
                              name="deskripsi" 
                              rows="4" 
                              required>{{ old('deskripsi', $pelayanan->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="syarat" class="block text-sm font-medium text-gray-700 mb-2">
                            Syarat & Ketentuan
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('syarat') border-red-500 @enderror" 
                                  id="syarat" 
                                  name="syarat" 
                                  rows="3">{{ old('syarat', $pelayanan->syarat ?? '') }}</textarea>
                        @error('syarat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="prosedur" class="block text-sm font-medium text-gray-700 mb-2">
                            Prosedur
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prosedur') border-red-500 @enderror" 
                                  id="prosedur" 
                                  name="prosedur" 
                                  rows="3">{{ old('prosedur', $pelayanan->prosedur ?? '') }}</textarea>
                        @error('prosedur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="waktu_layanan" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Layanan
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waktu_layanan') border-red-500 @enderror" 
                               id="waktu_layanan" 
                               name="waktu_layanan" 
                               value="{{ old('waktu_layanan', $pelayanan->waktu_layanan ?? '') }}" 
                               placeholder="Contoh: 7 hari kerja">
                        @error('waktu_layanan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="biaya" class="block text-sm font-medium text-gray-700 mb-2">
                            Biaya
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('biaya') border-red-500 @enderror" 
                               id="biaya" 
                               name="biaya" 
                               value="{{ old('biaya', $pelayanan->biaya ?? '') }}" 
                               placeholder="Contoh: Gratis atau Rp 50.000">
                        @error('biaya')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="kontak" class="block text-sm font-medium text-gray-700 mb-2">
                            Kontak
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kontak') border-red-500 @enderror" 
                               id="kontak" 
                               name="kontak" 
                               value="{{ old('kontak', $pelayanan->kontak ?? '') }}" 
                               placeholder="Nomor telepon atau email">
                        @error('kontak')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="aktif" {{ old('status', $pelayanan->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $pelayanan->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar
                    </label>
                    @if(isset($pelayanan->gambar) && $pelayanan->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $pelayanan->gambar) }}" 
                                 alt="Current Image" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            <p class="text-sm text-gray-500 mt-1">Gambar saat ini</p>
                        </div>
                    @endif
                    <input type="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gambar') border-red-500 @enderror" 
                           id="gambar" 
                           name="gambar" 
                           accept="image/*">
                    @error('gambar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>

                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.pelayanan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
@endsection
