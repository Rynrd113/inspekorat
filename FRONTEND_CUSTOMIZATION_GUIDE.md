# 🎨 Panduan Customization Frontend

**Portal Inspektorat Papua Tengah**  
**Panduan Lengkap Customization UI/UX**

---

## 📋 Daftar Isi

1. [Mengganti Theme & Warna](#mengganti-theme--warna)
2. [Customization Logo & Branding](#customization-logo--branding)
3. [Layout & Tata Letak](#layout--tata-letak)
4. [Typography & Font](#typography--font)
5. [Components & UI Elements](#components--ui-elements)
6. [Responsive Design](#responsive-design)
7. [Dark Mode Implementation](#dark-mode-implementation)
8. [Animation & Effects](#animation--effects)

---

## 🎨 Mengganti Theme & Warna

### 1. Setup Color Palette

Buat atau edit file `tailwind.config.js`:

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
        // Primary Colors - Sesuaikan dengan branding
        primary: {
          50: '#eff6ff',   // Sangat terang
          100: '#dbeafe',  // Terang
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',  // Base color
          600: '#2563eb',  // Agak gelap
          700: '#1d4ed8',  // Gelap
          800: '#1e40af',
          900: '#1e3a8a',  // Sangat gelap
          950: '#172554',
        },
        
        // Secondary Colors
        secondary: {
          50: '#f0fdf4',
          100: '#dcfce7',
          200: '#bbf7d0',
          300: '#86efac',
          400: '#4ade80',
          500: '#22c55e',  // Base green
          600: '#16a34a',
          700: '#15803d',
          800: '#166534',
          900: '#14532d',
        },
        
        // Accent Colors
        accent: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',  // Base red
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d',
        },
        
        // Custom Papua Tengah Colors
        papua: {
          blue: '#003f7f',    // Navy Blue Papua
          green: '#007a33',   // Forest Green
          gold: '#ffd700',    // Gold accent
          earth: '#8b4513',   // Earth brown
        },
        
        // Neutral Colors (opsional, override default gray)
        gray: {
          50: '#f9fafb',
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
          950: '#030712',
        }
      },
      
      // Gradient Colors
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
        'gradient-secondary': 'linear-gradient(135deg, #22c55e 0%, #15803d 100%)',
        'gradient-papua': 'linear-gradient(135deg, #003f7f 0%, #007a33 100%)',
        'hero-pattern': "url('/images/hero-pattern.svg')",
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
```

### 2. CSS Custom Properties

Tambahkan ke `resources/css/app.css`:

```css
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* CSS Custom Properties untuk theming */
:root {
  /* Primary Colors */
  --color-primary-50: 239 246 255;
  --color-primary-500: 59 130 246;
  --color-primary-600: 37 99 235;
  --color-primary-700: 29 78 216;
  --color-primary-900: 30 58 138;
  
  /* Secondary Colors */
  --color-secondary-500: 34 197 94;
  --color-secondary-600: 22 163 74;
  
  /* Custom Papua Colors */
  --color-papua-blue: 0 63 127;
  --color-papua-green: 0 122 51;
  --color-papua-gold: 255 215 0;
  
  /* Typography */
  --font-heading: 'Inter', system-ui, sans-serif;
  --font-body: 'Inter', system-ui, sans-serif;
  
  /* Spacing */
  --header-height: 4rem;
  --sidebar-width: 16rem;
  
  /* Border Radius */
  --radius-sm: 0.25rem;
  --radius-md: 0.375rem;
  --radius-lg: 0.5rem;
  --radius-xl: 0.75rem;
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

/* Dark mode variables (opsional) */
@media (prefers-color-scheme: dark) {
  :root {
    --color-primary-50: 30 58 138;
    --color-primary-500: 147 197 253;
    --color-primary-600: 96 165 250;
  }
}

/* Base Styles */
@layer base {
  html {
    scroll-behavior: smooth;
  }
  
  body {
    @apply font-sans text-gray-900 bg-gray-50;
    font-family: var(--font-body);
  }
  
  h1, h2, h3, h4, h5, h6 {
    @apply font-bold text-gray-900;
    font-family: var(--font-heading);
  }
  
  h1 { @apply text-4xl lg:text-5xl; }
  h2 { @apply text-3xl lg:text-4xl; }
  h3 { @apply text-2xl lg:text-3xl; }
  h4 { @apply text-xl lg:text-2xl; }
  h5 { @apply text-lg lg:text-xl; }
  h6 { @apply text-base lg:text-lg; }
}

/* Component Styles */
@layer components {
  /* Button Variants */
  .btn {
    @apply inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed;
  }
  
  .btn-primary {
    @apply bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500;
  }
  
  .btn-secondary {
    @apply bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-secondary-500;
  }
  
  .btn-outline {
    @apply border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-primary-500;
  }
  
  .btn-papua {
    @apply text-white focus:ring-papua-blue;
    background: linear-gradient(135deg, rgb(var(--color-papua-blue)) 0%, rgb(var(--color-papua-green)) 100%);
  }
  
  .btn-papua:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(0, 63, 127, 0.2);
  }
  
  /* Card Styles */
  .card {
    @apply bg-white rounded-lg shadow-md overflow-hidden;
  }
  
  .card-header {
    @apply px-6 py-4 border-b border-gray-200 bg-gray-50;
  }
  
  .card-body {
    @apply p-6;
  }
  
  .card-footer {
    @apply px-6 py-4 border-t border-gray-200 bg-gray-50;
  }
  
  /* Form Styles */
  .form-input {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent;
  }
  
  .form-label {
    @apply block text-sm font-medium text-gray-700 mb-2;
  }
  
  .form-error {
    @apply text-sm text-red-600 mt-1;
  }
  
  /* Navigation Styles */
  .nav-link {
    @apply px-3 py-2 rounded-md text-sm font-medium transition-colors;
  }
  
  .nav-link-active {
    @apply bg-primary-100 text-primary-700;
  }
  
  .nav-link-inactive {
    @apply text-gray-700 hover:text-primary-600 hover:bg-gray-100;
  }
  
  /* Hero Section */
  .hero-section {
    @apply relative py-16 lg:py-24;
    background: linear-gradient(135deg, rgb(var(--color-papua-blue)) 0%, rgb(var(--color-papua-green)) 100%);
  }
  
  .hero-content {
    @apply relative z-10 text-center text-white;
  }
  
  .hero-title {
    @apply text-4xl lg:text-6xl font-bold mb-6;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
  }
  
  .hero-subtitle {
    @apply text-xl lg:text-2xl opacity-90 mb-8;
  }
  
  /* Papua-specific styling */
  .papua-pattern {
    background-image: 
      radial-gradient(circle at 25px 25px, rgba(255,255,255,0.1) 2px, transparent 2px),
      radial-gradient(circle at 75px 75px, rgba(255,255,255,0.1) 2px, transparent 2px);
    background-size: 100px 100px;
  }
}

/* Utility Classes */
@layer utilities {
  .text-shadow {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
  }
  
  .text-shadow-lg {
    text-shadow: 4px 4px 8px rgba(0,0,0,0.2);
  }
  
  .backdrop-blur-xs {
    backdrop-filter: blur(2px);
  }
  
  .gradient-text {
    background: linear-gradient(135deg, rgb(var(--color-papua-blue)), rgb(var(--color-papua-green)));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
  
  .animate-slide-up {
    animation: slideUp 0.6s ease-out;
  }
  
  .animate-bounce-in {
    animation: bounceIn 0.8s ease-out;
  }
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

/* Responsive Typography */
@responsive {
  .text-responsive-sm {
    @apply text-sm lg:text-base;
  }
  
  .text-responsive-base {
    @apply text-base lg:text-lg;
  }
  
  .text-responsive-lg {
    @apply text-lg lg:text-xl;
  }
  
  .text-responsive-xl {
    @apply text-xl lg:text-2xl;
  }
}

/* Print Styles */
@media print {
  .no-print {
    display: none !important;
  }
  
  .print-friendly {
    background: white !important;
    color: black !important;
  }
}
```

### 3. Menggunakan Color Palette

Dalam template Blade:

```blade
<!-- Header dengan gradient Papua -->
<header class="bg-gradient-papua text-white">
    <div class="hero-section papua-pattern">
        <div class="hero-content">
            <h1 class="hero-title">Inspektorat Papua Tengah</h1>
            <p class="hero-subtitle">Portal Transparansi dan Akuntabilitas</p>
        </div>
    </div>
</header>

<!-- Button dengan style Papua -->
<button class="btn btn-papua">
    Lapor Sekarang
</button>

<!-- Card dengan custom styling -->
<div class="card">
    <div class="card-header">
        <h3 class="gradient-text">Berita Terbaru</h3>
    </div>
    <div class="card-body">
        <p class="text-gray-600">Konten berita...</p>
    </div>
</div>

<!-- Alert dengan custom colors -->
<div class="bg-papua-blue/10 border border-papua-blue/20 text-papua-blue rounded-lg p-4">
    <div class="flex items-center">
        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">Informasi Penting</span>
    </div>
</div>
```

---

## 🏷️ Customization Logo & Branding

### 1. Logo Variants

Siapkan beberapa variant logo:

```
public/
├── logo/
│   ├── logo-full.svg          # Logo lengkap dengan text
│   ├── logo-icon.svg          # Hanya icon/symbol
│   ├── logo-horizontal.svg    # Layout horizontal
│   ├── logo-vertical.svg      # Layout vertikal
│   ├── logo-white.svg         # Versi putih untuk dark background
│   ├── logo-dark.svg          # Versi gelap untuk light background
│   └── logo-monochrome.svg    # Versi monochrome
```

### 2. Logo Component

Buat component logo yang fleksibel:

```blade
{{-- resources/views/components/logo.blade.php --}}
@props([
    'variant' => 'full', // full, icon, horizontal, vertical
    'color' => 'default', // default, white, dark, monochrome
    'size' => 'md', // sm, md, lg, xl
    'class' => ''
])

@php
$sizes = [
    'sm' => 'h-8',
    'md' => 'h-12',
    'lg' => 'h-16',
    'xl' => 'h-20'
];

$logoFile = "logo/logo-{$variant}";
if ($color !== 'default') {
    $logoFile .= "-{$color}";
}
$logoFile .= '.svg';

$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-center {$class}"]) }}>
    <img src="{{ asset($logoFile) }}" 
         alt="Logo {{ config('app.name') }}" 
         class="{{ $sizeClass }} w-auto">
    
    @if($variant === 'full' || $variant === 'horizontal')
        <div class="ml-3 @if($color === 'white') text-white @else text-gray-900 @endif">
            <h1 class="text-lg font-bold leading-tight">Inspektorat</h1>
            <p class="text-sm opacity-75">Papua Tengah</p>
        </div>
    @endif
</div>
```

### 3. Dynamic Logo Usage

```blade
<!-- Header - Logo horizontal putih -->
<x-logo variant="horizontal" color="white" size="lg" class="py-4" />

<!-- Footer - Logo icon saja -->
<x-logo variant="icon" color="white" size="md" />

<!-- Mobile - Logo compact -->
<x-logo variant="icon" size="sm" class="md:hidden" />

<!-- Print - Logo monochrome -->
<x-logo variant="full" color="monochrome" size="lg" class="print-friendly" />
```

### 4. Favicon Generator

Script untuk generate favicon dari logo:

```bash
#!/bin/bash
# generate-favicons.sh

# Requirements: ImageMagick
# Install: sudo apt-get install imagemagick

LOGO_FILE="public/logo/logo-icon.svg"
OUTPUT_DIR="public"

# Generate different sizes
convert $LOGO_FILE -resize 16x16 $OUTPUT_DIR/favicon-16x16.png
convert $LOGO_FILE -resize 32x32 $OUTPUT_DIR/favicon-32x32.png
convert $LOGO_FILE -resize 48x48 $OUTPUT_DIR/favicon-48x48.png
convert $LOGO_FILE -resize 64x64 $OUTPUT_DIR/favicon-64x64.png
convert $LOGO_FILE -resize 128x128 $OUTPUT_DIR/favicon-128x128.png
convert $LOGO_FILE -resize 256x256 $OUTPUT_DIR/favicon-256x256.png

# Generate ICO file
convert $LOGO_FILE -resize 32x32 $OUTPUT_DIR/favicon.ico

# Generate Apple Touch Icons
convert $LOGO_FILE -resize 180x180 $OUTPUT_DIR/apple-touch-icon.png
convert $LOGO_FILE -resize 152x152 $OUTPUT_DIR/apple-touch-icon-152x152.png
convert $LOGO_FILE -resize 144x144 $OUTPUT_DIR/apple-touch-icon-144x144.png
convert $LOGO_FILE -resize 120x120 $OUTPUT_DIR/apple-touch-icon-120x120.png
convert $LOGO_FILE -resize 114x114 $OUTPUT_DIR/apple-touch-icon-114x114.png
convert $LOGO_FILE -resize 76x76 $OUTPUT_DIR/apple-touch-icon-76x76.png
convert $LOGO_FILE -resize 72x72 $OUTPUT_DIR/apple-touch-icon-72x72.png
convert $LOGO_FILE -resize 60x60 $OUTPUT_DIR/apple-touch-icon-60x60.png
convert $LOGO_FILE -resize 57x57 $OUTPUT_DIR/apple-touch-icon-57x57.png

# Generate Android Chrome Icons
convert $LOGO_FILE -resize 192x192 $OUTPUT_DIR/android-chrome-192x192.png
convert $LOGO_FILE -resize 512x512 $OUTPUT_DIR/android-chrome-512x512.png

echo "Favicons generated successfully!"
```

### 5. Favicon HTML Tags

Tambahkan ke layout head:

```blade
{{-- resources/views/layouts/app.blade.php --}}
<head>
    <!-- Standard Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon-64x64.png') }}">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('apple-touch-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-touch-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('apple-touch-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-touch-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('apple-touch-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-touch-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('apple-touch-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon-180x180.png') }}">
    
    <!-- Android Chrome Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <!-- Browser Config -->
    <meta name="msapplication-TileColor" content="#003f7f">
    <meta name="msapplication-TileImage" content="{{ asset('android-chrome-144x144.png') }}">
    <meta name="theme-color" content="#003f7f">
</head>
```

### 6. Web App Manifest

Buat file `public/site.webmanifest`:

```json
{
    "name": "Portal Inspektorat Papua Tengah",
    "short_name": "Inspektorat Papua",
    "description": "Portal resmi Inspektorat Provinsi Papua Tengah",
    "start_url": "/",
    "display": "standalone",
    "theme_color": "#003f7f",
    "background_color": "#ffffff",
    "icons": [
        {
            "src": "/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
```

---

## 📐 Layout & Tata Letak

### 1. Grid System

```blade
{{-- 12 Column Grid System --}}
<div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Grid items -->
    </div>
</div>

{{-- Custom Grid Layouts --}}
<!-- Hero + Sidebar Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <!-- Main content -->
    </div>
    <div class="lg:col-span-1">
        <!-- Sidebar -->
    </div>
</div>

<!-- Article Layout -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <aside class="lg:col-span-1">
        <!-- Left sidebar -->
    </aside>
    <main class="lg:col-span-2">
        <!-- Article content -->
    </main>
    <aside class="lg:col-span-1">
        <!-- Right sidebar -->
    </aside>
</div>
```

### 2. Container Variants

```css
/* resources/css/app.css */
@layer components {
    .container-full {
        @apply w-full;
    }
    
    .container-wide {
        @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
    }
    
    .container-normal {
        @apply max-w-5xl mx-auto px-4 sm:px-6 lg:px-8;
    }
    
    .container-narrow {
        @apply max-w-3xl mx-auto px-4 sm:px-6 lg:px-8;
    }
    
    .container-tight {
        @apply max-w-xl mx-auto px-4 sm:px-6 lg:px-8;
    }
}
```

### 3. Section Layouts

```blade
{{-- Hero Section --}}
<section class="hero-section papua-pattern">
    <div class="container-wide">
        <div class="hero-content">
            <h1 class="hero-title animate-fade-in">Portal Inspektorat</h1>
            <p class="hero-subtitle animate-slide-up">Papua Tengah</p>
            <div class="mt-8 animate-bounce-in">
                <a href="#" class="btn btn-papua mr-4">Mulai Lapor</a>
                <a href="#" class="btn btn-outline">Pelajari Lebih</a>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="py-16 bg-white">
    <div class="container-wide">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-papua-blue mb-2">150+</div>
                <div class="text-gray-600">Laporan Ditangani</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-papua-blue mb-2">95%</div>
                <div class="text-gray-600">Tingkat Penyelesaian</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-papua-blue mb-2">24/7</div>
                <div class="text-gray-600">Layanan Tersedia</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-papua-blue mb-2">100%</div>
                <div class="text-gray-600">Kerahasiaan Terjamin</div>
            </div>
        </div>
    </div>
</section>

{{-- News Section --}}
<section class="py-16 bg-gray-50">
    <div class="container-wide">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Berita Terbaru</h2>
            <p class="text-xl text-gray-600">Informasi terkini dari Inspektorat Papua Tengah</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
            <article class="card group hover:shadow-lg transition-shadow">
                <div class="card-body">
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        <img src="{{ $article->featured_image_url }}" 
                             alt="{{ $article->title }}"
                             class="object-cover rounded-lg group-hover:scale-105 transition-transform">
                    </div>
                    <h3 class="text-xl font-semibold mb-2 group-hover:text-primary-600 transition-colors">
                        {{ $article->title }}
                    </h3>
                    <p class="text-gray-600 mb-4">{{ $article->excerpt }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>{{ $article->published_at->format('d M Y') }}</span>
                        <span>{{ $article->views }} views</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('public.berita.index') }}" class="btn btn-outline">
                Lihat Semua Berita
            </a>
        </div>
    </div>
</section>
```

### 4. Responsive Breakpoints

```css
/* Custom breakpoints jika diperlukan */
@media (min-width: 475px) {
    /* xs */
}

@media (min-width: 640px) {
    /* sm */
}

@media (min-width: 768px) {
    /* md */
}

@media (min-width: 1024px) {
    /* lg */
}

@media (min-width: 1280px) {
    /* xl */
}

@media (min-width: 1536px) {
    /* 2xl */
}
```

---

## 🔤 Typography & Font

### 1. Font Setup

Import Google Fonts di layout:

```blade
{{-- resources/views/layouts/app.blade.php --}}
<head>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
</head>
```

### 2. Typography Configuration

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'serif': ['Merriweather', 'Georgia', 'serif'],
        'heading': ['Inter', 'system-ui', 'sans-serif'],
        'body': ['Inter', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
        '7xl': ['4.5rem', { lineHeight: '1' }],
        '8xl': ['6rem', { lineHeight: '1' }],
        '9xl': ['8rem', { lineHeight: '1' }],
      },
      lineHeight: {
        'extra-tight': '1.1',
        'tight': '1.25',
        'snug': '1.375',
        'normal': '1.5',
        'relaxed': '1.625',
        'loose': '2',
        'extra-loose': '2.5',
      },
      letterSpacing: {
        'tighter': '-0.05em',
        'tight': '-0.025em',
        'normal': '0em',
        'wide': '0.025em',
        'wider': '0.05em',
        'widest': '0.1em',
      }
    }
  }
}
```

### 3. Typography Styles

```css
/* resources/css/app.css */
@layer base {
  /* Typography Scale */
  .text-display-2xl {
    @apply text-7xl font-bold tracking-tight;
  }
  
  .text-display-xl {
    @apply text-6xl font-bold tracking-tight;
  }
  
  .text-display-lg {
    @apply text-5xl font-bold tracking-tight;
  }
  
  .text-display-md {
    @apply text-4xl font-bold tracking-tight;
  }
  
  .text-display-sm {
    @apply text-3xl font-bold tracking-tight;
  }
  
  .text-display-xs {
    @apply text-2xl font-bold tracking-tight;
  }
  
  /* Heading Styles */
  .heading-1 {
    @apply text-4xl lg:text-5xl xl:text-6xl font-bold tracking-tight text-gray-900;
    font-family: var(--font-heading);
  }
  
  .heading-2 {
    @apply text-3xl lg:text-4xl xl:text-5xl font-bold tracking-tight text-gray-900;
    font-family: var(--font-heading);
  }
  
  .heading-3 {
    @apply text-2xl lg:text-3xl xl:text-4xl font-bold tracking-tight text-gray-900;
    font-family: var(--font-heading);
  }
  
  .heading-4 {
    @apply text-xl lg:text-2xl xl:text-3xl font-semibold tracking-tight text-gray-900;
    font-family: var(--font-heading);
  }
  
  /* Body Text */
  .body-large {
    @apply text-lg lg:text-xl leading-relaxed text-gray-700;
  }
  
  .body-normal {
    @apply text-base lg:text-lg leading-relaxed text-gray-700;
  }
  
  .body-small {
    @apply text-sm lg:text-base leading-relaxed text-gray-600;
  }
  
  /* Special Text Styles */
  .text-hero {
    @apply text-4xl lg:text-6xl xl:text-7xl font-bold tracking-tight;
    font-family: var(--font-heading);
  }
  
  .text-subtitle {
    @apply text-lg lg:text-xl xl:text-2xl font-medium tracking-wide opacity-90;
  }
  
  .text-caption {
    @apply text-xs lg:text-sm font-medium text-gray-500 uppercase tracking-wide;
  }
  
  .text-overline {
    @apply text-xs font-semibold text-primary-600 uppercase tracking-wider;
  }
}

@layer components {
  /* Rich Text Content */
  .prose-custom {
    @apply prose prose-lg max-w-none;
  }
  
  .prose-custom h1 {
    @apply heading-1 mb-6;
  }
  
  .prose-custom h2 {
    @apply heading-2 mb-4 mt-8;
  }
  
  .prose-custom h3 {
    @apply heading-3 mb-3 mt-6;
  }
  
  .prose-custom h4 {
    @apply heading-4 mb-2 mt-4;
  }
  
  .prose-custom p {
    @apply body-normal mb-4;
  }
  
  .prose-custom a {
    @apply text-primary-600 hover:text-primary-700 font-medium underline;
  }
  
  .prose-custom ul, .prose-custom ol {
    @apply my-4 ml-6;
  }
  
  .prose-custom li {
    @apply mb-2;
  }
  
  .prose-custom blockquote {
    @apply border-l-4 border-primary-200 pl-4 py-2 my-4 italic text-gray-700 bg-gray-50;
  }
  
  .prose-custom code {
    @apply bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-sm;
  }
  
  .prose-custom pre {
    @apply bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto;
  }
}
```

### 4. Typography Components

```blade
{{-- Text Styles Component --}}
{{-- resources/views/components/text.blade.php --}}
@props(['variant' => 'body', 'size' => 'normal', 'weight' => 'normal', 'color' => 'default'])

@php
$variants = [
    'display' => 'text-display-md',
    'heading' => 'heading-2',
    'subheading' => 'heading-4',
    'body' => 'body-normal',
    'caption' => 'text-caption',
    'overline' => 'text-overline',
];

$sizes = [
    'xs' => 'text-xs',
    'sm' => 'text-sm',
    'normal' => 'text-base',
    'lg' => 'text-lg',
    'xl' => 'text-xl',
    '2xl' => 'text-2xl',
    '3xl' => 'text-3xl',
];

$weights = [
    'light' => 'font-light',
    'normal' => 'font-normal',
    'medium' => 'font-medium',
    'semibold' => 'font-semibold',
    'bold' => 'font-bold',
];

$colors = [
    'default' => 'text-gray-900',
    'muted' => 'text-gray-600',
    'light' => 'text-gray-500',
    'primary' => 'text-primary-600',
    'secondary' => 'text-secondary-600',
    'white' => 'text-white',
    'papua' => 'text-papua-blue',
];

$classes = collect([
    $variants[$variant] ?? $variants['body'],
    $sizes[$size] ?? null,
    $weights[$weight] ?? null,
    $colors[$color] ?? $colors['default'],
])->filter()->implode(' ');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
```

### 5. Typography Usage Examples

```blade
<!-- Page Title -->
<x-text variant="heading" size="3xl" weight="bold" color="papua">
    Portal Inspektorat Papua Tengah
</x-text>

<!-- Subtitle -->
<x-text variant="body" size="xl" color="muted">
    Transparansi dan Akuntabilitas untuk Papua yang Lebih Baik
</x-text>

<!-- Article Content -->
<div class="prose-custom">
    {!! $article->content !!}
</div>

<!-- Caption -->
<x-text variant="caption" color="light">
    Dipublikasikan: {{ $article->published_at->format('d F Y') }}
</x-text>

<!-- Hero Text -->
<h1 class="text-hero text-white text-shadow-lg">
    Whistleblower System
</h1>
<p class="text-subtitle text-white">
    Laporkan dugaan pelanggaran dengan aman dan terpercaya
</p>
```

---

## 🧩 Components & UI Elements

### 1. Advanced Button Components

```blade
{{-- resources/views/components/button-group.blade.php --}}
@props(['variant' => 'primary', 'size' => 'md'])

<div {{ $attributes->merge(['class' => 'inline-flex rounded-lg shadow-sm']) }} role="group">
    {{ $slot }}
</div>
```

```blade
{{-- Usage --}}
<x-button-group>
    <x-button variant="primary" class="rounded-r-none">Edit</x-button>
    <x-button variant="outline" class="rounded-none border-l-0">Duplicate</x-button>
    <x-button variant="outline" class="rounded-l-none border-l-0 text-red-600">Delete</x-button>
</x-button-group>
```

### 2. Advanced Card Components

```blade
{{-- resources/views/components/article-card.blade.php --}}
@props(['article', 'featured' => false])

<article {{ $attributes->merge(['class' => 'group cursor-pointer']) }}>
    <div class="card hover:shadow-xl transition-all duration-300 {{ $featured ? 'lg:flex' : '' }}">
        <div class="{{ $featured ? 'lg:w-1/2' : '' }}">
            <div class="aspect-w-16 aspect-h-9 {{ $featured ? 'h-full' : 'mb-4' }}">
                <img src="{{ $article->featured_image_url }}" 
                     alt="{{ $article->title }}"
                     class="object-cover {{ $featured ? 'rounded-l-lg' : 'rounded-lg' }} group-hover:scale-105 transition-transform">
            </div>
        </div>
        
        <div class="{{ $featured ? 'lg:w-1/2 lg:p-8' : 'p-6' }}">
            <!-- Category Badge -->
            <div class="mb-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    {{ ucfirst($article->category) }}
                </span>
            </div>
            
            <!-- Title -->
            <h3 class="{{ $featured ? 'text-2xl lg:text-3xl' : 'text-xl' }} font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">
                {{ $article->title }}
            </h3>
            
            <!-- Excerpt -->
            <p class="text-gray-600 mb-4 {{ $featured ? 'text-lg' : '' }}">
                {{ $article->excerpt }}
            </p>
            
            <!-- Meta -->
            <div class="flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $article->published_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{ number_format($article->views) }}</span>
                </div>
            </div>
            
            <!-- Read More -->
            <div class="mt-4">
                <a href="{{ route('public.berita.show', $article->id) }}" 
                   class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                    Baca Selengkapnya
                    <svg class="ml-1 h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</article>
```

### 3. Modal Component

```blade
{{-- resources/views/components/modal.blade.php --}}
@props(['id', 'maxWidth' => 'md', 'closeable' => true])

@php
$maxWidthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    '6xl' => 'max-w-6xl',
    '7xl' => 'max-w-7xl',
];

$maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['md'];
@endphp

<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="$event.detail == '{{ $id }}' ? show = true : null"
     x-on:close-modal.window="$event.detail == '{{ $id }}' ? show = false : null"
     x-on:keydown.escape.window="show = false"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
     
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-on:click="{{ $closeable ? 'show = false' : '' }}"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $maxWidthClass }} sm:w-full"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            {{ $slot }}
            
        </div>
    </div>
</div>
```

### 4. Dropdown Component

```blade
{{-- resources/views/components/dropdown.blade.php --}}
@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = [
    'left' => 'origin-top-left left-0',
    'right' => 'origin-top-right right-0',
];

$width = [
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    '72' => 'w-72',
][$width] ?? 'w-48';
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses[$align] }}"
         style="display: none;"
         @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
```

### 5. Breadcrumb Component

```blade
{{-- resources/views/components/breadcrumb.blade.php --}}
@props(['items' => []])

<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $index => $item)
            <li class="inline-flex items-center">
                @if($index > 0)
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" 
                       class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                        @if($index === 0 && isset($item['icon']))
                            {!! $item['icon'] !!}
                        @endif
                        {{ $item['title'] }}
                    </a>
                @else
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                        {{ $item['title'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
```

### 6. Usage Examples

```blade
<!-- Article Card -->
<x-article-card :article="$featuredArticle" featured />

<!-- Modal -->
<x-modal id="contact-modal" max-width="lg">
    <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Hubungi Kami</h3>
        <!-- Form content -->
    </div>
</x-modal>

<!-- Dropdown -->
<x-dropdown align="right" width="56">
    <x-slot name="trigger">
        <button class="btn btn-outline">
            Menu
            <svg class="ml-2 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
    </x-slot>
</x-dropdown>

<!-- Breadcrumb -->
<x-breadcrumb :items="[
    ['title' => 'Beranda', 'url' => route('public.index'), 'icon' => '<svg class=\'w-4 h-4 mr-2\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z\'></path></svg>'],
    ['title' => 'Berita', 'url' => route('public.berita.index')],
    ['title' => $article->title]
]" />
```

Dokumentasi ini memberikan panduan lengkap untuk kustomisasi frontend Portal Inspektorat Papua Tengah. Dengan mengikuti panduan ini, developer dapat dengan mudah mengubah tema, layout, dan komponen UI sesuai kebutuhan.
