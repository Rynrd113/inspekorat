# Dokumentasi Instalasi Portal Inspektorat Papua Tengah

## Daftar Isi
1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Instalasi](#instalasi)
3. [Konfigurasi](#konfigurasi)
4. [Menjalankan Aplikasi](#menjalankan-aplikasi)
5. [Penggunaan Admin Panel](#penggunaan-admin-panel)
6. [Troubleshooting](#troubleshooting)
7. [FAQ](#faq)

## Persyaratan Sistem

Sebelum menginstal aplikasi Portal Inspektorat Papua Tengah, pastikan sistem Anda memenuhi persyaratan berikut:

### Persyaratan Minimum
- **PHP**: Versi 8.3 atau lebih tinggi
- **Database**: MySQL 8.0+ atau PostgreSQL 13+  (Default: MySQL)
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **Node.js**: Versi 18.0+ (untuk build asset)
- **Composer**: Versi 2.0+
- **Memory**: Minimum 2GB RAM
- **Storage**: Minimum 1GB ruang kosong

### Extensions PHP yang Diperlukan
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- BCMath PHP Extension
- Fileinfo PHP Extension
- GD PHP Extension (untuk image processing)

## Instalasi

### 1. Download dan Extract Project

```bash
# Clone repository atau extract file project
git clone https://github.com/your-repo/portal-inspektorat.git
cd portal-inspektorat

# Atau jika menggunakan file zip
unzip portal-inspektorat.zip
cd portal-inspektorat
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Setup Database

```bash
# Jalankan migrasi database
php artisan migrate

# Seed data dummy (opsional)
php artisan db:seed
```

### 6. Build Assets

```bash
# Build assets untuk production
npm run build

# Atau untuk development
npm run dev
```

### 7. Setup Storage

```bash
# Create symbolic link untuk storage
php artisan storage:link

# Set permissions (Linux/macOS)
chmod -R 775 storage bootstrap/cache
```

## Konfigurasi

### Konfigurasi Dasar

Edit file `.env` untuk menyesuaikan konfigurasi aplikasi:

```env
# Informasi Aplikasi
APP_NAME="Portal Inspektorat Papua Tengah"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Portal Inspektorat Papua Tengah"

# Cache Configuration
CACHE_DRIVER=file
QUEUE_CONNECTION=database
```

### Konfigurasi Web Server

#### Apache (.htaccess)
File `.htaccess` sudah disediakan di folder `public/`. Pastikan Apache module `mod_rewrite` aktif.

#### Nginx
Contoh konfigurasi Nginx:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/portal-inspektorat/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Menjalankan Aplikasi

### Development Server

```bash
# Jalankan Laravel development server
php artisan serve

# Aplikasi akan berjalan di http://localhost:8000
```

### Production Server

1. Upload semua file ke web server
2. Point domain ke folder `public/`
3. Setup SSL certificate (recommended)
4. Setup cron job untuk scheduler:

```bash
# Tambahkan ke crontab
* * * * * cd /path/to/portal-inspektorat && php artisan schedule:run >> /dev/null 2>&1
```

## Penggunaan Admin Panel

### Login Admin

1. Buka browser dan akses: `https://yourdomain.com/admin/login`
2. Login dengan kredensial default:
   - **Email**: `admin@admin.com`
   - **Password**: `password`
3. **PENTING**: Segera ganti password default setelah login pertama

### Fitur Admin Panel

#### 1. Dashboard
- Overview statistik website
- Grafik pengunjung dan aktivitas
- Quick actions untuk tugas umum

#### 2. Manajemen Berita (Portal Papua Tengah)
- **Tambah Berita Baru**:
  1. Klik "Portal Papua Tengah" → "Tambah Baru"
  2. Isi form: Judul, Konten, Kategori, Penulis
  3. Set status "Published" untuk menampilkan di website
  4. Klik "Simpan"

- **Edit Berita**:
  1. Pilih berita dari daftar
  2. Klik "Edit"
  3. Ubah data yang diperlukan
  4. Klik "Update"

- **Hapus Berita**:
  1. Pilih berita dari daftar
  2. Klik "Hapus"
  3. Konfirmasi penghapusan

#### 3. Manajemen WBS (Whistleblower System)
- Melihat laporan WBS yang masuk
- Detail laporan dan tindak lanjut
- Export data untuk analisis

### Tips Penggunaan Admin

1. **Backup Data**: Selalu backup database sebelum melakukan perubahan besar
2. **Image Optimization**: Kompres gambar sebelum upload untuk performa optimal
3. **SEO**: Gunakan judul dan konten yang SEO-friendly
4. **Security**: Gunakan password yang kuat dan update secara berkala

## Troubleshooting

### Error Umum dan Solusinya

#### 1. "500 Internal Server Error"

**Solusi:**
```bash
# Check log error
tail -f storage/logs/laravel.log

# Set permission storage
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

#### 2. "Database Connection Error"

**Solusi:**
1. Periksa konfigurasi database di `.env`
2. Pastikan database server berjalan
3. Test koneksi database:
```bash
php artisan tinker
# Dalam tinker: DB::connection()->getPdo();
```

#### 3. "Asset Not Found / CSS/JS Not Loading"

**Solusi:**
```bash
# Rebuild assets
npm run build

# Clear view cache
php artisan view:clear

# Check public storage link
php artisan storage:link
```

#### 4. "Permission Denied"

**Solusi (Linux/macOS):**
```bash
# Set correct permissions
sudo chown -R www-data:www-data /path/to/portal-inspektorat
chmod -R 755 /path/to/portal-inspektorat
chmod -R 775 storage bootstrap/cache
```

### Performance Issues

#### 1. Website Lambat

**Solusi:**
```bash
# Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader
```

#### 2. Database Query Lambat

**Solusi:**
1. Enable query logging di `.env`:
```env
DB_LOG_QUERIES=true
```
2. Check slow queries di log
3. Add database indexes jika diperlukan

## FAQ

### Q: Bagaimana cara mengganti logo?
A: Lihat dokumentasi developer untuk panduan lengkap customization.

### Q: Apakah bisa menggunakan PostgreSQL?
A: Ya, ubah `DB_CONNECTION=pgsql` di file `.env` dan sesuaikan konfigurasi database.

### Q: Bagaimana cara backup data?
A: 
```bash
# Backup database
php artisan db:backup

# Manual backup
mysqldump -u username -p database_name > backup.sql
```

### Q: Bisakah mengubah tema warna?
A: Ya, lihat dokumentasi developer untuk panduan customization tema.

### Q: Bagaimana cara update aplikasi?
A: 
1. Backup data dan file
2. Download versi terbaru
3. Replace file (kecuali `.env` dan storage)
4. Run `composer install` dan `php artisan migrate`

### Q: Error "Class not found"?
A: 
```bash
# Regenerate autoload
composer dump-autoload
```

### Q: Lupa password admin?
A: 
```bash
# Reset melalui tinker
php artisan tinker
# Dalam tinker:
$user = App\Models\User::where('email', 'admin@admin.com')->first();
$user->password = Hash::make('newpassword');
$user->save();
```

## Dukungan

Jika mengalami masalah yang tidak tercakup dalam dokumentasi ini:

1. Check log error di `storage/logs/laravel.log`
2. Pastikan mengikuti semua langkah instalasi
3. Hubungi tim developer untuk dukungan teknis

---

**Portal Inspektorat Papua Tengah v1.0**  
Dibangun dengan Laravel 12 + Tailwind CSS  
© 2025 Inspektorat Provinsi Papua Tengah
