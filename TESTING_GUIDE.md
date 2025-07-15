# Inspekorat System - Comprehensive Testing Suite

Sistem testing komprehensif untuk aplikasi web Inspektorat yang menguji semua fitur, halaman, dan keamanan sistem secara otomatis.

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Struktur Testing](#struktur-testing)
3. [Instalasi](#instalasi)
4. [Cara Penggunaan](#cara-penggunaan)
5. [Jenis Test](#jenis-test)
6. [Hasil Testing](#hasil-testing)
7. [Troubleshooting](#troubleshooting)

## 🔍 Overview

Sistem testing ini dirancang untuk:
- **Menguji semua fitur CRUD** di admin panel
- **Menguji semua halaman public** dan fungsionalitasnya
- **Menguji autentikasi dan otorisasi** untuk semua role user
- **Menguji API endpoints** (public dan protected)
- **Menguji keamanan** (SQL injection, XSS, CSRF, dll)
- **Menguji performa** dengan load testing
- **Menguji UI/UX** dengan browser testing
- **Mengidentifikasi error** dan bug secara otomatis

## 🏗️ Struktur Testing

```
tests/
├── Feature/              # Feature tests
│   ├── AuthenticationTest.php
│   ├── PublicPagesTest.php
│   ├── WbsModuleTest.php
│   ├── PortalPapuaTengahTest.php
│   ├── RoleBasedAccessTest.php
│   ├── ApiEndpointsTest.php
│   ├── ErrorHandlingTest.php
│   └── PerformanceAndSecurityTest.php
├── Unit/                 # Unit tests
│   └── UserModelTest.php
├── Browser/              # Browser tests
│   └── ComprehensiveBrowserTest.php
database/factories/       # Test data factories
├── WbsFactory.php
└── PortalPapuaTengahFactory.php
```

## 🛠️ Instalasi

### Prerequisites
```bash
# Install PHP dependencies
composer install --dev

# Install Node.js dependencies (jika diperlukan)
npm install

# Install testing tools
sudo apt-get install apache2-utils  # Untuk load testing
```

### Setup Environment
```bash
# Copy .env.example ke .env.testing
cp .env.example .env.testing

# Edit .env.testing untuk testing
echo "DB_CONNECTION=sqlite" >> .env.testing
echo "DB_DATABASE=:memory:" >> .env.testing

# Setup database
php artisan migrate:fresh --env=testing --seed
```

### Optional: Browser Testing
```bash
# Install Laravel Dusk untuk browser testing
composer require --dev laravel/dusk
php artisan dusk:install
```

## 🚀 Cara Penggunaan

### 1. Master Test Automation (Recommended)
Menjalankan semua jenis test sekaligus:
```bash
./master_test_automation.sh
```

### 2. Individual Test Scripts

#### A. Comprehensive Tests (Unit + Feature)
```bash
./run_comprehensive_tests.sh
```

#### B. Load Testing
```bash
# Pastikan server berjalan di http://localhost:8000
php artisan serve
./load_testing.sh
```

#### C. Security Testing
```bash
# Pastikan server berjalan di http://localhost:8000
php artisan serve
./security_testing.sh
```

#### D. Browser Testing
```bash
# Pastikan server berjalan dan Dusk terinstall
php artisan serve
php artisan dusk
```

### 3. Manual PHPUnit Tests
```bash
# Run semua test
vendor/bin/phpunit

# Run specific test
vendor/bin/phpunit --filter=AuthenticationTest

# Run dengan coverage
vendor/bin/phpunit --coverage-html=coverage
```

## 🧪 Jenis Test

### 1. Unit Tests
- **UserModelTest**: Menguji model User dan semua method-nya
- **Factories**: Menguji factory untuk generate test data

### 2. Feature Tests
- **AuthenticationTest**: Login/logout semua user role
- **PublicPagesTest**: Semua halaman public dapat diakses
- **WbsModuleTest**: CRUD operations WBS untuk semua role
- **PortalPapuaTengahTest**: CRUD operations berita/konten
- **RoleBasedAccessTest**: Akses berdasarkan role user
- **ApiEndpointsTest**: Semua API endpoints (public + protected)
- **ErrorHandlingTest**: Error handling dan validation
- **PerformanceAndSecurityTest**: Basic performance dan security

### 3. Browser Tests
- **ComprehensiveBrowserTest**: End-to-end user journey
- **Responsive Design**: Testing di berbagai ukuran layar
- **Form Validation**: Testing validasi form
- **JavaScript Functionality**: Testing interaksi JavaScript

### 4. Load Tests
- **Homepage Load**: Concurrent users ke homepage
- **Public Pages**: Load testing semua halaman public
- **API Endpoints**: Load testing API
- **Form Submissions**: Load testing form submission
- **Database Load**: Testing performa database

### 5. Security Tests
- **SQL Injection**: Testing SQL injection vulnerabilities
- **XSS**: Testing Cross-Site Scripting
- **CSRF**: Testing Cross-Site Request Forgery protection
- **Directory Traversal**: Testing path traversal attacks
- **Authentication Bypass**: Testing akses tanpa autentikasi
- **File Upload Security**: Testing keamanan upload file
- **Information Disclosure**: Testing exposure data sensitif
- **Brute Force Protection**: Testing proteksi brute force
- **HTTP Security Headers**: Testing security headers
- **API Security**: Testing keamanan API endpoints

## 📊 Hasil Testing

### Lokasi Hasil
- **Master Results**: `master-test-results/run_TIMESTAMP/`
- **Individual Results**: `test-results/`, `load-test-results/`, `security-test-results/`

### File Hasil Utama
- **MASTER_TEST_REPORT.md**: Laporan komprehensif dalam format Markdown
- **test_summary.txt**: Ringkasan hasil test
- **coverage/index.html**: Code coverage report
- **Various .log files**: Log detail untuk setiap test

### Interpretasi Hasil

#### ✅ PASSED
- Semua test berjalan dengan sukses
- Tidak ada error atau issue yang ditemukan
- Fitur berfungsi sesuai ekspektasi

#### ❌ FAILED
- Ada test yang gagal
- Perlu review dan perbaikan
- Check log detail untuk informasi error

#### ⏭️ SKIPPED
- Test tidak dijalankan (biasanya karena dependency tidak tersedia)
- Opsional, tidak mempengaruhi fungsionalitas utama

## 🔧 Troubleshooting

### Common Issues

#### 1. Database Connection Error
```bash
# Pastikan database testing setup
touch database/database.sqlite
php artisan migrate:fresh --env=testing
```

#### 2. Server Not Running
```bash
# Start development server
php artisan serve --port=8000
```

#### 3. Memory Limit Error
```bash
# Increase memory limit
php -d memory_limit=2G vendor/bin/phpunit
```

#### 4. Permission Errors
```bash
# Fix file permissions
chmod +x *.sh
sudo chown -R $USER:$USER storage/
```

#### 5. Dependencies Missing
```bash
# Install missing dependencies
composer install --dev
sudo apt-get install apache2-utils curl
```

### Debugging Tips

1. **Check Logs**: Selalu check file .log untuk detail error
2. **Run Individual Tests**: Jalankan test satu per satu untuk isolasi masalah
3. **Use Verbose Mode**: Tambahkan `-v` atau `--verbose` untuk detail output
4. **Check Environment**: Pastikan .env.testing benar
5. **Clear Cache**: Jalankan `php artisan config:clear` sebelum testing

## 📈 Best Practices

### 1. Sebelum Testing
- Pastikan kode terbaru sudah committed
- Backup database production jika testing di environment yang sama
- Pastikan semua dependencies terinstall

### 2. Selama Testing
- Jangan interrupt testing yang sedang berjalan
- Monitor resource usage (CPU, memory)
- Catat semua error yang muncul

### 3. Setelah Testing
- Review semua hasil test
- Prioritas perbaikan berdasarkan severity
- Dokumentasikan issue yang ditemukan
- Rerun test setelah fix

## 🚨 Critical Issues

Test akan menandai sebagai CRITICAL jika:
- **Authentication bypass** ditemukan
- **SQL injection** vulnerability
- **XSS** vulnerability
- **CSRF** protection tidak berfungsi
- **Core functionality** tidak berjalan
- **Database** connection issues

## 📝 Contoh Output

```
===================================================================
                 INSPEKORAT SYSTEM - MASTER TEST AUTOMATION
===================================================================

📊 TEST RESULTS SUMMARY
=======================
Total Test Suites: 5
✅ Passed: 4
❌ Failed: 1
⏭️ Skipped: 0
🎯 Success Rate: 80%

📋 DETAILED RESULTS:
===================
Comprehensive Tests: ✅ PASSED
Browser Tests:       ⏭️ SKIPPED
Load Tests:          ✅ PASSED
Security Tests:      ❌ FAILED
Integration Tests:   ✅ PASSED

📁 RESULTS LOCATION:
====================
Main Report: master-test-results/run_20241215_143022/MASTER_TEST_REPORT.md
Summary: master-test-results/run_20241215_143022/test_summary.txt
All Results: master-test-results/run_20241215_143022/
```

## 🔄 Continuous Integration

Untuk CI/CD pipeline, tambahkan ke `.github/workflows/testing.yml`:

```yaml
name: Comprehensive Testing

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        
    - name: Install dependencies
      run: composer install --dev
      
    - name: Run comprehensive tests
      run: ./run_comprehensive_tests.sh
      
    - name: Upload test results
      uses: actions/upload-artifact@v2
      with:
        name: test-results
        path: test-results/
```

## 🆘 Support

Jika mengalami masalah:
1. Check [Troubleshooting](#troubleshooting) section
2. Review log files di hasil testing
3. Pastikan environment setup benar
4. Check Laravel dan PHP version compatibility

## 📚 Documentation

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)

---

**Happy Testing! 🧪✨**

*Sistem testing ini dirancang untuk memastikan kualitas dan keamanan aplikasi Inspektorat sebelum deployment ke production.*
