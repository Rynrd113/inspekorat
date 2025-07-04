# Portal Inspektorat Papua Selatan

Aplikasi web portal informasi pemerintahan untuk Inspektorat Provinsi Papua Selatan.

## Fitur Utama

### Public (Masyarakat)
- **Landing Page**: Informasi umum dan akses ke layanan
- **Form Pengaduan**: Masyarakat dapat mengajukan pengaduan online
- **Info Kantor**: Informasi kontak dan lokasi kantor
- **Berita & Pengumuman**: Informasi terkini dari portal

### Admin Panel
- **Dashboard**: Ringkasan statistik dan data terbaru
- **Kelola Pengaduan**: CRUD pengaduan dengan update status dan catatan admin
- **Kelola Info Kantor**: CRUD informasi kantor dan kontak
- **Kelola Web Portal**: CRUD konten berita, pengumuman, dan informasi
- **Authentication**: Login/logout admin dengan token API

## Teknologi

- **Backend**: Laravel 12 dengan Sanctum (API Authentication)
- **Frontend**: Blade Templates + Tailwind CSS + Vanilla JS
- **Database**: SQLite (development) / MySQL (production)
- **Asset Bundling**: Vite

## Instalasi & Setup

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite/MySQL

### Langkah Instalasi

1. **Clone repository dan install dependencies**
```bash
git clone <repository>
cd portal-inspektorat
composer install
npm install
```

2. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Setup database**
```bash
php artisan migrate
php artisan db:seed
```

4. **Build assets**
```bash
npm run dev
```

5. **Jalankan aplikasi**
```bash
php artisan serve
```

Aplikasi akan tersedia di `http://localhost:8000`

## Akun Demo

### Admin
- **Email**: admin@inspektorat.go.id
- **Password**: admin123

### Super Admin
- **Email**: superadmin@inspektorat.go.id
- **Password**: superadmin123

## Struktur API

### Authentication
- `POST /api/auth/login` - Login admin
- `POST /api/auth/logout` - Logout admin

### Pengaduan
- `GET /api/pengaduan` - List pengaduan (admin)
- `POST /api/pengaduan` - Buat pengaduan baru (public)
- `GET /api/pengaduan/{id}` - Detail pengaduan (admin)
- `PUT /api/pengaduan/{id}` - Update pengaduan (admin)
- `DELETE /api/pengaduan/{id}` - Hapus pengaduan (admin)

### Info Kantor
- `GET /api/info-kantor` - List info kantor
- `POST /api/info-kantor` - Buat info kantor (admin)
- `PUT /api/info-kantor/{id}` - Update info kantor (admin)
- `DELETE /api/info-kantor/{id}` - Hapus info kantor (admin)

### Web Portal
- `GET /api/web-portal` - List konten
- `POST /api/web-portal` - Buat konten (admin)
- `PUT /api/web-portal/{id}` - Update konten (admin)
- `DELETE /api/web-portal/{id}` - Hapus konten (admin)

### Dashboard
- `GET /api/dashboard/stats` - Statistik dashboard (admin)

## Fitur Teknis

### Backend
- **MVC Architecture**: Controllers terpisah untuk API dan Web
- **FormRequest Validation**: Validasi input yang ketat
- **API Resources**: Response JSON yang terstruktur
- **Database Indexing**: Optimasi query dengan index
- **Caching**: Cache untuk performa optimal
- **Eager Loading**: Menghindari N+1 query problem

### Frontend
- **Blade Components**: Komponen reusable (Button, Card, Input, Alert)
- **Responsive Design**: Mobile-first dengan Tailwind CSS
- **AJAX Forms**: Form submission tanpa reload halaman
- **Real-time Updates**: Update status dan data secara real-time
- **Search & Filter**: Pencarian dan filter data dengan debounce

### Security
- **CSRF Protection**: Perlindungan dari serangan CSRF
- **Sanctum Authentication**: Token-based API authentication
- **Role-based Access**: Pembatasan akses berdasarkan role
- **Input Sanitization**: Sanitasi input untuk mencegah XSS

## Development

### Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/           # API Controllers
│   │   ├── Admin/         # Admin Web Controllers
│   │   └── PublicController.php
│   ├── Requests/          # Form Request Validation
│   └── Resources/         # API Resources
├── Models/                # Eloquent Models
└── View/Components/       # Blade Components

resources/
├── views/
│   ├── layouts/           # Layout templates
│   ├── components/        # Blade components
│   ├── public/            # Public pages
│   └── admin/             # Admin pages
├── css/
└── js/

public/
├── js/
│   └── admin.js          # Admin panel JavaScript
└── logo.svg              # Logo aplikasi
```

### Conventions

- **Naming**: PascalCase untuk Models, camelCase untuk methods
- **Routes**: RESTful routes dengan resource controllers
- **Database**: Snake_case untuk kolom, timestamps required
- **API**: JSON responses dengan consistent structure
- **Frontend**: Tailwind CSS utilities, minimal custom CSS

## Production Deployment

### Optimasi
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Environment
- Update `.env` untuk production settings
- Setup database production (MySQL/PostgreSQL)
- Configure web server (Apache/Nginx)
- Setup SSL certificate
- Configure backups

## Support

Untuk bantuan atau pertanyaan, hubungi tim pengembang atau buat issue di repository.

---

**Portal Inspektorat Papua Selatan**  
Dikembangkan dengan ❤️ menggunakan Laravel & Tailwind CSS
