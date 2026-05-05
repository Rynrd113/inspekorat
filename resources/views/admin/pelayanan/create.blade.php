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
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Pelayanan Baru</h1>
        <p class="text-gray-600 mt-1">Tambahkan layanan publik yang akan ditampilkan di portal</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">
                <i class="fas fa-plus mr-2 text-blue-600"></i>
                Form Tambah Pelayanan
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.pelayanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama & Kategori -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pelayanan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="nama"
                               value="{{ old('nama') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama') border-red-500 @enderror"
                               placeholder="Contoh: Izin Usaha"
                               required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori"
                                id="kategori"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kategori') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\Pelayanan::getKategoriOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi"
                              id="deskripsi"
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Jelaskan deskripsi pelayanan secara singkat"
                              required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Syarat & Prosedur -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="persyaratan" class="block text-sm font-medium text-gray-700 mb-2">
                            Syarat & Ketentuan
                        </label>
                        <textarea name="persyaratan"
                                  id="persyaratan"
                                  rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('persyaratan') border-red-500 @enderror"
                                  placeholder="Masukkan syarat, satu per baris">{{ old('persyaratan') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">💡 Tulis setiap syarat di baris baru</p>
                        @error('persyaratan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prosedur" class="block text-sm font-medium text-gray-700 mb-2">
                            Prosedur
                        </label>
                        <textarea name="prosedur"
                                  id="prosedur"
                                  rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prosedur') border-red-500 @enderror"
                                  placeholder="Masukkan prosedur, satu per baris">{{ old('prosedur') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">💡 Tulis setiap langkah di baris baru</p>
                        @error('prosedur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Waktu & Biaya -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="waktu_penyelesaian" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Penyelesaian
                        </label>
                        <input type="text"
                               name="waktu_penyelesaian"
                               id="waktu_penyelesaian"
                               value="{{ old('waktu_penyelesaian') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: 7 hari kerja">
                        @error('waktu_penyelesaian')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="biaya" class="block text-sm font-medium text-gray-700 mb-2">
                            Biaya
                        </label>
                        <input type="text"
                               name="biaya"
                               id="biaya"
                               value="{{ old('biaya') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Gratis atau Rp 50.000">
                        @error('biaya')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- PIC & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="kontak_pic" class="block text-sm font-medium text-gray-700 mb-2">
                            Kontak Penanggung Jawab
                        </label>
                        <input type="text"
                               name="kontak_pic"
                               id="kontak_pic"
                               value="{{ old('kontak_pic') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Nama atau nomor telepon">
                        @error('kontak_pic')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox"
                                   name="status"
                                   id="status"
                                   value="1"
                                   {{ old('status') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Aktif (tampilkan di portal)</span>
                        </label>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Divider -->
                <hr class="my-6 border-gray-200">

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <a href="{{ route('admin.pelayanan.index') }}"
                       class="inline-flex items-center justify-center px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pelayanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
