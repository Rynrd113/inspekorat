# 📦 Panduan Instalasi Portal Inspektorat Papua Tengah
## **(Dari File ZIP via Google Drive)**

---

## 🎯 Langkah-Langkah Instalasi

### 1. **Persiapan Sistem**

#### Persyaratan yang Diperlukan:
- **PHP 8.2+** ([Download XAMPP](https://www.apachefriends.org/download.html) atau [Laravel Herd](https://herd.laravel.com/))
- **Composer** ([Download](https://getcomposer.org/download/))
- **Node.js 18+** ([Download](https://nodejs.org/))
- **phpMyAdmin** atau **HeidiSQL** (untuk import database)

#### Untuk Windows (Mudah):
1. **Install XAMPP** - Sudah include PHP, MySQL, phpMyAdmin
2. **Install Composer** 
3. **Install Node.js**

#### Untuk Linux/macOS:
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd
sudo apt install mysql-server composer nodejs npm

# macOS (dengan Homebrew)
brew install php@8.2 composer node mysql
```

---

### 2. **Download dan Extract Project**

1. **Download** file ZIP dari Google Drive
2. **Extract** ke folder web server:
   - **XAMPP**: `C:\xampp\htdocs\portal-inspektorat\`
   - **Laravel Herd**: `~/Herd/portal-inspektorat/`
   - **Linux**: `/var/www/html/portal-inspektorat/`

---

### 3. **Setup Database MySQL**

#### Menggunakan XAMPP (Windows - Paling Mudah)
1. Start **XAMPP Control Panel**
2. Start **Apache** dan **MySQL**
3. Buka **phpMyAdmin** (http://localhost/phpmyadmin)
4. **Create Database**: `portal_inspektorat`
5. **Import** file `database/portal_inspektorat_mysql.sql`

#### Menggunakan HeidiSQL (Windows)
1. Buka **HeidiSQL**
2. **Connect** ke MySQL server
3. **Create Database**: `portal_inspektorat`
4. **Import** file `database/portal_inspektorat_mysql.sql`

#### Command Line (Linux/macOS)
```bash
# Buat database
mysql -u root -p -e "CREATE DATABASE portal_inspektorat;"

# Import data
mysql -u root -p portal_inspektorat < database/portal_inspektorat_mysql.sql
```

---

### 4. **Konfigurasi Project**

#### 4.1 Copy Environment File
```bash
# Buka terminal/command prompt di folder project
cd portal-inspektorat

# Windows
copy .env.example .env

# Linux/macOS
cp .env.example .env
```

#### 4.2 Edit File `.env`

**Konfigurasi MySQL:**
```env
APP_NAME="Portal Inspektorat Papua Tengah"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=root
DB_PASSWORD=

# Jika MySQL Anda memiliki password, isi DB_PASSWORD
# Contoh: DB_PASSWORD=your_password
```

---

### 5. **Install Dependencies & Setup**

Buka **terminal/command prompt** di folder project dan jalankan:

```bash
# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Install JavaScript dependencies
npm install

# Build assets
npm run build

# (Opsional) Jika menggunakan MySQL dan ingin reset database
php artisan migrate:fresh --seed
```

---

### 6. **Menjalankan Aplikasi**

```bash
# Start Laravel development server
php artisan serve

# Atau jika ingin akses dari network lain
php artisan serve --host=0.0.0.0 --port=8000
```

**Aplikasi akan berjalan di:** http://localhost:8000

---

### 7. **Login Admin**

#### Kredensial Default:
- **Email**: `admin@inspektorat.go.id`
- **Password**: `admin123`

#### Akses Admin Panel:
- **URL**: http://localhost:8000/admin
- **Dashboard**: http://localhost:8000/admin/dashboard

---

## 🔧 Troubleshooting

### Masalah Umum & Solusi:

#### 1. **Error "composer command not found"**
```bash
# Download dan install Composer dari getcomposer.org
# Atau cek PATH environment variable
```

#### 2. **Error "php command not found"**
```bash
# Pastikan PHP sudah terinstall dan ada di PATH
# Untuk XAMPP, tambahkan C:\xampp\php ke PATH
```

#### 3. **Error Database Connection**
```bash
# Pastikan MySQL running (XAMPP Control Panel)
# Cek kredensial database di file .env
# Pastikan database sudah dibuat
```

#### 4. **Permission Error (Linux/macOS)**
```bash
# Set permission yang benar
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 5. **Error "NPM not found"**
```bash
# Install Node.js dari nodejs.org
# Restart terminal setelah install
```

#### 6. **Error CSS/JS tidak muncul**
```bash
# Build ulang assets
npm run build

# Atau untuk development
npm run dev
```

---

## 📁 Struktur File Database

**File yang disertakan:**
- `database/portal_inspektorat_mysql.sql` - Database MySQL siap import
- `database/portal_inspektorat.sql` - Backup database

**Setup Database:**
- **MySQL**: Import `portal_inspektorat_mysql.sql` ke phpMyAdmin/HeidiSQL
- **Pastikan** database `portal_inspektorat` sudah dibuat sebelum import

---

## 🚀 Quick Setup (Copy-Paste)

**Setelah extract ZIP, jalankan command ini:**

```bash
# Masuk ke folder project
cd portal-inspektorat

# Copy environment file
cp .env.example .env

# Install dependencies
composer install
npm install

# Generate key
php artisan key:generate

# Build assets
npm run build

# Jalankan server
php artisan serve
```

**Done!** Buka http://localhost:8000

---

## 📞 Bantuan

Jika ada masalah:
1. **Cek file log**: `storage/logs/laravel.log`
2. **Restart server**: Stop server (Ctrl+C) lalu `php artisan serve` lagi
3. **Clear cache**: `php artisan cache:clear && php artisan config:clear`

---

## 📋 Checklist Instalasi

- [ ] PHP 8.2+ terinstall
- [ ] Composer terinstall  
- [ ] Node.js terinstall
- [ ] File ZIP sudah di-extract
- [ ] Database setup (MySQL/SQLite)
- [ ] File `.env` sudah dikonfigurasi
- [ ] `composer install` berhasil
- [ ] `npm install` dan `npm run build` berhasil
- [ ] `php artisan key:generate` berhasil
- [ ] Server berjalan dengan `php artisan serve`
- [ ] Aplikasi bisa diakses di browser
- [ ] Login admin berhasil

---

**🎉 Selamat! Portal Inspektorat Papua Tengah siap digunakan!**
