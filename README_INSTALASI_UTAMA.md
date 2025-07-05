# 🚀 CARA INSTALL - BACA INI DULU!

## 📦 Instalasi Super Cepat (MySQL)

### 1. **Persiapan** (Install tools ini dulu):
- **[XAMPP](https://www.apachefriends.org/download.html)** (PHP + MySQL + phpMyAdmin)
- **[Composer](https://getcomposer.org/download/)** (Package manager PHP)  
- **[Node.js](https://nodejs.org/)** (JavaScript runtime)

### 2. **Setup Database**:
1. Start XAMPP → Start **Apache** dan **MySQL**
2. Buka **phpMyAdmin**: http://localhost/phpmyadmin
3. **Buat database**: `portal_inspektorat`
4. **Import** file: `database/portal_inspektorat_mysql.sql`

### 3. **Extract & Jalankan Script**:
- Extract ZIP ke folder web (misal: `C:\xampp\htdocs\portal-inspektorat\`)
- Buka **Command Prompt/Terminal** di folder tersebut

**Windows:**
```cmd
scripts\install-mysql.bat
```

**Linux/macOS:**
```bash
chmod +x scripts/install-mysql.sh
./scripts/install-mysql.sh
```

**Selesai!** 🎉

---

## 🌐 Akses Aplikasi

**Jalankan server:**
```bash
php artisan serve
```

**Buka browser:** http://localhost:8000

**Login Admin:**
- Email: `admin@inspektorat.go.id`  
- Password: `admin123`

**Admin Panel:** http://localhost:8000/admin

---

## 📁 Struktur File

```
portal-inspektorat/
├── 📄 README.md                    # Panduan ini
├── 📁 scripts/                     # Script instalasi
│   ├── install-mysql.bat          # Instalasi Windows (MySQL)
│   ├── install-mysql.sh           # Instalasi Linux/macOS (MySQL)
│   ├── install-sqlite.bat         # Backup: SQLite Windows
│   └── install-sqlite.sh          # Backup: SQLite Linux/macOS
├── 📁 docs/                        # Dokumentasi
│   └── 📁 installation/           # Panduan instalasi
│       ├── PANDUAN_INSTALASI_VIA_ZIP.md   # Panduan detail
│       └── PANDUAN_PENGIRIM.md             # Panduan untuk pengirim
├── 📁 database/                    # File database
│   ├── portal_inspektorat_mysql.sql       # Database MySQL
│   └── portal_inspektorat.sql             # Backup database
├── .env.mysql                     # Konfigurasi MySQL siap pakai
└── ... (Laravel files)
```

---

## 📖 Dokumentasi Lengkap

- **Panduan Detail**: [docs/installation/PANDUAN_INSTALASI_VIA_ZIP.md](docs/installation/PANDUAN_INSTALASI_VIA_ZIP.md)
- **Troubleshooting**: Solusi masalah umum ada di panduan
- **Panduan Pengirim**: [docs/installation/PANDUAN_PENGIRIM.md](docs/installation/PANDUAN_PENGIRIM.md)

---

## ❓ Bantuan

**Kalau ada error, cek:**
1. **Prerequisites** sudah install semua?
2. **Database** `portal_inspektorat` sudah dibuat & di-import?
3. **MySQL** sudah running di XAMPP?
4. **Terminal/Command Prompt** dibuka di folder project yang benar?

**Urutan troubleshooting:**
1. Pastikan XAMPP running (Apache + MySQL)
2. Buka phpMyAdmin → Buat database `portal_inspektorat`
3. Import file `database/portal_inspektorat_mysql.sql`
4. Jalankan script instalasi lagi

**Happy coding!** 🚀
