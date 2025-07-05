@echo off
REM ============================================
REM Portal Inspektorat Papua Tengah
REM Auto Installation Script for Windows
REM ============================================

setlocal enabledelayedexpansion

echo.
echo ============================================
echo 🏛️  Portal Inspektorat Papua Tengah
echo     Auto Installation Script for Windows
echo ============================================
echo.

REM Configuration
set DB_NAME=portal_inspektorat
set DB_USER=root
set DB_PASSWORD=
set APP_URL=http://localhost:8000

echo [STEP] Checking system requirements...

REM Check PHP
php -v >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP 8.3+ first
    pause
    exit /b 1
)

REM Check Composer
composer --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Composer is not installed or not in PATH
    echo Please install Composer first
    pause
    exit /b 1
)

REM Check Node.js
node --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Node.js is not installed or not in PATH
    echo Please install Node.js first
    pause
    exit /b 1
)

REM Check NPM
npm --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] NPM is not installed or not in PATH
    echo Please install NPM first
    pause
    exit /b 1
)

echo [SUCCESS] System requirements check passed

echo [STEP] Installing PHP dependencies...
call composer install --no-interaction --prefer-dist --optimize-autoloader
if errorlevel 1 (
    echo [ERROR] Failed to install PHP dependencies
    pause
    exit /b 1
)
echo [SUCCESS] PHP dependencies installed

echo [STEP] Installing Node.js dependencies...
call npm ci
if errorlevel 1 (
    echo [WARNING] npm ci failed, trying npm install...
    call npm install
    if errorlevel 1 (
        echo [ERROR] Failed to install Node.js dependencies
        pause
        exit /b 1
    )
)
echo [SUCCESS] Node.js dependencies installed

echo [STEP] Setting up environment configuration...
if not exist ".env" (
    copy ".env.example" ".env"
    echo [SUCCESS] Environment file created from template
) else (
    echo [WARNING] Environment file already exists
)

REM Check if MySQL is available (for Laragon/XAMPP environments)
mysql -u %DB_USER% -e "SELECT 1;" >nul 2>&1
if errorlevel 1 (
    echo [WARNING] MySQL not accessible, using SQLite fallback
    
    REM Configure for SQLite
    powershell -Command "(gc .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' -replace 'DB_DATABASE=.*', 'DB_DATABASE=database/database.sqlite' | Out-File -encoding ASCII .env"
    
    REM Create SQLite database file
    if not exist "database" mkdir database
    type nul > database\database.sqlite
    echo [SUCCESS] Environment configured for SQLite
) else (
    echo [SUCCESS] MySQL detected
    
    REM Create database
    echo [STEP] Creating database '%DB_NAME%'...
    mysql -u %DB_USER% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
    if errorlevel 1 (
        echo [ERROR] Failed to create database
        pause
        exit /b 1
    )
    
    REM Configure for MySQL
    powershell -Command "(gc .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql' -replace 'DB_HOST=.*', 'DB_HOST=127.0.0.1' -replace 'DB_PORT=.*', 'DB_PORT=3306' -replace 'DB_DATABASE=.*', 'DB_DATABASE=%DB_NAME%' -replace 'DB_USERNAME=.*', 'DB_USERNAME=%DB_USER%' -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=%DB_PASSWORD%' | Out-File -encoding ASCII .env"
    echo [SUCCESS] Environment configured for MySQL
)

REM Update APP_URL
powershell -Command "(gc .env) -replace 'APP_URL=.*', 'APP_URL=%APP_URL%' | Out-File -encoding ASCII .env"

echo [STEP] Generating application key...
call php artisan key:generate --no-interaction
if errorlevel 1 (
    echo [ERROR] Failed to generate application key
    pause
    exit /b 1
)
echo [SUCCESS] Application key generated

echo [STEP] Testing Laravel database connection...
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Laravel cannot connect to database
    pause
    exit /b 1
)
echo [SUCCESS] Laravel database connection successful

echo [STEP] Running database migrations...
call php artisan migrate --no-interaction --force
if errorlevel 1 (
    echo [ERROR] Database migration failed
    pause
    exit /b 1
)
echo [SUCCESS] Database migrations completed

echo [STEP] Seeding database with sample data...
call php artisan db:seed --no-interaction --force
if errorlevel 1 (
    echo [WARNING] Database seeding failed or no seeders available
) else (
    echo [SUCCESS] Database seeding completed
)

echo [STEP] Creating storage symbolic link...
call php artisan storage:link
if errorlevel 1 (
    echo [WARNING] Failed to create storage link (may already exist)
) else (
    echo [SUCCESS] Storage symbolic link created
)

echo [STEP] Building frontend assets...
call npm run build
if errorlevel 1 (
    echo [WARNING] Production build failed, trying development build...
    call npm run dev
    if errorlevel 1 (
        echo [ERROR] Failed to build frontend assets
        pause
        exit /b 1
    )
)
echo [SUCCESS] Frontend assets built

echo [STEP] Clearing application cache...
call php artisan config:clear >nul 2>&1
call php artisan cache:clear >nul 2>&1
call php artisan view:clear >nul 2>&1
call php artisan route:clear >nul 2>&1
echo [SUCCESS] Application cache cleared

echo [STEP] Running final verification...
php artisan tinker --execute="echo 'Laravel is working';" >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Laravel verification failed
    pause
    exit /b 1
)
echo [SUCCESS] Laravel application is ready

echo.
echo ============================================
echo 🎉 INSTALLATION COMPLETED SUCCESSFULLY! 🎉
echo ============================================
echo.
echo 📋 Installation Summary:
echo   • Application URL: %APP_URL%
echo.
echo 🚀 Next Steps:
echo   1. Start the development server:
echo      php artisan serve
echo.
echo   2. Open your browser and visit:
echo      %APP_URL%
echo.
echo   3. Admin Panel Access:
echo      URL: %APP_URL%/admin/login
echo      Email: admin@admin.com
echo      Password: password
echo.
echo ⚠️  IMPORTANT SECURITY NOTES:
echo   • Change the default admin password immediately
echo   • Review and secure your .env file
echo   • Set proper file permissions for production
echo.
echo ✅ Ready to use Portal Inspektorat Papua Tengah!
echo.
pause
