# Testing Scripts

Direktori ini berisi script-script untuk testing end-to-end aplikasi Inspektorat.

## File Testing:

### 1. `test_e2e.php`
Script testing dasar untuk:
- Koneksi database
- Testing semua models
- CRUD operations
- Validasi data

### 2. `test_api_e2e.php`
Script testing untuk:
- HTTP endpoints
- Form submissions
- API responses
- Route accessibility

### 3. `test_comprehensive_e2e.php`
Script testing comprehensive untuk:
- Controller logic
- Model relationships
- File operations
- Configuration testing
- Queue system

### 4. `final_e2e_report.php`
Script laporan final yang menghasilkan:
- Statistik database
- Status semua fitur
- Frontend accessibility test
- Admin panel security test

## Cara Penggunaan:

```bash
# Testing dasar
php testing/test_e2e.php

# Testing API dan HTTP
php testing/test_api_e2e.php

# Testing comprehensive
php testing/test_comprehensive_e2e.php

# Laporan final
php testing/final_e2e_report.php
```

## Catatan:
- Pastikan server Laravel sedang berjalan (`php artisan serve`)
- Database harus sudah di-migrate
- File testing ini hanya untuk development/testing environment