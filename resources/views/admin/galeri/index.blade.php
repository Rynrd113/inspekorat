@extends('layouts.admin')

@use('Illuminate\Support\Facades\Storage')

@section('title', 'Manajemen Galeri')

@section('header', 'Manajemen Galeri')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><i class="fas fa-chevron-right mx-2 text-gray-300"></i></li>
<li class="text-gray-600">Galeri</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Galeri</h1>
            <p class="text-gray-600 mt-1">Kelola foto dan video galeri</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.galeri.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Media
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <x-search-input 
                            name="search"
                            placeholder="Cari media..."
                            value="{{ request('search') }}"
                            with-icon="true"
                            size="md"
                        />
                    </div>

                    <!-- Filter Fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
                        <select name="tipe" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Tipe</option>
                            <option value="foto" {{ request('tipe') == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ request('tipe') == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="kegiatan" {{ request('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="acara" {{ request('kategori') == 'acara' ? 'selected' : '' }}>Acara</option>
                            <option value="fasilitas" {{ request('kategori') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <x-button type="submit" variant="primary" size="md">
                        <i class="fas fa-search mr-2"></i>Cari
                    </x-button>
                    
                    <x-button 
                        type="button" 
                        variant="secondary" 
                        size="md"
                        onclick="window.location.href='{{ route('admin.galeri.index') }}'"
                    >
                        <i class="fas fa-undo mr-2"></i>Reset
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        @if(isset($galeris) && $galeris->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galeris as $galeri)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        @if($galeri->file_path && Storage::disk('public')->exists($galeri->file_path))
                            @if($galeri->is_image)
                                <img src="{{ asset('uploads/' . $galeri->file_path) }}" class="w-full h-48 object-cover" alt="{{ $galeri->judul }}">
                                <div class="absolute bottom-2 left-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-image mr-1"></i>Foto
                                    </span>
                                </div>
                            @elseif($galeri->is_video)
                                <div class="bg-gray-800 flex items-center justify-center h-48">
                                    <i class="fas fa-play-circle text-white text-4xl"></i>
                                </div>
                            @else
                                <div class="bg-gray-600 flex items-center justify-center h-48">
                                    <i class="fas fa-file text-white text-4xl"></i>
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-200 flex items-center justify-center h-48">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                                <div class="absolute bottom-2 left-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation mr-1"></i>No File
                                    </span>
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-2 left-2">
                            @if($galeri->is_image)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Foto</span>
                            @elseif($galeri->is_video)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Video</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ strtoupper($galeri->file_type) }}</span>
                            @endif
                        </div>
                        <div class="absolute top-2 right-2">
                            @if($galeri->status)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Non-aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $galeri->judul }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($galeri->deskripsi, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ $galeri->created_at->format('d M Y') }}</span>
                            <div class="flex space-x-2">
                                @if($galeri && $galeri->id)
                                    <a href="/admin/galeri/{{ $galeri->id }}" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/galeri/{{ $galeri->id }}/edit" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="confirmDelete({{ $galeri->id }})" 
                                            class="text-red-600 hover:text-red-900" 
                                            title="Hapus"
                                            data-delete-url="/admin/galeri/{{ $galeri->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm">
                                        ID: {{ $galeri->id ?? 'null' }} - Data tidak valid
                                        <br>
                                        Debug: {{ json_encode($galeri->toArray()) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        @if($galeris->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $galeris->links() }}
        </div>
        @endif
        
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-images text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada media galeri</h3>
            <p class="text-gray-600 mb-4">Belum ada foto atau video yang ditambahkan ke galeri.</p>
            <a href="{{ route('admin.galeri.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Media Pertama
            </a>
        </div>
        @endif
    </div>

    <!-- Media View Modal -->
    <div id="mediaModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Media Preview
                        </h3>
                        <button type="button" onclick="closeMediaModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="mediaContent" class="flex items-center justify-center">
                        <!-- Media content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Hapus
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin menghapus media ini?
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" action="" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Hapus
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Define functions in global scope immediately
(function() {
    'use strict';
    
    window.viewMedia = function(type, src) {
        var mediaContent = document.getElementById('mediaContent');
        var modal = document.getElementById('mediaModal');
        
        if (type === 'foto') {
            mediaContent.innerHTML = '<img src="' + src + '" class="max-w-full max-h-96 object-contain" alt="Media">';
        } else if (type === 'video') {
            mediaContent.innerHTML = '<video controls class="max-w-full max-h-96"><source src="' + src + '" type="video/mp4">Your browser does not support the video tag.</video>';
        }
        
        modal.classList.remove('hidden');
    };

    window.closeMediaModal = function() {
        document.getElementById('mediaModal').classList.add('hidden');
    };

    window.confirmDelete = function(id) {
        var deleteUrl = '/admin/galeri/' + id;
        var form = document.getElementById('deleteForm');
        form.action = deleteUrl;
        document.getElementById('deleteModal').classList.remove('hidden');
    };

    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };

    // Initialize modal listeners
    function initModalListeners() {
        var mediaModal = document.getElementById('mediaModal');
        var deleteModal = document.getElementById('deleteModal');
        
        if (mediaModal) {
            mediaModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.closeMediaModal();
                }
            });
        }
        
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    window.closeDeleteModal();
                }
            });
        }
    }

    // Run when script loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initModalListeners);
    } else {
        initModalListeners();
    }
})();
</script>
@endpush
