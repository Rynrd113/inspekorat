@echo off
setlocal enabledelayedexpansion

REM Development Environment Setup Script for Portal Inspektorat Papua Tengah
REM This script automates the setup process for new developers on Windows

echo =====================================
echo   Portal Inspektorat Papua Tengah
echo   Development Setup Script (Windows)
echo =====================================
echo.

REM Configuration
set PROJECT_NAME=Portal Inspektorat Papua Tengah
set PHP_VERSION=8.2
set NODE_VERSION=18
set DB_NAME=portal_inspektorat

REM Check if running as administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [WARNING] Running without administrator privileges. Some operations may fail.
    echo.
)

REM Function to check if command exists
:check_command
where %1 >nul 2>&1
if %errorLevel% neq 0 (
    echo [ERROR] %1 is not installed or not in PATH
    set /a errors+=1
    goto :eof
) else (
    echo [SUCCESS] %1 is available
)
goto :eof

echo [STEP] Checking prerequisites...
set errors=0

REM Check PHP
call :check_command php
if %errorLevel% equ 0 (
    for /f "tokens=2 delims= " %%i in ('php -r "echo PHP_VERSION;"') do set php_version=%%i
    echo [INFO] PHP version: !php_version!
)

REM Check Composer
call :check_command composer

REM Check Node.js
call :check_command node
if %errorLevel% equ 0 (
    for /f "tokens=1" %%i in ('node -v') do set node_version=%%i
    echo [INFO] Node.js version: !node_version!
)

REM Check NPM
call :check_command npm

REM Check MySQL
call :check_command mysql

if %errors% gtr 0 (
    echo.
    echo [ERROR] Please install missing prerequisites:
    echo   - PHP ^>= %PHP_VERSION% (https://www.php.net/downloads)
    echo   - Composer (https://getcomposer.org/)
    echo   - Node.js ^>= %NODE_VERSION% (https://nodejs.org/)
    echo   - MySQL (https://dev.mysql.com/downloads/mysql/)
    echo.
    pause
    exit /b 1
)

echo [SUCCESS] All prerequisites met!
echo.

REM Ask for confirmation
set /p "continue=Continue with setup? (Y/n): "
if /i "!continue!" equ "n" (
    echo Setup cancelled.
    pause
    exit /b 0
)

echo.

REM Check if we're in the right directory
if not exist "composer.json" (
    echo [ERROR] composer.json not found. Are you in the project root?
    pause
    exit /b 1
)

if not exist "package.json" (
    echo [ERROR] package.json not found. Are you in the project root?
    pause
    exit /b 1
)

REM Install PHP dependencies
echo [STEP] Installing PHP dependencies...
composer install --no-dev --optimize-autoloader
if %errorLevel% neq 0 (
    echo [ERROR] Failed to install PHP dependencies
    pause
    exit /b 1
)
echo [SUCCESS] PHP dependencies installed
echo.

REM Install JavaScript dependencies
echo [STEP] Installing JavaScript dependencies...
npm install
if %errorLevel% neq 0 (
    echo [ERROR] Failed to install JavaScript dependencies
    pause
    exit /b 1
)
echo [SUCCESS] JavaScript dependencies installed
echo.

REM Setup environment file
echo [STEP] Setting up environment configuration...
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo [SUCCESS] Environment file created from .env.example
    ) else (
        echo [ERROR] .env.example file not found
        pause
        exit /b 1
    )
) else (
    echo [INFO] Environment file already exists
)

REM Generate application key
php artisan key:generate --ansi
echo [SUCCESS] Application key generated
echo.

REM Setup database
echo [STEP] Setting up database...
set /p "mysql_password=Enter MySQL root password: "

REM Create database
mysql -u root -p%mysql_password% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if %errorLevel% neq 0 (
    echo [ERROR] Failed to create database. Please check your MySQL credentials.
    pause
    exit /b 1
)

echo [SUCCESS] Database '%DB_NAME%' created/verified

REM Get database credentials
set /p "db_user=Enter database username (default: root): "
if "!db_user!" equ "" set db_user=root

set /p "db_password=Enter database password: "

REM Update .env file
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=%DB_NAME%' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_USERNAME=.*', 'DB_USERNAME=%db_user%' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=%db_password%' | Set-Content .env"

echo [SUCCESS] Database configuration updated in .env
echo.

REM Run database migrations
echo [STEP] Running database migrations...
php artisan migrate --force
if %errorLevel% neq 0 (
    echo [ERROR] Migration failed. Please check your database configuration.
    pause
    exit /b 1
)

echo [SUCCESS] Database migrations completed

set /p "seed_db=Do you want to seed the database with sample data? (y/N): "
if /i "!seed_db!" equ "y" (
    php artisan db:seed
    echo [SUCCESS] Database seeded with sample data
)
echo.

REM Build frontend assets
echo [STEP] Building frontend assets...
npm run build
if %errorLevel% neq 0 (
    echo [ERROR] Failed to build frontend assets
    pause
    exit /b 1
)
echo [SUCCESS] Frontend assets built successfully
echo.

REM Setup file permissions and directories
echo [STEP] Setting up directories and permissions...
if not exist "storage\logs" mkdir "storage\logs"
if not exist "storage\framework\cache" mkdir "storage\framework\cache"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"
if not exist "storage\backups" mkdir "storage\backups"

echo [SUCCESS] Directories created
echo.

REM Setup development tools
echo [STEP] Setting up development tools...
REM Install development dependencies
composer install

REM Create batch files for development
echo [STEP] Creating development scripts...

REM Fresh install script
echo @echo off > fresh-install.bat
echo echo 🔄 Fresh installation in progress... >> fresh-install.bat
echo composer install >> fresh-install.bat
echo npm install >> fresh-install.bat
echo copy .env.example .env >> fresh-install.bat
echo php artisan key:generate >> fresh-install.bat
echo php artisan migrate:fresh --seed >> fresh-install.bat
echo npm run build >> fresh-install.bat
echo echo ✅ Fresh installation completed! >> fresh-install.bat
echo pause >> fresh-install.bat

REM Quick start script
echo @echo off > quick-start.bat
echo echo 🚀 Starting development servers... >> quick-start.bat
echo echo 📱 Laravel server will start at: http://localhost:8000 >> quick-start.bat
echo echo 🎨 Vite server will start at: http://localhost:5173 >> quick-start.bat
echo echo. >> quick-start.bat
echo echo Press Ctrl+C to stop servers >> quick-start.bat
echo echo. >> quick-start.bat
echo start "Laravel Server" cmd /c "php artisan serve" >> quick-start.bat
echo start "Vite Dev Server" cmd /c "npm run dev" >> quick-start.bat
echo pause >> quick-start.bat

REM Backup script
echo @echo off > backup-db.bat
echo for /f "tokens=2 delims==" %%%%I in ('wmic os get localdatetime /format:list ^| find "="') do set datetime=%%%%I >> backup-db.bat
echo set timestamp=%%datetime:~0,8%%_%%datetime:~8,6%% >> backup-db.bat
echo set backup_file=backup_%%timestamp%%.sql >> backup-db.bat
echo mysqldump -u root -p %DB_NAME% ^> "storage\backups\%%backup_file%%" >> backup-db.bat
echo echo ✅ Database backed up to storage\backups\%%backup_file%% >> backup-db.bat
echo pause >> backup-db.bat

echo [SUCCESS] Development scripts created
echo.

REM Final setup
echo [STEP] Performing final setup...
php artisan config:clear >nul 2>&1
php artisan route:clear >nul 2>&1
php artisan view:clear >nul 2>&1
composer dump-autoload -o >nul

echo [SUCCESS] Final setup completed
echo.

REM Show completion message
echo =====================================
echo ✅ Development environment setup completed successfully! 🎉
echo =====================================
echo.
echo [INFO] Next steps:
echo   1. Start the development server: quick-start.bat
echo   2. Visit your application: http://localhost:8000
echo.
echo [INFO] Useful scripts:
echo   • Fresh installation: fresh-install.bat
echo   • Start dev servers: quick-start.bat
echo   • Backup database: backup-db.bat
echo.
echo [INFO] Useful commands:
echo   • Run tests: php artisan test
echo   • Clear cache: php artisan config:clear
echo   • Run migrations: php artisan migrate
echo   • Start tinker: php artisan tinker
echo.
echo [INFO] Documentation:
echo   • Developer Guide: DEVELOPER_DOCUMENTATION.md
echo   • Frontend Guide: FRONTEND_CUSTOMIZATION_GUIDE.md
echo   • Database Guide: DATABASE_API_DOCUMENTATION.md
echo   • Deployment Guide: DEPLOYMENT_MAINTENANCE_GUIDE.md
echo.
echo [SUCCESS] Happy coding! 🚀
echo.
pause
