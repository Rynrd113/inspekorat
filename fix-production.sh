#!/bin/bash

# =========================================
# Production Fix Script
# Run this on server after git pull
# =========================================

echo "🔧 Fixing Production Configuration..."

# 1. Update .env
echo "📝 Updating .env file..."
sed -i 's|APP_URL=https://inspektoratpapuatengahprov.id/|APP_URL=https://inspektoratpapuatengahprov.id|g' .env
sed -i 's|LOG_LEVEL=debug|LOG_LEVEL=error|g' .env
sed -i 's|APP_ENV=local|APP_ENV=production|g' .env
sed -i 's|APP_DEBUG=true|APP_DEBUG=false|g' .env

# 2. Clear all cache
echo "🗑️  Clearing cache..."
php artisan optimize:clear
rm -rf bootstrap/cache/*.php

# 3. Rebuild cache
echo "⚡ Building cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 4. Fix permissions
echo "🔐 Fixing permissions..."
chmod -R 775 storage bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;

# 5. Ensure storage link exists
echo "🔗 Checking storage link..."
if [ ! -L "public/storage" ]; then
    rm -rf public/storage
    ln -s ../storage/app/public public/storage
    echo "✅ Storage link created"
else
    echo "✅ Storage link already exists"
fi

# 6. Verify setup
echo ""
echo "📊 Checking status..."
php artisan about

echo ""
echo "✅ Production fix completed!"
echo "🌐 Test your site: https://inspektoratpapuatengahprov.id"
