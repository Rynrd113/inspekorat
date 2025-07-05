@echo off
echo ========================================
echo    Portal Inspektorat Papua Tengah
echo        Quick Setup for ZIP Version
echo ========================================
echo.

echo [INFO] Script ini akan menginstall Portal Inspektorat dengan MySQL
echo [INFO] Pastikan XAMPP sudah running (Apache + MySQL)
echo.
pause

echo [1/8] Copying environment file...
copy .env.example .env
if %errorlevel% neq 0 (
    echo ERROR: Gagal copy .env.example
    pause
    exit /b 1
)

echo [2/8] Configuring environment for MySQL...
powershell -Command "(gc .env) -replace 'DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql' | Out-File -encoding ASCII .env"
powershell -Command "(gc .env) -replace 'DB_DATABASE=laravel', 'DB_DATABASE=portal_inspektorat' | Out-File -encoding ASCII .env"
powershell -Command "(gc .env) -replace 'APP_NAME=Laravel', 'APP_NAME=\"Portal Inspektorat Papua Tengah\"' | Out-File -encoding ASCII .env"

echo [3/8] Installing PHP dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERROR: composer install gagal. Pastikan Composer terinstall.
    echo Download dari: https://getcomposer.org/download/
    pause
    exit /b 1
)

echo [4/8] Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo ERROR: Gagal generate key. Pastikan PHP terinstall.
    pause
    exit /b 1
)

echo [5/8] Installing JavaScript dependencies...
npm install
if %errorlevel% neq 0 (
    echo ERROR: npm install gagal. Pastikan Node.js terinstall.
    echo Download dari: https://nodejs.org/
    pause
    exit /b 1
)

echo [6/8] Building assets...
npm run build
if %errorlevel% neq 0 (
    echo ERROR: npm run build gagal.
    pause
    exit /b 1
)

echo [7/8] Setting up database...
echo [INFO] Pastikan MySQL sudah running dan database 'portal_inspektorat' sudah dibuat
echo [INFO] Jika belum, buka phpMyAdmin dan buat database 'portal_inspektorat'
echo.
pause

php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Database migration gagal!
    echo.
    echo SOLUSI:
    echo 1. Pastikan XAMPP MySQL sudah running
    echo 2. Buka phpMyAdmin: http://localhost/phpmyadmin
    echo 3. Buat database baru: portal_inspektorat
    echo 4. Import file: database/portal_inspektorat_mysql.sql
    echo 5. Jalankan script ini lagi
    echo.
    pause
    exit /b 1
)

echo [8/8] Setting up storage permissions...
if not exist "storage\logs" mkdir storage\logs
if not exist "bootstrap\cache" mkdir bootstrap\cache

php artisan config:clear
php artisan cache:clear
php artisan storage:link

echo.
echo ========================================
echo        INSTALASI BERHASIL! 🎉
echo ========================================
echo.
echo Database: MySQL (portal_inspektorat)
echo.
echo Untuk menjalankan aplikasi:
echo   php artisan serve
echo.
echo Kemudian buka browser dan akses:
echo   http://localhost:8000
echo.
echo Login Admin:
echo   Email: admin@inspektorat.go.id
echo   Password: admin123
echo.
echo Admin Panel: http://localhost:8000/admin
echo ========================================
echo.
pause
