@echo off
echo ========================================
echo    Portal Inspektorat Papua Tengah
echo        Quick Setup for ZIP Version
echo ========================================
echo.

echo [1/7] Copying environment file...
copy .env.example .env
if %errorlevel% neq 0 (
    echo ERROR: Gagal copy .env.example
    pause
    exit /b 1
)

echo [2/7] Installing PHP dependencies...
composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERROR: composer install gagal. Pastikan Composer terinstall.
    echo Download dari: https://getcomposer.org/download/
    pause
    exit /b 1
)

echo [3/7] Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo ERROR: Gagal generate key. Pastikan PHP terinstall.
    pause
    exit /b 1
)

echo [4/7] Installing JavaScript dependencies...
npm install
if %errorlevel% neq 0 (
    echo ERROR: npm install gagal. Pastikan Node.js terinstall.
    echo Download dari: https://nodejs.org/
    pause
    exit /b 1
)

echo [5/7] Building assets...
npm run build
if %errorlevel% neq 0 (
    echo ERROR: npm run build gagal.
    pause
    exit /b 1
)

echo [6/7] Setting up storage permissions...
if not exist "storage\logs" mkdir storage\logs
if not exist "bootstrap\cache" mkdir bootstrap\cache

echo [7/7] Clearing cache...
php artisan config:clear
php artisan cache:clear

echo.
echo ========================================
echo        INSTALASI BERHASIL! 🎉
echo ========================================
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
