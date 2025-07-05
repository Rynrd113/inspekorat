# 🏛️ Panduan Instalasi Portal Inspektorat Papua Tengah
## Untuk Windows dengan Laragon (MySQL + PHP 8.3 + phpMyAdmin)

---

## 📋 Daftar Isi
1. [Persiapan Awal](#1-persiapan-awal)
2. [Setup Laragon](#2-setup-laragon)
3. [Instalasi Project](#3-instalasi-project)
4. [Konfigurasi Database](#4-konfigurasi-database)
5. [Menjalankan Aplikasi](#5-menjalankan-aplikasi)
6. [Verifikasi Instalasi](#6-verifikasi-instalasi)
7. [Troubleshooting](#7-troubleshooting)

---

## 1. Persiapan Awal

### ✅ Yang Perlu Disiapkan:
- **Laragon** sudah terinstall dengan PHP 8.3
- **File project** Portal Inspektorat (folder ini)
- **Koneksi internet** untuk download dependencies

### 🔧 Cek Versi PHP di Laragon:
1. Buka Laragon
2. Klik **Menu** > **PHP** > Pastikan **PHP 8.3** aktif
3. Jika belum ada PHP 8.3, download dari menu **Tools** > **Quick add** > **PHP**

---

## 2. Setup Laragon

### 🚀 Langkah-langkah:

#### A. Start Services
1. **Buka Laragon**
2. **Start All** (tombol hijau) - tunggu hingga Apache dan MySQL menyala
3. **Pastikan status**: ✅ Apache ✅ MySQL

#### B. Verifikasi phpMyAdmin
1. Klik **Database** di Laragon
2. phpMyAdmin akan terbuka di browser
3. Login tanpa password (default Laragon)

#### C. Cek PHP Extensions
1. Klik **Menu** > **Tools** > **Terminal**
2. Ketik: `php -m` dan tekan Enter
3. **Pastikan ada extensions ini:**
   - ✅ openssl
   - ✅ pdo_mysql
   - ✅ mbstring
   - ✅ tokenizer
   - ✅ xml
   - ✅ ctype
   - ✅ json
   - ✅ bcmath
   - ✅ fileinfo
   - ✅ gd

---

## 3. Instalasi Project

### 📁 Penempatan File:

#### A. Copy Project ke Laragon
1. **Buka folder Laragon**: `C:\laragon\www\`
2. **Copy seluruh folder project** ke dalam `www`
3. **Rename folder** menjadi `portal-inspektorat`
4. **Path final**: `C:\laragon\www\portal-inspektorat\`

#### B. Install Dependencies
1. **Buka Terminal Laragon**: Menu > Tools > Terminal
2. **Masuk ke folder project**:
   ```bash
   cd portal-inspektorat
   ```
3. **Install Composer dependencies**:
   ```bash
   composer install
   ```
   ⏳ *Tunggu sampai selesai (3-5 menit)*

4. **Install Node.js dependencies** (jika ada):
   ```bash
   npm install
   ```

---

## 4. Konfigurasi Database

### 🗄️ Setup Database:

#### A. Buat Database Baru
1. **Buka phpMyAdmin** (klik Database di Laragon)
2. **Klik tab "Databases"**
3. **Buat database baru**:
   - Nama: `portal_inspektorat`
   - Collation: `utf8mb4_unicode_ci`
4. **Klik "Create"**

#### B. Konfigurasi Environment
1. **Di folder project**, copy file `.env.example` menjadi `.env`
2. **Edit file `.env`** dengan notepad:
   ```env
   APP_NAME="Portal Inspektorat Papua Tengah"
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://portal-inspektorat.test

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_inspektorat
   DB_USERNAME=root
   DB_PASSWORD=
   ```

#### C. Generate App Key
1. **Di Terminal Laragon**:
   ```bash
   php artisan key:generate
   ```

#### D. Migrasi Database
1. **Jalankan migrasi**:
   ```bash
   php artisan migrate
   ```
2. **Jika ada seeders**:
   ```bash
   php artisan db:seed
   ```

---

## 5. Menjalankan Aplikasi

### 🌐 Metode 1: Menggunakan Artisan (Recommended)
1. **Di Terminal Laragon**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
2. **Akses di browser**: `http://localhost:8000`

### 🌐 Metode 2: Menggunakan Virtual Host Laragon
1. **Klik kanan Laragon** > **Apache** > **sites-enabled**
2. **Tambah file baru**: `portal-inspektorat.conf`
3. **Isi dengan**:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/laragon/www/portal-inspektorat/public"
       ServerName portal-inspektorat.test
       <Directory "C:/laragon/www/portal-inspektorat/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
4. **Restart Apache** di Laragon
5. **Akses**: `http://portal-inspektorat.test`

---

## 6. Verifikasi Instalasi

### ✅ Checklist Berhasil:

#### A. Homepage Loading
- [ ] Homepage portal terbuka tanpa error
- [ ] Logo dan menu terlihat
- [ ] Berita terbaru muncul (jika ada data)

#### B. Admin Panel
1. **Akses**: `http://localhost:8000/admin` atau `http://portal-inspektorat.test/admin`
2. **Login** dengan akun default (cek di seeders)
3. **Verifikasi CRUD** bisa digunakan

#### C. Database Connection
- [ ] Data tersimpan ke MySQL
- [ ] Migrasi berhasil di phpMyAdmin
- [ ] Tabel terbuat dengan benar

---

## 7. Troubleshooting

### ❌ Error Common & Solusi:

#### **Error: "Class not found"**
```bash
composer dump-autoload
```

#### **Error: "Permission denied"**
1. **Set permission folder**:
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

#### **Error: Database connection**
1. **Cek MySQL status** di Laragon
2. **Verifikasi .env** database settings
3. **Test connection**:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

#### **Error: "Mixed content" (HTTPS/HTTP)**
1. **Edit .env**:
   ```env
   APP_URL=http://localhost:8000
   ```
2. **Clear cache**:
   ```bash
   php artisan config:clear
   ```

#### **Error: Port 8000 sudah digunakan**
```bash
php artisan serve --port=8080
```

### 🆘 Bantuan Tambahan:

#### **Command Utilities:**
```bash
# Clear semua cache
php artisan optimize:clear

# Lihat routes
php artisan route:list

# Cek konfigurasi
php artisan config:show

# Reset database
php artisan migrate:fresh --seed
```

#### **File Permissions (jika error):**
- `storage/` folder harus writable
- `bootstrap/cache/` folder harus writable
- `.env` file harus readable

---

## 📞 Tips untuk Panduan via Telepon:

### 🗣️ Langkah Komunikasi:
1. **Pastikan Laragon running**: "Apakah tombol Start All sudah hijau?"
2. **Konfirmasi setiap step**: "Apakah composer install sudah selesai?"
3. **Screenshot error**: "Kirim foto error kalau ada masalah"
4. **Test bertahap**: "Coba buka localhost:8000 dulu"

### 📋 Checklist Telepon:
- [ ] Laragon Apache & MySQL menyala
- [ ] PHP 8.3 aktif
- [ ] Project di `C:\laragon\www\portal-inspektorat\`
- [ ] File `.env` sudah dibuat
- [ ] Database `portal_inspektorat` ada di phpMyAdmin
- [ ] `composer install` berhasil
- [ ] `php artisan key:generate` berhasil
- [ ] `php artisan migrate` berhasil
- [ ] Website buka di `http://localhost:8000`

---

## 🎉 Selamat!
**Portal Inspektorat Papua Tengah** siap digunakan!

**Default Access:**
- **Frontend**: `http://localhost:8000`
- **Admin**: `http://localhost:8000/admin`
- **Database**: phpMyAdmin via Laragon

---

*Dibuat untuk tim IT - Inspektorat Papua Tengah*  
*Panduan ini dirancang untuk instalasi mudah via telepon/remote*
