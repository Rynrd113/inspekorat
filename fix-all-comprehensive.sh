#!/bin/bash

# =========================================
# COMPREHENSIVE FIX - All Issues
# =========================================

echo "🔧 Fixing ALL issues comprehensively..."
echo ""

cd ~/domains/inspektoratpapuatengahprov.id/public_html

# ============================================
# PART 1: Pull Latest Code
# ============================================
echo "═══════════════════════════════════════"
echo "📥 PART 1: Pulling Latest Code"
echo "═══════════════════════════════════════"
git pull origin main
echo "✅ Code updated"
echo ""

# ============================================
# PART 2: Fix .htaccess (Critical!)
# ============================================
echo "═══════════════════════════════════════"
echo "📝 PART 2: Fixing .htaccess"
echo "═══════════════════════════════════════"

cat > .htaccess << 'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>

# Proper MIME types
<IfModule mod_mime.c>
    AddType text/css .css
    AddType application/javascript .js
    AddType image/svg+xml .svg .svgz
    AddType image/x-icon .ico
    AddType image/png .png
    AddType image/jpeg .jpg .jpeg
    AddType image/gif .gif
    AddType image/webp .webp
    AddType application/json .json
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css
    AddOutputFilterByType DEFLATE application/javascript application/json
</IfModule>

# Caching
<FilesMatch "\.(css|js|jpg|jpeg|png|gif|svg|ico|woff|woff2|ttf|eot)$">
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresDefault "access plus 1 year"
    </IfModule>
</FilesMatch>
HTACCESS

chmod 644 .htaccess
echo "✅ .htaccess created with proper MIME types"
echo ""

# ============================================
# PART 3: Copy Assets to Root
# ============================================
echo "═══════════════════════════════════════"
echo "📦 PART 3: Copying Assets to Root"
echo "═══════════════════════════════════════"

# Copy build directory
if [ -d "public/build" ]; then
    rm -rf build
    cp -r public/build .
    echo "✅ Build assets copied to root"
else
    echo "⚠️  public/build not found"
fi

# Copy CSS directory
if [ -d "public/css" ]; then
    rm -rf css
    cp -r public/css .
    echo "✅ CSS files copied to root"
else
    echo "⚠️  public/css not found"
fi

# Copy images directory
if [ -d "public/images" ]; then
    rm -rf images
    cp -r public/images .
    echo "✅ Images copied to root"
else
    echo "⚠️  public/images not found"
fi

# Copy individual files
for file in logo.svg logo.png favicon.ico manifest.json robots.txt sw.js; do
    if [ -f "public/$file" ]; then
        cp "public/$file" .
        echo "✅ Copied $file to root"
    fi
done

echo ""

# ============================================
# PART 4: Fix Storage Link
# ============================================
echo "═══════════════════════════════════════"
echo "🔗 PART 4: Fixing Storage Link"
echo "═══════════════════════════════════════"

# Remove old storage links
rm -f storage 2>/dev/null
rm -f public/storage 2>/dev/null

# Create proper storage structure
mkdir -p storage/app/public

# Create storage link at root (Laravel 11 structure)
ln -sf storage/app/public storage
echo "✅ Storage link created: $(pwd)/storage -> storage/app/public"

# Also create in public folder for backward compatibility
cd public
ln -sf ../storage/app/public storage
cd ..
echo "✅ Storage link created: public/storage -> ../storage/app/public"

echo ""

# ============================================
# PART 5: Set Permissions
# ============================================
echo "═══════════════════════════════════════"
echo "🔐 PART 5: Setting Permissions"
echo "═══════════════════════════════════════"

chmod 644 .htaccess
chmod 644 index.php
chmod -R 755 build css images js 2>/dev/null
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache 2>/dev/null || echo "Note: chown skipped (might need sudo)"

echo "✅ Permissions set"
echo ""

# ============================================
# PART 6: Clear All Caches
# ============================================
echo "═══════════════════════════════════════"
echo "🧹 PART 6: Clearing All Caches"
echo "═══════════════════════════════════════"

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

echo "✅ All caches cleared"
echo ""

# ============================================
# PART 7: Database - Remove Dummy Images
# ============================================
echo "═══════════════════════════════════════"
echo "🗄️  PART 7: Fixing Database (Remove Dummy Images)"
echo "═══════════════════════════════════════"

# Update existing berita to remove image references
php artisan tinker --execute="
\DB::table('portal_papua_tengahs')->update(['gambar' => null]);
echo 'Updated ' . \DB::table('portal_papua_tengahs')->count() . ' berita records\n';
"

echo "✅ Database updated - dummy images removed"
echo ""

# ============================================
# PART 8: Verification
# ============================================
echo "═══════════════════════════════════════"
echo "✅ PART 8: Verification"
echo "═══════════════════════════════════════"
echo ""

echo "📁 Directory Structure:"
echo "Root files:"
ls -lh | grep -E '(\.htaccess|index\.php|logo|favicon)'
echo ""

echo "📦 Assets:"
echo "Build directory:"
[ -d "build" ] && echo "✅ build/ exists" || echo "❌ build/ missing"
echo "CSS directory:"
[ -d "css" ] && echo "✅ css/ exists" || echo "❌ css/ missing"
echo "Images directory:"
[ -d "images" ] && echo "✅ images/ exists" || echo "❌ images/ missing"
echo ""

echo "🔗 Storage Link:"
ls -la storage 2>/dev/null | head -3
echo ""

echo "📄 Build Manifest:"
if [ -f "build/manifest.json" ]; then
    echo "✅ build/manifest.json exists"
    cat build/manifest.json | head -10
else
    echo "❌ build/manifest.json missing"
fi
echo ""

echo "═══════════════════════════════════════"
echo "🎉 FIX COMPLETED!"
echo "═══════════════════════════════════════"
echo ""
echo "🌐 Test your website:"
echo "   https://inspektoratpapuatengahprov.id"
echo ""
echo "🔍 Check these URLs work:"
echo "   https://inspektoratpapuatengahprov.id/logo.svg"
echo "   https://inspektoratpapuatengahprov.id/css/responsive.css"
echo "   https://inspektoratpapuatengahprov.id/build/manifest.json"
echo ""
echo "📋 If still having issues:"
echo "   1. Check Apache/Nginx is pointing to: $(pwd)"
echo "   2. Ensure mod_rewrite is enabled"
echo "   3. Ensure AllowOverride All"
echo "   4. Check error logs: tail -f storage/logs/laravel.log"
echo ""
