#!/bin/bash
# Hostinger Deployment Script for inspekorat
# Run this on your Hostinger server

set -e

echo "=========================================="
echo "Starting Hostinger deployment..."
echo "=========================================="

cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html

echo ""
echo "1️⃣  Pulling latest changes from GitHub..."
git pull origin main

echo ""
echo "2️⃣  Verifying storage structure..."
ls -la public/storage
echo "✅ Storage symlink verified"

echo ""
echo "3️⃣  Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear

echo ""
echo "4️⃣  Testing new JavaScript build..."
curl -s -o /dev/null -w "Public JS Build: %{http_code}\n" https://inspektorat.papuatengahprov.cloud/build/assets/js/public.CIpbYAly.js

echo ""
echo "5️⃣  Testing storage galeri access..."
curl -s -o /dev/null -w "Storage Galeri: %{http_code}\n" https://inspektorat.papuatengahprov.cloud/storage/galeri/3DRdbOcLVrxgRb9yLf2F27JO4aG2EyAOkhpuKDEQ.jpg

echo ""
echo "6️⃣  Testing hero slider storage..."
curl -s -o /dev/null -w "Hero Slider: %{http_code}\n" https://inspektorat.papuatengahprov.cloud/storage/hero-sliders/wYsk44IDLaXjo5cpm7R3PvLeaxJUC20UGmE2prLa.jpg

echo ""
echo "=========================================="
echo "✅ Deployment completed successfully!"
echo "=========================================="
echo ""
echo "Verify in browser:"
echo "  Homepage: https://inspektorat.papuatengahprov.cloud/"
echo "  Check console for errors"
echo "  Filter buttons should work without 'filterBerita' errors"
echo "  Gallery images should load with HTTP 200"

