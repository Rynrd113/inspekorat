# 📋 Update Sistem - Portal Inspektorat Papua Tengah

## 🆕 Fitur Baru yang Telah Ditambahkan

### 1. **Portal OPD (Organisasi Perangkat Daerah)**

#### Fitur Portal OPD:
- **Halaman Publik Portal OPD**: Menampilkan daftar semua OPD dengan informasi lengkap
- **Manajemen Admin Portal OPD**: CRUD operations untuk data OPD
- **Upload Logo dan Banner**: Fitur upload gambar untuk logo dan banner OPD
- **Informasi Lengkap OPD**: Nama, singkatan, alamat, kontak, kepala OPD, visi, misi

#### Files yang Dibuat/Diupdate:
- `app/Models/PortalOpd.php` - Model untuk data OPD
- `app/Http/Controllers/PortalOpdController.php` - Controller untuk halaman publik
- `app/Http/Controllers/Admin/PortalOpdController.php` - Controller untuk admin
- `database/migrations/2025_07_07_151415_create_portal_opds_table.php` - Migration tabel portal_opds
- `database/seeders/PortalOpdSeeder.php` - Sample data OPD
- `resources/views/public/portal-opd/index.blade.php` - Halaman publik Portal OPD
- `resources/views/admin/portal-opd/index.blade.php` - Admin list OPD
- `resources/views/admin/portal-opd/create.blade.php` - Form tambah OPD

### 2. **Sistem Role-Based Access Control (RBAC)**

#### Role yang Tersedia:
1. **User** - User biasa dengan akses terbatas
2. **Admin WBS** - Khusus mengelola Whistleblowing System
3. **Admin Berita** - Khusus mengelola berita dan portal berita
4. **Admin Portal OPD** - Khusus mengelola data Portal OPD
5. **Admin** - Akses ke semua modul admin (kecuali manajemen user)
6. **Super Admin** - Akses penuh termasuk manajemen user

#### Files yang Dibuat/Diupdate:
- `app/Models/User.php` - Ditambahkan methods untuk role management
- `app/Http/Middleware/RoleMiddleware.php` - Middleware untuk proteksi role
- `database/migrations/2025_07_07_152631_update_users_table_role_column.php` - Update kolom role
- `bootstrap/app.php` - Registrasi middleware role

### 3. **Manajemen User (Super Admin Only)**

#### Fitur User Management:
- **CRUD User**: Create, Read, Update, Delete user
- **Role Assignment**: Assign role ke setiap user
- **Search & Filter**: Pencarian berdasarkan nama, email, dan role
- **Proteksi**: Hanya Super Admin yang dapat mengelola user

#### Files yang Dibuat/Diupdate:
- `app/Http/Controllers/Admin/UserController.php` - Controller manajemen user
- `resources/views/admin/users/index.blade.php` - List user
- `resources/views/admin/users/create.blade.php` - Form tambah user
- `database/seeders/SuperAdminSeeder.php` - Sample user dengan berbagai role

### 4. **Update Navigasi dan UI**

#### Perubahan UI:
- **Sidebar Admin**: Ditambahkan menu Portal OPD dan Manajemen User
- **Role-based Menu**: Menu hanya muncul sesuai role user
- **Navigation Publik**: Ditambahkan link Portal OPD di menu utama
- **Role Indicator**: Tampilan role user di sidebar admin

#### Files yang Diupdate:
- `resources/views/layouts/admin.blade.php` - Update sidebar dengan role-based menu
- `resources/views/public/index.blade.php` - Tambah menu Portal OPD

### 5. **Routes dengan Role Protection**

#### Proteksi Route:
```php
// WBS routes - hanya admin_wbs, admin, superadmin
Route::middleware('role:admin_wbs,admin,superadmin')->group(function () {
    Route::resource('wbs', AdminWbsController::class);
});

// Portal Berita routes - hanya admin_berita, admin, superadmin  
Route::middleware('role:admin_berita,admin,superadmin')->group(function () {
    Route::resource('portal-papua-tengah', AdminPortalPapuaTengahController::class);
});

// Portal OPD routes - hanya admin_portal_opd, admin, superadmin
Route::middleware('role:admin_portal_opd,admin,superadmin')->group(function () {
    Route::resource('portal-opd', AdminPortalOpdController::class);
});

// User Management - hanya superadmin
Route::middleware('role:superadmin')->group(function () {
    Route::resource('users', AdminUserController::class);
});
```

#### Files yang Diupdate:
- `routes/web.php` - Update dengan role-based protection

---

## 🔧 Cara Menggunakan Sistem Baru

### 1. **Login dengan Role Berbeda**

**Akun yang Tersedia:**
- **Super Admin**: 
  - Email: `superadmin@inspektorat.id`
  - Password: `superadmin123`
  - Akses: Semua fitur termasuk manajemen user

- **Admin**: 
  - Email: `admin@inspektorat.id`
  - Password: `admin123`
  - Akses: Semua modul admin kecuali manajemen user

- **Admin WBS**: 
  - Email: `admin.wbs@inspektorat.id`
  - Password: `adminwbs123`
  - Akses: Hanya WBS

- **Admin Berita**: 
  - Email: `admin.berita@inspektorat.id`
  - Password: `adminberita123`
  - Akses: Hanya Portal Berita

- **Admin Portal OPD**: 
  - Email: `admin.opd@inspektorat.id`
  - Password: `adminopd123`
  - Akses: Hanya Portal OPD

### 2. **Mengakses Portal OPD**

**Halaman Publik:**
- URL: `/portal-opd`
- Fitur: Melihat daftar OPD, pencarian, detail OPD

**Admin Portal OPD:**
- Login sebagai admin yang memiliki akses Portal OPD
- Menu: Admin Panel → Portal OPD
- Fitur: CRUD OPD, upload logo/banner

### 3. **Manajemen User (Super Admin)**

**Akses:**
- Login sebagai Super Admin
- Menu: Admin Panel → Manajemen User
- Fitur: Tambah user baru, edit role, hapus user

---

## 🔄 Migrasi dan Seeding

### Menjalankan Migration:
```bash
php artisan migrate
```

### Menjalankan Seeder:
```bash
# Buat user dengan berbagai role
php artisan db:seed --class=SuperAdminSeeder

# Buat sample data OPD
php artisan db:seed --class=PortalOpdSeeder
```

---

## 🚀 Testing

### 1. Test Role-Based Access:
1. Login dengan akun admin WBS → Hanya bisa akses WBS
2. Login dengan akun admin berita → Hanya bisa akses Portal Berita
3. Login dengan akun admin OPD → Hanya bisa akses Portal OPD
4. Login dengan Super Admin → Bisa akses semua termasuk manajemen user

### 2. Test Portal OPD:
1. Buka `/portal-opd` di browser → Lihat daftar OPD
2. Login admin → Kelola data OPD
3. Test upload logo dan banner

### 3. Test User Management:
1. Login sebagai Super Admin
2. Masuk menu Manajemen User
3. Test tambah user dengan role berbeda
4. Test edit dan hapus user

---

## 📝 Catatan Penting

1. **Super Admin**: Hanya Super Admin yang dapat mengelola user dan mengubah role
2. **File Upload**: Pastikan direktori `storage/app/public` dapat diakses untuk upload logo/banner
3. **Database**: Kolom role di tabel users telah diubah dari enum ke string untuk fleksibilitas
4. **Security**: Setiap route admin dilindungi middleware role yang sesuai
5. **UI**: Menu admin sidebar hanya menampilkan menu sesuai role user yang login

---

## 🎯 Langkah Selanjutnya

Sistem sudah siap digunakan dengan:
- ✅ Portal OPD lengkap dengan halaman publik dan admin
- ✅ Sistem role-based access control
- ✅ Manajemen user untuk Super Admin
- ✅ Sample data untuk testing
- ✅ UI/UX yang responsif dan modern

Untuk pengembangan lebih lanjut, bisa ditambahkan:
- Fitur audit log untuk tracking aktivitas user
- Dashboard analytics untuk setiap role
- Export/import data OPD
- Integrasi dengan sistem lain
