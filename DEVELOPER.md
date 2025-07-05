# Dokumentasi Developer Portal Inspektorat Papua Tengah

## Daftar Isi
1. [Arsitektur Aplikasi](#arsitektur-aplikasi)
2. [Struktur Project](#struktur-project)
3. [Customization Guide](#customization-guide)
4. [Development Workflow](#development-workflow)
5. [Database Schema](#database-schema)
6. [API Documentation](#api-documentation)
7. [Frontend Components](#frontend-components)
8. [Deployment Guide](#deployment-guide)

## Arsitektur Aplikasi

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Blade Templates + Tailwind CSS + Vanilla JavaScript
- **Database**: MySQL (Default), PostgreSQL (Alternative)
- **Authentication**: Laravel Sanctum
- **Build Tool**: Vite
- **Icons**: Font Awesome 6.5.1

### Design Pattern
- **MVC Pattern**: Model-View-Controller architecture
- **Repository Pattern**: Data abstraction layer
- **Service Layer**: Business logic separation
- **Component-Based**: Reusable UI components

### Directory Structure
```
portal-inspektorat/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   └── PublicController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── PortalPapuaTengah.php
│   │   └── Wbs.php
│   └── Providers/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── public/             # Public pages
│   │   └── admin/              # Admin panel pages
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
└── database/
    ├── migrations/
    └── seeders/
```

## Struktur Project

### Models

#### 1. PortalPapuaTengah Model
```php
// app/Models/PortalPapuaTengah.php
class PortalPapuaTengah extends Model
{
    protected $table = 'portal_papua_tengah';
    
    protected $fillable = [
        'judul', 'konten', 'kategori', 'penulis', 
        'is_published', 'published_at', 'views'
    ];
    
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];
    
    // Scopes
    public function scopePublished($query) {
        return $query->where('is_published', true);
    }
    
    public function scopeOrdered($query) {
        return $query->orderBy('published_at', 'desc');
    }
}
```

#### 2. Wbs Model
```php
// app/Models/Wbs.php
class Wbs extends Model
{
    protected $table = 'wbs';
    
    protected $fillable = [
        'nama_pelapor', 'email', 'nomor_telepon', 'jenis_laporan',
        'subjek', 'deskripsi', 'lokasi_kejadian', 'tanggal_kejadian',
        'bukti_pendukung', 'status', 'admin_notes'
    ];
    
    protected $casts = [
        'tanggal_kejadian' => 'date',
        'bukti_pendukung' => 'array',
    ];
}
```

### Controllers

#### Public Controller
```php
// app/Http/Controllers/PublicController.php
class PublicController extends Controller
{
    public function index(): View {
        // Homepage with 5 latest news
    }
    
    public function berita(Request $request): View {
        // All news with search, filter, pagination
    }
    
    public function show($id): View {
        // Single news detail with related articles
    }
    
    public function wbs(): View {
        // WBS form page
    }
}
```

#### Admin Controllers
```php
// app/Http/Controllers/Admin/PortalPapuaTengahController.php
class PortalPapuaTengahController extends Controller
{
    // CRUD operations for news management
    public function index() { /* List all news */ }
    public function create() { /* Show create form */ }
    public function store() { /* Save new news */ }
    public function show() { /* Show single news */ }
    public function edit() { /* Show edit form */ }
    public function update() { /* Update news */ }
    public function destroy() { /* Delete news */ }
}
```

## Customization Guide

### 1. Mengubah Logo

#### Langkah 1: Prepare Logo Files
```bash
# Place logo files in public directory
public/
├── logo.svg        # SVG version (recommended)
├── logo.png        # PNG fallback
└── favicon.ico     # Favicon
```

#### Langkah 2: Update Logo di Layout
```blade
<!-- resources/views/layouts/app.blade.php -->
<div class="flex items-center">
    <a href="{{ route('public.index') }}" class="flex items-center">
        <img src="{{ asset('logo.svg') }}" alt="Logo" class="w-10 h-10 mr-3">
        <!-- Atau menggunakan icon -->
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-shield-alt text-white text-lg"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-900">Inspektorat</h1>
            <p class="text-sm text-gray-500">Papua Tengah</p>
        </div>
    </a>
</div>
```

### 2. Mengubah Skema Warna

#### Primary Colors
File: `tailwind.config.js`
```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',   // Very light blue
          500: '#3b82f6',  // Main blue
          600: '#2563eb',  // Darker blue
          700: '#1d4ed8',  // Even darker blue
        },
        // Custom colors
        'brand-primary': '#your-color',
        'brand-secondary': '#your-color',
      }
    }
  }
}
```

#### CSS Variables
File: `resources/css/app.css`
```css
:root {
  --color-primary: #3b82f6;
  --color-primary-dark: #2563eb;
  --color-secondary: #10b981;
  --color-accent: #f59e0b;
}

/* Custom utility classes */
.bg-brand-primary {
  background-color: var(--color-primary);
}

.text-brand-primary {
  color: var(--color-primary);
}
```

#### Update Components
```bash
# Find and replace blue colors
grep -r "bg-blue-600" resources/views/
grep -r "text-blue-600" resources/views/

# Replace with your brand colors
# bg-blue-600 → bg-brand-primary
# text-blue-600 → text-brand-primary
```

### 3. Mengubah Konten Statis

#### Info Kantor
File: `app/Http/Controllers/PublicController.php`
```php
public function index(): View
{
    // Update static office info
    $infoKantor = new \stdClass();
    $infoKantor->nama = 'Nama Instansi Anda';
    $infoKantor->alamat = 'Alamat Lengkap';
    $infoKantor->telepon = '(0xxx) xxxxx';
    $infoKantor->email = 'email@domain.com';
    $infoKantor->jam_operasional = 'Senin - Jumat: 08:00 - 16:00';
    $infoKantor->website = 'https://website.com';
    
    return view('public.index', compact('infoKantor'));
}
```

#### Hero Section Content
File: `resources/views/public/index.blade.php`
```blade
<!-- Update hero slides -->
<div class="slide active">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 max-w-4xl mx-auto text-center text-white px-4">
        <h1 class="text-4xl lg:text-6xl font-bold mb-6">
            Judul Hero Anda
        </h1>
        <p class="text-xl lg:text-2xl mb-8">
            Deskripsi instansi atau layanan Anda
        </p>
        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold inline-flex items-center transition-colors">
            Call to Action
            <i class="fas fa-arrow-right ml-3"></i>
        </a>
    </div>
</div>
```

### 4. Menambah Fitur Baru

#### Step 1: Create Migration
```bash
php artisan make:migration create_new_feature_table
```

#### Step 2: Create Model
```bash
php artisan make:model NewFeature
```

#### Step 3: Create Controller
```bash
php artisan make:controller Admin/NewFeatureController --resource
```

#### Step 4: Add Routes
```php
// routes/web.php
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('new-feature', NewFeatureController::class);
});
```

#### Step 5: Create Views
```bash
# Create admin views
resources/views/admin/new-feature/
├── index.blade.php
├── create.blade.php
├── show.blade.php
└── edit.blade.php
```

### 5. Customizing Layouts

#### Admin Layout
File: `resources/views/layouts/admin.blade.php`
```blade
<!-- Add new menu item -->
<nav class="mt-8">
    <div class="px-4 space-y-2">
        <!-- Existing menu items -->
        
        <!-- New menu item -->
        <a href="{{ route('admin.new-feature.index') }}" 
           class="flex items-center px-4 py-3 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
            <i class="fas fa-new-icon mr-3"></i>
            New Feature
        </a>
    </div>
</nav>
```

#### Public Layout
File: `resources/views/layouts/app.blade.php`
```blade
<!-- Add new navigation item -->
<nav class="hidden md:block">
    <div class="flex items-center space-x-8">
        <a href="{{ route('new-feature') }}" 
           class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
            New Feature
        </a>
    </div>
</nav>
```

## Development Workflow

### 1. Local Development Setup

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Start development servers
php artisan serve          # Backend (port 8000)
npm run dev               # Frontend asset watching
```

### 2. Making Changes

#### Frontend Changes
```bash
# Watch for changes (auto-reload)
npm run dev

# Build for production
npm run build
```

#### Backend Changes
```bash
# Clear caches after changes
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Database changes
php artisan migrate
php artisan db:seed
```

### 3. Testing

#### Manual Testing Checklist
- [ ] Homepage loads correctly
- [ ] Navigation works on all pages
- [ ] News list and detail pages
- [ ] Search and filter functionality
- [ ] Admin panel CRUD operations
- [ ] WBS form submission
- [ ] Responsive design on mobile

#### Automated Testing (Optional)
```bash
# Run Laravel tests
php artisan test

# Create new test
php artisan make:test NewFeatureTest
```

## Database Schema

### Tables Overview

#### 1. portal_papua_tengah
```sql
CREATE TABLE portal_papua_tengah (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    is_published BOOLEAN DEFAULT FALSE,
    published_at TIMESTAMP NULL,
    views INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Indexes
CREATE INDEX idx_published ON portal_papua_tengah(is_published, published_at);
CREATE INDEX idx_kategori ON portal_papua_tengah(kategori);
CREATE INDEX idx_views ON portal_papua_tengah(views);
```

#### 2. wbs
```sql
CREATE TABLE wbs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama_pelapor VARCHAR(255),
    email VARCHAR(255),
    nomor_telepon VARCHAR(20),
    jenis_laporan VARCHAR(100) NOT NULL,
    subjek VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    lokasi_kejadian VARCHAR(255),
    tanggal_kejadian DATE,
    bukti_pendukung JSON,
    status ENUM('pending', 'in_review', 'resolved', 'rejected') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Indexes
CREATE INDEX idx_status ON wbs(status);
CREATE INDEX idx_jenis_laporan ON wbs(jenis_laporan);
```

#### 3. users
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Seeder Data

#### Default Admin User
```php
// database/seeders/UserSeeder.php
User::create([
    'name' => 'Administrator',
    'email' => 'admin@admin.com',
    'password' => Hash::make('password'),
]);
```

## API Documentation

### WBS API Endpoints

#### Submit WBS Report
```http
POST /api/wbs
Content-Type: application/json

{
    "nama_pelapor": "string|nullable",
    "email": "email|nullable",
    "nomor_telepon": "string|nullable",
    "jenis_laporan": "string|required",
    "subjek": "string|required",
    "deskripsi": "string|required",
    "lokasi_kejadian": "string|nullable",
    "tanggal_kejadian": "date|nullable"
}
```

#### Response
```json
{
    "success": true,
    "message": "Laporan berhasil dikirim",
    "data": {
        "id": 1,
        "jenis_laporan": "Korupsi",
        "subjek": "Laporan dugaan korupsi",
        "status": "pending",
        "created_at": "2025-01-01T00:00:00Z"
    }
}
```

### News API Endpoints (Future Development)

#### Get News List
```http
GET /api/news?page=1&per_page=10&kategori=informasi&search=keyword
```

#### Get Single News
```http
GET /api/news/{id}
```

## Frontend Components

### 1. Reusable Components

#### Button Component
```blade
<!-- resources/views/components/button.blade.php -->
@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md'])

@php
$classes = match($variant) {
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
    'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white',
    default => 'bg-blue-600 hover:bg-blue-700 text-white'
};

$sizes = match($size) {
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
    default => 'px-4 py-2 text-base'
};
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes . ' ' . $sizes . ' font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) }}>
    {{ $slot }}
</button>
```

Usage:
```blade
<x-button variant="primary" size="lg">Submit</x-button>
<x-button variant="outline">Cancel</x-button>
```

#### Card Component
```blade
<!-- resources/views/components/card.blade.php -->
@props(['title' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    
    <div @class(['p-6' => $padding])>
        {{ $slot }}
    </div>
</div>
```

### 2. JavaScript Components

#### Search Filter
```javascript
// resources/js/components/search-filter.js
class SearchFilter {
    constructor(formSelector, resultsSelector) {
        this.form = document.querySelector(formSelector);
        this.results = document.querySelector(resultsSelector);
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
        this.setupAutoComplete();
    }
    
    handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);
        this.fetchResults(formData);
    }
    
    async fetchResults(formData) {
        try {
            const params = new URLSearchParams(formData);
            const response = await fetch(`/api/search?${params}`);
            const data = await response.json();
            this.renderResults(data);
        } catch (error) {
            console.error('Search error:', error);
        }
    }
    
    renderResults(data) {
        // Update results container
        this.results.innerHTML = this.generateResultsHTML(data);
    }
}
```

## Deployment Guide

### 1. Production Server Setup

#### Requirements
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install nginx mysql-server php8.3-fpm php8.3-mysql php8.3-xml php8.3-gd php8.3-curl php8.3-mbstring php8.3-zip

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

#### SSL Setup (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### 2. Deployment Process

#### Automated Deployment Script
```bash
#!/bin/bash
# deploy.sh

# Pull latest code
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# Database migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Restart services
sudo systemctl reload nginx
sudo systemctl reload php8.3-fpm

echo "Deployment completed successfully!"
```

### 3. Environment Configuration

#### Production .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=portal_inspektorat
DB_USERNAME=production_user
DB_PASSWORD=secure_password

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

### 4. Monitoring & Maintenance

#### Log Monitoring
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

#### Performance Monitoring
```bash
# Database performance
mysql -e "SHOW PROCESSLIST;"

# Server resources
htop
df -h
free -m
```

#### Backup Script
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups"

# Database backup
mysqldump -u username -p password database_name > $BACKUP_DIR/db_backup_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz /path/to/portal-inspektorat --exclude=node_modules --exclude=vendor

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

---

## Contributing

### Code Standards
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Write tests for new features

### Git Workflow
```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "feat: add new feature"

# Push and create pull request
git push origin feature/new-feature
```

### Code Review Checklist
- [ ] Code follows PSR-12 standards
- [ ] No hardcoded values
- [ ] Error handling implemented
- [ ] Security considerations addressed
- [ ] Performance implications considered
- [ ] Documentation updated

---

**Portal Inspektorat Papua Tengah v1.0**  
Developer Documentation  
© 2025 Inspektorat Provinsi Papua Tengah
