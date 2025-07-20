@extends('layouts.public')

@section('title', 'Kontak Kami - Portal Inspektorat Papua Tengah')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center">
            <div class="lg:w-2/3 text-center lg:text-left">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Kontak Kami</h1>
                <p class="text-xl text-blue-100">Hubungi kami untuk informasi lebih lanjut atau sampaikan masukan Anda.</p>
            </div>
            <div class="lg:w-1/3 text-center mt-8 lg:mt-0">
                <i class="fas fa-envelope text-8xl opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('public.index') }}" class="text-blue-600 hover:text-blue-800">Beranda</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-700">Kontak</li>
        </ol>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="ml-auto text-green-700 hover:text-green-900" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h4 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-paper-plane text-blue-600 mr-3"></i>
                        Kirim Pesan
                    </h4>
                    
                    <form method="POST" action="{{ route('kontak.kirim') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}"
                                       required>
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="pesan" class="block text-sm font-medium text-gray-700 mb-2">Pesan <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pesan') border-red-500 @enderror" 
                                      id="pesan" 
                                      name="pesan" 
                                      rows="6" 
                                      placeholder="Tuliskan pesan atau pertanyaan Anda di sini..."
                                      required>{{ old('pesan') }}</textarea>
                            @error('pesan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h4 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        Informasi Kontak
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-medium text-gray-900 mb-1">Alamat</h6>
                                <p class="text-gray-600">{{ $kontak->alamat ?? 'Jl. Raya Nabire No. 123, Nabire, Papua Tengah' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-phone text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-medium text-gray-900 mb-1">Telepon</h6>
                                <p class="text-gray-600">{{ $kontak->telepon ?? '(0984) 21234' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-medium text-gray-900 mb-1">Email</h6>
                                <p class="text-gray-600">{{ $kontak->email ?? 'inspektorat@paputengah.go.id' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-clock text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-medium text-gray-900 mb-1">Jam Operasional</h6>
                                <p class="text-gray-600">{{ $kontak->jam_operasional ?? 'Senin - Jumat: 08:00 - 16:00 WIT' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h5 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                        Butuh Bantuan Cepat?
                    </h5>
                    <p class="text-gray-600 mb-4">Lihat halaman FAQ untuk jawaban atas pertanyaan yang sering diajukan.</p>
                    <a href="{{ route('public.faq') }}" class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lihat FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
