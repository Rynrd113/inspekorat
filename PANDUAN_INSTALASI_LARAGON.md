# 🏛️ Panduan Instalasi Portal Inspektorat Papua Tengah
## Untuk Windows dengan Laragon (MySQL + PHP 8.3 + HeidiSQL + Node.js)

**⚠️ CATATAN PENTING**: Portal ini menggunakan **MySQL** sebagai database default untuk production. Panduan ini akan setup dengan MySQL.

---

## 📋 Daftar Isi
1. [Instalasi Laragon](#1-instalasi-laragon)
2. [Konfigurasi Laragon](#2-konfigurasi-laragon)
3. [Instalasi Project](#3-instalasi-project)
4. [Konfigurasi Database](#4-konfigurasi-database)
5. [Menjalankan Aplikasi](#5-menjalankan-aplikasi)
6. [Verifikasi Instalasi](#6-verifikasi-instalasi)
7. [Troubleshooting](#7-troubleshooting)

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
2. **Laragon sudah include**:
   - ✅ **PHP 8.3** (sudah built-in)
   - ✅ **Node.js** (sudah built-in)
   - ✅ **Apache** Web Server
   - ✅ **MySQL** Database
   - ✅ **HeidiSQL** Database Manager
3. **Siap untuk Start All** - semua tools sudah tersedia

---

## 2. Konfigurasi Laragon

### 🚀 Setup Laragon Environment

#### A. Start All Services
1. **Buka Laragon**
2. **Start All** (tombol hijau besar) - tunggu hingga semua service menyala
3. **Pastikan status**:
   - ✅ **Apache** (Web Server)
   - ✅ **MySQL** (Database)
   - ✅ **PHP 8.3** aktif (built-in)

#### B. Verifikasi Tools Built-in
1. **PHP 8.3**: Sudah include dengan extensions lengkap
2. **Node.js**: Sudah include untuk frontend assets
3. **HeidiSQL**: Database manager built-in
4. **Composer**: Package manager PHP built-in

#### B. Verifikasi Tools Built-in
1. **PHP 8.3**: Sudah include dengan extensions lengkap
2. **Node.js**: Sudah include untuk frontend assets
3. **HeidiSQL**: Database manager built-in
4. **Composer**: Package manager PHP built-in

#### C. Verifikasi HeidiSQL
1. **Klik tombol "Database"** di Laragon
2. **HeidiSQL** akan terbuka (aplikasi desktop)
3. **Login** tanpa password (default Laragon)
4. **Pastikan bisa akses** database interface

> **📝 Catatan HeidiSQL:**
> - HeidiSQL adalah client database desktop yang lebih powerful dari phpMyAdmin
> - Interface mirip dengan MySQL Workbench tetapi lebih ringan
> - Mendukung multiple database types (MySQL, MariaDB, PostgreSQL, SQLite)
> - Fitur advanced: query builder, data export/import, user management
> - Shortcut penting: F5 (refresh), F9 (execute query), Ctrl+T (new tab)

#### D. Test PHP dan Extensions (Built-in)
1. **Klik Menu** > **Tools** > **Terminal**
2. **Test PHP version**:
   ```powershell
   php --version
   ```
   Output harus: `PHP 8.3.x`
3. **Cek extensions**:
   ```powershell
   php -m
   ```
4. **Extensions sudah include**:
   - ✅ openssl, pdo_mysql, mbstring, tokenizer
   - ✅ xml, ctype, json, bcmath, fileinfo, gd, curl, zip

#### E. Test Composer (Built-in)
1. **Di Terminal Laragon**:
   ```powershell
   composer --version
   ```
2. **Output expected**: `Composer version 2.x.x`

#### F. Test Node.js (Built-in)
1. **Di Terminal Laragon**:
   ```powershell
   node --version
   npm --version
   ```
2. **Output expected**: 
   - Node.js: `v20.x.x` atau `v18.x.x`
   - NPM: `10.x.x` atau `9.x.x`

---

## 3. Instalasi Project

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
   ```powershell
   cd portal-inspektorat
   ```
2. **Copy environment file**:
   ```powershell
   copy .env.example .env
   ```

#### C. Install Composer Dependencies
1. **Pastikan masih di folder project**:
   ```powershell
   pwd
   ```
   Output: `C:\laragon\www\portal-inspektorat`

2. **Install dependencies PHP**:
   ```powershell
   composer install
   ```
   ⏳ **Tunggu proses selesai** (5-10 menit tergantung internet)
   
3. **Jika ada error permissions**, jalankan:
   ```powershell
   composer install --no-scripts
   ```

#### D. Install Node.js Dependencies (Built-in)
1. **Cek apakah ada package.json**:
   ```powershell
   dir package.json
   ```
   
2. **Jika ada, install npm packages**:
   ```powershell
   npm install
   ```
   ⏳ **Tunggu proses selesai** (3-5 menit)

3. **Build assets untuk production**:
   ```powershell
   npm run build
   ```
   
   **Atau untuk development**:
   ```powershell
   npm run dev
   ```

---

## 4. Konfigurasi Database

### 🗄️ Setup Database MySQL

#### A. Buat Database Baru
1. **Pastikan Laragon running** (Apache & MySQL hijau)
2. **Klik tombol "Database"** di Laragon
3. **HeidiSQL terbuka** (aplikasi desktop)
4. **Buat database baru**:
   - **Klik kanan** pada sidebar kiri (session) → **Create new** → **Database**
   - **Database name**: `portal_inspektorat`
   - **Collation**: `utf8mb4_unicode_ci` (pilih dari dropdown)
5. **Klik "OK"**
6. **Verifikasi**: Database muncul di sidebar kiri

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
   ```powershell
   php artisan key:generate
   ```
2. **Output**: `Application key set successfully.`
3. **Verifikasi**: File `.env` sekarang punya `APP_KEY=base64:...`

#### D. Test Database Connection
1. **Test koneksi database**:
   ```powershell
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
   ```powershell
   php artisan migrate
   ```
2. **Konfirmasi jika ditanya**: `yes`
3. **Tunggu proses selesai** - semua tabel akan dibuat

4. **Jalankan seeders (jika ada)**:
   ```powershell
   php artisan db:seed
   ```
   
5. **Atau kombinasi reset + seed**:
   ```powershell
   php artisan migrate:fresh --seed
   ```

#### F. Verifikasi di HeidiSQL
1. **Refresh HeidiSQL** (F5 atau klik refresh button)
2. **Klik database** `portal_inspektorat` di sidebar
3. **Pastikan tabel-tabel sudah ada**:
   - ✅ users
   - ✅ pengaduans (atau wbs)
   - ✅ info_kantors
   - ✅ web_portals
   - ✅ migrations
   - ✅ dll sesuai project

> **💡 Tips HeidiSQL:**
> - **Double-click tabel** untuk melihat data
> - **Tab "Data"** untuk browse records
> - **Tab "Query"** untuk menjalankan SQL custom
> - **Klik kanan tabel** → Export untuk backup data
> - **Tools** → **Export database as SQL** untuk backup lengkap

---

## 5. Menjalankan Aplikasi

### 🌐 Start Application Server

#### A. Metode 1: Artisan Serve (Recommended untuk Development)
1. **Pastikan masih di Terminal Laragon** dalam folder project
2. **Jalankan Laravel development server**:
   ```powershell
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

#### C. Build Frontend Assets (Built-in Node.js)
1. **Untuk development** (watch mode):
   ```powershell
   npm run dev
   ```
   
2. **Untuk production** (optimized):
   ```powershell
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

## 6. Verifikasi Instalasi

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
1. **Buka HeidiSQL**: Klik "Database" di Laragon
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
   ```powershell
   php --version
   ```
   Expected: `PHP 8.3.x`
   
2. **Cek Laravel commands**:
   ```powershell
   php artisan --version
   ```
   Expected: `Laravel Framework 12.x`
   
3. **Cek Node.js (built-in)**:
   ```powershell
   node --version
   npm --version
   ```

4. **Test artisan commands**:
   ```powershell
   php artisan route:list
   php artisan config:show
   ```

---

## 7. Troubleshooting

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

#### **Error: HeidiSQL tidak bisa connect ke database**
**Gejala**: "Can't connect to MySQL server" atau connection timeout
**Solusi**:
1. **Pastikan MySQL running** di Laragon (hijau)
2. **Cek connection settings** di HeidiSQL:
   - Hostname: `127.0.0.1` atau `localhost`
   - Port: `3306`
   - User: `root`
   - Password: `(kosong)`
3. **Test connection** dengan tombol "Open" di HeidiSQL
4. **Restart MySQL service** di Laragon jika perlu

#### **Error: HeidiSQL tidak terbuka dari Laragon**
**Gejala**: Klik "Database" tidak membuka HeidiSQL
**Solusi**:
1. **Buka HeidiSQL manual** dari Start Menu
2. **Create new connection** dengan settings:
   ```
   Network type: MySQL (TCP/IP)
   Hostname/IP: 127.0.0.1
   Port: 3306
   User: root
   Password: (blank)
   ```
3. **Save connection** dengan nama "Laragon Local"

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
Support: "HeidiSQL terbuka - aplikasi desktop"
Support: "Klik kanan di sidebar > Create new > Database"
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
- **🗄️ Database**: HeidiSQL via tombol "Database" di Laragon
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

# Backup database (via Laravel)
php artisan backup:run

# Clear cache saat update
php artisan optimize:clear
```

### **Database Backup dengan HeidiSQL:**
1. **Buka HeidiSQL** → Klik "Database" di Laragon
2. **Klik kanan database** `portal_inspektorat`
3. **Export database as SQL** → Pilih lokasi save
4. **Atau via Tools** → **Export database as SQL** untuk backup lengkap
5. **Untuk restore**: **File** → **Load SQL file** → Pilih file backup

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
