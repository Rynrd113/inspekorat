@echo off
REM setup-dev.bat - Development Environment Setup Script for Windows
REM Portal Inspektorat Papua Tengah

echo.
echo 🚀 Setting up development environment for Portal Inspektorat Papua Tengah...
echo.

REM Check if we're in the right directory
if not exist "artisan" (
    echo [ERROR] This script must be run from the Laravel project root directory!
    pause
    exit /b 1
)

REM Check PHP
echo [INFO] Checking PHP version...
php -r "echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;"
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in PATH
    pause
    exit /b 1
)

REM Check PHP version requirement
php -r "exit(version_compare(PHP_VERSION, '8.2.0', '<') ? 1 : 0);"
if %errorlevel% neq 0 (
    echo [ERROR] PHP 8.2 or higher is required
    pause
    exit /b 1
)
echo [SUCCESS] PHP version OK ✓

REM Check Composer
echo [INFO] Checking Composer...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed or not in PATH
    pause
    exit /b 1
)
echo [SUCCESS] Composer OK ✓

REM Check Node.js
echo [INFO] Checking Node.js...
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Node.js is not installed or not in PATH
    pause
    exit /b 1
)
echo [SUCCESS] Node.js OK ✓

REM Check NPM
echo [INFO] Checking NPM...
npm --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] NPM is not installed or not in PATH
    pause
    exit /b 1
)
echo [SUCCESS] NPM OK ✓

REM Install Composer dependencies
echo [INFO] Installing Composer dependencies...
composer install
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install Composer dependencies
    pause
    exit /b 1
)
echo [SUCCESS] Composer dependencies installed ✓

REM Install NPM dependencies
echo [INFO] Installing NPM dependencies...
npm install
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install NPM dependencies
    pause
    exit /b 1
)
echo [SUCCESS] NPM dependencies installed ✓

REM Setup environment file
echo [INFO] Setting up environment file...
if not exist ".env" (
    copy ".env.example" ".env" >nul
    echo [SUCCESS] .env file created from .env.example ✓
) else (
    echo [WARNING] .env file already exists, skipping...
)

REM Generate application key
echo [INFO] Generating application key...
php artisan key:generate
if %errorlevel% neq 0 (
    echo [ERROR] Failed to generate application key
    pause
    exit /b 1
)
echo [SUCCESS] Application key generated ✓

REM Setup database
echo [INFO] Setting up database...
set /p migrate="Do you want to run database migrations? (y/N): "
if /i "%migrate%"=="y" (
    php artisan migrate
    if %errorlevel% equ 0 (
        echo [SUCCESS] Database migrations completed ✓
        
        set /p seed="Do you want to seed the database with sample data? (y/N): "
        if /i "!seed!"=="y" (
            php artisan db:seed
            echo [SUCCESS] Database seeded with sample data ✓
        )
    ) else (
        echo [WARNING] Database migrations failed. Please check your database configuration.
    )
) else (
    echo [WARNING] Skipping database setup. You can run 'php artisan migrate' later.
)

REM Create storage link
echo [INFO] Creating storage link...
php artisan storage:link
if %errorlevel% neq 0 (
    echo [WARNING] Failed to create storage link. You may need to run this as administrator.
) else (
    echo [SUCCESS] Storage link created ✓
)

REM Build frontend assets
echo [INFO] Building frontend assets...
npm run build
if %errorlevel% neq 0 (
    echo [ERROR] Failed to build frontend assets
    pause
    exit /b 1
)
echo [SUCCESS] Frontend assets built ✓

REM Final success message
echo.
echo [SUCCESS] 🎉 Development environment setup completed!
echo.
echo Next steps:
echo 1. Configure your database settings in the .env file
echo 2. Run 'php artisan migrate --seed' if you skipped database setup
echo 3. Start the development server with 'php artisan serve'
echo 4. Start the frontend development server with 'npm run dev'
echo.
echo Access points:
echo - Frontend: http://localhost:8000
echo - Admin Panel: http://localhost:8000/admin
echo   Email: admin@papuatengah.go.id
echo   Password: password
echo.
echo Happy coding! 🚀
echo.
pause
