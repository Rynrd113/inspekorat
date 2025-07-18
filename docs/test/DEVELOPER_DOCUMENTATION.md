# 📋 Dokumentasi Developer - Portal Inspektorat Papua Tengah

**Versi**: 1.0.0  
**Tanggal**: Juli 2025  
**Framework**: Laravel 12.x  
**Frontend**: Tailwind CSS 4.x  

---

## 📚 Daftar Isi

1. [Arsitektur Aplikasi](#arsitektur-aplikasi)
2. [Setup Development Environment](#setup-development-environment)
3. [Struktur Folder & File](#struktur-folder--file)
4. [Frontend Customization](#frontend-customization)
5. [Branding & Logo](#branding--logo)
6. [Layout & UI Components](#layout--ui-components)
7. [Database & Models](#database--models)
8. [Authentication & Authorization](#authentication--authorization)
9. [API Routes](#api-routes)
10. [Deployment](#deployment)
11. [Troubleshooting](#troubleshooting)

---

## 🏗️ Arsitektur Aplikasi

### Stack Teknologi
- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Tailwind CSS 4.x dengan Vite
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Sanctum
- **Build Tool**: Vite 6.x
- **Package Manager**: Composer + NPM

### Struktur MVC
```
app/
├── Http/
│   ├── Controllers/       # Controllers untuk business logic
│   ├── Requests/         # Form Request validation
│   └── Resources/        # API Resources
├── Models/               # Eloquent Models
└── Providers/           # Service Providers
```

---

## 🚀 Setup Development Environment

### Prerequisites
```bash
# Versi minimum yang diperlukan
PHP >= 8.2
Composer >= 2.0
Node.js >= 18.0
NPM >= 8.0
MySQL >= 8.0 (atau PostgreSQL/SQLite)
```

### Instalasi Development
```bash
# 1. Clone repository
git clone https://github.com/Rynrd113/inspekorat.git
cd inspekorat

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Database setup
php artisan migrate --seed

# 6. Build assets
npm run dev

# 7. Start development server
php artisan serve
```

### Development Commands
```bash
# Frontend development (hot reload)
npm run dev

# Build untuk production
npm run build

# Clean install dependencies
npm run clean

# Laravel commands
php artisan serve          # Start server
php artisan migrate       # Run migrations
php artisan db:seed       # Run seeders
php artisan cache:clear   # Clear cache
```

---

## 📁 Struktur Folder & File

### Frontend Assets
```
resources/
├── css/
│   └── app.css           # Main stylesheet (Tailwind)
├── js/
│   └── app.js           # Main JavaScript entry
└── views/               # Blade templates
    ├── layouts/         # Layout templates
    ├── components/      # Reusable components
    ├── public/         # Public pages
    └── admin/          # Admin panel pages
```

### Public Assets
```
public/
├── build/              # Compiled assets (auto-generated)
├── js/                 # Static JavaScript files
├── logo.svg           # Main logo
├── favicon.ico        # Favicon
└── index.php          # Entry point
```

### Key Configuration Files
```
vite.config.js         # Vite build configuration
package.json          # NPM dependencies
tailwind.config.js    # Tailwind CSS configuration (if exists)
.env                  # Environment variables
```

---

## 🎨 Frontend Customization

### 1. Tailwind CSS Configuration

Jika belum ada, buat file `tailwind.config.js`:
```bash
npx tailwindcss init
```

Contoh konfigurasi custom:
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          900: '#1e3a8a',
        },
        secondary: {
          500: '#10b981',
          600: '#059669',
        }
      },
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'serif': ['Merriweather', 'serif'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### 2. CSS Customization

Edit `resources/css/app.css`:
```css
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* Custom CSS */
@layer base {
  body {
    @apply font-sans antialiased;
  }
  
  h1, h2, h3, h4, h5, h6 {
    @apply font-bold text-gray-900;
  }
}

@layer components {
  .btn-primary {
    @apply bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors;
  }
  
  .btn-secondary {
    @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors;
  }
  
  .card {
    @apply bg-white rounded-lg shadow-md p-6;
  }
  
  .input-field {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent;
  }
}

@layer utilities {
  .text-shadow {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
  }
}
```

### 3. JavaScript Customization

Edit `resources/js/app.js`:
```javascript
import './bootstrap';

// Alpine.js untuk reactive components
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Custom JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Smooth scrolling untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-auto-hide');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
});

// Utility functions
window.formatDate = function(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        timeZone: 'Asia/Jakarta'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

window.truncateText = function(text, length = 150) {
    if (text.length <= length) return text;
    return text.substr(0, length) + '...';
};
```

---

## 🏷️ Branding & Logo

### 1. Mengganti Logo

**Lokasi file logo**: `public/logo.svg`

**Format yang disupport**:
- SVG (recommended untuk scalability)
- PNG (untuk raster images)
- WebP (untuk optimasi ukuran)

**Ukuran yang disarankan**:
- Logo header: 200x60px
- Logo footer: 150x45px
- Favicon: 32x32px, 16x16px

**Cara mengganti logo**:
```bash
# 1. Replace file logo utama
cp new-logo.svg public/logo.svg

# 2. Generate favicon dari logo
# Gunakan online tools atau ImageMagick
convert logo.svg -resize 32x32 public/favicon.ico

# 3. Update reference di template jika perlu
# File: resources/views/layouts/app.blade.php
```

### 2. Update Logo di Template

Edit file layout utama `resources/views/layouts/app.blade.php`:
```blade
<!-- Header Logo -->
<div class="flex items-center">
    <img src="{{ asset('logo.svg') }}" 
         alt="{{ config('app.name') }}" 
         class="h-12 w-auto">
    <span class="ml-3 text-xl font-bold text-gray-900">
        {{ config('app.name') }}
    </span>
</div>

<!-- Footer Logo -->
<div class="flex items-center justify-center mb-4">
    <img src="{{ asset('logo.svg') }}" 
         alt="{{ config('app.name') }}" 
         class="h-10 w-auto opacity-80">
</div>
```

### 3. Brand Colors Configuration

Update brand colors di `tailwind.config.js`:
```javascript
theme: {
  extend: {
    colors: {
      // Brand colors Inspektorat Papua Tengah
      brand: {
        primary: '#1e40af',    // Navy blue
        secondary: '#059669',   // Green
        accent: '#dc2626',     // Red
        gray: '#6b7280',       // Neutral gray
      },
      // Custom color palette
      papua: {
        50: '#f0f9ff',
        100: '#e0f2fe',
        500: '#0ea5e9',
        600: '#0284c7',
        700: '#0369a1',
        800: '#075985',
        900: '#0c4a6e',
      }
    }
  }
}
```

---

## 🎛️ Layout & UI Components

### 1. Main Layout Structure

**File**: `resources/views/layouts/app.blade.php`
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional CSS -->
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Header -->
    @include('components.header')
    
    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
```

### 2. Header Component

**File**: `resources/views/components/header.blade.php`
```blade
<header class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <a href="{{ route('public.index') }}" class="flex items-center">
                    <img src="{{ asset('logo.svg') }}" 
                         alt="Logo Inspektorat Papua Tengah" 
                         class="h-10 w-auto">
                    <div class="ml-3 hidden md:block">
                        <h1 class="text-lg font-bold text-gray-900">Inspektorat</h1>
                        <p class="text-sm text-gray-600">Papua Tengah</p>
                    </div>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('public.index') }}" 
                   class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors
                          {{ request()->routeIs('public.index') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('public.berita.index') }}" 
                   class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors
                          {{ request()->routeIs('public.berita.*') ? 'text-primary-600 bg-primary-50' : '' }}">
                    Berita
                </a>
                <a href="{{ route('public.wbs') }}" 
                   class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors
                          {{ request()->routeIs('public.wbs') ? 'text-primary-600 bg-primary-50' : '' }}">
                    WBS
                </a>
                <a href="#" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                    Profil
                </a>
                <a href="#" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                    Kontak
                </a>
            </nav>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" 
                        class="text-gray-700 hover:text-primary-600 focus:outline-none focus:text-primary-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50 border-t">
                <a href="{{ route('public.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-100">
                    Beranda
                </a>
                <a href="{{ route('public.berita.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-100">
                    Berita
                </a>
                <a href="{{ route('public.wbs') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-100">
                    WBS
                </a>
                <a href="#" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-100">
                    Profil
                </a>
                <a href="#" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-100">
                    Kontak
                </a>
            </div>
        </div>
    </div>
</header>
```

### 3. Footer Component

**File**: `resources/views/components/footer.blade.php`
```blade
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Section -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('logo.svg') }}" 
                         alt="Logo Inspektorat Papua Tengah" 
                         class="h-12 w-auto filter brightness-0 invert">
                    <div class="ml-3">
                        <h3 class="text-xl font-bold">Inspektorat Provinsi</h3>
                        <p class="text-gray-300">Papua Tengah</p>
                    </div>
                </div>
                <p class="text-gray-300 mb-4 max-w-md">
                    Portal resmi Inspektorat Provinsi Papua Tengah. 
                    Menyediakan informasi publik dan layanan Whistleblower System (WBS) 
                    untuk mendukung transparansi dan akuntabilitas pemerintahan.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323C5.902 8.246 7.053 7.756 8.35 7.756s2.448.49 3.323 1.297c.897.897 1.297 2.048 1.297 3.345s-.49 2.448-1.297 3.323c-.875.807-2.026 1.267-3.323 1.267z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <span class="sr-only">YouTube</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('public.index') }}" class="text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('public.berita.index') }}" class="text-gray-300 hover:text-white transition-colors">Berita</a></li>
                    <li><a href="{{ route('public.wbs') }}" class="text-gray-300 hover:text-white transition-colors">WBS</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Profil</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Kontak</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                <div class="space-y-2 text-gray-300">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm">
                            Jl. Trikora No. 1, Nabire<br>
                            Papua Tengah 98816
                        </span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-sm">(0984) 21234</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm">inspektorat@papuatengah.go.id</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="mt-8 pt-8 border-t border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} Inspektorat Provinsi Papua Tengah. Semua hak dilindungi.
                </p>
                <div class="mt-2 md:mt-0">
                    <span class="text-gray-400 text-sm">
                        Dikembangkan dengan ❤️ untuk transparansi publik
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
```

### 4. Reusable UI Components

Buat file component yang dapat digunakan berulang:

**File**: `resources/views/components/alert.blade.php`
```blade
@props(['type' => 'info', 'dismissible' => false])

@php
$classes = [
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
][$type];

$iconClasses = [
    'info' => 'text-blue-400',
    'success' => 'text-green-400',
    'warning' => 'text-yellow-400',
    'error' => 'text-red-400',
][$type];
@endphp

<div {{ $attributes->merge(['class' => "border rounded-md p-4 {$classes} " . ($dismissible ? 'alert-auto-hide' : '')]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            @if($type === 'success')
                <svg class="h-5 w-5 {{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            @elseif($type === 'error')
                <svg class="h-5 w-5 {{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            @elseif($type === 'warning')
                <svg class="h-5 w-5 {{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            @else
                <svg class="h-5 w-5 {{ $iconClasses }}" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            @endif
        </div>
        <div class="ml-3">
            <div class="text-sm font-medium">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" 
                            class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $iconClasses }} hover:bg-opacity-20">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
```

**File**: `resources/views/components/card.blade.php`
```blade
@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md overflow-hidden']) }}>
    @if($title || $subtitle)
        <div class="px-6 py-4 border-b border-gray-200">
            @if($title)
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
```

**File**: `resources/views/components/button.blade.php`
```blade
@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button'])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary' => 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-500',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-500',
    'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    'outline' => 'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 focus:ring-primary-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
```

### 5. Menggunakan Components

Dalam file Blade template:
```blade
<!-- Alert -->
<x-alert type="success" dismissible>
    Data berhasil disimpan!
</x-alert>

<!-- Card -->
<x-card title="Berita Terbaru" subtitle="Informasi terkini dari Inspektorat">
    <p>Konten berita...</p>
</x-card>

<!-- Button -->
<x-button variant="primary" size="lg" onclick="submitForm()">
    Simpan Data
</x-button>
```

---

## 💾 Database & Models

### 1. Model Relationships

**Contoh Model dengan relationships:**

```php
// app/Models/PortalPapuaTengah.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalPapuaTengah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'status',
        'published_at',
        'views',
        'author_id',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer'
    ];

    protected $dates = ['deleted_at'];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }
        
        return asset('images/default-news.jpg');
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
```

### 2. Migration Examples

**Contoh migration dengan indexes dan foreign keys:**

```php
// database/migrations/create_portal_papua_tengahs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('portal_papua_tengahs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('category', 50)->default('news');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['status', 'published_at']);
            $table->index('category');
            $table->index('author_id');
            $table->fullText(['title', 'content']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('portal_papua_tengahs');
    }
};
```

### 3. Seeders

**Contoh seeder untuk data awal:**

```php
// database/seeders/PortalPapuaTengahSeeder.php
<?php

namespace Database\Seeders;

use App\Models\PortalPapuaTengah;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortalPapuaTengahSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@papuatengah.go.id')->first();
        
        $articles = [
            [
                'title' => 'Inspektorat Papua Tengah Luncurkan Portal Informasi Publik',
                'content' => '<p>Inspektorat Provinsi Papua Tengah dengan bangga meluncurkan portal informasi publik yang menyediakan akses mudah terhadap berbagai informasi dan layanan.</p><p>Portal ini dilengkapi dengan fitur Whistleblower System (WBS) yang memungkinkan masyarakat untuk melaporkan dugaan pelanggaran secara aman dan terpercaya.</p>',
                'category' => 'pengumuman',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Sosialisasi Sistem Pelaporan WBS Kepada Masyarakat',
                'content' => '<p>Kegiatan sosialisasi Whistleblower System (WBS) dilaksanakan untuk meningkatkan pemahaman masyarakat tentang pentingnya pelaporan dugaan pelanggaran.</p><p>Melalui sistem ini, identitas pelapor akan dijaga kerahasiaannya dan setiap laporan akan ditindaklanjuti sesuai prosedur yang berlaku.</p>',
                'category' => 'kegiatan',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Laporan Kinerja Inspektorat Triwulan I 2025',
                'content' => '<p>Inspektorat Provinsi Papua Tengah menyampaikan laporan kinerja triwulan pertama tahun 2025 yang menunjukkan peningkatan signifikan dalam berbagai aspek.</p><p>Berbagai program pengawasan dan pembinaan telah dilaksanakan dengan hasil yang memuaskan.</p>',
                'category' => 'laporan',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'author_id' => $admin->id,
            ],
        ];
        
        foreach ($articles as $article) {
            PortalPapuaTengah::create($article);
        }
    }
}
```

---

## 🔐 Authentication & Authorization

### 1. Authentication Setup

**Config authentication di `config/auth.php`:**
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

**Middleware untuk admin:**
```php
// app/Http/Middleware/AdminAuth.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return redirect()->route('admin.login')
                           ->with('error', 'Anda harus login sebagai admin.');
        }

        return $next($request);
    }
}
```

### 2. Authorization Policies

**Contoh Policy untuk Portal:**
```php
// app/Policies/PortalPapuaTengahPolicy.php
<?php

namespace App\Policies;

use App\Models\PortalPapuaTengah;
use App\Models\User;

class PortalPapuaTengahPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function view(User $user, PortalPapuaTengah $portal)
    {
        return $user->is_admin;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, PortalPapuaTengah $portal)
    {
        return $user->is_admin && 
               ($user->id === $portal->author_id || $user->role === 'super_admin');
    }

    public function delete(User $user, PortalPapuaTengah $portal)
    {
        return $user->is_admin && 
               ($user->id === $portal->author_id || $user->role === 'super_admin');
    }
}
```

---

## 🚀 Deployment

### 1. Production Optimization

**Script untuk optimasi production:**
```bash
#!/bin/bash
# deploy.sh

echo "🚀 Starting deployment..."

# Install dependencies
composer install --optimize-autoloader --no-dev --no-interaction

# Install and build frontend assets
npm ci --prefer-offline --no-audit
npm run build

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Database migrations
php artisan migrate --force

# Clear and optimize
php artisan optimize:clear
php artisan optimize

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deployment completed!"
```

### 2. Environment Configuration

**File `.env.production`:**
```env
APP_NAME="Portal Inspektorat Papua Tengah"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://inspektorat.papuatengah.go.id

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@papuatengah.go.id"
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=public

# Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### 3. Web Server Configuration

**Nginx Configuration:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name inspektorat.papuatengah.go.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name inspektorat.papuatengah.go.id;
    
    root /var/www/inspektorat/public;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: ws: wss: data: blob: 'unsafe-inline'; frame-ancestors 'self';" always;
    add_header Permissions-Policy "interest-cohort=()" always;
    
    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;
    
    # File Handling
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Asset Optimization
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    # Security
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    location ~ /storage/app/private {
        deny all;
    }
}
```

---

## 🔧 Troubleshooting

### 1. Common Issues

**Permission Issues:**
```bash
# Fix Laravel permissions
sudo chown -R www-data:www-data /var/www/inspektorat
sudo chmod -R 755 /var/www/inspektorat
sudo chmod -R 775 /var/www/inspektorat/storage
sudo chmod -R 775 /var/www/inspektorat/bootstrap/cache
```

**Clear All Cache:**
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload
```

**Database Issues:**
```bash
# Reset database (development only)
php artisan migrate:fresh --seed

# Check database connection
php artisan tinker
DB::connection()->getPdo();
```

**Asset Issues:**
```bash
# Rebuild assets
npm run clean
npm install
npm run build

# Check Vite configuration
npx vite --version
```

### 2. Performance Monitoring

**Install Laravel Telescope (development):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**Install Laravel Debugbar (development):**
```bash
composer require barryvdh/laravel-debugbar --dev
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

### 3. Backup Strategy

**Database Backup Script:**
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/inspektorat"
DB_NAME="portal_inspektorat"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/inspektorat/storage/app/public

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $DATE"
```

---

## 📞 Support & Maintenance

### 1. Development Team Contacts
- **Lead Developer**: developer@papuatengah.go.id
- **System Administrator**: sysadmin@papuatengah.go.id
- **Project Manager**: pm@papuatengah.go.id

### 2. Maintenance Schedule
- **Daily**: Automated backups, log monitoring
- **Weekly**: Security updates, performance review
- **Monthly**: Full system backup, dependency updates
- **Quarterly**: Security audit, performance optimization

### 3. Version Control
```bash
# Git workflow
git checkout develop
git pull origin develop
git checkout -b feature/new-feature
# Make changes
git add .
git commit -m "feat: add new feature"
git push origin feature/new-feature
# Create pull request
```

---

**© 2025 Inspektorat Provinsi Papua Tengah**  
*Dokumentasi ini dibuat untuk membantu developer dalam mengembangkan dan memelihara Portal Inspektorat Papua Tengah*
