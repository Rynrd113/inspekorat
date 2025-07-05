#!/bin/bash

# Script untuk membuat ZIP distribusi Portal Inspektorat Papua Tengah

PROJECT_NAME="portal-inspektorat"
DIST_DIR="dist"
ARCHIVE_NAME="${PROJECT_NAME}-distribusi.zip"

echo "=================================================="
echo "    Membuat ZIP Distribusi Portal Inspektorat"
echo "=================================================="
echo

# Create distribution directory
echo "📁 Membuat folder distribusi..."
rm -rf $DIST_DIR
mkdir -p $DIST_DIR/$PROJECT_NAME

# Copy essential files
echo "📋 Copying files..."

# Core application files
cp -r app/ $DIST_DIR/$PROJECT_NAME/
cp -r bootstrap/ $DIST_DIR/$PROJECT_NAME/
cp -r config/ $DIST_DIR/$PROJECT_NAME/
cp -r database/ $DIST_DIR/$PROJECT_NAME/
cp -r public/ $DIST_DIR/$PROJECT_NAME/
cp -r resources/ $DIST_DIR/$PROJECT_NAME/
cp -r routes/ $DIST_DIR/$PROJECT_NAME/
cp -r storage/ $DIST_DIR/$PROJECT_NAME/

# Scripts directory
cp -r scripts/ $DIST_DIR/$PROJECT_NAME/

# Configuration files
cp artisan $DIST_DIR/$PROJECT_NAME/
cp composer.json $DIST_DIR/$PROJECT_NAME/
cp composer.lock $DIST_DIR/$PROJECT_NAME/
cp package.json $DIST_DIR/$PROJECT_NAME/
cp vite.config.js $DIST_DIR/$PROJECT_NAME/
cp .env.example $DIST_DIR/$PROJECT_NAME/

# Copy the pre-configured .env for easy setup
cp .env.zip $DIST_DIR/$PROJECT_NAME/.env.mysql

# Documentation - copy entire docs folder
cp -r docs/ $DIST_DIR/$PROJECT_NAME/

# Main documentation files
cp docs/installation/README_INSTALASI_ZIP.md $DIST_DIR/$PROJECT_NAME/README.md

# Make shell script executable
chmod +x $DIST_DIR/$PROJECT_NAME/scripts/install-mysql.sh
chmod +x $DIST_DIR/$PROJECT_NAME/scripts/install-sqlite.sh

# Clean up unnecessary files in dist
echo "🧹 Cleaning up..."
find $DIST_DIR/$PROJECT_NAME/storage -name "*.log" -delete 2>/dev/null
rm -rf $DIST_DIR/$PROJECT_NAME/storage/logs/*.log 2>/dev/null
rm -rf $DIST_DIR/$PROJECT_NAME/bootstrap/cache/*.php 2>/dev/null

# Ensure storage directories exist
mkdir -p $DIST_DIR/$PROJECT_NAME/storage/app/public
mkdir -p $DIST_DIR/$PROJECT_NAME/storage/framework/cache/data
mkdir -p $DIST_DIR/$PROJECT_NAME/storage/framework/sessions
mkdir -p $DIST_DIR/$PROJECT_NAME/storage/framework/testing
mkdir -p $DIST_DIR/$PROJECT_NAME/storage/framework/views
mkdir -p $DIST_DIR/$PROJECT_NAME/storage/logs

# Create empty .gitkeep files to preserve directory structure
touch $DIST_DIR/$PROJECT_NAME/storage/app/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/app/public/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/framework/cache/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/framework/cache/data/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/framework/sessions/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/framework/testing/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/framework/views/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/storage/logs/.gitkeep
touch $DIST_DIR/$PROJECT_NAME/bootstrap/cache/.gitkeep

# Create the ZIP file
echo "📦 Membuat file ZIP..."
cd $DIST_DIR
zip -r "../$ARCHIVE_NAME" $PROJECT_NAME/ -x "*.DS_Store*" "*.git*"
cd ..

# Clean up dist directory
rm -rf $DIST_DIR

echo
echo "✅ ZIP distribusi berhasil dibuat!"
echo "📁 File: $ARCHIVE_NAME"
echo "📊 Ukuran: $(du -h "$ARCHIVE_NAME" | cut -f1)"
echo
echo "📋 Yang disertakan:"
echo "   ✓ Source code lengkap"
echo "   ✓ Database MySQL siap import"
echo "   ✓ Script instalasi otomatis (MySQL)"
echo "   ✓ Panduan instalasi lengkap"
echo "   ✓ File konfigurasi .env siap pakai"
echo "   ✓ Dokumentasi terorganisir"
echo
echo "🚀 File siap untuk di-upload ke Google Drive!"
echo "=================================================="
