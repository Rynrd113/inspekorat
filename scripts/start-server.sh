#!/bin/bash

echo "========================================"
echo "   Portal Inspektorat Papua Tengah"
echo "        Starting Server..."
echo "========================================"
echo

echo "[INFO] Memulai Laravel development server..."
echo "[INFO] Aplikasi akan berjalan di: http://localhost:8000"
echo "[INFO] Untuk stop server: tekan Ctrl+C"
echo

php artisan serve --host=0.0.0.0 --port=8000
