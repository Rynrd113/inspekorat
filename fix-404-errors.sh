#!/bin/bash

# =========================================
# Fix All 404 Errors - Complete Solution
# =========================================

echo "🔧 Fixing all 404 errors..."

cd ~/domains/inspektoratpapuatengahprov.id/public_html

# 1. Fix logo.svg (create symlink from public to root)
echo ""
echo "📍 Step 1: Fix logo.svg..."
if [ -f "public/logo.svg" ]; then
    ln -sf public/logo.svg logo.svg
    echo "✅ logo.svg linked to root"
else
    echo "⚠️  public/logo.svg not found, copying from public/images/logo.png"
    # Convert logo.png to svg location if needed
    cp public/images/logo.png public/logo.svg 2>/dev/null || echo "❌ No logo files found"
fi

# 2. Remove old storage link and recreate
echo ""
echo "📍 Step 2: Fix storage link..."
rm -f public/storage
ln -sf ../storage/app/public public/storage
echo "✅ Storage link recreated"

# 3. Verify storage/app/public exists
echo ""
echo "📍 Step 3: Verify storage structure..."
mkdir -p storage/app/public/berita
chmod -R 775 storage
echo "✅ Storage directories created"

# 4. Pull latest code (to get seeder without images)
echo ""
echo "📍 Step 4: Pull latest code..."
git pull origin main
echo "✅ Code updated"

# 5. Truncate portal_papua_tengahs table and reseed
echo ""
echo "📍 Step 5: Reset berita data (without images)..."
php artisan db:seed --class=PortalPapuaTengahSeeder --force
echo "✅ Berita reseeded without images"

# 6. Clear all caches
echo ""
echo "📍 Step 6: Clear all caches..."
php artisan optimize:clear
php artisan view:clear
php artisan cache:clear
echo "✅ All caches cleared"

# 7. Verify permissions
echo ""
echo "📍 Step 7: Set permissions..."
chmod -R 775 storage bootstrap/cache
echo "✅ Permissions set"

# 8. Check results
echo ""
echo "========================================"
echo "📊 Verification Results:"
echo "========================================"

echo ""
echo "1. Logo.svg:"
ls -lh logo.svg 2>/dev/null && echo "✅ Found" || echo "❌ Missing"

echo ""
echo "2. Storage link:"
ls -lh public/storage 2>/dev/null && echo "✅ Linked" || echo "❌ Not linked"

echo ""
echo "3. Berita data:"
php artisan tinker --execute="echo 'Total berita: ' . \App\Models\PortalPapuaTengah::count();"

echo ""
echo "========================================"
echo "✅ Fix completed!"
echo "========================================"
echo ""
echo "🌐 Test your site: https://inspektoratpapuatengahprov.id"
echo ""
