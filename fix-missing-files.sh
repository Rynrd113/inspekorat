#!/bin/bash

# =========================================
# Fix Missing Files - Logo & Images
# =========================================

echo "🔧 Fixing missing files..."

cd ~/domains/inspektoratpapuatengahprov.id/public_html

# 1. Ensure logo.svg exists in root (symlink from public)
if [ -f "public/logo.svg" ]; then
    ln -sf public/logo.svg logo.svg
    echo "✅ Logo.svg linked"
else
    echo "⚠️  public/logo.svg not found"
fi

# 2. Create storage/berita directory if not exists
mkdir -p storage/app/public/berita
echo "✅ Created storage/app/public/berita"

# 3. Check if berita images exist, if not create placeholder
BERITA_IMAGES=(
    "pengawasan-terintegrasi-full.jpg"
    "rekrutmen-auditor-full.jpg"
    "sosialisasi-spip-full.jpg"
)

for img in "${BERITA_IMAGES[@]}"; do
    if [ ! -f "storage/app/public/berita/$img" ]; then
        # Create a simple placeholder image (1x1 transparent PNG as JPG won't work without imagemagick)
        echo "⚠️  $img not found - using placeholder"
        # Copy from default if exists
        if [ -f "public/images/logo.png" ]; then
            cp public/images/logo.png "storage/app/public/berita/$img"
            echo "✅ Created placeholder: $img"
        fi
    else
        echo "✅ $img exists"
    fi
done

# 4. Ensure storage link is correct
rm -f public/storage
ln -sf ../storage/app/public public/storage
echo "✅ Storage link verified"

# 5. List what we have
echo ""
echo "📁 Storage structure:"
ls -la storage/app/public/
echo ""
ls -la storage/app/public/berita/ 2>/dev/null || echo "⚠️  berita folder empty"

echo ""
echo "✅ Fix completed!"
