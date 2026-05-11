@extends('layouts.public')

@section('title', 'Web Portal - Inspektorat Papua Tengah')

@section('content')

<div class="min-h-screen bg-gray-50">
    <div class="bg-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Web Portal</h1>
                <p class="text-xl text-blue-100">Daftar portal dan tautan resmi terkait Inspektorat Papua Tengah</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($webPortals->count() > 0)
        @php
            $grouped = $webPortals->groupBy('kategori');
        @endphp

        @foreach($grouped as $kategori => $portals)
        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4 capitalize border-b border-gray-200 pb-2">
                <i class="fas fa-folder-open mr-2 text-blue-500"></i>{{ ucfirst($kategori ?? 'Umum') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($portals as $portal)
                <a href="{{ $portal->url }}" target="_blank" rel="noopener noreferrer"
                   class="bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300 p-6 flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-globe text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 mb-1">{{ $portal->nama }}</h3>
                        @if($portal->deskripsi)
                        <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($portal->deskripsi, 100) }}</p>
                        @endif
                        <span class="inline-flex items-center mt-2 text-xs text-blue-600">
                            <i class="fas fa-external-link-alt mr-1"></i>Kunjungi
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach

        @else
        <div class="text-center py-16">
            <i class="fas fa-globe text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada portal tersedia</h3>
            <p class="text-gray-500">Data portal sedang dalam proses pengisian.</p>
        </div>
        @endif
    </div>
</div>

@endsection
