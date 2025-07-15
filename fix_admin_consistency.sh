#!/bin/bash

# Script untuk memperbaiki konsistensi tampilan admin
# Mengganti @section('content') dengan @section('main-content')

echo "Memperbaiki konsistensi tampilan admin..."

# Directory admin views
ADMIN_DIR="/home/rynrd/Documents/Project/agent/inspekorat/resources/views/admin"

# Cari file yang menggunakan @section('content') dan ganti ke @section('main-content')
find "$ADMIN_DIR" -name "*.blade.php" -not -path "*/_templates/*" -exec grep -l "@section('content')" {} \; | while read file; do
    echo "Memperbaiki file: $file"
    
    # Backup file
    cp "$file" "$file.backup"
    
    # Ganti @section('content') dengan @section('main-content')
    sed -i "s/@section('content')/@section('main-content')/g" "$file"
    
    # Pastikan ada breadcrumb section jika belum ada
    if ! grep -q "@section('breadcrumb')" "$file"; then
        # Tambahkan breadcrumb section setelah header
        sed -i "/^@section('header'/a\\
\\
@section('breadcrumb')\\
<li><a href=\"{{ route('admin.dashboard') }}\" class=\"text-blue-600 hover:text-blue-800\">Dashboard</a></li>\\
<li><i class=\"fas fa-chevron-right mx-2 text-gray-300\"></i></li>\\
<li class=\"text-gray-600\">Current Page</li>\\
@endsection" "$file"
    fi
done

echo "✅ Perbaikan konsistensi selesai!"
echo "File backup tersimpan dengan ekstensi .backup"

# Tampilkan file yang sudah diperbaiki
echo ""
echo "File yang diperbaiki:"
find "$ADMIN_DIR" -name "*.blade.php.backup" -exec basename {} .backup \;
