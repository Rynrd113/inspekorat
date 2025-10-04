@extends('layouts.admin')

@section('header', 'Edit Profil Organisasi')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.profil.index') }}" class="text-blue-600 hover:text-blue-800">Profil Organisasi</a></li>
<li><span class="text-gray-500">Edit</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Profil Organisasi</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi profil organisasi</p>
        </div>
        <a href="{{ route('admin.profil.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Nama Organisasi -->
                        <div>
                            <label for="nama_organisasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Organisasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_organisasi" 
                                   name="nama_organisasi" 
                                   value="{{ old('nama_organisasi', 'Inspektorat Provinsi Papua Tengah') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_organisasi') border-red-500 @enderror"
                                   required>
                            @error('nama_organisasi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Visi -->
                        <div>
                            <label for="visi" class="block text-sm font-medium text-gray-700 mb-2">
                                Visi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="visi" 
                                      name="visi" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visi') border-red-500 @enderror"
                                      required>{{ old('visi', 'Terwujudnya Aparatur dan Hasil Pengawasan Internal yang Profesional dan Berkualitas demi Pelayanan Publik vang Prima') }}</textarea>
                            @error('visi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Misi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Misi <span class="text-red-500">*</span>
                            </label>
                            <div id="misi-container" class="space-y-3">
                                <div class="flex items-center space-x-2">
                                    <input type="text" 
                                           name="misi[]" 
                                           value="{{ old('misi.0', 'Mewujudkan Peningkatan Kualitas Aparatur dan Hasil Pengawasan untuk mendorong Pelayanan Publik dan Pemerintahan yang Akuntabel') }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Misi 1"
                                           required>
                                    <button type="button" onclick="removeMisi(this)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addMisi()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus mr-1"></i>Tambah Misi
                            </button>
                            @error('misi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sejarah -->
                        <div>
                            <label for="sejarah" class="block text-sm font-medium text-gray-700 mb-2">Sejarah Singkat</label>
                            <textarea id="sejarah" 
                                      name="sejarah" 
                                      rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('sejarah') border-red-500 @enderror">{{ old('sejarah', 'Inspektorat Provinsi Papua Tengah dibentuk seiring dengan pembentukan provinsi Papua Tengah berdasarkan Undang-Undang Nomor 14 Tahun 2022...') }}</textarea>
                            @error('sejarah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-card>

                <!-- Contact Information -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Kontak</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat <span class="text-red-500">*</span>
                                </label>
                                <textarea id="alamat" 
                                          name="alamat" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat') border-red-500 @enderror"
                                          required>{{ old('alamat', 'Jl. Raya Nabire No. 123, Nabire, Papua Tengah') }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="telepon" 
                                       name="telepon" 
                                       value="{{ old('telepon', '(0984) 21234') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telepon') border-red-500 @enderror"
                                       required>
                                @error('telepon')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', 'inspektorat@paputengah.go.id') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                <input type="url" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website', 'https://inspektorat.paputengah.go.id') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror">
                                @error('website')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Operasional -->
                            <div>
                                <label for="jam_operasional" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Operasional <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="jam_operasional" 
                                       name="jam_operasional" 
                                       value="{{ old('jam_operasional', 'Senin - Jumat: 08:00 - 16:00 WIT') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_operasional') border-red-500 @enderror"
                                       required>
                                @error('jam_operasional')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Side Panel -->
            <div class="space-y-6">
                <!-- Struktur Organisasi Upload -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Struktur Organisasi</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="struktur_organisasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Struktur Organisasi
                            </label>
                            <input type="file" 
                                   id="struktur_organisasi" 
                                   name="struktur_organisasi" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('struktur_organisasi') border-red-500 @enderror">
                            <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG, GIF. Maksimal 5MB</p>
                            @error('struktur_organisasi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Structure Preview -->
                        <div class="mt-4">
                            <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-sitemap text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">Preview Struktur</p>
                                    <p class="text-gray-400 text-xs">File akan muncul di sini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Actions -->
                <x-card>
                    <div class="p-6">
                        <div class="space-y-4">
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                            
                            <a href="{{ route('admin.profil.index') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function addMisi() {
    const container = document.getElementById('misi-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" 
               name="misi[]" 
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Misi baru"
               required>
        <button type="button" onclick="removeMisi(this)" class="text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeMisi(button) {
    const container = document.getElementById('misi-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    } else {
        alert('Minimal harus ada satu misi');
    }
}

// Preview struktur organisasi
document.getElementById('struktur_organisasi').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('.fa-sitemap').parentElement.parentElement;
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-48 object-contain rounded-lg">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
