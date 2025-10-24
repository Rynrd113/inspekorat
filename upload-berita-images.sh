#!/bin/bash

# =========================================
# Upload Berita Images to Server
# =========================================

echo "📤 Uploading berita images..."

# Check if images exist locally
LOCAL_PATH="storage/app/public/berita"

if [ ! -d "$LOCAL_PATH" ]; then
    echo "❌ Local berita folder not found: $LOCAL_PATH"
    exit 1
fi

# Upload to server using scp
SERVER="u953792975@145.79.28.5"
PORT="65002"
REMOTE_PATH="~/domains/inspektoratpapuatengahprov.id/public_html/storage/app/public/berita/"

echo "🔄 Uploading from $LOCAL_PATH to server..."

scp -P $PORT -r "$LOCAL_PATH"/* "$SERVER:$REMOTE_PATH"

echo "✅ Upload completed!"
echo ""
echo "🔧 Next steps:"
echo "1. SSH to server: ssh -p $PORT $SERVER"
echo "2. Run: cd ~/domains/inspektoratpapuatengahprov.id/public_html"
echo "3. Verify: ls -la storage/app/public/berita/"
