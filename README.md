# Portal Inspektorat Papua Tengah

Portal Informasi Pemerintahan resmi Inspektorat Provinsi Papua Tengah yang dibangun dengan teknologi modern untuk menyediakan akses mudah terhadap informasi publik, berita, dan layanan Whistleblower System (WBS).

## 🌟 Fitur Utama

### 📰 Portal Berita
- **Homepage Preview**: 5 berita terbaru dengan filter interaktif (Terbaru/Terpopuler)
- **Halaman Daftar Lengkap**: Semua berita dengan search, filter kategori, dan pagination
- **Halaman Detail**: Konten lengkap dengan social sharing dan berita terkait
- **Admin Panel**: CRUD lengkap untuk manajemen berita

### 🛡️ Whistleblower System (WBS)
- **Form Pelaporan**: Interface user-friendly untuk submit laporan
- **Multiple Input Types**: Text, file upload, dan berbagai jenis laporan
- **Admin Management**: Panel admin untuk review dan tindak lanjut laporan
- **Status Tracking**: Monitoring status laporan

### 🏢 Info Kantor
- **Informasi Kontak**: Alamat, telepon, email, jam operasional
- **Lokasi**: Koordinat dan link maps
- **Static Display**: Tampil otomatis di homepage

### 🎨 Desain Modern
- **Responsive Design**: Optimal di desktop, tablet, dan mobile
- **Hero Slider**: 3 slide dengan auto-play dan navigasi
- **Blue Color Scheme**: Konsisten dengan branding pemerintahan
- **Accessibility**: Desain yang mudah diakses semua kalangan

## 🚀 Teknologi

- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Blade Templates + Tailwind CSS + Vanilla JavaScript
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **Build Tool**: Vite
- **Icons**: Font Awesome 6.5.1

## 📋 Quick Start

### Untuk User/Installer
```bash
# 1. Clone/Download project
git clone https://github.com/your-repo/portal-inspektorat.git
cd portal-inspektorat

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate --seed

# 5. Build assets & run
npm run build
php artisan serve
```

**🔗 Akses aplikasi di**: http://localhost:8000  
**🔐 Login admin**: http://localhost:8000/admin/login  
- Email: `admin@admin.com`  
- Password: `password`

### Dokumentasi Lengkap
- **📖 [Panduan Instalasi](INSTALL.md)** - Dokumentasi lengkap untuk user dan administrator
- **🛠️ [Developer Guide](DEVELOPER.md)** - Dokumentasi teknis untuk developer

## 🏗️ Struktur Project

```
portal-inspektorat/
├── 📁 app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # Admin panel controllers
│   │   └── PublicController.php # Public pages controller
│   └── Models/                 # Database models
├── 📁 resources/
│   ├── views/
│   │   ├── public/             # Public pages (homepage, berita, wbs)
│   │   ├── admin/              # Admin panel pages  
│   │   └── layouts/            # Layout templates
│   ├── css/                    # Tailwind CSS
│   └── js/                     # JavaScript components
├── 📁 routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
├── 📁 database/
│   ├── migrations/             # Database schema
│   └── seeders/                # Sample data
├── 📄 INSTALL.md               # Dokumentasi instalasi
├── 📄 DEVELOPER.md             # Dokumentasi developer
└── 📄 README.md                # File ini
```

## 🎯 Use Cases

### 👥 Untuk Masyarakat
- **Membaca Berita**: Akses informasi terbaru dari Inspektorat
- **Mencari Informasi**: Search dan filter berita berdasarkan kategori
- **Melaporkan Dugaan**: Submit laporan melalui WBS secara anonim
- **Kontak Kantor**: Mendapatkan informasi kontak dan lokasi

### 👨‍💼 Untuk Admin
- **Manajemen Berita**: Tambah, edit, hapus, dan publish berita
- **Review Laporan WBS**: Monitor dan tindak lanjut laporan masuk
- **Dashboard Analytics**: Overview statistik dan aktivitas website
- **User Management**: Kelola akses admin panel

### 👩‍💻 Untuk Developer
- **Customization**: Mudah mengubah logo, warna, dan konten
- **Extensible**: Arsitektur modular untuk penambahan fitur
- **API Ready**: RESTful API untuk integrasi mobile app
- **Performance**: Optimized untuk load time dan SEO

## 🔧 Customization

### Mengubah Logo
```bash
# 1. Ganti file logo
public/logo.svg          # Logo utama
public/favicon.ico       # Favicon

# 2. Update di layout
resources/views/layouts/app.blade.php
```

### Mengubah Warna Tema
```bash
# 1. Edit Tailwind config
tailwind.config.js

# 2. Update CSS variables
resources/css/app.css

# 3. Rebuild assets
npm run build
```

### Menambah Fitur Baru
```bash
# 1. Generate model & controller
php artisan make:model NewFeature -mcr

# 2. Add routes
routes/web.php

# 3. Create views
resources/views/admin/new-feature/
```

Lihat **[DEVELOPER.md](DEVELOPER.md)** untuk panduan customization lengkap.

## 📱 Screenshots

### Homepage
- Hero slider dengan 3 slide auto-play
- Section berita dengan filter Terbaru/Terpopuler
- Info kantor dan layanan pintasan

### Halaman Berita
- Grid layout responsive dengan search & filter
- Pagination untuk handling banyak data
- Category filter dan sort options

### Admin Panel
- Dashboard dengan statistik
- CRUD berita dengan rich editor
- Management laporan WBS

## 🔒 Security Features

- **Authentication**: Laravel Sanctum untuk admin panel
- **Input Validation**: Comprehensive server-side validation
- **CSRF Protection**: Built-in Laravel CSRF protection  
- **SQL Injection Prevention**: Eloquent ORM dengan prepared statements
- **XSS Protection**: Blade template escaping
- **File Upload Security**: Validation dan sanitization

## 🚀 Performance

- **Caching**: Database query caching untuk performa optimal
- **Asset Optimization**: Minified CSS/JS dengan Vite
- **Database Indexing**: Proper indexing untuk query cepat
- **Lazy Loading**: Optimized image dan content loading
- **CDN Ready**: Asset serving siap untuk CDN

## 📊 Browser Support

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile Safari (iOS 14+)
- ✅ Chrome Mobile (Android 10+)

## 🤝 Contributing

Kontribusi sangat diterima! Silakan baca [DEVELOPER.md](DEVELOPER.md) untuk panduan development.

### Development Workflow
1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

Project ini dikembangkan untuk Inspektorat Provinsi Papua Tengah.

## 📞 Support

Untuk pertanyaan teknis atau dukungan:

- **Dokumentasi**: [INSTALL.md](INSTALL.md) | [DEVELOPER.md](DEVELOPER.md)
- **Issues**: [GitHub Issues](https://github.com/your-repo/portal-inspektorat/issues)

## 🎉 Acknowledgments

- **Laravel Framework**: Foundation yang solid untuk web development
- **Tailwind CSS**: Utility-first CSS framework yang powerful
- **Font Awesome**: Icon library yang comprehensive
- **Vite**: Modern build tool untuk asset bundling

---

**Portal Inspektorat Papua Tengah v1.0**  
Dibangun dengan ❤️ untuk melayani masyarakat Papua Tengah  
© 2025 Inspektorat Provinsi Papua Tengah

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# inspekorat
