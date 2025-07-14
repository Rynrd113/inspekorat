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

    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
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
                            name="nama"
                            id="nama"
                            type="text"
                            value="{{ old('nama') }}"
                            required="true"
                            :error="$errors->first('nama')"
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
                        <x-input
                            label="Syarat & Ketentuan"
                            name="syarat"
                            id="syarat"
                            type="textarea"
                            rows="3"
                            :error="$errors->first('syarat')"
                        >{{ old('syarat') }}</x-input>
                    </div>
                    
                    <div>
                        <x-input
                            label="Prosedur"
                            name="prosedur"
                            id="prosedur"
                            type="textarea"
                            rows="3"
                            :error="$errors->first('prosedur')"
                        >{{ old('prosedur') }}</x-input>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input
                            label="Waktu Layanan"
                            name="waktu_layanan"
                            id="waktu_layanan"
                            type="text"
                            value="{{ old('waktu_layanan') }}"
                            placeholder="Contoh: 7 hari kerja"
                            :error="$errors->first('waktu_layanan')"
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
                            label="Kontak"
                            name="kontak"
                            id="kontak"
                            type="text"
                            value="{{ old('kontak') }}"
                            placeholder="Nomor telepon atau email"
                            :error="$errors->first('kontak')"
                        />
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('status') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
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
                    <input type="file" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors @error('gambar') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                           id="gambar" 
                           name="gambar" 
                           accept="image/*">
                    @error('gambar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
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
