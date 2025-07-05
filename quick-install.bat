@echo off
:: Portal Inspektorat Papua Tengah - Quick Installation Script for Windows
:: This script optimizes the installation process for faster setup

echo.
echo 🚀 Starting Quick Installation for Portal Inspektorat Papua Tengah...
echo.

:: Check if composer is installed
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Composer is not installed. Please install Composer first.
    pause
    exit /b 1
)

:: Check if Node.js is installed
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed. Please install Node.js first.
    pause
    exit /b 1
)

:: Set environment variables for faster installation
set COMPOSER_DISABLE_XDEBUG_WARN=1
set COMPOSER_MEMORY_LIMIT=-1
set COMPOSER_PROCESS_TIMEOUT=0

echo 📦 Installing Composer dependencies (optimized)...
composer install --optimize-autoloader --prefer-dist --no-dev --no-scripts

echo.
echo 📄 Setting up environment file...
if not exist .env (
    copy .env.example .env
    echo ✅ .env file created
)

echo.
echo 🔑 Generating application key...
php artisan key:generate --force

echo.
echo 🗃️ Setting up database...
if not exist database\database.sqlite (
    type nul > database\database.sqlite
    echo ✅ SQLite database file created
)

echo.
echo 📊 Running database migrations...
php artisan migrate --force

echo.
echo 🎯 Optimizing application...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo.
echo 📱 Installing Node.js dependencies...
npm ci --prefer-offline --no-audit

echo.
echo 🎨 Building frontend assets...
npm run build

echo.
echo ✅ Quick installation completed successfully!
echo.
echo 🌐 To start the application, run:
echo    php artisan serve
echo.
echo 📝 Admin Panel:
echo    URL: http://localhost:8000/admin
echo    Default credentials are in the documentation
echo.
pause
