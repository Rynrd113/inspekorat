@extends('layouts.public')

@section('title', $berita->judul . ' - Portal Inspektorat Papua Tengah')
@section('description', Str::limit(strip_tags($berita->konten), 160))

@section('content')

<div class="min-h-screen bg-gray-50">

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('public.index') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-home"></i>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <a href="{{ route('public.berita.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Berita</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ Str::limit($berita->judul, 50) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Article Content -->
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Article Header -->
        <header class="mb-8">
            <div class="mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ strtoupper($berita->kategori) }}
                </span>
            </div>
            
            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $berita->judul }}
            </h1>
            
            <div class="flex flex-wrap items-center text-sm text-gray-500 space-x-6 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    <span>{{ $berita->penulis }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $berita->published_at->format('d F Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <span>{{ $berita->published_at->format('H:i') }} WIT</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    <span>{{ number_format($berita->views) }} views</span>
                </div>
            </div>
        </header>

        <!-- Article Body -->
        <div class="prose prose-lg max-w-none">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <!-- Featured Image Placeholder -->
                <div class="mb-8">
                    <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-blue-400 text-6xl"></i>
                    </div>
                </div>
                
                <!-- Article Content -->
                <div class="text-gray-700 leading-relaxed">
                    {!! nl2br(e($berita->konten)) !!}
                </div>
            </div>
        </div>

        <!-- Share & Actions -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                    <span class="text-sm font-medium text-gray-900">Bagikan:</span>
                    <div class="flex space-x-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f mr-2"></i>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($berita->judul) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-sky-500 hover:bg-sky-600 transition-colors">
                            <i class="fab fa-twitter mr-2"></i>
                            Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . request()->fullUrl()) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <i class="fab fa-whatsapp mr-2"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('public.berita.index') }}" class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </article>

    <!-- Related Articles -->
    @if($relatedBerita->count() > 0)
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="border-t border-gray-200 pt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Berita Terkait</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedBerita as $related)
                <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ strtoupper($related->kategori) }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 hover:text-blue-600 transition-colors">
                            <a href="{{ route('public.berita.show', $related->id) }}">
                                {{ Str::limit($related->judul, 60) }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4">
                            {{ Str::limit(strip_tags($related->konten), 100) }}
                        </p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $related->published_at->format('d M Y') }}</span>
                            <span>{{ number_format($related->views) }} views</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
