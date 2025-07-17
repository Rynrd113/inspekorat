# Portal Inspektorat Papua Tengah

Portal informasi dan layanan publik resmi Inspektorat Provinsi Papua Tengah dengan sistem manajemen konten komprehensif, Portal OPD, Whistleblower System (WBS), dan sistem role-based access control yang lengkap.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com)

## 🚀 Fitur Utama

### 🌐 **Portal Publik**
- **Beranda**: Halaman utama dengan hero section dan informasi terkini
- **Profil Inspektorat**: Visi, misi, sejarah, struktur organisasi lengkap
- **Portal Berita**: Manajemen berita dengan editor rich text, kategori, dan pencarian
- **Portal OPD**: Direktori lengkap Organisasi Perangkat Daerah dengan profil, visi-misi, dan kontak
- **Pelayanan**: Katalog layanan Inspektorat dengan detail prosedur dan persyaratan
- **Dokumen Publik**: Repository dokumen dengan kategorisasi dan sistem download
- **Galeri**: Koleksi foto dan video kegiatan dengan viewer modal interaktif
- **FAQ**: Sistem tanya jawab dengan search dan kategorisasi
- **Whistleblower System (WBS)**: Sistem pelaporan yang aman dan terstruktur
- **Kontak**: Informasi kontak lengkap dengan peta lokasi dan form kontak
- **Statistik Real-time**: Dashboard data terkini OPD, berita, dan laporan WBS
- **Responsive Design**: Optimized untuk desktop, tablet, dan mobile dengan Bootstrap 5

### 🔐 **Admin Panel dengan Role-Based Access Control**
- **Dashboard Dinamis**: Tampilan berdasarkan role dan hak akses user
- **User Management**: Manajemen user dengan sistem role (SuperAdmin eksklusif)
- **Profil Management**: Kelola profil organisasi, visi, misi, dan struktur
- **Pelayanan Management**: CRUD layanan dengan kategorisasi dan status
- **Dokumen Management**: Upload, kategorisasi, dan kontrol akses dokumen
- **Galeri Management**: Upload foto/video dengan thumbnail dan metadata
- **FAQ Management**: Kelola pertanyaan dengan urutan dan status tampil
- **Portal OPD Management**: CRUD lengkap untuk data OPD
- **Portal Papua Tengah**: Manajemen berita dan konten
- **WBS Management**: Kelola laporan whistleblower dengan tracking status
- **Multi-Role Support**: 11 level role dengan hak akses berbeda
- **Performance Monitoring**: Real-time tracking dan optimization
- **Security Features**: RBAC, CSRF protection, input validation

### 👥 **Sistem Role & Permissions**
- **SuperAdmin**: Akses penuh termasuk user management dan system settings
- **Admin**: Akses ke semua modul kecuali user management  
- **Admin Profil**: Khusus mengelola profil organisasi
- **Admin Pelayanan**: Khusus mengelola layanan publik
- **Admin Dokumen**: Khusus mengelola repository dokumen
- **Admin Galeri**: Khusus mengelola galeri foto/video
- **Admin FAQ**: Khusus mengelola sistem tanya jawab
- **Admin Portal OPD**: Khusus mengelola data OPD
- **Admin Berita**: Khusus mengelola berita/konten
- **Admin WBS**: Khusus mengelola laporan WBS
- **User**: Akses terbatas/view only dengan dashboard khusus

## 💻 Teknologi & Arsitektur

- **Backend**: Laravel 12.x dengan PHP 8.2+
- **Frontend**: Bootstrap 5.3 dengan Vite untuk build modern
- **Database**: MySQL dengan optimasi Query Builder dan Eloquent ORM
- **Authentication**: Laravel Sanctum dengan custom role middleware
- **Caching**: Redis/File Cache untuk optimasi performa
- **File Storage**: Laravel Storage dengan validasi keamanan
- **Architecture**: Repository Pattern, Service Layer, Action Classes
- **Security**: RBAC, CSRF, XSS protection, input validation
- **Testing**: PHPUnit untuk unit dan feature testing

## Instalasi

### Requirements

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL/PostgreSQL (atau SQLite untuk development)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Rynrd113/inspekorat.git
   cd inspekorat
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portal_inspektorat
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Migrasi database**
   ```bash
   php artisan migrate --seed
   ```

   Ini akan membuat tabel dan data sample termasuk:
   - **SuperAdmin**: `superadmin@inspektorat.id` / `superadmin123`
   - **Admin untuk semua modul**: Setiap admin memiliki akses sesuai rolenya
   - **Sample Data**: 10 OPD, berita, dokumen, galeri, FAQ, pelayanan, dan data WBS sample

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

Aplikasi akan berjalan di `http://localhost:8000`

### 🔑 Akun untuk Testing

| Role | Email | Password | Akses |
|------|-------|----------|--------|
| **Super Admin** | `superadmin@inspektorat.id` | `superadmin123` | Semua fitur + User Management |
| **Admin** | `admin@inspektorat.id` | `admin123` | Semua modul admin |
| **Admin Profil** | `admin.profil@inspektorat.id` | `adminprofil123` | Hanya Profil |
| **Admin Pelayanan** | `admin.pelayanan@inspektorat.id` | `adminpelayanan123` | Hanya Pelayanan |
| **Admin Dokumen** | `admin.dokumen@inspektorat.id` | `admindokumen123` | Hanya Dokumen |
| **Admin Galeri** | `admin.galeri@inspektorat.id` | `admingaleri123` | Hanya Galeri |
| **Admin FAQ** | `admin.faq@inspektorat.id` | `adminfaq123` | Hanya FAQ |
| **Admin WBS** | `admin.wbs@inspektorat.id` | `adminwbs123` | Hanya WBS |
| **Admin Berita** | `admin.berita@inspektorat.id` | `adminberita123` | Hanya Berita |
| **Admin Portal OPD** | `admin.opd@inspektorat.id` | `adminopd123` | Hanya Portal OPD |

**Admin Panel**: `http://localhost:8000/admin`

### 🚀 Cara Menjalankan

**Migration & Seeding:**
```bash
# Jalankan migration
php artisan migrate

# Seed data admin dan sample data
php artisan db:seed --class=SuperAdminSeeder
php artisan db:seed --class=PortalOpdSeeder

# Atau jalankan semua seeder sekaligus
php artisan migrate --seed
```

**Development Mode:**
```bash
# Terminal 1 - Backend server
php artisan serve

# Terminal 2 - Frontend development (optional)
npm run dev
```

## Development

### 🛠️ Development Mode
Untuk development mode:

```bash
# Terminal 1 - Backend
php artisan serve

# Terminal 2 - Frontend (watch mode)
npm run dev
```

### 🧪 Testing & Seeding
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed

# Seed data tertentu
php artisan db:seed --class=SuperAdminSeeder
php artisan db:seed --class=PortalOpdSeeder
php artisan db:seed --class=PortalPapuaTengahSeeder
php artisan db:seed --class=PelayananSeeder
php artisan db:seed --class=DokumenSeeder
php artisan db:seed --class=GaleriSeeder
php artisan db:seed --class=FaqSeeder
php artisan db:seed --class=WbsSeeder

# Jalankan testing
php artisan test

# Testing dengan coverage
php artisan test --coverage

# Testing spesifik
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Optimize untuk development
php artisan optimize:clear
```

### 🔧 Development Tools
```bash
# Generate application key
php artisan key:generate

# Clear semua cache
php artisan optimize:clear

# Link storage
php artisan storage:link

# View routes
php artisan route:list
```

### 🗄️ Struktur Database

### Tabel Utama
- `users`: Data pengguna dengan sistem role
- `portal_opds`: Data OPD Papua Tengah
- `portal_papua_tengahs`: Konten berita dan artikel
- `profils`: Data profil organisasi (baru)
- `pelayanans`: Data layanan publik (baru)
- `dokumens`: Repository dokumen (baru)
- `galeris`: Galeri foto dan video (baru)
- `faqs`: Sistem tanya jawab (baru)
- `wbs`: Data laporan Whistleblower
- `info_kantors`: Informasi kantor dan kontak

### Relasi Database
- **Users ↔ All Modules**: Relasi creator/updater untuk audit trail
- **Category System**: Kategorisasi untuk dokumen, FAQ, dan galeri
- **File Management**: Integrasi dengan Laravel Storage
- **Soft Deletes**: Implementasi pada semua model utama
- **Performance**: Indexing dan caching untuk query optimization

## 🛣️ Route Structure

### Public Routes
```
/ - Beranda dengan statistik dan showcase
/profil - Profil organisasi lengkap
/berita - Daftar berita
/berita/{id} - Detail berita
/pelayanan - Katalog layanan publik
/dokumen - Repository dokumen
/galeri - Galeri foto dan video
/faq - Sistem tanya jawab
/portal-opd - Daftar OPD
/portal-opd/{opd} - Detail OPD
/wbs - Form WBS
/kontak - Informasi kontak dan form
```

### Admin Routes (Protected by Auth + Role)
```
/admin/dashboard - Dashboard role-based
/admin/users/* - User management (SuperAdmin only)
/admin/profil/* - Profil management
/admin/pelayanan/* - Pelayanan management
/admin/dokumen/* - Dokumen management
/admin/galeri/* - Galeri management
/admin/faq/* - FAQ management
/admin/portal-opd/* - OPD management
/admin/portal-papua-tengah/* - Berita management
/admin/wbs/* - WBS management
```

## 🚀 Deployment

### Production Setup

1. **Optimize aplikasi**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm run build
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Setup permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chmod -R 775 storage/app/public
   ```

3. **Environment configuration**
   ```bash
   # Set production environment
   APP_ENV=production
   APP_DEBUG=false
   
   # Generate application key
   php artisan key:generate
   
   # Create storage link
   php artisan storage:link
   ```

4. **Database setup**
   ```bash
   # Run migrations in production
   php artisan migrate --force
   
   # Seed essential data
   php artisan db:seed --class=SuperAdminSeeder
   ```

5. **Configure web server** untuk mengarah ke folder `public/`

## 🔒 Keamanan & Best Practices

- **CSRF Protection**: Pada semua form dengan token validation
- **Input Validation**: Comprehensive validation rules dan sanitization
- **SQL Injection Prevention**: Eloquent ORM dan Query Builder
- **XSS Protection**: Blade templating dengan auto-escaping
- **File Upload Security**: Type validation dan storage isolation
- **Role-Based Access Control**: Middleware protection pada setiap route
- **Cache Security**: Sensitive data tidak di-cache
- **Password Hashing**: Laravel default bcrypt hashing
- **Session Security**: Secure session management
- **API Security**: Sanctum authentication untuk API endpoints

## 🏗️ Architecture & Design Patterns

### Clean Architecture Implementation
- **Repository Pattern**: Abstraksi data access layer
- **Service Layer**: Business logic separation
- **Action Classes**: Single responsibility operations
- **Dependency Injection**: IoC container untuk loose coupling
- **Interface Segregation**: Contracts untuk better testability

### Performance Optimization
- **Database Indexing**: Optimized query performance
- **Redis Caching**: Multi-layer caching strategy
- **Eager Loading**: N+1 query problem prevention
- **Asset Optimization**: Vite build optimization
- **Image Processing**: Thumbnail generation dan compression

### Testing Strategy
- **Unit Testing**: Model dan service testing
- **Feature Testing**: Controller dan integration testing
- **Database Testing**: Seeding dan migration testing
- **Performance Testing**: Load dan stress testing
- **Security Testing**: Vulnerability dan penetration testing

## 📱 Browser & Device Support

### Desktop
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

### Mobile
- iOS Safari 14+
- Chrome Mobile 90+
- Samsung Internet 13+

### Responsive Breakpoints
- Mobile: 320px - 767px
- Tablet: 768px - 1023px  
- Desktop: 1024px+

## 🆕 Update Log

### v3.0.0 - July 2025 (Latest)
- ✅ **Complete System Implementation**: Semua 11 modul lengkap
- ✅ **Enhanced Architecture**: Repository Pattern, Service Layer, Action Classes
- ✅ **New Modules**: Profil, Pelayanan, Dokumen, Galeri, FAQ
- ✅ **Bootstrap 5 Migration**: UI/UX modern dan responsive
- ✅ **Performance Optimization**: Redis caching, query optimization
- ✅ **Enhanced Security**: Advanced RBAC, input validation, CSRF protection
- ✅ **Testing Framework**: PHPUnit dengan comprehensive test coverage
- ✅ **Documentation**: Complete developer dan user documentation

### v2.0.0 - July 2025
- ✅ **Portal OPD**: Sistem manajemen OPD lengkap
- ✅ **Role-Based Access Control**: 11 level role dengan middleware protection
- ✅ **User Management**: CRUD user dengan assignment role (SuperAdmin)
- ✅ **Enhanced Dashboard**: Dashboard dinamis berdasarkan role
- ✅ **Public Homepage**: Statistik real-time dan showcase Portal OPD
- ✅ **Performance**: Caching optimization dan query improvement
- ✅ **Security**: Enhanced validation dan role-based route protection

### v1.0.0 - Baseline
- ✅ Portal Papua Tengah (Berita)
- ✅ Whistleblower System (WBS)
- ✅ Info Kantor
- ✅ Basic Admin Panel

## 🤝 Kontribusi

Proyek ini dikembangkan untuk kepentingan publik Provinsi Papua Tengah. Untuk kontribusi:

1. Fork repository
2. Buat feature branch (`git checkout -b feature/amazing-feature`)
3. Commit perubahan (`git commit -m 'Add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

## 🎯 Panduan Penggunaan

### Sistem Admin Terbaru
**Dashboard Role-Based:**
- Setiap admin memiliki dashboard khusus sesuai role
- Quick actions untuk fungsi yang sering digunakan
- Statistics real-time untuk modul yang dikelola
- Notifications untuk updates dan alerts

**User Management (Super Admin):**
1. Login sebagai Super Admin
2. Akses "Manajemen User" di dashboard
3. Kelola user dengan berbagai role:
   - Tambah user baru dengan role spesifik
   - Edit role dan status user existing
   - Monitor aktivitas user per modul
   - Reset password dan manage permissions

### Modul Baru yang Tersedia

**Profil Management:**
- Kelola visi, misi, dan sejarah organisasi
- Upload struktur organisasi
- Manage contact information
- Update logo dan branding

**Pelayanan Publik:**
- CRUD layanan dengan kategorisasi
- Upload dokumen persyaratan
- Manage prosedur dan SOP
- Tracking status layanan

**Dokumen Repository:**
- Upload dan kategorisasi dokumen
- Kontrol akses public/private
- Download tracking dan analytics
- Bulk upload functionality

**Galeri Management:**
- Upload foto dan video
- Album management
- Thumbnail generation
- Metadata editing

**FAQ System:**
- Manage pertanyaan dan jawaban
- Kategorisasi FAQ
- Order management
- Public/private toggle

### Portal OPD (Enhanced)
**Admin:**
1. Login sebagai Admin Portal OPD atau Super Admin
2. Akses menu "Portal OPD" di sidebar
3. Tambah OPD baru dengan informasi lengkap:
   - Nama OPD dan Singkatan
   - Visi, Misi, dan Deskripsi
   - Informasi Kepala OPD
   - Kontak (alamat, telepon, email, website)
   - Upload logo dan banner
4. Set status "Aktif" untuk menampilkan di public

**Public:**
- Akses `/portal-opd` untuk melihat daftar OPD
- Klik OPD untuk detail lengkap dengan informasi kontak
- Gunakan pencarian untuk menemukan OPD tertentu
- Filter berdasarkan kategori dan status

## 📞 Support & Kontak

- **Email Teknis**: dev@papuatengah.go.id
- **Inspektorat Papua Tengah**: inspektorat@papuatengah.go.id
- **Dokumentasi**: Lihat folder `/docs` untuk dokumentasi teknis lengkap

- **Email Teknis**: dev@papuatengah.go.id
- **Inspektorat Papua Tengah**: inspektorat@papuatengah.go.id
- **Dokumentasi**: Lihat folder `/docs` untuk dokumentasi teknis lengkap

## � Troubleshooting

### Common Issues

**1. Route [admin.portal-opd.index] not defined**
```bash
php artisan route:clear
php artisan config:clear
```

**2. View not found errors**
```bash
php artisan view:clear
php artisan optimize:clear
```

**3. Permission denied errors**
```bash
chmod -R 775 storage bootstrap/cache
php artisan storage:link
```

**4. Database connection issues**
- Periksa konfigurasi `.env`
- Pastikan database service berjalan
- Verify credentials database

### Development Tips
- Gunakan `php artisan serve` untuk development
- Jalankan `npm run dev` untuk hot reload
- Enable debug mode: `APP_DEBUG=true` di `.env`
- Monitor logs: `tail -f storage/logs/laravel.log`

## ❓ FAQ

**Q: Bagaimana cara menambah role baru?**
A: Edit `User` model, tambahkan role di method `getRoles()`, update middleware `RoleMiddleware`, dan sesuaikan routes di `web.php` dengan middleware role baru.

**Q: Bagaimana cara reset password admin?**
A: Gunakan tinker: 
```bash
php artisan tinker
User::where('email', 'admin@inspektorat.id')->first()->update(['password' => bcrypt('newpassword')]);
```

**Q: Bagaimana cara menjalankan testing?**
A: 
```bash
# Jalankan semua tests
php artisan test

# Jalankan specific test
php artisan test --testsuite=Feature

# Jalankan dengan coverage
php artisan test --coverage
```

**Q: Modul baru tidak muncul?**
A: Pastikan:
- Migration telah dijalankan: `php artisan migrate`
- Seeder telah dijalankan: `php artisan db:seed`
- Cache di-clear: `php artisan optimize:clear`
- Role user sesuai dengan akses modul

**Q: Error saat upload file?**
A: Pastikan:
- Storage link: `php artisan storage:link`
- Permissions: `chmod -R 775 storage/app/public`
- File size limit sesuai dengan php.ini
- Folder storage/app/public ada dan writable

## �📄 License

© 2025 Inspektorat Provinsi Papua Tengah. Dikembangkan untuk kepentingan publik.
