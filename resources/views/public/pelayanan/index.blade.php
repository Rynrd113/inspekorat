@extends('layouts.public')

@section('title', 'Layanan Publik - Inspektorat Papua Tengah')
@section('description', 'Akses berbagai layanan publik yang disediakan oleh Inspektorat Provinsi Papua Tengah.')

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Layanan Publik
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Berbagai layanan publik yang disediakan oleh Inspektorat Provinsi Papua Tengah
                </p>
            </div>
        </div>
    </section>

    <!-- Under Development Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center min-h-[400px]">
                <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mb-6">
                        <i class="fas fa-tools text-6xl text-yellow-500 mb-4"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-3">
                        Dalam Tahap Pengembangan
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Halaman layanan publik sedang dalam tahap pengembangan dan akan segera tersedia untuk melayani Anda dengan lebih baik.
                    </p>
                    <a href="{{ route('public.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
