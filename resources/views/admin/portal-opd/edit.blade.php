@extends('layouts.admin')

@section('header', 'Edit Portal OPD')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.portal-opd.index') }}" class="text-blue-600 hover:text-blue-800">Portal OPD</a></li>
<li><span class="text-gray-500">Edit</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Portal OPD</h1>
            <p class="text-gray-600 mt-1">Edit informasi {{ $portalOpd->nama_opd }}</p>
        </div>
        <a href="{{ route('admin.portal-opd.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <x-card>
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Edit Informasi OPD</h3>
        </div>
        
        <form action="{{ route('admin.portal-opd.update', $portalOpd) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="nama_opd" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama OPD <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_opd" id="nama_opd" required
                               value="{{ old('nama_opd', $portalOpd->nama_opd) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Contoh: Dinas Pendidikan Papua Tengah">
                        @error('nama_opd')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="singkatan" class="block text-sm font-medium text-gray-700 mb-1">
                            Singkatan
                        </label>
                        <input type="text" name="singkatan" id="singkatan"
                               value="{{ old('singkatan', $portalOpd->singkatan) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Contoh: DISDIK">
                        @error('singkatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kepala_opd" class="block text-sm font-medium text-gray-700 mb-1">
                            Kepala OPD
                        </label>
                        <input type="text" name="kepala_opd" id="kepala_opd"
                               value="{{ old('kepala_opd', $portalOpd->kepala_opd) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Nama Kepala OPD">
                        @error('kepala_opd')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nip_kepala" class="block text-sm font-medium text-gray-700 mb-1">
                            NIP Kepala OPD
                        </label>
                        <input type="text" name="nip_kepala" id="nip_kepala"
                               value="{{ old('nip_kepala', $portalOpd->nip_kepala) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="NIP">
                        @error('nip_kepala')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat
                        </label>
                        <textarea name="alamat" id="alamat" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Alamat lengkap OPD">{{ old('alamat', $portalOpd->alamat) }}</textarea>
                        @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">
                                Telepon
                            </label>
                            <input type="text" name="telepon" id="telepon"
                                   value="{{ old('telepon', $portalOpd->telepon) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Nomor telepon">
                            @error('telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $portalOpd->email) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Email resmi">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                            Website
                        </label>
                        <input type="url" name="website" id="website"
                               value="{{ old('website', $portalOpd->website) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="https://example.com">
                        @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">
                            Logo OPD
                        </label>
                        @if($portalOpd->logo)
                        <div class="mb-2">
                            <img src="{{ $portalOpd->logo_url }}" alt="Current Logo" class="w-20 h-20 object-cover rounded">
                            <p class="text-xs text-gray-500 mt-1">Logo saat ini</p>
                        </div>
                        @endif
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="banner" class="block text-sm font-medium text-gray-700 mb-1">
                            Banner OPD
                        </label>
                        @if($portalOpd->banner)
                        <div class="mb-2">
                            <img src="{{ $portalOpd->banner_url }}" alt="Current Banner" class="w-full h-20 object-cover rounded">
                            <p class="text-xs text-gray-500 mt-1">Banner saat ini</p>
                        </div>
                        @endif
                        <input type="file" name="banner" id="banner" accept="image/*"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah.</p>
                        @error('banner')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="status" value="1" {{ old('status', $portalOpd->status) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Status Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Full Width Sections -->
            <div class="space-y-6">
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Deskripsi singkat tentang OPD">{{ old('deskripsi', $portalOpd->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="visi" class="block text-sm font-medium text-gray-700 mb-1">
                        Visi
                    </label>
                    <textarea name="visi" id="visi" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Visi OPD">{{ old('visi', $portalOpd->visi) }}</textarea>
                    @error('visi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Misi
                    </label>
                    <div id="misi-container" class="space-y-2">
                        @if(old('misi') || $portalOpd->misi)
                            @php
                                $misiItems = old('misi', $portalOpd->misi) ?? [''];
                            @endphp
                            @foreach($misiItems as $index => $misiItem)
                                <div class="flex items-center space-x-2 misi-item">
                                    <input type="text" name="misi[]" value="{{ $misiItem }}"
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Poin misi...">
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-misi">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center space-x-2 misi-item">
                                <input type="text" name="misi[]"
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Poin misi...">
                                <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-misi">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-misi" class="mt-2 px-4 py-2 bg-green-500 text-white text-sm rounded-md hover:bg-green-600">
                        <i class="fas fa-plus mr-2"></i>Tambah Misi
                    </button>
                    @error('misi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.portal-opd.index') }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </x-card>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const misiContainer = document.getElementById('misi-container');
    const addMisiBtn = document.getElementById('add-misi');

    addMisiBtn.addEventListener('click', function() {
        const misiItem = document.createElement('div');
        misiItem.className = 'flex items-center space-x-2 misi-item';
        misiItem.innerHTML = `
            <input type="text" name="misi[]"
                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Poin misi...">
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 remove-misi">
                <i class="fas fa-trash"></i>
            </button>
        `;
        misiContainer.appendChild(misiItem);
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-misi');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.misi-item').remove();
            });
        });
    }

    updateRemoveButtons();
});
</script>
@endsection
