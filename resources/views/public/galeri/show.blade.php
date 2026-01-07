@extends('layouts.public')

@use('Illuminate\Support\Facades\Storage')

@section('title', $galeri->judul . ' - Galeri Inspektorat Papua Tengah')
@section('description', Str::limit($galeri->deskripsi, 160))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('public.index') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li>
                        <a href="{{ route('public.galeri.index') }}" class="text-gray-500 hover:text-gray-700">
                            Galeri
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    </li>
                    <li class="text-gray-900 font-medium">{{ Str::limit($galeri->judul, 50) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Gallery Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Media Display -->
                    <div class="relative bg-gray-900">
                        @if(in_array($galeri->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            @php
                                $imagePath = $galeri->file_path;
                                $imageUrl = null;
                                
                                // Try different paths
                                if (Storage::disk('public')->exists($imagePath)) {
                                    $imageUrl = asset('storage/' . $imagePath);
                                } elseif (file_exists(public_path('uploads/' . $imagePath))) {
                                    $imageUrl = asset('uploads/' . $imagePath);
                                } elseif (Storage::disk('public')->exists('uploads/' . $imagePath)) {
                                    $imageUrl = asset('storage/uploads/' . $imagePath);
                                }
                            @endphp
                            
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $galeri->judul }}" 
                                     class="w-full max-h-[600px] object-contain mx-auto">
                            @else
                                <div class="w-full h-96 flex items-center justify-center">
                                    <div class="text-center text-gray-400">
                                        <i class="fas fa-image text-6xl mb-4"></i>
                                        <p>Gambar tidak tersedia</p>
                                    </div>
                                </div>
                            @endif
                        @elseif(in_array($galeri->file_type, ['mp4', 'avi', 'mov', 'wmv', 'webm']))
                            <video controls class="w-full max-h-[600px]">
                                <source src="{{ asset('storage/' . $galeri->file_path) }}" type="video/{{ $galeri->file_type }}">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        @else
                            <div class="w-full h-96 flex items-center justify-center">
                                <div class="text-center text-gray-400">
                                    <i class="fas fa-file text-6xl mb-4"></i>
                                    <p>File: {{ $galeri->file_name }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Content Details -->
                    <div class="p-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $galeri->judul }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                <i class="fas fa-folder mr-1"></i>{{ $galeri->kategori }}
                            </span>
                            @if($galeri->tanggal_publikasi)
                            <span>
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $galeri->tanggal_publikasi->format('d F Y') }}
                            </span>
                            @endif
                            @if($galeri->file_size)
                            <span>
                                <i class="fas fa-file mr-1"></i>
                                {{ number_format($galeri->file_size / 1024, 2) }} KB
                            </span>
                            @endif
                        </div>

                        @if($galeri->deskripsi)
                        <div class="prose max-w-none text-gray-600">
                            <p>{{ $galeri->deskripsi }}</p>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-6 flex flex-wrap gap-4">
                            @php
                                $downloadUrl = null;
                                if (Storage::disk('public')->exists($galeri->file_path)) {
                                    $downloadUrl = asset('storage/' . $galeri->file_path);
                                } elseif (file_exists(public_path('uploads/' . $galeri->file_path))) {
                                    $downloadUrl = asset('uploads/' . $galeri->file_path);
                                }
                            @endphp
                            
                            @if($downloadUrl)
                            <a href="{{ $downloadUrl }}" 
                               download="{{ $galeri->file_name }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                            @endif
                            
                            <a href="{{ route('public.galeri.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Galeri
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Related Items -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-images text-blue-500 mr-2"></i>Galeri Terkait
                    </h3>
                    
                    @if($related && $related->count() > 0)
                        <div class="space-y-4">
                            @foreach($related as $item)
                                <a href="{{ route('public.galeri.show', $item->id) }}" 
                                   class="block group">
                                    <div class="flex gap-4">
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                            @php
                                                $thumbUrl = null;
                                                if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
                                                    $thumbUrl = asset('storage/' . $item->thumbnail);
                                                } elseif ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                                                    $thumbUrl = asset('storage/' . $item->file_path);
                                                } elseif ($item->file_path && file_exists(public_path('uploads/' . $item->file_path))) {
                                                    $thumbUrl = asset('uploads/' . $item->file_path);
                                                }
                                            @endphp
                                            
                                            @if($thumbUrl && in_array($item->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <img src="{{ $thumbUrl }}" 
                                                     alt="{{ $item->judul }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                            @elseif(in_array($item->file_type, ['mp4', 'avi', 'mov', 'wmv', 'webm']))
                                                <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                                    <i class="fas fa-play text-white"></i>
                                                </div>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                                {{ $item->judul }}
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $item->tanggal_publikasi ? $item->tanggal_publikasi->format('d M Y') : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Tidak ada galeri terkait.</p>
                    @endif

                    <!-- View All Link -->
                    <div class="mt-6 pt-4 border-t">
                        <a href="{{ route('public.galeri.index') }}" 
                           class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                            Lihat Semua Galeri
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
