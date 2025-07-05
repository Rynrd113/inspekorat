# 📚 Portal Inspektorat Papua Tengah - Developer Documentation

**Dokumentasi Lengkap untuk Developer**  
*Portal Informasi dan Whistleblower System (WBS)*

---

## 📋 Daftar Dokumentasi

### 🏠 [1. Dokumentasi Utama](./DEVELOPER_DOCUMENTATION.md)
**Panduan utama untuk developer yang mencakup:**
- Arsitektur aplikasi Laravel
- Setup development environment 
- Struktur folder dan file
- Database schema dan relationships
- Authentication & authorization
- API routes dan endpoints
- Deployment dan troubleshooting

### 🎨 [2. Panduan Customization Frontend](./FRONTEND_CUSTOMIZATION_GUIDE.md)
**Panduan lengkap untuk kustomisasi UI/UX:**
- Mengganti theme dan color palette
- Customization logo dan branding
- Layout dan tata letak responsive
- Typography dan font management
- Components dan UI elements
- Dark mode implementation
- Animation dan visual effects

### 🗄️ [3. Database & API Documentation](./DATABASE_API_DOCUMENTATION.md)
**Dokumentasi database dan API reference:**
- Database schema lengkap
- Model relationships dan Eloquent
- Migration dan seeder guide
- API endpoints dokumentasi
- Query optimization techniques
- Backup dan recovery strategy

### 🚀 [4. Deployment & Maintenance Guide](./DEPLOYMENT_MAINTENANCE_GUIDE.md)
**Panduan deployment production dan maintenance:**
- Server requirements dan setup
- Production deployment process
- Security configuration
- Performance optimization
- Monitoring dan logging
- Backup strategy
- Maintenance tasks dan troubleshooting

---

## 🚀 Quick Start Guide

### 1. Setup Development Environment
```bash
# Clone repository
git clone https://github.com/Rynrd113/inspekorat.git
cd inspekorat

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Start development
npm run dev           # Terminal 1 - Frontend
php artisan serve     # Terminal 2 - Backend
```

### 2. Access Points
- **Frontend**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
  - Email: `admin@papuatengah.go.id`
  - Password: `password`

### 3. Development Commands
```bash
# Frontend development
npm run dev          # Development with hot reload
npm run build        # Production build
npm run clean        # Clean install

# Laravel commands
php artisan migrate  # Run migrations
php artisan db:seed  # Run seeders
php artisan cache:clear  # Clear caches
```

---

## 🏗️ Arsitektur Aplikasi

### Technology Stack
- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Tailwind CSS 4.x + Vite
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Sanctum
- **Build Tool**: Vite 6.x

### Key Features
- **Portal Berita**: Manajemen konten dengan rich editor
- **Whistleblower System (WBS)**: Sistem pelaporan aman
- **Admin Dashboard**: Panel administrasi lengkap
- **Responsive Design**: Mobile-first approach
- **API Ready**: RESTful API endpoints

### Folder Structure
```
app/
├── Http/Controllers/     # Business logic
├── Models/              # Eloquent models
├── Requests/            # Form validation
└── Resources/           # API resources

resources/
├── views/               # Blade templates
├── css/                 # Stylesheets
└── js/                  # JavaScript

public/
├── build/               # Compiled assets
└── logo.svg            # Main logo

database/
├── migrations/          # Database migrations
├── seeders/            # Data seeders
└── factories/          # Model factories
```

---

## 🎯 Customization Areas

### 1. Visual Branding
- **Logo**: Replace `public/logo.svg`
- **Colors**: Edit Tailwind color palette
- **Typography**: Configure fonts in `tailwind.config.js`
- **Layout**: Modify Blade templates in `resources/views/`

### 2. Functionality
- **Models**: Add/modify in `app/Models/`
- **Controllers**: Business logic in `app/Http/Controllers/`
- **Routes**: Configure in `routes/web.php` dan `routes/api.php`
- **Middleware**: Custom middleware in `app/Http/Middleware/`

### 3. Frontend Components
- **Blade Components**: Reusable in `resources/views/components/`
- **CSS Classes**: Custom utilities in `resources/css/app.css`
- **JavaScript**: Interactive features in `resources/js/app.js`

---

## 🔧 Common Development Tasks

### 1. Mengganti Logo
```bash
# 1. Replace logo file
cp new-logo.svg public/logo.svg

# 2. Generate favicons
./scripts/generate-favicons.sh

# 3. Update template references (if needed)
# Edit resources/views/layouts/app.blade.php
```

### 2. Menambah Halaman Baru
```bash
# 1. Create controller
php artisan make:controller NewPageController

# 2. Create view
touch resources/views/new-page.blade.php

# 3. Add route
# Edit routes/web.php
```

### 3. Mengubah Color Scheme
```javascript
// Edit tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          500: '#your-color',
          600: '#your-darker-color',
        }
      }
    }
  }
}
```

### 4. Menambah API Endpoint
```bash
# 1. Create API controller
php artisan make:controller Api/NewApiController

# 2. Create resource (optional)
php artisan make:resource NewApiResource

# 3. Add route
# Edit routes/api.php
```

---

## 🚀 Deployment Checklist

### Pre-Deployment
- [ ] Test all functionality locally
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `npm run build`
- [ ] Configure production `.env`
- [ ] Set up SSL certificate
- [ ] Configure web server (Nginx/Apache)

### Post-Deployment
- [ ] Run `php artisan migrate --force`
- [ ] Run optimization commands
- [ ] Set file permissions
- [ ] Configure backups
- [ ] Set up monitoring
- [ ] Test all endpoints

---

## 🐛 Troubleshooting

### Common Issues

**1. Permission Issues**
```bash
sudo chown -R www-data:www-data /var/www/inspektorat
sudo chmod -R 755 /var/www/inspektorat
sudo chmod -R 775 storage bootstrap/cache
```

**2. Cache Issues**
```bash
php artisan optimize:clear
composer dump-autoload
```

**3. Database Issues**
```bash
php artisan migrate:fresh --seed  # Development only
php artisan migrate --force        # Production
```

**4. Asset Issues**
```bash
npm run clean
npm install
npm run build
```

---

## 📞 Support & Contact

### Development Team
- **Lead Developer**: developer@papuatengah.go.id
- **System Admin**: sysadmin@papuatengah.go.id
- **Project Manager**: pm@papuatengah.go.id

### Repository
- **GitHub**: https://github.com/Rynrd113/inspekorat
- **Issues**: Report bugs and feature requests
- **Wiki**: Additional documentation

### Resources
- **Laravel Documentation**: https://laravel.com/docs
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Vite Documentation**: https://vitejs.dev/guide/

---

## 📄 License & Credits

**© 2025 Inspektorat Provinsi Papua Tengah**

Portal ini dikembangkan untuk mendukung transparansi dan akuntabilitas pemerintahan Papua Tengah. Semua dokumentasi dan kode sumber tersedia untuk tujuan pengembangan dan maintenance.

### Built With
- [Laravel](https://laravel.com) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework  
- [Vite](https://vitejs.dev) - Build Tool
- [Alpine.js](https://alpinejs.dev) - JavaScript Framework

---

**Selamat mengembangkan! 🚀**
