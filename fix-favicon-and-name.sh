#!/bin/bash

# =========================================
# Fix Favicon & Update App Name
# =========================================

echo "🔧 Fixing favicon and updating app name..."

# 1. Update .env APP_NAME
echo "📝 Updating APP_NAME in .env..."
sed -i 's|APP_NAME="Portal Inspektorat Papua Tengah"|APP_NAME="Portal Inspektorat Provinsi Papua Tengah"|g' .env

# 2. Ensure favicon exists in public
echo "🖼️  Checking favicon..."
if [ -f "favicon.ico" ] && [ ! -f "public/favicon.ico" ]; then
    cp favicon.ico public/favicon.ico
    echo "✅ Favicon copied to public/"
elif [ -f "public/favicon.ico" ]; then
    echo "✅ Favicon already exists in public/"
else
    echo "⚠️  Warning: favicon.ico not found"
fi

# 3. Clear all caches
echo "🗑️  Clearing caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Verify
echo ""
echo "📊 Verifying configuration..."
php artisan config:show app.name 2>/dev/null || echo "App Name: (check .env)"

echo ""
echo "✅ Fix completed!"
echo "🔄 Refresh your browser with Ctrl+F5"
