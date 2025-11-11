<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Portal Informasi Pemerintahan - Inspektorat Papua Tengah')</title>
    <meta name="description" content="@yield('description', 'Portal Informasi Pemerintahan Inspektorat Provinsi Papua Tengah - Layanan publik dan informasi pemerintahan.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Vite Assets -->
    @if(isset($isAdmin) && $isAdmin)
        @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js', 'resources/js/admin.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <!-- Global Styles -->
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="@yield('body-class', 'h-full bg-gray-50 font-sans antialiased')">
    <div id="app" class="min-h-full">
        @if(!isset($isAdmin) || !$isAdmin)
            @include('components.navigation')
        @endif

        <!-- Main Content -->
        <main class="@if(isset($isAdmin) && $isAdmin) flex-grow-1 @endif">
            @yield('content')
        </main>

        @if(!isset($isAdmin) || !$isAdmin)
            @include('components.footer')
        @endif
    </div>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>