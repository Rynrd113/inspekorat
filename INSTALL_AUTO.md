# 🚀 Auto Installation Scripts

Portal Inspektorat Papua Tengah dilengkapi dengan script instalasi otomatis untuk mempermudah proses setup.

## 📋 Script yang Tersedia

### 1. `install.sh` (Linux/macOS)
Script Bash untuk sistem Linux dan macOS yang akan:
- ✅ Validasi system requirements (PHP 8.3+, MySQL/MariaDB, Node.js)
- ✅ Auto-detect database engine (MySQL/MariaDB/SQLite)
- ✅ Install dependencies (Composer & NPM)
- ✅ Setup database dan run migrations
- ✅ Configure environment (.env)
- ✅ Build frontend assets
- ✅ Set file permissions

### 2. `install.bat` (Windows)
Script Batch untuk Windows yang akan:
- ✅ Validasi system requirements
- ✅ Compatible dengan Laragon, XAMPP, WAMP
- ✅ Auto-detect MySQL atau fallback ke SQLite
- ✅ Install dependencies dan setup lengkap

## 🎯 Cara Penggunaan

### Linux/macOS
```bash
# Clone repository
git clone https://github.com/Rynrd113/inspekorat.git portal-inspektorat
cd portal-inspektorat

# Jalankan auto installer
chmod +x install.sh
./install.sh
```

### Windows
```cmd
REM Clone repository
git clone https://github.com/Rynrd113/inspekorat.git portal-inspektorat
cd portal-inspektorat

REM Jalankan auto installer
install.bat
```

## 📋 System Requirements

### Minimum Requirements
- **PHP**: 8.3+
- **Database**: MySQL 8.0+ / MariaDB 10.3+ / SQLite 3.8+
- **Node.js**: 18.0+
- **Composer**: 2.0+
- **Memory**: 2GB RAM
- **Storage**: 1GB free space

### PHP Extensions Required
- OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD

## 🔧 Database Configuration

### Auto Detection
Script akan otomatis mendeteksi database engine yang tersedia:

1. **MySQL/MariaDB** (Priority 1)
   - Akan mencoba koneksi ke MySQL
   - Membuat database `portal_inspektorat`
   - Configure `.env` untuk MySQL

2. **SQLite** (Fallback)
   - Jika MySQL tidak tersedia
   - Membuat file `database/database.sqlite`
   - Configure `.env` untuk SQLite

### Manual Database Setup
Jika auto detection gagal, Anda bisa setup manual:

```bash
# Untuk MySQL
mysql -u root -p
CREATE DATABASE portal_inspektorat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Update .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=root
DB_PASSWORD=your_password
```

## ⚠️ Troubleshooting

### Error: "Command not found"
**Solusi**: Install missing software
```bash
# Ubuntu/Debian
sudo apt install php8.3 composer nodejs npm mysql-server

# CentOS/RHEL
sudo yum install php composer nodejs npm mysql-server

# macOS (Homebrew)
brew install php composer node mysql
```

### Error: "Permission denied"
**Solusi**: Set execute permission
```bash
chmod +x install.sh
```

### Error: "Database connection failed"
**Solusi**: 
1. Pastikan MySQL service running
2. Check credentials di `.env`
3. Manual create database

### Error: "Composer install failed"
**Solusi**:
```bash
# Clear composer cache
composer clear-cache
composer install --no-cache
```

### Error: "NPM install failed"
**Solusi**:
```bash
# Clear npm cache
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

## 🎉 Post Installation

Setelah instalasi berhasil:

### 1. Start Development Server
```bash
php artisan serve
# Aplikasi akan berjalan di http://localhost:8000
```

### 2. Admin Panel Access
- **URL**: http://localhost:8000/admin/login
- **Email**: admin@admin.com
- **Password**: password

### 3. Important Security Steps
- ⚠️ **Change default admin password**
- ⚠️ **Review `.env` file for production**
- ⚠️ **Set proper file permissions**
- ⚠️ **Enable HTTPS for production**

## 📚 Related Documentation

- **INSTALL.md** - Manual installation guide
- **DATABASE.md** - Database setup and management
- **DEVELOPER.md** - Development documentation
- **PANDUAN_INSTALASI_LARAGON.md** - Windows Laragon guide

## 🆘 Support

Jika mengalami masalah:
1. Check log files: `storage/logs/laravel.log`
2. Run dengan verbose: `bash -x install.sh`
3. Check system requirements
4. Try manual installation method

---

**Portal Inspektorat Papua Tengah**  
Auto Installation Documentation  
© 2025 Inspektorat Provinsi Papua Tengah
