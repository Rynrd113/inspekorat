@extends('layouts.admin')

@section('header', 'Detail Berita')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600">Dashboard</a>
    <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.portal-papua-tengah.index') }}" class="text-gray-400 hover:text-gray-600">Portal Berita</a>
    <i class="fas fa-chevron-right mx-2 text-gray-300"></i>
</li>
<li class="text-gray-600">Detail Berita</li>
@endsection

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Berita</h1>
            <p class="text-gray-600">Informasi lengkap berita</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.portal-papua-tengah.edit', $portalPapuaTengah) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.portal-papua-tengah.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Konten Berita -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Konten Utama -->
        <div class="lg:col-span-2">
            <x-card>
                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">{{ $portalPapuaTengah->judul }}</h2>
                        <p class="text-gray-600 text-sm mt-2">{{ $portalPapuaTengah->slug }}</p>
                    </div>
                    
                    <!-- Metadata -->
                    <div class="flex flex-wrap items-center gap-4 py-4 border-y border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-user mr-2"></i>
                            <span>{{ $portalPapuaTengah->penulis }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $portalPapuaTengah->published_at ? $portalPapuaTengah->published_at->format('d F Y') : 'Belum dipublikasikan' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-eye mr-2"></i>
                            <span>{{ $portalPapuaTengah->views }} views</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($portalPapuaTengah->kategori === 'berita') bg-blue-100 text-blue-800
                                @elseif($portalPapuaTengah->kategori === 'pengumuman') bg-green-100 text-green-800
                                @elseif($portalPapuaTengah->kategori === 'kegiatan') bg-yellow-100 text-yellow-800
                                @elseif($portalPapuaTengah->kategori === 'regulasi') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($portalPapuaTengah->kategori) }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            @if($portalPapuaTengah->is_published)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Dipublikasikan
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-edit mr-1"></i>
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Konten -->
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($portalPapuaTengah->konten)) !!}
                    </div>
                    
                    <!-- Tags -->
                    @if($portalPapuaTengah->tags)
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Tags:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $portalPapuaTengah->tags) as $tag)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-tag mr-1"></i>
                                {{ trim($tag) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </x-card>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informasi Publikasi -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Publikasi</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            @if($portalPapuaTengah->is_published)
                                <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Dipublikasikan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-edit mr-1"></i>
                                    Draft
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
                        <div class="mt-1 text-sm text-gray-900">
                            {{ $portalPapuaTengah->published_at ? $portalPapuaTengah->published_at->format('d F Y H:i') : 'Belum dipublikasikan' }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                        <div class="mt-1 text-sm text-gray-900">
                            {{ $portalPapuaTengah->created_at->format('d F Y H:i') }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Diubah</label>
                        <div class="mt-1 text-sm text-gray-900">
                            {{ $portalPapuaTengah->updated_at->format('d F Y H:i') }}
                        </div>
                    </div>
                </div>
            </x-card>
            
            <!-- Statistik -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Views</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $portalPapuaTengah->views }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Panjang Konten</span>
                        <span class="text-lg font-semibold text-gray-900">{{ strlen($portalPapuaTengah->konten) }} karakter</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Jumlah Tag</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $portalPapuaTengah->tags ? count(explode(',', $portalPapuaTengah->tags)) : 0 }}</span>
                    </div>
                </div>
            </x-card>
            
            <!-- Aksi -->
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.portal-papua-tengah.edit', $portalPapuaTengah) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>Edit Berita
                    </a>
                    
                    <form method="POST" action="{{ route('admin.portal-papua-tengah.destroy', $portalPapuaTengah) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-trash mr-2"></i>Hapus Berita
                        </button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
