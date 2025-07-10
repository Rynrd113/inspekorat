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
- **Kontak**: Informasi kontak lengkap dengan peta lokasi
- **Statistik Real-time**: Dashboard data terkini OPD, berita, dan laporan WBS
- **Responsive Design**: Optimized untuk desktop, tablet, dan mobile

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
- **Multi-Role Support**: 10+ level role dengan hak akses berbeda

### 👥 **Sistem Role & Permissions**
- **SuperAdmin**: Akses penuh termasuk user management
- **Admin**: Akses ke semua modul kecuali user management  
- **Admin Profil**: Khusus mengelola profil organisasi
- **Admin Pelayanan**: Khusus mengelola layanan publik
- **Admin Dokumen**: Khusus mengelola repository dokumen
- **Admin Galeri**: Khusus mengelola galeri foto/video
- **Admin FAQ**: Khusus mengelola sistem tanya jawab
- **Admin Portal OPD**: Khusus mengelola data OPD
- **Admin Berita**: Khusus mengelola berita/konten
- **Admin WBS**: Khusus mengelola laporan WBS
- **User**: Akses terbatas/view only

## 💻 Teknologi & Arsitektur

- **Backend**: Laravel 12.x dengan PHP 8.2+
- **Frontend**: Tailwind CSS 3.x dengan Vite
- **Database**: MySQL dengan optimasi Query Builder dan Eloquent ORM
- **Authentication**: Laravel Sanctum dengan custom role middleware
- **Caching**: Laravel Cache untuk optimasi performa
- **File Storage**: Laravel Storage dengan validasi keamanan

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
   - **SuperAdmin**: `superadmin@papuatengah.go.id` / `password`
   - **Admin Modul**: Admin untuk setiap modul dengan password `password`
   - **Sample Data**: 10 OPD, berita, dan data WBS sample

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
php artisan db:seed --class=WbsSeeder

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

## 🗄️ Struktur Database

### Tabel Utama
- `users`: Data pengguna dengan sistem role
- `portal_opds`: Data OPD Papua Tengah (baru)
- `portal_papua_tengahs`: Konten berita dan artikel
- `wbs`: Data laporan Whistleblower
- `info_kantors`: Informasi kantor dan kontak
- `web_portals`: Data portal web

### Relasi Database
- **Users ↔ Portal OPDs**: Relasi creator/updater
- **Users ↔ Portal Papua Tengah**: Relasi penulis/editor
- **Users ↔ WBS**: Relasi processor/handler
- **Soft Deletes**: Implementasi pada semua model utama

## 🛣️ Route Structure

### Public Routes
```
/ - Beranda dengan statistik dan showcase
/berita - Daftar berita
/berita/{id} - Detail berita
/portal-opd - Daftar OPD (baru)
/portal-opd/{opd} - Detail OPD (baru)
/wbs - Form WBS
```

### Admin Routes (Protected by Auth + Role)
```
/admin/dashboard - Dashboard role-based
/admin/users/* - User management (SuperAdmin only)
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

### v2.0.0 - July 2025
- ✅ **Portal OPD**: Sistem manajemen OPD lengkap
- ✅ **Role-Based Access Control**: 6 level role dengan middleware protection
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

### Portal OPD (Fitur Baru)
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

### User Management (Super Admin)
1. Login sebagai Super Admin
2. Akses "Manajemen User" di dashboard
3. Kelola user dengan role-based access:
   - Tambah user baru dengan role tertentu
   - Edit role dan status user existing
   - Monitor aktivitas user per modul

### Role-Based Dashboard
Dashboard admin menampilkan menu berdasarkan role:
- **Super Admin**: Semua modul + User Management
- **Admin**: Semua modul operasional
- **Admin Modul**: Hanya modul sesuai spesialisasi

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
A: Edit `User` model, tambahkan role di method `getRoles()`, dan update middleware `RoleMiddleware`. Kemudian update routes di `web.php` dengan middleware role baru.

**Q: Bagaimana cara reset password admin?**
A: Gunakan tinker: 
```bash
php artisan tinker
User::where('email', 'admin@inspektorat.id')->first()->update(['password' => bcrypt('newpassword')]);
```

**Q: Bagaimana cara backup database?**
A: 
```bash
# MySQL backup
mysqldump -u username -p database_name > backup.sql

# Restore backup
mysql -u username -p database_name < backup.sql
```

**Q: Portal OPD tidak muncul di public?**
A: Pastikan:
- Status OPD = active (status = 1)
- Data seeder ter-load: `php artisan db:seed --class=PortalOpdSeeder`
- Cache di-clear: `php artisan cache:clear`

**Q: Akun admin tidak bisa login?**
A: Periksa:
- Email dan password sesuai tabel akun testing
- Jalankan seeder: `php artisan db:seed --class=SuperAdminSeeder`
- Clear cache: `php artisan optimize:clear`

**Q: Error "Route not defined"?**
A: Jalankan:
```bash
php artisan route:clear
php artisan config:clear
php artisan optimize:clear
```

**Q: Upload file tidak berfungsi?**
A: Pastikan:
- Storage link: `php artisan storage:link`
- Permissions: `chmod -R 775 storage/app/public`
- Folder storage/app/public ada dan writable

## �📄 License

© 2025 Inspektorat Provinsi Papua Tengah. Dikembangkan untuk kepentingan publik.
