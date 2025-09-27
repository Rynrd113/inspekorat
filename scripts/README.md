# Scripts Directory

Direktori ini berisi utility scripts untuk maintenance dan operasional aplikasi Inspektorat.

## Structure:

### Root Scripts:
- `reorganize_files.sh` - Script untuk reorganisasi struktur file

### Maintenance Scripts (`maintenance/`):
- `check_users.php` - Script untuk memeriksa data user
- `check_vite_assets.sh` - Script untuk memeriksa asset Vite
- `fix_admin_consistency.sh` - Script perbaikan konsistensi admin
- `routine_maintenance.sh` - Script maintenance rutin
- `update_admin_consistency.sh` - Script update konsistensi admin
- `update_roles.php` - Script untuk update role user

## Usage:

```bash
# Make scripts executable
chmod +x scripts/*.sh
chmod +x scripts/maintenance/*.sh

# Run specific maintenance script
./scripts/maintenance/routine_maintenance.sh

# Check users
php scripts/maintenance/check_users.php
```

## Notes:
- Pastikan memiliki permission yang tepat untuk menjalankan scripts
- Scripts maintenance sebaiknya dijalankan dalam environment yang sesuai
- Backup data sebelum menjalankan scripts yang mengubah database