#!/bin/bash

# Script untuk maintenance rutin proyek Laravel
# Portal Inspektorat

echo "🧹 Memulai maintenance rutin..."

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fungsi untuk menampilkan status
show_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

show_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

show_error() {
    echo -e "${RED}❌ $1${NC}"
}

# 1. Cleanup cache
echo "🗑️  Membersihkan cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
show_status "Cache cleared"

# 2. Optimize untuk production (jika diperlukan)
if [ "$1" == "production" ]; then
    echo "🚀 Optimizing untuk production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    show_status "Production optimization completed"
fi

# 3. Cek permission storage
echo "🔒 Mengecek permission storage..."
if [ -d "storage" ]; then
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    show_status "Storage permissions updated"
else
    show_error "Storage directory not found"
fi

# 4. Cleanup file temporary
echo "🧹 Membersihkan file temporary..."
find . -name "*.tmp" -delete 2>/dev/null
find . -name "*.temp" -delete 2>/dev/null
find . -name "*~" -delete 2>/dev/null
rm -f cookies.txt getMessage email role admin 2>/dev/null
show_status "Temporary files cleaned"

# 5. Cek dependencies
echo "📦 Mengecek dependencies..."
if [ -f "composer.json" ]; then
    composer install --no-dev --optimize-autoloader
    show_status "Composer dependencies updated"
fi

if [ -f "package.json" ]; then
    npm ci --production
    show_status "NPM dependencies updated"
fi

# 6. Build assets
echo "🏗️  Building assets..."
if [ -f "vite.config.js" ]; then
    npm run build
    show_status "Assets built successfully"
fi

# 7. Cek database connection
echo "🗄️  Mengecek koneksi database..."
if php artisan migrate:status >/dev/null 2>&1; then
    show_status "Database connection OK"
else
    show_warning "Database connection issue - check .env file"
fi

# 8. Generate report
echo "📊 Generating maintenance report..."
REPORT_FILE="storage/logs/maintenance_$(date +%Y%m%d_%H%M%S).log"
{
    echo "=== MAINTENANCE REPORT ==="
    echo "Date: $(date)"
    echo "User: $(whoami)"
    echo "PHP Version: $(php -v | head -n 1)"
    echo "Laravel Version: $(php artisan --version)"
    echo "Node Version: $(node --version)"
    echo "NPM Version: $(npm --version)"
    echo ""
    echo "=== DISK USAGE ==="
    du -sh storage/
    du -sh vendor/
    du -sh node_modules/
    echo ""
    echo "=== RECENT LOGS ==="
    tail -n 20 storage/logs/laravel.log 2>/dev/null || echo "No recent logs"
} > "$REPORT_FILE"

show_status "Maintenance report saved to: $REPORT_FILE"

echo ""
echo "🎉 Maintenance completed successfully!"
echo "📋 Next steps:"
echo "   1. Review the maintenance report"
echo "   2. Test the application"
echo "   3. Monitor logs for any issues"
