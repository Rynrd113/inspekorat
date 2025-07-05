# 🏛️ Panduan Instalasi Portal Inspektorat Papua Tengah
## Untuk Windows dengan Laragon (MySQL + PHP 8.3 + phpMyAdmin + Node.js)

**⚠️ CATATAN PENTING**: Portal ini menggunakan **MySQL** sebagai database default untuk production. Panduan ini akan setup dengan MySQL.

---

## 📋 Daftar Isi
1. [Instalasi Laragon](#1-instalasi-laragon)
2. [Setup PHP Multiple Versions](#2-setup-php-multiple-versions)
3. [Setup Node.js](#3-setup-nodejs)
4. [Konfigurasi Laragon](#4-konfigurasi-laragon)
5. [Instalasi Project](#5-instalasi-project)
6. [Konfigurasi Database](#6-konfigurasi-database)
7. [Menjalankan Aplikasi](#7-menjalankan-aplikasi)
8. [Verifikasi Instalasi](#8-verifikasi-instalasi)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. Instalasi Laragon

### 📥 Download dan Install Laragon

#### A. Download Laragon
1. **Buka browser** dan kunjungi: `https://laragon.org/download/`
2. **Download Laragon Full** (recommended - sudah include Apache, MySQL, PHP, dll)
3. **File size**: ±180MB
4. **Simpan file** di folder Downloads

#### B. Instalasi Laragon
1. **Klik kanan** file installer Laragon → **Run as Administrator**
2. **Setup Wizard**:
   - Language: **English** (atau Indonesia)
   - License Agreement: **I agree**
   - Installation Directory: `C:\laragon` (default - **jangan diubah**)
   - Select Components: **Full installation** (centang semua)
   - Create Desktop Shortcut: **✅ Yes**
3. **Klik Install** dan tunggu proses selesai (3-5 menit)
4. **Launch Laragon**: ✅ Centang dan klik **Finish**

#### C. First Run Laragon
1. **Laragon terbuka** pertama kali
2. **Jangan Start dulu** - kita setup PHP dan Node.js dulu
3. **Tutup Laragon** sementara

---

## 2. Setup PHP Multiple Versions

### 🐘 Install PHP 8.3 dan Versi Lain

#### A. Download PHP Versions
1. **Buka Laragon**
2. **Klik Menu** > **Tools** > **Quick add** > **PHP**
3. **Download PHP yang dibutuhkan**:
   - ✅ **PHP 8.3** (untuk project ini)
   - ✅ **PHP 8.2** (backup compatibility)
   - ✅ **PHP 8.1** (legacy support)

#### B. Manual Download (jika Quick add tidak work)
1. **Buka**: `https://windows.php.net/downloads/releases/`
2. **Download PHP 8.3 Thread Safe**:
   - File: `php-8.3.x-Win32-vs16-x64.zip`
   - Extract ke: `C:\laragon\bin\php\php-8.3.x\`
3. **Download PHP 8.2 Thread Safe**:
   - File: `php-8.2.x-Win32-vs16-x64.zip`  
   - Extract ke: `C:\laragon\bin\php\php-8.2.x\`

#### C. Verifikasi PHP Installation
1. **Buka Laragon**
2. **Klik Menu** > **PHP** > Pastikan ada:
   - ✅ php-8.3.x
   - ✅ php-8.2.x (optional)
3. **Pilih PHP 8.3** sebagai default
4. **Restart Laragon** jika diminta

#### D. Konfigurasi PHP 8.3
1. **Klik Menu** > **PHP** > **php.ini**
2. **Cari dan pastikan extensions ini aktif** (hilangkan ; di depan):
   ```ini
   extension=openssl
   extension=pdo_mysql
   extension=mbstring
   extension=tokenizer
   extension=xml
   extension=ctype
   extension=json
   extension=bcmath
   extension=fileinfo
   extension=gd
   extension=curl
   extension=zip
   ```
3. **Save file** dan restart Laragon

---

## 3. Setup Node.js

### 🟢 Install Node.js untuk Laravel Mix/Vite

#### A. Download Node.js
1. **Buka**: `https://nodejs.org/`
2. **Download LTS Version** (Long Term Support)
   - Recommended: **Node.js 20.x LTS**
   - File: `node-v20.x.x-x64.msi`

#### B. Install Node.js
1. **Double click** file installer
2. **Setup Wizard**:
   - Welcome: **Next**
   - License Agreement: **I accept** → **Next**
   - Destination Folder: Default → **Next**
   - Custom Setup: **Next** (install semua)
   - Tools for Native Modules: **✅ Automatically install** → **Next**
3. **Install** dan tunggu selesai
4. **Restart komputer** jika diminta

#### C. Verifikasi Node.js
1. **Buka Command Prompt** (Windows + R → cmd)
2. **Test Node.js**:
   ```bash
   node --version
   ```
   Output: `v20.x.x`
3. **Test NPM**:
   ```bash
   npm --version
   ```
   Output: `10.x.x`

#### D. Install Global Packages (Optional)
```bash
npm install -g @vue/cli
npm install -g create-react-app
npm install -g yarn
```

---

## 4. Konfigurasi Laragon

### 🚀 Final Setup Laragon

#### A. Start All Services
1. **Buka Laragon**
2. **Start All** (tombol hijau besar) - tunggu hingga semua service menyala
3. **Pastikan status**:
   - ✅ **Apache** (Web Server)
   - ✅ **MySQL** (Database)
   - ✅ **PHP 8.3** aktif

#### B. Verifikasi phpMyAdmin
1. **Klik tombol "Database"** di Laragon
2. **phpMyAdmin** akan terbuka di browser
3. **Login** tanpa password (default Laragon)
4. **Pastikan bisa akses** database interface

#### C. Test PHP dan Extensions
1. **Klik Menu** > **Tools** > **Terminal**
2. **Test PHP version**:
   ```bash
   php --version
   ```
   Output harus: `PHP 8.3.x`
3. **Cek extensions**:
   ```bash
   php -m
   ```
4. **Pastikan ada extensions**:
   - ✅ openssl, pdo_mysql, mbstring, tokenizer
   - ✅ xml, ctype, json, bcmath, fileinfo, gd

#### D. Test Composer
1. **Di Terminal Laragon**:
   ```bash
   composer --version
   ```
2. **Jika belum ada Composer**:
   - Download: `https://getcomposer.org/Composer-Setup.exe`
   - Install dengan setup wizard
   - Restart Command Prompt

---

## 5. Instalasi Project

### 📁 Penempatan dan Setup Project

#### A. Copy Project ke Laragon
1. **Tutup Laragon** sementara (penting!)
2. **Buka Windows Explorer** ke: `C:\laragon\www\`
3. **Copy/Cut seluruh folder project** "portal-inspektorat" ke dalam folder `www`
4. **Pastikan struktur**:
   ```
   C:\laragon\www\portal-inspektorat\
   ├── app/
   ├── config/
   ├── database/
   ├── public/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── composer.json
   ├── .env.example
   └── artisan
   ```
5. **Buka kembali Laragon** dan Start All

#### B. Setup Project Environment
1. **Masuk ke folder project** via Terminal Laragon:
   ```bash
   cd portal-inspektorat
   ```
2. **Copy environment file**:
   ```bash
   copy .env.example .env
   ```
   (Untuk Windows Command Prompt)
   
   **Atau jika menggunakan Git Bash**:
   ```bash
   cp .env.example .env
   ```

#### C. Install Composer Dependencies
1. **Pastikan masih di folder project**:
   ```bash
   pwd
   ```
   Output: `/c/laragon/www/portal-inspektorat` (Git Bash) atau `C:\laragon\www\portal-inspektorat`

2. **Install dependencies PHP**:
   ```bash
   composer install
   ```
   ⏳ **Tunggu proses selesai** (5-10 menit tergantung internet)
   
3. **Jika ada error permissions**, jalankan:
   ```bash
   composer install --no-scripts
   ```

#### D. Install Node.js Dependencies (Untuk Frontend Assets)
1. **Cek apakah ada package.json**:
   ```bash
   dir package.json
   ```
   
2. **Jika ada, install npm packages**:
   ```bash
   npm install
   ```
   ⏳ **Tunggu proses selesai** (3-5 menit)

3. **Build assets untuk production**:
   ```bash
   npm run build
   ```
   
   **Atau untuk development**:
   ```bash
   npm run dev
   ```

#### E. Set Folder Permissions (Windows)
1. **Klik kanan folder** `storage` → Properties → Security
2. **Edit permissions** untuk "Everyone" → **Full Control**
3. **Ulangi untuk folder** `bootstrap/cache`

---

## 6. Konfigurasi Database

### 🗄️ Setup Database MySQL

#### A. Buat Database Baru
1. **Pastikan Laragon running** (Apache & MySQL hijau)
2. **Klik tombol "Database"** di Laragon
3. **phpMyAdmin terbuka** di browser
4. **Klik tab "Databases"** di menu atas
5. **Buat database baru**:
   - **Database name**: `portal_inspektorat`
   - **Collation**: `utf8mb4_unicode_ci` (pilih dari dropdown)
6. **Klik "Create"**
7. **Verifikasi**: Database muncul di sidebar kiri

#### B. Konfigurasi Environment File
1. **Buka file `.env`** dengan Notepad++ atau VS Code
2. **Edit bagian database** dengan nilai berikut:
   ```env
   APP_NAME="Portal Inspektorat Papua Tengah"
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_inspektorat
   DB_USERNAME=root
   DB_PASSWORD=
   
   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   ```
3. **Save file** (Ctrl+S)

#### C. Generate Application Key
1. **Di Terminal Laragon**:
   ```bash
   php artisan key:generate
   ```
2. **Output**: `Application key set successfully.`
3. **Verifikasi**: File `.env` sekarang punya `APP_KEY=base64:...`

#### D. Test Database Connection
1. **Test koneksi database**:
   ```bash
   php artisan tinker
   ```
2. **Di dalam tinker, ketik**:
   ```php
   DB::connection()->getPdo();
   ```
3. **Jika berhasil**: Muncul object PDO
4. **Keluar dari tinker**: `exit` atau Ctrl+C

#### E. Migrasi Database
1. **Jalankan migrasi untuk membuat tabel**:
   ```bash
   php artisan migrate
   ```
2. **Konfirmasi jika ditanya**: `yes`
3. **Tunggu proses selesai** - semua tabel akan dibuat

4. **Jalankan seeders (jika ada)**:
   ```bash
   php artisan db:seed
   ```
   
5. **Atau kombinasi reset + seed**:
   ```bash
   php artisan migrate:fresh --seed
   ```

#### F. Verifikasi di phpMyAdmin
1. **Refresh phpMyAdmin**
2. **Klik database** `portal_inspektorat`
3. **Pastikan tabel-tabel sudah ada**:
   - ✅ users
   - ✅ pengaduans (atau wbs)
   - ✅ info_kantors
   - ✅ web_portals
   - ✅ migrations
   - ✅ dll sesuai project

---

## 7. Menjalankan Aplikasi

### 🌐 Start Application Server

#### A. Metode 1: Artisan Serve (Recommended untuk Development)
1. **Pastikan masih di Terminal Laragon** dalam folder project
2. **Jalankan Laravel development server**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
3. **Output yang benar**:
   ```
   Laravel development server started: http://127.0.0.1:8000
   [Ctrl+C to quit]
   ```
4. **Akses website**: 
   - Buka browser → `http://localhost:8000`
   - Atau → `http://127.0.0.1:8000`

#### B. Metode 2: Virtual Host Laragon (Untuk Production-like)
1. **Stop artisan serve** (Ctrl+C jika sedang jalan)
2. **Klik kanan icon Laragon** di system tray
3. **Apache** → **sites-enabled** → **Buka folder**
4. **Buat file baru**: `portal-inspektorat.conf`
5. **Edit dengan Notepad**, isi:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/laragon/www/portal-inspektorat/public"
       ServerName portal-inspektorat.test
       ServerAlias *.portal-inspektorat.test
       <Directory "C:/laragon/www/portal-inspektorat/public">
           AllowOverride All
           Require all granted
           Options Indexes FollowSymLinks
       </Directory>
   </VirtualHost>
   ```
6. **Save file**
7. **Restart Apache** di Laragon (Stop → Start)
8. **Edit hosts file Windows**:
   - Buka: `C:\Windows\System32\drivers\etc\hosts` (as Administrator)
   - Tambah baris: `127.0.0.1 portal-inspektorat.test`
9. **Akses**: `http://portal-inspektorat.test`

#### C. Build Frontend Assets (Jika menggunakan Vite/Mix)
1. **Untuk development** (watch mode):
   ```bash
   npm run dev
   ```
   
2. **Untuk production** (optimized):
   ```bash
   npm run build
   ```

3. **Jika pakai Vite** dan ada error CORS, edit `vite.config.js`:
   ```javascript
   export default defineConfig({
       server: {
           host: '0.0.0.0',
           port: 5173,
           hmr: {
               host: 'localhost'
           }
       }
   });
   ```

---

## 8. Verifikasi Instalasi

### ✅ Testing dan Checklist

#### A. Test Homepage Loading
1. **Buka browser** (Chrome/Firefox/Edge)
2. **Akses URL**: `http://localhost:8000`
3. **Checklist yang harus berhasil**:
   - [ ] **Homepage loading** tanpa error 500/404
   - [ ] **Logo dan header** terlihat dengan benar
   - [ ] **Menu navigasi** berfungsi
   - [ ] **Berita terbaru** muncul (jika ada data)
   - [ ] **Footer** dengan info kontak tampil
   - [ ] **CSS styling** terapply dengan benar

#### B. Test Admin Panel Access
1. **Akses admin panel**:
   - URL: `http://localhost:8000/admin`
   - Atau: `http://portal-inspektorat.test/admin`
2. **Test login** (cek di database/seeders untuk akun default):
   - Username: `admin@example.com` atau sesuai seeder
   - Password: `password` atau sesuai seeder
3. **Verifikasi dashboard admin**:
   - [ ] **Login berhasil** tanpa error
   - [ ] **Dashboard** terbuka
   - [ ] **Menu CRUD** berfungsi (Create, Read, Update, Delete)
   - [ ] **Form input** bekerja
   - [ ] **Data tersimpan** ke database

#### C. Test Database Connection
1. **Buka phpMyAdmin**: Klik "Database" di Laragon
2. **Cek database** `portal_inspektorat`:
   - [ ] **Semua tabel** ada (users, web_portals, info_kantors, dll)
   - [ ] **Data seeder** tersimpan (jika ada)
   - [ ] **Migration history** di tabel `migrations`
3. **Test CRUD dari aplikasi**:
   - [ ] **Insert data** melalui admin panel
   - [ ] **Update data** berfungsi
   - [ ] **Delete data** berfungsi
   - [ ] **Data konsisten** di database

#### D. Test Development Environment
1. **Cek PHP version**:
   ```bash
   php --version
   ```
   Expected: `PHP 8.3.x`
   
2. **Cek Laravel commands**:
   ```bash
   php artisan --version
   ```
   Expected: `Laravel Framework 12.x`
   
3. **Cek Node.js (jika pakai)**:
   ```bash
   node --version
   npm --version
   ```

4. **Test artisan commands**:
   ```bash
   php artisan route:list
   php artisan config:show
   ```

---

## 9. Troubleshooting

### ❌ Common Errors & Solutions

#### **Error: Laragon tidak bisa start Apache/MySQL**
**Gejala**: Service merah atau tidak mau start
**Solusi**:
1. **Cek port conflict**:
   - Apache port 80: Matikan IIS, Skype, atau app lain
   - MySQL port 3306: Matikan XAMPP/WAMP jika ada
2. **Run as Administrator**: Klik kanan Laragon → Run as Administrator
3. **Restart Windows** dan coba lagi

#### **Error: "Class not found" atau Autoload**
**Gejala**: `Class 'App\Models\User' not found`
**Solusi**:
```bash
composer dump-autoload
php artisan clear-compiled
php artisan config:clear
```

#### **Error: "Permission denied" di Windows**
**Gejala**: Tidak bisa write ke storage/logs
**Solusi**:
1. **Klik kanan folder** `storage` → Properties → Security
2. **Edit** → Add "Everyone" → Full Control
3. **Ulangi untuk** `bootstrap/cache`

#### **Error: Database connection refused**
**Gejala**: `SQLSTATE[HY000] [2002] Connection refused`
**Solusi**:
1. **Pastikan MySQL running** di Laragon (hijau)
2. **Cek .env file**:
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. **Test manual**:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

#### **Error: "Mixed content" atau HTTPS issues**
**Gejala**: Assets tidak load, HTTPS/HTTP mixed
**Solusi**:
1. **Edit .env**:
   ```env
   APP_URL=http://localhost:8000
   ```
2. **Clear config**:
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

#### **Error: Port 8000 sudah digunakan**
**Gejala**: `Address already in use`
**Solusi**:
```bash
# Gunakan port lain
php artisan serve --port=8080
# Atau cek process yang pakai port 8000
netstat -ano | findstr :8000
```

#### **Error: Composer tidak ditemukan**
**Gejala**: `'composer' is not recognized`
**Solusi**:
1. **Download Composer**: `https://getcomposer.org/Composer-Setup.exe`
2. **Install dengan setup**
3. **Restart Command Prompt**
4. **Test**: `composer --version`

#### **Error: Node.js command not found**
**Gejala**: `'node' is not recognized`
**Solusi**:
1. **Restart Command Prompt** setelah install Node.js
2. **Cek PATH environment variable**
3. **Re-install Node.js** jika perlu

#### **Error: Laravel Mix/Vite build gagal**
**Gejala**: `npm run dev` error
**Solusi**:
```bash
# Delete node_modules dan reinstall
rm -rf node_modules
rm package-lock.json
npm install

# Clear npm cache
npm cache clean --force
```

### 🆘 Bantuan Advanced:

#### **Debugging Commands:**
```bash
# Lihat log errors
php artisan log:clear
tail -f storage/logs/laravel.log

# Cek konfigurasi
php artisan config:show
php artisan route:list
php artisan about

# Reset database
php artisan migrate:fresh --seed

# Clear semua cache
php artisan optimize:clear
```

#### **Environment Debugging:**
```bash
# Cek PHP info
php -i | findstr "Configuration File"

# Cek loaded extensions
php -m

# Cek composer dependencies
composer show

# Cek npm packages
npm list
```

#### **File Permissions (Windows)**
Jika masih ada masalah permissions:
1. **Disable UAC** sementara
2. **Run Command Prompt as Administrator**
3. **Set full permissions**:
   ```cmd
   icacls "C:\laragon\www\portal-inspektorat\storage" /grant Everyone:F /T
   icacls "C:\laragon\www\portal-inspektorat\bootstrap\cache" /grant Everyone:F /T
   ```

---

## 📞 Panduan Support via Telepon/Remote

### 📋 Checklist Instalasi untuk Support Remote

#### **Pre-Installation Checklist:**
- [ ] **Windows 10/11** (64-bit) terinstall
- [ ] **Admin access** tersedia
- [ ] **Internet connection** stabil (min 10 Mbps)
- [ ] **Antivirus** di-disable sementara
- [ ] **Free space** minimal 5GB di drive C:

#### **Step-by-Step Phone Support:**

##### **TAHAP 1: Download & Install Laragon (15 menit)**
```
Support: "Buka browser, ketik laragon.org"
User: "Sudah"
Support: "Klik Download, pilih Laragon Full"
User: "File 180MB ya?"
Support: "Benar. Download dulu, kasih tau kalau selesai"
[tunggu download]
Support: "Klik kanan file installer, Run as Administrator"
Support: "Ikuti wizard, semua default aja, jangan ubah path"
```

##### **TAHAP 2: Install PHP 8.3 (10 menit)**
```
Support: "Buka Laragon, jangan Start dulu"
Support: "Klik Menu > Tools > Quick add > PHP"
Support: "Cari PHP 8.3, download"
Support: "Kalau susah, buka windows.php.net/downloads"
Support: "Download PHP 8.3 Thread Safe 64-bit"
```

##### **TAHAP 3: Install Node.js (10 menit)**
```
Support: "Buka nodejs.org"
Support: "Download LTS version - yang hijau"
Support: "Install dengan semua default"
Support: "Restart komputer setelah selesai"
```

##### **TAHAP 4: Setup Project (20 menit)**
```
Support: "Copy folder project ke C:\laragon\www\"
Support: "Rename jadi portal-inspektorat"
Support: "Buka Laragon, Start All"
Support: "Apache dan MySQL harus hijau"
Support: "Klik Menu > Tools > Terminal"
Support: "Ketik: cd portal-inspektorat"
Support: "Ketik: composer install"
```

##### **TAHAP 5: Database Setup (10 menit)**
```
Support: "Klik Database di Laragon"
Support: "phpMyAdmin terbuka di browser"
Support: "Tab Databases > Create database"
Support: "Nama: portal_inspektorat"
Support: "Collation: utf8mb4_unicode_ci"
```

##### **TAHAP 6: Final Config (10 menit)**
```
Support: "Di terminal, ketik: copy .env.example .env"
Support: "Edit file .env dengan notepad"
Support: "Ganti DB_DATABASE=portal_inspektorat"
Support: "Ketik: php artisan key:generate"
Support: "Ketik: php artisan migrate"
```

##### **TAHAP 7: Test (5 menit)**
```
Support: "Ketik: php artisan serve"
Support: "Buka browser: localhost:8000"
Support: "Website harus muncul tanpa error"
```

### 🗣️ Skrip Komunikasi Support:

#### **Opening:**
*"Halo, saya akan bantu install Portal Inspektorat. Estimasi 1-1.5 jam. Pastikan koneksi internet stabil dan komputer tidak dipakai aplikasi lain. Siap mulai?"*

#### **Progress Checking:**
```
"Apakah Laragon sudah muncul icon di desktop?"
"Bisa lihat tombol Start All di Laragon?"
"Apache dan MySQL sudah hijau belum?"
"Ada error merah di terminal tidak?"
"Website bisa dibuka di localhost:8000?"
```

#### **Error Handling:**
```
Error 500: "Coba ketik: php artisan config:clear"
Error Connection: "Cek MySQL hijau di Laragon"
Permission Error: "Klik kanan folder storage > Properties > Security"
Port Error: "Ketik: php artisan serve --port=8080"
```

---

## 🎯 Final Success Checklist

### ✅ INSTALASI BERHASIL - FINAL CHECKLIST

#### **System Requirements Met:**
- [ ] **Laragon** terinstall dan running
- [ ] **PHP 8.3** aktif dan configured
- [ ] **Node.js LTS** terinstall dan accessible
- [ ] **MySQL** service running di Laragon
- [ ] **Apache** service running di Laragon

#### **Project Setup Completed:**
- [ ] **Project files** di `C:\laragon\www\portal-inspektorat\`
- [ ] **Composer dependencies** installed (`vendor/` folder ada)
- [ ] **Node.js dependencies** installed (`node_modules/` folder ada)
- [ ] **Environment file** `.env` configured properly
- [ ] **Application key** generated

#### **Database Configuration:**
- [ ] **Database** `portal_inspektorat` created in MySQL
- [ ] **Tables migrated** successfully (cek di phpMyAdmin)
- [ ] **Seeder data** loaded (jika ada)
- [ ] **Database connection** tested and working

#### **Application Running:**
- [ ] **Development server** running (`php artisan serve`)
- [ ] **Homepage** accessible di `http://localhost:8000`
- [ ] **Admin panel** accessible di `http://localhost:8000/admin`
- [ ] **No errors** di browser console
- [ ] **CSS/JS assets** loading properly

#### **Functionality Tests:**
- [ ] **User registration/login** working (jika ada)
- [ ] **Admin CRUD operations** working
- [ ] **File uploads** working (jika ada)
- [ ] **Email functionality** configured (jika diperlukan)

---

## 🎉 SELAMAT! INSTALASI SELESAI

**🌐 Portal Inspektorat Papua Tengah** siap digunakan!

### **Default Access URLs:**
- **🏠 Frontend**: `http://localhost:8000`
- **⚙️ Admin Panel**: `http://localhost:8000/admin`
- **🗄️ Database**: phpMyAdmin via tombol "Database" di Laragon
- **📊 Logs**: `storage/logs/laravel.log`

### **Default Login Credentials:**
*(Cek file `database/seeders/` untuk akun default admin)*
- **Email**: `admin@example.com` (atau sesuai seeder)
- **Password**: `password` (atau sesuai seeder)

### **Next Steps - Kustomisasi:**
1. **Ganti logo** dan branding sesuai Inspektorat Papua Tengah
2. **Update konten** info kantor dan kontak
3. **Setup email** untuk notifikasi WBS
4. **Konfigurasi backup** database rutin
5. **Setup SSL certificate** untuk production

### **Maintenance Commands:**
```bash
# Update project dari repository
git pull origin main
composer install
php artisan migrate

# Backup database
php artisan backup:run

# Clear cache saat update
php artisan optimize:clear
```

---

## 📞 Support Lanjutan

**Jika ada masalah setelah instalasi:**
1. **Cek log errors**: `storage/logs/laravel.log`
2. **Restart Laragon** (Stop All → Start All)
3. **Clear cache**: `php artisan optimize:clear`
4. **Hubungi developer** dengan detail error

**Tim IT Inspektorat Papua Tengah**  
*Portal siap digunakan untuk pelayanan publik yang lebih baik! 🏛️*

---

*Panduan ini dirancang untuk instalasi mudah dengan support remote/telepon*  
*Total estimasi waktu: 60-90 menit*
