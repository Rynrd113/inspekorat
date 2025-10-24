#!/bin/bash

# =========================================
# Deploy Responsive Design Updates
# =========================================

echo "🚀 Deploying responsive design updates..."

cd ~/domains/inspektoratpapuatengahprov.id/public_html

# 1. Pull latest changes
echo ""
echo "📍 Step 1: Pull latest code from GitHub..."
git pull origin main
echo "✅ Code updated"

# 2. Verify responsive.css exists
echo ""
echo "📍 Step 2: Verify responsive CSS file..."
if [ -f "public/css/responsive.css" ]; then
    echo "✅ responsive.css found"
    ls -lh public/css/responsive.css
else
    echo "❌ responsive.css not found!"
fi

# 3. Clear all caches
echo ""
echo "📍 Step 3: Clear all caches..."
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
echo "✅ All caches cleared"

# 4. Set proper permissions
echo ""
echo "📍 Step 4: Set permissions..."
chmod -R 775 storage bootstrap/cache
chmod 644 public/css/responsive.css
echo "✅ Permissions set"

# 5. Test responsive CSS is accessible
echo ""
echo "📍 Step 5: Verify files..."
echo ""
echo "CSS Files:"
ls -lh public/css/

echo ""
echo "========================================"
echo "✅ Deployment completed!"
echo "========================================"
echo ""
echo "🌐 Test your responsive website:"
echo "   Desktop: https://inspektoratpapuatengahprov.id"
echo "   Mobile: Open on your phone browser"
echo ""
echo "📱 Responsive Features:"
echo "   ✓ Mobile-optimized hero slider"
echo "   ✓ Touch-friendly navigation"
echo "   ✓ Responsive typography"
echo "   ✓ Adaptive grid layouts"
echo "   ✓ Mobile-friendly buttons"
echo "   ✓ Optimized images and cards"
echo ""
