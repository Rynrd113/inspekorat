@extends('layouts.public')

@section('title', $berita->judul . ' - Portal Inspektorat Papua Tengah')
@section('description', Str::limit(strip_tags($berita->konten), 160))

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center flex-wrap gap-1 text-sm text-gray-500">
                <li>
                    <a href="{{ route('public.index') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li><i class="fas fa-chevron-right text-gray-300 mx-1 text-xs"></i></li>
                <li>
                    <a href="{{ route('public.berita.index') }}" class="hover:text-blue-600 transition-colors">Berita</a>
                </li>
                <li><i class="fas fa-chevron-right text-gray-300 mx-1 text-xs"></i></li>
                <li class="text-gray-800 font-medium truncate max-w-xs">{{ Str::limit($berita->judul, 50) }}</li>
            </ol>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Main Article Card --}}
        <article class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Featured Image --}}
            @php $imgRaw = $berita->thumbnail ?? $berita->gambar; @endphp
            @if($imgRaw)
                @php
                    $imgUrl = filter_var($imgRaw, FILTER_VALIDATE_URL)
                        ? $imgRaw
                        : asset('storage/' . $imgRaw);
                @endphp
                <div class="w-full aspect-video overflow-hidden bg-gray-100">
                    <img src="{{ $imgUrl }}"
                         alt="{{ $berita->judul }}"
                         class="w-full h-full object-cover"
                         onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center\'><i class=\'fas fa-newspaper text-white text-6xl opacity-30\'></i></div>'">
                </div>
            @else
                <div class="w-full h-56 sm:h-72 bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                    <i class="fas fa-newspaper text-white text-6xl opacity-30"></i>
                </div>
            @endif

            <div class="p-6 sm:p-10">

                {{-- Category Badge --}}
                <div class="mb-4">
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full">
                        {{ $berita->kategori }}
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-6">
                    {{ $berita->judul }}
                </h1>

                {{-- Meta --}}
                <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500 pb-6 border-b border-gray-100">
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-user-circle text-gray-400"></i>
                        {{ $berita->author ?? 'Redaksi' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                        {{ $berita->tanggal_publikasi
                            ? \Carbon\Carbon::parse($berita->tanggal_publikasi)->translatedFormat('d F Y')
                            : 'Tanggal tidak tersedia' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fas fa-eye text-gray-400"></i>
                        {{ number_format($berita->views) }} dilihat
                    </span>
                </div>

                {{-- Article Body --}}
                <div class="mt-6 text-gray-700 text-base sm:text-lg leading-relaxed space-y-4">
                    @foreach(array_filter(explode("\n", $berita->konten)) as $paragraph)
                        <p>{{ trim($paragraph) }}</p>
                    @endforeach
                </div>

                {{-- Share --}}
                <div class="mt-10 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-sm font-medium text-gray-600">Bagikan:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($berita->judul) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-white bg-sky-500 hover:bg-sky-600 transition-colors">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . request()->fullUrl()) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                    <a href="{{ route('public.berita.index') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </article>

        {{-- Related Articles --}}
        @if($relatedBerita->count() > 0)
        <section class="mt-10">
            <h2 class="text-xl font-bold text-gray-900 mb-5">Berita Terkait</h2>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
                @foreach($relatedBerita as $related)
                <a href="{{ route('public.berita.show', $related->id) }}"
                   class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                    <div class="h-36 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center flex-shrink-0">
                        @php $relImg = $related->thumbnail ?? $related->gambar; @endphp
                        @if($relImg)
                            <img src="{{ filter_var($relImg, FILTER_VALIDATE_URL) ? $relImg : asset('storage/' . $relImg) }}"
                                 alt="{{ $related->judul }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'">
                        @else
                            <i class="fas fa-newspaper text-blue-300 text-3xl"></i>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <span class="text-xs font-semibold text-blue-600 uppercase mb-1">{{ $related->kategori }}</span>
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors leading-snug mb-2">
                            {{ Str::limit($related->judul, 65) }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-auto">
                            {{ \Carbon\Carbon::parse($related->tanggal_publikasi)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>
@endsection
