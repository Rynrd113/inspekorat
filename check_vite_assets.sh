#!/bin/bash

# Script to check if Vite assets are loading properly
echo "🔍 Checking Vite Asset Loading Status..."
echo ""

# Check if Vite dev server is running
echo "1. Checking Vite dev server status..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:5173/resources/js/app.js | grep -q "200"; then
    echo "   ✅ Vite dev server is running on port 5173"
    echo "   ✅ app.js is accessible"
else
    echo "   ❌ Vite dev server is not running or app.js is not accessible"
fi

if curl -s -o /dev/null -w "%{http_code}" http://localhost:5173/resources/css/app.css | grep -q "200"; then
    echo "   ✅ app.css is accessible"
else
    echo "   ❌ app.css is not accessible"
fi

if curl -s -o /dev/null -w "%{http_code}" http://localhost:5173/resources/js/admin.js | grep -q "200"; then
    echo "   ✅ admin.js is accessible"
else
    echo "   ❌ admin.js is not accessible"
fi

if curl -s -o /dev/null -w "%{http_code}" http://localhost:5173/resources/css/admin.css | grep -q "200"; then
    echo "   ✅ admin.css is accessible"
else
    echo "   ❌ admin.css is not accessible"
fi

echo ""

# Check if Laravel server is running
echo "2. Checking Laravel server status..."
if curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000 | grep -q "200"; then
    echo "   ✅ Laravel server is running on port 8000"
else
    echo "   ❌ Laravel server is not running"
fi

echo ""

# Check CSP headers
echo "3. Checking Content Security Policy headers..."
CSP_HEADER=$(curl -s -I http://127.0.0.1:8000 | grep -i "content-security-policy")
if echo "$CSP_HEADER" | grep -q "localhost:5173"; then
    echo "   ✅ CSP allows Vite dev server (localhost:5173)"
else
    echo "   ❌ CSP does not allow Vite dev server"
fi

if echo "$CSP_HEADER" | grep -q "fonts.bunny.net"; then
    echo "   ✅ CSP allows external fonts (fonts.bunny.net)"
else
    echo "   ❌ CSP does not allow external fonts"
fi

if echo "$CSP_HEADER" | grep -q "cdnjs.cloudflare.com"; then
    echo "   ✅ CSP allows external CDN (cdnjs.cloudflare.com)"
else
    echo "   ❌ CSP does not allow external CDN"
fi

echo ""

# Check for any console errors in the main page
echo "4. Checking for potential asset loading issues..."
PAGE_RESPONSE=$(curl -s http://127.0.0.1:8000)
if echo "$PAGE_RESPONSE" | grep -q "http://localhost:5173"; then
    echo "   ✅ Page references Vite dev server correctly"
else
    echo "   ❌ Page does not reference Vite dev server"
fi

echo ""
echo "🎉 Asset loading check complete!"
echo ""
echo "If you see any ❌ symbols above, please:"
echo "1. Start Vite dev server: npm run dev"
echo "2. Start Laravel server: php artisan serve"
echo "3. Clear Laravel cache: php artisan cache:clear"
echo ""
echo "To run this script again: bash check_vite_assets.sh"
