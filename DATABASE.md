# 🗃️ Panduan Database Portal Inspektorat Papua Tengah

## Daftar Isi
1. [File Database yang Tersedia](#file-database-yang-tersedia)
2. [Metode 1: Laravel Migration (Recommended)](#metode-1-laravel-migration-recommended)
3. [Metode 2: Import Manual ke phpMyAdmin](#metode-2-import-manual-ke-phpmyadmin)
4. [Metode 3: Import via Command Line](#metode-3-import-via-command-line)
5. [Verifikasi Database](#verifikasi-database)
6. [Troubleshooting](#troubleshooting)

## File Database yang Tersedia

Project ini menyediakan beberapa file database:

```
database/
├── database.sqlite                    # SQLite database (development)
├── portal_inspektorat.sql             # SQLite dump file
├── portal_inspektorat_mysql.sql       # MySQL database (production ready)
├── migrations/                        # Laravel migration files
└── seeders/                          # Sample data seeders
```

## Metode 1: Laravel Migration (Recommended)

**Kelebihan**: Otomatis, aman, dan sesuai Laravel best practices.

### Step 1: Konfigurasi Database
```bash
# Edit file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 2: Buat Database
Buat database kosong di MySQL/phpMyAdmin:
```sql
CREATE DATABASE portal_inspektorat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 3: Jalankan Migration
```bash
cd /path/to/portal-inspektorat

# Install dependencies
composer install

# Generate app key
php artisan key:generate

# Jalankan migration
php artisan migrate

# Insert sample data
php artisan db:seed
```

### Step 4: Verifikasi
```bash
# Check tables
php artisan tinker
# Dalam tinker:
DB::select("SHOW TABLES");
App\Models\User::count();
App\Models\PortalPapuaTengah::count();
```

## Metode 2: Import Manual ke phpMyAdmin

**Kelebihan**: Mudah untuk user non-technical.

### Step 1: Akses phpMyAdmin
1. Buka browser dan akses phpMyAdmin
2. Login dengan credentials MySQL Anda

### Step 2: Buat Database
1. Klik "New" atau "Database"
2. Nama database: `portal_inspektorat`
3. Collation: `utf8mb4_unicode_ci`
4. Klik "Create"

### Step 3: Import File SQL
1. Klik database `portal_inspektorat`
2. Klik tab "Import"
3. Klik "Choose File"
4. Pilih file: `database/portal_inspektorat_mysql.sql`
5. Klik "Go" atau "Import"

### Step 4: Verifikasi Import
- Check apakah semua tabel sudah dibuat
- Check apakah data sample sudah ada
- Tables yang harus ada:
  - `users` (1 admin user)
  - `portal_papua_tengah` (6 sample news)
  - `wbs` (2 sample reports)
  - Dan tabel supporting lainnya

## Metode 3: Import via Command Line

**Kelebihan**: Cepat dan cocok untuk deployment automation.

### Step 1: Buat Database
```bash
mysql -u root -p -e "CREATE DATABASE portal_inspektorat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 2: Import SQL File
```bash
mysql -u root -p portal_inspektorat < database/portal_inspektorat_mysql.sql
```

### Step 3: Verifikasi
```bash
mysql -u root -p portal_inspektorat -e "SHOW TABLES;"
mysql -u root -p portal_inspektorat -e "SELECT COUNT(*) FROM users;"
mysql -u root -p portal_inspektorat -e "SELECT COUNT(*) FROM portal_papua_tengah;"
```

## Struktur Database

### Tabel Utama

#### 1. `users` - Admin Users
- **Purpose**: Menyimpan data admin yang bisa login ke admin panel
- **Default User**: 
  - Email: `admin@admin.com`
  - Password: `password`

#### 2. `portal_papua_tengah` - News Articles
- **Purpose**: Menyimpan artikel berita/informasi
- **Sample Data**: 6 artikel dengan berbagai kategori
- **Fields**: judul, konten, kategori, penulis, views, published_at

#### 3. `wbs` - Whistleblower Reports
- **Purpose**: Menyimpan laporan WBS dari masyarakat
- **Sample Data**: 2 laporan contoh
- **Fields**: jenis_laporan, subjek, deskripsi, status

### Tabel Supporting
- `cache`, `cache_locks` - System caching
- `jobs`, `job_batches`, `failed_jobs` - Queue system
- `sessions` - User sessions
- `personal_access_tokens` - API authentication
- `migrations` - Laravel migration tracking

## Verifikasi Database

### Check dari Laravel
```bash
php artisan tinker

# Check connection
DB::connection()->getPdo();

# Check tables
DB::select("SHOW TABLES");

# Check data
App\Models\User::all();
App\Models\PortalPapuaTengah::published()->count();
App\Models\Wbs::count();
```

### Check dari MySQL
```sql
-- Check tables
SHOW TABLES;

-- Check users
SELECT id, name, email FROM users;

-- Check news
SELECT id, judul, kategori, is_published FROM portal_papua_tengah;

-- Check WBS reports
SELECT id, jenis_laporan, subjek, status FROM wbs;
```

### Check dari Application
1. Akses: `http://localhost:8000`
2. Check apakah berita tampil di homepage
3. Akses: `http://localhost:8000/admin/login`
4. Login dengan: `admin@admin.com` / `password`
5. Check apakah admin panel bisa diakses

## Kredensial Default

### Admin Panel
- **URL**: `/admin/login`
- **Email**: `admin@admin.com`
- **Password**: `password`

⚠️ **PENTING**: Ganti password default setelah installation!

## Data Sample yang Tersedia

### News Articles (6 articles)
1. Selamat Datang di Portal Inspektorat Papua Tengah
2. Pengumuman Pelaksanaan Monitoring dan Evaluasi
3. Sosialisasi Sistem Whistleblower (WBS)
4. Laporan Hasil Pemeriksaan Kinerja Tahun 2024
5. Peluncuran Program Zona Integritas
6. Pelatihan Audit Internal untuk Aparatur

### WBS Reports (2 reports)
1. Dugaan Penyimpangan Dana Program (status: in_review)
2. Keluhan Pelayanan Publik (status: pending)

## Troubleshooting

### Error: "Database connection failed"
```bash
# Check MySQL service
sudo systemctl status mysql

# Check credentials di .env
cat .env | grep DB_

# Test connection
php artisan tinker
# Dalam tinker: DB::connection()->getPdo();
```

### Error: "Table doesn't exist"
```bash
# Re-run migrations
php artisan migrate:fresh --seed

# Atau import ulang SQL file
mysql -u root -p portal_inspektorat < database/portal_inspektorat_mysql.sql
```

### Error: "Access denied for user"
```bash
# Check MySQL user permissions
mysql -u root -p
# Dalam MySQL:
GRANT ALL PRIVILEGES ON portal_inspektorat.* TO 'your_username'@'localhost';
FLUSH PRIVILEGES;
```

### Error: "Charset/Collation mismatch"
```sql
-- Set proper charset
ALTER DATABASE portal_inspektorat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Backup & Restore

### Backup Database
```bash
# Via Laravel
php artisan db:backup

# Via mysqldump
mysqldump -u username -p portal_inspektorat > backup_$(date +%Y%m%d).sql
```

### Restore Database
```bash
# Drop & recreate database
mysql -u root -p -e "DROP DATABASE portal_inspektorat; CREATE DATABASE portal_inspektorat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import backup
mysql -u root -p portal_inspektorat < backup_20250104.sql
```

## Production Notes

### Security
- Ganti password default admin
- Gunakan strong password untuk database
- Set proper MySQL user permissions
- Enable MySQL SSL jika diperlukan

### Performance
- Add database indexes untuk query optimization
- Configure MySQL for optimal performance
- Setup database connection pooling jika diperlukan

### Monitoring
- Monitor database size dan growth
- Setup automated backup
- Monitor slow queries
- Setup database performance alerts

---

**Portal Inspektorat Papua Tengah**  
Database Setup & Management Guide  
© 2025 Inspektorat Provinsi Papua Tengah
