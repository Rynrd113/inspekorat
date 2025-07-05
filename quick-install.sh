#!/bin/bash

# Portal Inspektorat Papua Tengah - Quick Installation Script
# This script optimizes the installation process for faster setup

echo "🚀 Starting Quick Installation for Portal Inspektorat Papua Tengah..."

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js first."
    exit 1
fi

# Set environment variables for faster installation
export COMPOSER_DISABLE_XDEBUG_WARN=1
export COMPOSER_MEMORY_LIMIT=-1
export COMPOSER_PROCESS_TIMEOUT=0

echo "📦 Installing Composer dependencies (optimized)..."
composer install --optimize-autoloader --prefer-dist --no-dev --no-scripts

echo "📄 Setting up environment file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ .env file created"
fi

echo "🔑 Generating application key..."
php artisan key:generate --force

echo "🗃️ Setting up database..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "✅ SQLite database file created"
fi

echo "📊 Running database migrations..."
php artisan migrate --force

echo "🎯 Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📱 Installing Node.js dependencies..."
npm ci --prefer-offline --no-audit

echo "🎨 Building frontend assets..."
npm run build

echo "🔧 Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo ""
echo "✅ Quick installation completed successfully!"
echo ""
echo "🌐 To start the application, run:"
echo "   php artisan serve"
echo ""
echo "📝 Admin Panel:"
echo "   URL: http://localhost:8000/admin"
echo "   Default credentials are in the documentation"
echo ""
