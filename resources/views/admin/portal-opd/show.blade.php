@extends('layouts.admin')

@section('header', 'Detail Portal OPD')

@section('breadcrumb')
<li><a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
<li><a href="{{ route('admin.portal-opd.index') }}" class="text-blue-600 hover:text-blue-800">Portal OPD</a></li>
<li><span class="text-gray-500">Detail</span></li>
@endsection

@section('main-content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Portal OPD</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap {{ $portalOpd->nama_opd }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.portal-opd.edit', $portalOpd) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('public.portal-opd.show', $portalOpd) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                <i class="fas fa-eye mr-2"></i>Lihat Publik
            </a>
            <a href="{{ route('admin.portal-opd.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama OPD</label>
                            <p class="text-gray-900">{{ $portalOpd->nama_opd }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Singkatan</label>
                            <p class="text-gray-900">{{ $portalOpd->singkatan ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kepala OPD</label>
                            <p class="text-gray-900">{{ $portalOpd->kepala_opd ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP Kepala</label>
                            <p class="text-gray-900">{{ $portalOpd->nip_kepala ?: '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            @if($portalOpd->status)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Nonaktif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Description -->
            @if($portalOpd->deskripsi)
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Deskripsi</h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($portalOpd->deskripsi)) !!}
                    </div>
                </div>
            </x-card>
            @endif

            <!-- Vision & Mission -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($portalOpd->visi)
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Visi</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 italic">{{ $portalOpd->visi }}</p>
                    </div>
                </x-card>
                @endif

                @if($portalOpd->misi && count($portalOpd->misi) > 0)
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Misi</h3>
                    </div>
                    <div class="p-6">
                        <ol class="space-y-2">
                            @foreach($portalOpd->misi as $index => $misiItem)
                            @if($misiItem)
                            <li class="flex items-start space-x-2">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 text-sm font-bold rounded-full flex items-center justify-center">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-gray-700">{{ $misiItem }}</span>
                            </li>
                            @endif
                            @endforeach
                        </ol>
                    </div>
                </x-card>
                @endif
            </div>

            <!-- Address -->
            @if($portalOpd->alamat)
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Alamat</h3>
                </div>
                <div class="p-6">
                    <div class="text-gray-700">
                        {!! nl2br(e($portalOpd->alamat)) !!}
                    </div>
                </div>
            </x-card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Images -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Media</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                        <div class="w-20 h-20 border border-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ $portalOpd->logo_url }}" alt="{{ $portalOpd->nama_opd }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Banner -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Banner</label>
                        <div class="w-full h-24 border border-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ $portalOpd->banner_url }}" alt="{{ $portalOpd->nama_opd }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Contact Info -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Kontak</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <p class="text-gray-900">{{ $portalOpd->telepon ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $portalOpd->email ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        @if($portalOpd->website)
                        <a href="{{ $portalOpd->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            {{ $portalOpd->website }}
                            <i class="fas fa-external-link-alt text-xs ml-1"></i>
                        </a>
                        @else
                        <p class="text-gray-900">-</p>
                        @endif
                    </div>
                </div>
            </x-card>

            <!-- Metadata -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Metadata</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat Oleh</label>
                        <p class="text-gray-900">{{ $portalOpd->creator?->name ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ $portalOpd->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($portalOpd->updated_at != $portalOpd->created_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diupdate</label>
                        <p class="text-gray-900">{{ $portalOpd->updated_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diupdate Oleh</label>
                        <p class="text-gray-900">{{ $portalOpd->updater?->name ?: '-' }}</p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Actions -->
            <x-card>
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.portal-opd.edit', $portalOpd) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit OPD
                    </a>
                    <a href="{{ route('public.portal-opd.show', $portalOpd) }}" target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat di Publik
                    </a>
                    <form action="{{ route('admin.portal-opd.destroy', $portalOpd) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus OPD ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Hapus OPD
                        </button>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
