<div align="center">

# 🏛️ Portal Inspektorat Papua Tengah

<img src="https://via.placeholder.com/150x150/2563EB/FFFFFF?text=PT" alt="Logo Papua Tengah" width="150" height="150">

**Portal Informasi Pemerintahan Resmi**  
*Inspektorat Provinsi Papua Tengah*

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

---

*Portal modern untuk transparansi pemerintahan dengan akses mudah terhadap informasi publik, berita terkini, dan sistem pelaporan Whistleblower (WBS)*

</div>

## 🌟 Fitur Unggulan

<table>
<tr>
<td width="50%">

### 📰 **Portal Berita Interaktif**
- 🏠 **Homepage Preview**: 5 berita terbaru dengan filter real-time
- 📋 **Daftar Lengkap**: Search, filter kategori & pagination
- 📄 **Detail Artikel**: Konten lengkap + social sharing
- ⚙️ **Admin Panel**: CRUD management yang powerful

</td>
<td width="50%">

### 🛡️ **Whistleblower System (WBS)**
- 📝 **Form Pelaporan**: Interface yang user-friendly
- 📎 **Multiple Input**: Text, file upload & berbagai jenis
- 👨‍💼 **Admin Management**: Review & tindak lanjut laporan
- 📊 **Status Tracking**: Monitor progress laporan

</td>
</tr>
<tr>
<td width="50%">

### 🏢 **Info Kantor Lengkap**
- 📞 **Kontak Detail**: Alamat, telepon, email, jam kerja
- 🗺️ **Lokasi**: Koordinat GPS & integrasi maps
- 🖥️ **Auto Display**: Tampil otomatis di homepage

</td>
<td width="50%">

### 🎨 **Desain Modern & Responsive**
- 📱 **Multi-Device**: Desktop, tablet, mobile optimized
- 🎠 **Hero Slider**: 3 slide auto-play dengan navigasi
- 🔵 **Blue Theme**: Konsisten dengan branding pemerintah
- ♿ **Accessibility**: Mudah diakses semua kalangan

</td>
</tr>
</table>

## 🚀 Stack Teknologi

<div align="center">

| Kategori | Teknologi | Versi | Deskripsi |
|----------|-----------|-------|-----------|
| 🖥️ **Backend** | Laravel | 12.x | PHP Framework Modern |
| 🎨 **Frontend** | Tailwind CSS | 3.x | Utility-First CSS Framework |
| 🔧 **Build Tool** | Vite | 5.x | Fast Development Build Tool |
| 🗄️ **Database** | MySQL/PostgreSQL | 8.0+/13+ | Relational Database |
| 🔐 **Auth** | Laravel Sanctum | Built-in | API Token Authentication |
| 📱 **JavaScript** | Vanilla JS | ES6+ | Modern JavaScript |
| 🎯 **Icons** | Font Awesome | 6.5.1 | Icon Library |
| 🐘 **PHP** | PHP | 8.3+ | Server-side Language |

</div>

## 🚀 Quick Start

<details>
<summary><b>⚡ Auto Installation (RECOMMENDED)</b></summary>

### 🎯 One-Command Installation
```bash
# Clone project dan jalankan auto installer
git clone https://github.com/Rynrd113/inspekorat.git portal-inspektorat
cd portal-inspektorat
chmod +x install.sh
./install.sh
```

**Auto installer akan:**
- ✅ Cek system requirements (PHP 8.3+, MySQL, Node.js)
- ✅ Install dependencies (Composer & NPM)
- ✅ Setup database (MySQL/SQLite otomatis)
- ✅ Configure environment (.env)
- ✅ Run migrations & seeders
- ✅ Build frontend assets
- ✅ Set permissions

**Setelah instalasi selesai:**
```bash
php artisan serve
# Buka http://localhost:8000
```

</details>

<details>
<summary><b>📥 Manual Installation (Advanced)</b></summary>

### 1️⃣ Download & Setup
```bash
# Clone project
git clone https://github.com/your-repo/portal-inspektorat.git
cd portal-inspektorat

# Install dependencies
composer install && npm install
```

### 2️⃣ Konfigurasi Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3️⃣ Setup Database
```bash
# Edit .env file dengan kredensial database Anda
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4️⃣ Migrasi & Seeding
```bash
# Jalankan migrasi database
php artisan migrate

# Insert sample data
php artisan db:seed
```

### 5️⃣ Build & Run
```bash
# Build assets
npm run build

# Start development server
php artisan serve
```

### 6️⃣ Access Portal
- 🌐 **Frontend**: http://localhost:8000
- 👨‍💼 **Admin Panel**: http://localhost:8000/admin
- 🔑 **Login**: admin@papuatengah.go.id / password

</details>

<details>
<summary><b>👨‍💻 Untuk Developer (Klik untuk expand)</b></summary>

### Development Mode
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
php artisan serve          # Backend (Terminal 1)
npm run dev                # Frontend (Terminal 2)
```

### Build Production
```bash
# Optimize untuk production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

</details>

## 📚 Dokumentasi Lengkap

<div align="center">

| 📖 Dokumen | 🎯 Target | 📝 Deskripsi |
|------------|-----------|--------------|
| **[� INSTALL_AUTO.md](INSTALL_AUTO.md)** | ⚡ Quick Start | Auto installation scripts (Linux/Windows) |
| **[�📥 INSTALL.md](INSTALL.md)** | 👤 User/Installer | Panduan lengkap instalasi step-by-step |
| **[🏛️ PANDUAN_INSTALASI_LARAGON.md](PANDUAN_INSTALASI_LARAGON.md)** | 🪟 Windows User | Instalasi dengan Laragon (Windows) |
| **[👨‍💻 DEVELOPER.md](DEVELOPER.md)** | 🔧 Developer | Guide development, customization & deployment |
| **[🗄️ DATABASE.md](DATABASE.md)** | 📊 Database Admin | Import database, troubleshooting & FAQ |
| **[📋 CHANGELOG.md](CHANGELOG.md)** | 📈 Project Manager | History versi dan update fitur |

</div>

---

## ⚡ Fitur Demo

<details>
<summary><b>🎬 Screenshot & Preview (Klik untuk lihat)</b></summary>

### 🏠 Homepage
- Hero slider dengan 3 slide auto-play
- Preview 5 berita terbaru dengan filter interaktif
- Info kantor dan kontak lengkap
- Design responsive dan modern

### 📰 Portal Berita
- Halaman daftar lengkap dengan pagination
- Search dan filter berdasarkan kategori
- Detail artikel dengan social sharing
- Berita terkait dan navigasi antar artikel

### 🛡️ Whistleblower System
- Form pelaporan yang user-friendly
- Upload file dan berbagai jenis input
- Admin panel untuk manajemen laporan
- Tracking status laporan

### 👨‍� Admin Panel
- Dashboard dengan statistik
- CRUD management untuk semua konten
- User management dan permission
- Monitoring dan reporting

</details>

---

## 🏗️ Struktur Project

<details>
<summary><b>📁 Folder Structure (Klik untuk expand)</b></summary>

```
portal-inspektorat/
├── 📁 app/
│   ├── 📁 Http/Controllers/     # Controllers
│   ├── 📁 Models/               # Eloquent Models
│   └── 📁 Providers/            # Service Providers
├── 📁 database/
│   ├── 📄 database.sqlite       # SQLite Development DB
│   ├── 📄 portal_inspektorat_mysql.sql  # MySQL Production DB
│   ├── 📁 migrations/           # Database Migrations
│   └── 📁 seeders/              # Sample Data Seeders
├── 📁 resources/
│   ├── 📁 css/                  # Stylesheets
│   ├── 📁 js/                   # JavaScript Files
│   └── 📁 views/                # Blade Templates
├── 📁 routes/
│   ├── 📄 web.php               # Web Routes
│   └── 📄 api.php               # API Routes
├── 📁 public/                   # Public Assets
├── 📄 README.md                 # This file
├── 📄 INSTALL.md                # Installation Guide
├── 📄 DEVELOPER.md              # Developer Guide
├── 📄 DATABASE.md               # Database Guide
└── 📄 CHANGELOG.md              # Version History
```

</details>

---

## 💡 Tips & Best Practices

<div align="center">

### 🚀 **Performance**
> Gunakan `php artisan optimize` untuk production  
> Enable caching untuk performa optimal

### 🔒 **Security**
> Update dependencies secara rutin  
> Gunakan HTTPS di production

### 📱 **Responsive**
> Test di berbagai device  
> Optimize untuk mobile-first

### 🔧 **Maintenance**
> Backup database secara rutin  
> Monitor log untuk error

</div>

---

## 🤝 Kontribusi & Support

<div align="center">

### 📞 **Support**
📧 Email: admin@papuatengah.go.id  
🌐 Website: https://inspektorat.papuatengah.go.id  
📱 Telepon: (021) 123-4567

### 👥 **Tim Pengembang**
🏢 **Inspektorat Provinsi Papua Tengah**  
📍 Jl. Contoh No. 123, Papua Tengah

### 📄 **Lisensi**
Project ini dikembangkan untuk kepentingan publik  
© 2025 Inspektorat Provinsi Papua Tengah

</div>

---

<div align="center">

**⭐ Jika project ini bermanfaat, jangan lupa berikan star! ⭐**

</div>

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
