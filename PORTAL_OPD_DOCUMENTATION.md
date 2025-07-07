# Portal OPD dan Role-Based Access Control

## Fitur yang Ditambahkan

### 1. Portal OPD (Organisasi Perangkat Daerah)
Portal OPD adalah fitur untuk mengelola dan menampilkan informasi Organisasi Perangkat Daerah di Provinsi Papua Tengah.

#### Fitur Portal OPD:
- **Halaman Publik**: Menampilkan daftar OPD untuk masyarakat umum
- **Pencarian**: Fitur pencarian berdasarkan nama OPD, deskripsi, atau kepala OPD
- **Informasi Lengkap**: Nama OPD, kepala OPD, alamat, telepon, email, website, logo
- **Status**: OPD aktif/nonaktif
- **Responsive Design**: Tampilan responsif untuk semua perangkat

#### Admin Portal OPD:
- **CRUD Operations**: Create, Read, Update, Delete data OPD
- **Upload Logo**: Fitur upload logo OPD
- **Status Management**: Mengatur status aktif/nonaktif
- **Audit Trail**: Mencatat siapa yang membuat dan mengupdate data

### 2. Role-Based Access Control (RBAC)
Sistem hak akses berbasis role untuk mengatur akses pengguna ke berbagai fitur admin.

#### Roles yang Tersedia:

1. **Super Admin** (`superadmin`)
   - Akses penuh ke semua fitur
   - Dapat mengelola semua user dan role
   - Dapat mengakses: WBS, Berita, Portal OPD, User Management

2. **Admin** (`admin`)
   - Akses ke semua fitur kecuali user management
   - Dapat mengakses: WBS, Berita, Portal OPD

3. **Admin WBS** (`admin_wbs`)
   - Hanya dapat mengelola data WBS
   - Akses terbatas ke dashboard

4. **Admin Berita** (`admin_berita`)
   - Hanya dapat mengelola portal berita
   - Akses terbatas ke dashboard

5. **Admin Portal OPD** (`admin_portal_opd`)
   - Hanya dapat mengelola data Portal OPD
   - Akses terbatas ke dashboard

6. **User** (`user`)
   - Akses minimal, hanya dashboard (view only)

### 3. User Management (Super Admin Only)
Fitur manajemen user yang hanya dapat diakses oleh Super Admin.

#### Fitur User Management:
- **CRUD Users**: Mengelola semua user dalam sistem
- **Role Assignment**: Menentukan role untuk setiap user
- **Password Management**: Mengatur password user
- **User Statistics**: Statistik jumlah user per role

## Instalasi dan Setup

### 1. Migration
```bash
php artisan migrate
```

### 2. Seeder (User Default)
```bash
php artisan db:seed --class=SuperAdminSeeder
```

### 3. Link Storage (untuk upload logo)
```bash
php artisan storage:link
```

## User Default yang Dibuat

Setelah menjalankan seeder, user berikut akan tersedia:

1. **Super Admin**
   - Email: `superadmin@paputengah.go.id`
   - Password: `password`
   - Role: Super Admin

2. **Admin**
   - Email: `admin@paputengah.go.id`
   - Password: `password`
   - Role: Admin

3. **Admin WBS**
   - Email: `admin-wbs@paputengah.go.id`
   - Password: `password`
   - Role: Admin WBS

4. **Admin Berita**
   - Email: `admin-berita@paputengah.go.id`
   - Password: `password`
   - Role: Admin Berita

5. **Admin Portal OPD**
   - Email: `admin-opd@paputengah.go.id`
   - Password: `password`
   - Role: Admin Portal OPD

## Routes yang Ditambahkan

### Public Routes
- `GET /portal-opd` - Halaman portal OPD untuk publik

### Admin Routes (dengan middleware role)
- `GET|POST /admin/portal-opd` - CRUD Portal OPD (role: admin_portal_opd, admin, superadmin)
- `GET|POST /admin/users` - User Management (role: superadmin)

## Model dan Database

### 1. Model PortalOpd
- **Fields**: nama_opd, deskripsi, alamat, telepon, email, website, logo, kepala_opd, status
- **Relationships**: belongsTo User (creator, updater)
- **Scopes**: active()

### 2. Model User (Updated)
- **New Methods**:
  - `isSuperAdmin()`: Cek apakah user adalah super admin
  - `hasRole($role)`: Cek role spesifik
  - `getRoles()`: Array semua role yang tersedia

### 3. Middleware RoleMiddleware
- Mengatur akses berdasarkan role
- Super admin otomatis memiliki akses ke semua fitur
- Middleware dapat menerima multiple roles

## Tampilan (Views)

### Portal OPD Views
- `public/portal-opd.blade.php` - Halaman publik portal OPD
- `admin/portal-opd/index.blade.php` - Daftar OPD (admin)
- `admin/portal-opd/create.blade.php` - Form tambah OPD
- `admin/portal-opd/edit.blade.php` - Form edit OPD
- `admin/portal-opd/show.blade.php` - Detail OPD

### User Management Views
- `admin/users/index.blade.php` - Daftar user
- `admin/users/create.blade.php` - Form tambah user
- `admin/users/edit.blade.php` - Form edit user
- `admin/users/show.blade.php` - Detail user

### Updated Views
- `layouts/admin.blade.php` - Sidebar dengan menu role-based
- `admin/dashboard.blade.php` - Dashboard dengan quick actions role-based
- `public/index.blade.php` - Tambah menu Portal OPD di navigation

## Keamanan

1. **Role-based Access Control**: Setiap menu admin dilindungi middleware role
2. **Form Validation**: Validasi input pada semua form
3. **File Upload Security**: Validasi tipe dan ukuran file upload
4. **Password Hashing**: Password otomatis di-hash menggunakan bcrypt
5. **CSRF Protection**: Semua form dilindungi CSRF token

## Catatan Pengembangan

1. **Folder Structure**: Mengikuti konvensi Laravel
2. **Naming Convention**: Konsisten menggunakan snake_case untuk database, camelCase untuk PHP
3. **Responsive Design**: Semua halaman menggunakan Tailwind CSS yang responsive
4. **User Experience**: Interface yang user-friendly dengan feedback yang jelas
5. **Error Handling**: Proper error handling dan pesan kesalahan yang informatif

## Testing

Untuk menguji fitur:

1. Login sebagai Super Admin untuk mengakses semua fitur
2. Buat user dengan role berbeda-beda
3. Test akses menu berdasarkan role
4. Test CRUD operations Portal OPD
5. Test halaman publik Portal OPD
