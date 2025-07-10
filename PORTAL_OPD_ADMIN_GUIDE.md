# Portal OPD - User Admin

## User Admin untuk Portal OPD

Berikut adalah credentials untuk login sebagai admin Portal OPD:

### Super Admin
- **Email**: superadmin@inspektorat.id
- **Password**: superadmin123
- **Role**: super_admin
- **Akses**: Semua modul termasuk Portal OPD

### Admin Utama
- **Email**: admin@inspektorat.id
- **Password**: admin123
- **Role**: admin
- **Akses**: Semua modul termasuk Portal OPD

### Admin Portal OPD
- **Email**: admin.opd@inspektorat.id
- **Password**: adminopd123
- **Role**: admin_portal_opd
- **Akses**: Khusus Portal OPD

## Fitur CRUD Portal OPD

### 1. Tambah Data OPD
- **URL**: `/admin/portal-opd/create`
- **Method**: GET (form), POST (submit)
- **Akses**: admin_portal_opd, admin, super_admin

### 2. Lihat Daftar OPD
- **URL**: `/admin/portal-opd`
- **Method**: GET
- **Fitur**: Search, filter status, pagination
- **Akses**: admin_portal_opd, admin, super_admin

### 3. Edit Data OPD
- **URL**: `/admin/portal-opd/{id}/edit`
- **Method**: GET (form), PUT (submit)
- **Akses**: admin_portal_opd, admin, super_admin

### 4. Hapus Data OPD
- **URL**: `/admin/portal-opd/{id}`
- **Method**: DELETE
- **Akses**: admin_portal_opd, admin, super_admin

### 5. Detail Data OPD
- **URL**: `/admin/portal-opd/{id}`
- **Method**: GET
- **Akses**: admin_portal_opd, admin, super_admin

## Masalah yang Sudah Diperbaiki

1. **Tombol Tambah Data**: Mengganti `@can('create', App\Models\PortalOpd::class)` dengan `@if(auth()->user()->hasAnyRole(['admin_portal_opd', 'admin', 'super_admin']))`

2. **Tombol Edit/Hapus**: Mengganti `@can('update', $opd)` dan `@can('delete', $opd)` dengan pengecekan role yang sama

3. **Akses Role**: Menggunakan method `hasAnyRole()` yang sudah tersedia di User model

## Cara Login

1. Buka URL: `http://localhost:8000/admin/login`
2. Masukkan email dan password salah satu admin di atas
3. Klik Login
4. Akses menu Portal OPD
5. Tombol "Tambah OPD" sekarang akan muncul

## Database

- **Tabel**: portal_opds
- **Migration**: 2025_07_07_151415_create_portal_opds_table.php
- **Seeder**: PortalOpdSeeder.php (untuk data contoh)
- **User Admin**: SuperAdminSeeder.php (untuk akun admin)
