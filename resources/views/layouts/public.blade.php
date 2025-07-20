<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Portal Informasi Pemerintahan - Inspektorat Papua Tengah')</title>
    <meta name="description" content="@yield('description', 'Portal Informasi Pemerintahan Inspektorat Provinsi Papua Tengah - Layanan publik dan informasi pemerintahan.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    <x-navigation />

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto py-12">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('logo.svg') }}" alt="Logo" class="h-10 w-10 mr-3">
                        <div>
                            <div class="font-bold text-lg">Inspektorat Provinsi</div>
                            <small class="text-gray-300">Papua Tengah</small>
                        </div>
                    </div>
                    <p class="text-gray-300">Portal resmi Inspektorat Provinsi Papua Tengah untuk layanan publik dan informasi pemerintahan.</p>
                </div>
                
                <div>
                    <h5 class="font-bold mb-3">Informasi</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ route('public.profil') }}" class="text-gray-300 hover:text-white transition-colors">Profil</a></li>
                        <li><a href="{{ route('public.berita.index') }}" class="text-gray-300 hover:text-white transition-colors">Berita</a></li>
                        <li><a href="{{ route('public.galeri.index') }}" class="text-gray-300 hover:text-white transition-colors">Galeri</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-bold mb-3">Layanan</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ route('public.pelayanan.index') }}" class="text-gray-300 hover:text-white transition-colors">Pelayanan</a></li>
                        <li><a href="{{ route('public.dokumen.index') }}" class="text-gray-300 hover:text-white transition-colors">Dokumen</a></li>
                        <li><a href="{{ route('public.pengaduan') }}" class="text-gray-300 hover:text-white transition-colors">Pengaduan</a></li>
                        <li><a href="{{ route('public.wbs') }}" class="text-gray-300 hover:text-white transition-colors">WBS</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-bold mb-3">Bantuan</h5>
                    <ul class="space-y-2">
                        <li><a href="{{ route('public.faq') }}" class="text-gray-300 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="{{ route('public.kontak') }}" class="text-gray-300 hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                    
                    <h5 class="font-bold mb-3 mt-6">Ikuti Kami</h5>
                    <div class="flex gap-2">
                        <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-blue-400 transition-colors"><i class="fab fa-twitter text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors"><i class="fab fa-youtube text-sm"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="my-8 border-gray-600">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="mb-2 md:mb-0">&copy; {{ date('Y') }} Inspektorat Provinsi Papua Tengah. All rights reserved.</p>
                <small class="text-gray-300">Powered by Laravel & Tailwind CSS</small>
            </div>
        </div>
    </footer>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
