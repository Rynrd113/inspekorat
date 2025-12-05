#!/bin/bash
# Script to sync storage files from storage/app/public to document root storage folder
# This is needed because hosting document root is public_html instead of public_html/public

echo "Syncing storage files..."

# Sync galeri folder
echo "Syncing galeri..."
mkdir -p storage/galeri
rsync -av --ignore-existing storage/app/public/galeri/ storage/galeri/

# Sync albums folder if exists
if [ -d "storage/app/public/albums" ]; then
    echo "Syncing albums..."
    mkdir -p storage/albums
    rsync -av --ignore-existing storage/app/public/albums/ storage/albums/
fi

# Sync other folders if needed
for folder in storage/app/public/*; do
    if [ -d "$folder" ]; then
        folder_name=$(basename "$folder")
        if [ "$folder_name" != "galeri" ] && [ "$folder_name" != "albums" ]; then
            echo "Syncing $folder_name..."
            mkdir -p "storage/$folder_name"
            rsync -av --ignore-existing "$folder/" "storage/$folder_name/"
        fi
    fi
done

echo "Sync completed!"
