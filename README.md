# Portal Inspektorat Papua Tengah

Portal informasi dan layanan publik resmi Inspektorat Provinsi Papua Tengah dengan sistem manajemen berita dan Whistleblower System (WBS).

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)

## Fitur Utama

- **Portal Berita**: Manajemen berita dengan editor rich text, kategori, dan pencarian
- **Whistleblower System (WBS)**: Sistem pelaporan yang aman dan terstruktur
- **Informasi Kantor**: Profil lengkap instansi dengan kontak dan lokasi
- **Admin Panel**: Dashboard administrasi untuk mengelola konten
- **Responsive Design**: Optimized untuk desktop, tablet, dan mobile

## Teknologi

- **Backend**: Laravel 12.x dengan PHP 8.2+
- **Frontend**: Tailwind CSS 3.x dengan Vite
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Sanctum

## Instalasi

### Requirements

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL/PostgreSQL (atau SQLite untuk development)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Rynrd113/inspekorat.git
   cd inspekorat
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_inspektorat
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Migrasi database**
   ```bash
   php artisan migrate --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

Aplikasi akan berjalan di `http://localhost:8000`

### Akses Admin

- URL: `http://localhost:8000/admin`
- Email: `admin@papuatengah.go.id`
- Password: `password`

## Development

Untuk development mode:

```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend (watch mode)
npm run dev
```

## Struktur Database

- `users`: Data pengguna admin
- `web_portals`: Konten berita dan artikel
- `wbs`: Data laporan Whistleblower
- `info_kantors`: Informasi kantor dan kontak
- `portal_papua_tengahs`: Data khusus Papua Tengah

## Deployment

Untuk production:

1. **Optimize aplikasi**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Setup permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

3. **Configure web server** untuk mengarah ke folder `public/`

## Security

- CSRF protection pada semua form
- Input validation dan sanitization
- SQL injection prevention dengan Eloquent ORM
- XSS protection dengan Blade templating
- File upload security validation

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## License

© 2025 Inspektorat Provinsi Papua Tengah. Dikembangkan untuk kepentingan publik.
