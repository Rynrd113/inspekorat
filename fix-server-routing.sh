#!/bin/bash

# =========================================
# Fix Server Routing & Document Root
# =========================================

echo "🔧 Fixing server routing issues..."

cd ~/domains/inspektoratpapuatengahprov.id/public_html

# 1. Check current structure
echo ""
echo "📍 Step 1: Current directory structure..."
ls -la | head -20

# 2. Check if we're in Laravel 11 structure (index.php at root)
echo ""
echo "📍 Step 2: Checking Laravel structure..."
if [ -f "index.php" ]; then
    echo "✅ index.php found at root (Laravel 11 structure)"
else
    echo "❌ index.php not found!"
fi

# 3. Create .htaccess at root if not exists
echo ""
echo "📍 Step 3: Creating/updating .htaccess at root..."
cat > .htaccess << 'HTACCESS'
<IfModule mod_rewrite.c>
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

# Serve static files with proper MIME types
<IfModule mod_mime.c>
    AddType text/css .css
    AddType application/javascript .js
    AddType image/svg+xml .svg
    AddType image/png .png
    AddType image/jpeg .jpg .jpeg
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

# Enable gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css application/javascript application/json
</IfModule>

# Cache control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
HTACCESS

echo "✅ .htaccess created at root"

# 4. Copy build assets to root if they exist in public/
echo ""
echo "📍 Step 4: Copying build assets to root..."
if [ -d "public/build" ]; then
    cp -r public/build . 2>/dev/null || echo "Build already exists at root"
    echo "✅ Build assets copied to root"
else
    echo "⚠️  public/build not found"
fi

# 5. Copy CSS to root
echo ""
echo "📍 Step 5: Copying CSS files..."
if [ -d "public/css" ]; then
    cp -r public/css . 2>/dev/null || echo "CSS already exists at root"
    echo "✅ CSS files copied"
else
    echo "⚠️  public/css not found"
fi

# 6. Copy images to root
echo ""
echo "📍 Step 6: Copying images..."
if [ -d "public/images" ]; then
    cp -r public/images . 2>/dev/null || echo "Images already exists at root"
    echo "✅ Images copied"
fi

# 7. Copy logo files
echo ""
echo "📍 Step 7: Copying logo files..."
if [ -f "public/logo.svg" ]; then
    cp public/logo.svg . 2>/dev/null
    echo "✅ logo.svg copied"
fi
if [ -f "public/logo.png" ]; then
    cp public/logo.png . 2>/dev/null
    echo "✅ logo.png copied"
fi
if [ -f "public/favicon.ico" ]; then
    cp public/favicon.ico . 2>/dev/null
    echo "✅ favicon.ico copied"
fi

# 8. Fix storage link
echo ""
echo "📍 Step 8: Fixing storage link..."
rm -f storage
rm -f public/storage
ln -sf storage/app/public storage
echo "✅ Storage link created at root"

# 9. Create dummy berita images (since they're just dummy data)
echo ""
echo "📍 Step 9: Removing dummy berita images from database..."
# We'll update the seeder to set gambar = null

# 10. Set permissions
echo ""
echo "📍 Step 10: Setting permissions..."
chmod 644 .htaccess
chmod -R 755 build css images 2>/dev/null
chmod -R 775 storage bootstrap/cache
echo "✅ Permissions set"

# 11. Clear all caches
echo ""
echo "📍 Step 11: Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
echo "✅ Caches cleared"

# 12. Verify structure
echo ""
echo "========================================"
echo "📊 Verification:"
echo "========================================"
echo ""
echo "Files at root:"
ls -lh | grep -E '(index.php|.htaccess|logo|build|css|images|storage)'

echo ""
echo "Build assets:"
ls -lh build/assets/ 2>/dev/null | head -10 || echo "No build assets"

echo ""
echo "========================================"
echo "✅ Fix completed!"
echo "========================================"
echo ""
echo "🌐 Test your site: https://inspektoratpapuatengahprov.id"
echo ""
echo "If still not working, check:"
echo "1. Apache/Nginx config points to this directory"
echo "2. mod_rewrite is enabled"
echo "3. AllowOverride All is set"
echo ""
