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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/public.css', 'resources/js/app.js', 'resources/js/public.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('public.index') }}">
                <img src="{{ asset('logo.svg') }}" alt="Logo" height="40" class="me-2">
                <div>
                    <div class="fw-bold text-primary">Inspektorat Provinsi</div>
                    <small class="text-muted">Papua Tengah</small>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.index') }}">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('public.profil') }}">Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('public.berita.index') }}">Berita</a></li>
                            <li><a class="dropdown-item" href="{{ route('public.galeri.index') }}">Galeri</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Layanan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('public.pelayanan.index') }}">Pelayanan</a></li>
                            <li><a class="dropdown-item" href="{{ route('public.dokumen.index') }}">Dokumen</a></li>
                            <li><a class="dropdown-item" href="{{ route('public.pengaduan') }}">Pengaduan</a></li>
                            <li><a class="dropdown-item" href="{{ route('public.wbs') }}">WBS</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.kontak') }}">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('logo.svg') }}" alt="Logo" height="40" class="me-2">
                        <div>
                            <div class="fw-bold">Inspektorat Provinsi</div>
                            <small>Papua Tengah</small>
                        </div>
                    </div>
                    <p class="text-light">Portal resmi Inspektorat Provinsi Papua Tengah untuk layanan publik dan informasi pemerintahan.</p>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Informasi</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('public.profil') }}">Profil</a></li>
                        <li><a href="{{ route('public.berita.index') }}">Berita</a></li>
                        <li><a href="{{ route('public.galeri.index') }}">Galeri</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Layanan</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('public.pelayanan.index') }}">Pelayanan</a></li>
                        <li><a href="{{ route('public.dokumen.index') }}">Dokumen</a></li>
                        <li><a href="{{ route('public.pengaduan') }}">Pengaduan</a></li>
                        <li><a href="{{ route('public.wbs') }}">WBS</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Bantuan</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('public.faq') }}">FAQ</a></li>
                        <li><a href="{{ route('public.kontak') }}">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 border-secondary">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Inspektorat Provinsi Papua Tengah. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-light">Powered by Laravel & Bootstrap</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
