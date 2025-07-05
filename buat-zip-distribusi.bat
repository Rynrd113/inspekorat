@echo off
setlocal enabledelayedexpansion

set PROJECT_NAME=portal-inspektorat
set DIST_DIR=dist
set ARCHIVE_NAME=%PROJECT_NAME%-distribusi.zip

echo ==================================================
echo     Membuat ZIP Distribusi Portal Inspektorat
echo ==================================================
echo.

REM Create distribution directory
echo 📁 Membuat folder distribusi...
if exist %DIST_DIR% rmdir /s /q %DIST_DIR%
mkdir %DIST_DIR%\%PROJECT_NAME%

REM Copy essential files
echo 📋 Copying files...

REM Core application files
xcopy /E /I /Q app %DIST_DIR%\%PROJECT_NAME%\app
xcopy /E /I /Q bootstrap %DIST_DIR%\%PROJECT_NAME%\bootstrap
xcopy /E /I /Q config %DIST_DIR%\%PROJECT_NAME%\config
xcopy /E /I /Q database %DIST_DIR%\%PROJECT_NAME%\database
xcopy /E /I /Q public %DIST_DIR%\%PROJECT_NAME%\public
xcopy /E /I /Q resources %DIST_DIR%\%PROJECT_NAME%\resources
xcopy /E /I /Q routes %DIST_DIR%\%PROJECT_NAME%\routes
xcopy /E /I /Q storage %DIST_DIR%\%PROJECT_NAME%\storage

REM Scripts directory
xcopy /E /I /Q scripts %DIST_DIR%\%PROJECT_NAME%\scripts

REM Configuration files
copy /Y artisan %DIST_DIR%\%PROJECT_NAME%\
copy /Y composer.json %DIST_DIR%\%PROJECT_NAME%\
copy /Y composer.lock %DIST_DIR%\%PROJECT_NAME%\
copy /Y package.json %DIST_DIR%\%PROJECT_NAME%\
copy /Y vite.config.js %DIST_DIR%\%PROJECT_NAME%\
copy /Y .env.example %DIST_DIR%\%PROJECT_NAME%\

REM Copy the pre-configured .env for easy setup
copy /Y .env.zip %DIST_DIR%\%PROJECT_NAME%\.env.mysql

REM Documentation - copy entire docs folder
xcopy /E /I /Q docs %DIST_DIR%\%PROJECT_NAME%\docs

REM Main documentation files
copy /Y docs\installation\README_INSTALASI_ZIP.md %DIST_DIR%\%PROJECT_NAME%\README.md

REM Clean up unnecessary files in dist
echo 🧹 Cleaning up...
del /Q %DIST_DIR%\%PROJECT_NAME%\storage\logs\*.log 2>nul
del /Q %DIST_DIR%\%PROJECT_NAME%\bootstrap\cache\*.php 2>nul

REM Ensure storage directories exist
if not exist %DIST_DIR%\%PROJECT_NAME%\storage\app\public mkdir %DIST_DIR%\%PROJECT_NAME%\storage\app\public
if not exist %DIST_DIR%\%PROJECT_NAME%\storage\framework\cache\data mkdir %DIST_DIR%\%PROJECT_NAME%\storage\framework\cache\data
if not exist %DIST_DIR%\%PROJECT_NAME%\storage\framework\sessions mkdir %DIST_DIR%\%PROJECT_NAME%\storage\framework\sessions
if not exist %DIST_DIR%\%PROJECT_NAME%\storage\framework\testing mkdir %DIST_DIR%\%PROJECT_NAME%\storage\framework\testing
if not exist %DIST_DIR%\%PROJECT_NAME%\storage\framework\views mkdir %DIST_DIR%\%PROJECT_NAME%\storage\framework\views
if not exist %DIST_DIR%\%PROJECT_NAME%\storage\logs mkdir %DIST_DIR%\%PROJECT_NAME%\storage\logs

REM Create empty .gitkeep files to preserve directory structure
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\app\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\app\public\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\framework\cache\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\framework\cache\data\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\framework\sessions\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\framework\testing\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\framework\views\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\storage\logs\.gitkeep
echo. > %DIST_DIR%\%PROJECT_NAME%\bootstrap\cache\.gitkeep

REM Create the ZIP file (requires PowerShell for Windows)
echo 📦 Membuat file ZIP...
powershell -command "Compress-Archive -Path '%DIST_DIR%\%PROJECT_NAME%' -DestinationPath '%ARCHIVE_NAME%' -Force"

REM Clean up dist directory
rmdir /s /q %DIST_DIR%

echo.
echo ✅ ZIP distribusi berhasil dibuat!
echo 📁 File: %ARCHIVE_NAME%
for %%A in ("%ARCHIVE_NAME%") do echo 📊 Ukuran: %%~zA bytes
echo.
echo 📋 Yang disertakan:
echo    ✓ Source code lengkap
echo    ✓ Database MySQL siap import
echo    ✓ Script instalasi otomatis (MySQL)
echo    ✓ Panduan instalasi lengkap
echo    ✓ File konfigurasi .env siap pakai
echo    ✓ Dokumentasi terorganisir
echo.
echo 🚀 File siap untuk di-upload ke Google Drive!
echo ==================================================
pause
