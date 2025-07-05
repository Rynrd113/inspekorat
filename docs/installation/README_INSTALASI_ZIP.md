# 🚀 CARA INSTALL - BACA INI DULU!

## 📦 Instalasi Super Cepat (3 Langkah)

### 1. **Persiapan** (Install tools ini dulu):
- **[XAMPP](https://www.apachefriends.org/download.html)** (PHP + MySQL + phpMyAdmin)
- **[Composer](https://getcomposer.org/download/)** (Package manager PHP)  
- **[Node.js](https://nodejs.org/)** (JavaScript runtime)

### 2. **Extract & Setup**:
- Extract ZIP ke folder web (misal: `C:\xampp\htdocs\portal-inspektorat\`)
- Buka **Command Prompt/Terminal** di folder tersebut

### 3. **Jalankan Script**:

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

## 📖 Dokumentasi Lengkap

- **Panduan Detail**: [PANDUAN_INSTALASI_VIA_ZIP.md](PANDUAN_INSTALASI_VIA_ZIP.md)
- **Setup Database**: Lihat panduan untuk MySQL/SQLite options
- **Troubleshooting**: Solusi masalah umum ada di panduan

---

## 🔍 File Database

**Database MySQL:**
- Import `database/portal_inspektorat_mysql.sql` ke phpMyAdmin/HeidiSQL
- Pastikan database `portal_inspektorat` sudah dibuat dulu

**Setup Database:**
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Klik "New" → Buat database: `portal_inspektorat`
3. Pilih database → Import → Pilih file `portal_inspektorat_mysql.sql`

---

## ❓ Bantuan

Kalau ada error, cek:
1. **Prerequisites** sudah install semua?
2. **Terminal/Command Prompt** dibuka di folder project yang benar?
3. **Internet connection** untuk download dependencies

**Happy coding!** 🚀
