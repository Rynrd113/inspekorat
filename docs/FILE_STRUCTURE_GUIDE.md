# 📁 Panduan Struktur File Proyek

## 🎯 Struktur Direktori Utama

```
inspekorat/
├── 📁 app/                    # Aplikasi Laravel
├── 📁 config/                 # Konfigurasi
│   └── 📁 dev/               # Konfigurasi development
├── 📁 database/              # Database & migrations
├── 📁 docs/                  # 📚 Dokumentasi
│   ├── 📁 guides/            # Panduan penggunaan
│   ├── 📁 specifications/    # Spesifikasi sistem
│   └── 📁 troubleshooting/   # Panduan troubleshooting
├── 📁 public/                # File public
├── 📁 resources/             # Resources (CSS, JS, Views)
├── 📁 routes/                # Route definitions
├── 📁 scripts/               # 🔧 Script maintenance
│   └── 📁 maintenance/       # Script maintenance
├── 📁 storage/               # Storage files
├── 📁 tests/                 # Testing files
└── 📁 vendor/                # Dependencies

## 🚀 File Utama Root Directory
- `artisan`              # Laravel CLI
- `composer.json`        # PHP dependencies
- `package.json`         # Node.js dependencies
- `phpunit.xml`          # PHPUnit configuration
- `tailwind.config.js`   # Tailwind CSS config
- `vite.config.js`       # Vite bundler config
- `postcss.config.js`    # PostCSS config
- `.env.example`         # Environment template
- `README.md`            # Dokumentasi utama
- `CHANGELOG.md`         # Changelog
```

## 📖 Panduan Dokumentasi

### 📁 docs/guides/
- **ADMIN_COMPONENT_GUIDE.md** - Panduan komponen admin
- **ADMIN_CONSISTENCY_GUIDE.md** - Panduan konsistensi admin
- **FRONTEND_CUSTOMIZATION_GUIDE.md** - Panduan kustomisasi frontend
- **PORTAL_OPD_ADMIN_GUIDE.md** - Panduan admin Portal OPD

### 📁 docs/specifications/
- **ARCHITECTURE_IMPLEMENTATION_STATUS.md** - Status implementasi arsitektur
- **COMPLETE_SYSTEM_SPECIFICATION.md** - Spesifikasi sistem lengkap
- **DATABASE_API_DOCUMENTATION.md** - Dokumentasi API database
- **MEDIUM_PRIORITY_IMPLEMENTATION.md** - Implementasi prioritas menengah

### 📁 docs/troubleshooting/
- **TROUBLESHOOTING_DATABASE.md** - Troubleshooting database
- **DEPLOYMENT_MAINTENANCE_GUIDE.md** - Panduan maintenance deployment

## 🔧 Script Maintenance

### 📁 scripts/maintenance/
- **fix_admin_consistency.sh** - Perbaikan konsistensi admin
- **update_admin_consistency.sh** - Update konsistensi admin
- **check_vite_assets.sh** - Cek aset Vite
- **check_users.php** - Cek pengguna

### Cara Menjalankan Script:
```bash
# Dari root directory
./scripts/maintenance/script_name.sh

# Atau dengan bash
bash scripts/maintenance/script_name.sh
```

## 📝 Tips Pengelolaan File

### ✅ **Yang Harus Dilakukan:**
1. **Simpan dokumentasi** di folder `docs/` sesuai kategori
2. **Simpan script** di folder `scripts/` sesuai fungsi
3. **Gunakan .gitignore** untuk file temporary
4. **Gunakan nama file yang deskriptif**

### ❌ **Yang Harus Dihindari:**
1. **Jangan simpan** file dokumentasi di root directory
2. **Jangan commit** file temporary (.tmp, .cache, dll)
3. **Jangan buat** folder di root tanpa dokumentasi
4. **Jangan simpan** file konfigurasi development di root

## 🔄 Maintenance Rutin

### Mingguan:
- Jalankan `scripts/maintenance/check_vite_assets.sh`
- Review file log di `storage/logs/`

### Bulanan:
- Update dependencies: `composer update && npm update`
- Cleanup cache: `php artisan cache:clear`
- Backup database

### Sesuai Kebutuhan:
- Jalankan script di `scripts/maintenance/` sesuai masalah yang muncul
- Review dokumentasi di `docs/` dan update jika diperlukan

## 📞 Bantuan

Jika mengalami masalah:
1. Cek `docs/troubleshooting/` untuk panduan troubleshooting
2. Review `CHANGELOG.md` untuk perubahan terbaru
3. Jalankan script maintenance yang sesuai
