#!/bin/bash

# Script untuk reorganisasi file proyek Laravel
# Dibuat untuk Portal Inspektorat

echo "🚀 Memulai reorganisasi file..."

# Membuat direktori jika belum ada
mkdir -p docs/guides
mkdir -p docs/specifications
mkdir -p docs/troubleshooting
mkdir -p scripts/maintenance
mkdir -p config/dev

echo "📁 Memindahkan file dokumentasi..."

# Pindahkan file-file dokumentasi ke folder docs
mv ADMIN_COMPONENT_GUIDE.md docs/guides/ 2>/dev/null || true
mv ADMIN_CONSISTENCY_GUIDE.md docs/guides/ 2>/dev/null || true
mv ADMIN_CONSISTENCY_SUMMARY.md docs/guides/ 2>/dev/null || true
mv ADVANCED_CUSTOMIZATION_GUIDE.md docs/guides/ 2>/dev/null || true
mv FRONTEND_CONSISTENCY_REPORT.md docs/guides/ 2>/dev/null || true
mv FRONTEND_CUSTOMIZATION_GUIDE.md docs/guides/ 2>/dev/null || true
mv PORTAL_OPD_ADMIN_GUIDE.md docs/guides/ 2>/dev/null || true
mv PORTAL_OPD_DOCUMENTATION.md docs/guides/ 2>/dev/null || true

# Pindahkan spesifikasi sistem
mv ARCHITECTURE_IMPLEMENTATION_STATUS.md docs/specifications/ 2>/dev/null || true
mv COMPLETE_SYSTEM_SPECIFICATION.md docs/specifications/ 2>/dev/null || true
mv CONSOLIDATION_SUMMARY.md docs/specifications/ 2>/dev/null || true
mv DATABASE_API_DOCUMENTATION.md docs/specifications/ 2>/dev/null || true
mv MEDIUM_PRIORITY_IMPLEMENTATION.md docs/specifications/ 2>/dev/null || true
mv UPDATE_SISTEM_DOCUMENTATION.md docs/specifications/ 2>/dev/null || true
mv VITE_FIX_SUMMARY.md docs/specifications/ 2>/dev/null || true

# Pindahkan troubleshooting
mv TROUBLESHOOTING_DATABASE.md docs/troubleshooting/ 2>/dev/null || true
mv DEPLOYMENT_MAINTENANCE_GUIDE.md docs/troubleshooting/ 2>/dev/null || true

echo "🔧 Memindahkan script maintenance..."

# Pindahkan script maintenance
mv fix_admin_consistency.sh scripts/maintenance/ 2>/dev/null || true
mv update_admin_consistency.sh scripts/maintenance/ 2>/dev/null || true
mv check_vite_assets.sh scripts/maintenance/ 2>/dev/null || true
mv check_users.php scripts/maintenance/ 2>/dev/null || true

echo "⚙️ Memindahkan file konfigurasi development..."

# Pindahkan file konfigurasi development
mv nginx.conf config/dev/ 2>/dev/null || true

echo "🧹 Membersihkan file temporary..."

# Hapus file temporary dan tidak penting
rm -f cookies.txt 2>/dev/null || true
rm -f .phpunit.result.cache 2>/dev/null || true
rm -f getMessage 2>/dev/null || true
rm -f email 2>/dev/null || true
rm -f role 2>/dev/null || true
rm -f admin 2>/dev/null || true

echo "✅ Reorganisasi selesai!"
echo "📋 Struktur baru:"
echo "   docs/"
echo "   ├── guides/          # Panduan penggunaan"
echo "   ├── specifications/  # Spesifikasi sistem"
echo "   └── troubleshooting/ # Panduan troubleshooting"
echo ""
echo "   scripts/"
echo "   └── maintenance/     # Script maintenance"
echo ""
echo "   config/"
echo "   └── dev/            # Konfigurasi development"
