# 📤 Panduan untuk Mengirim Project ke Teman

## 🎯 Langkah untuk Anda (Pengirim)

### 1. **Buat ZIP Distribusi**

**Linux/macOS:**
```bash
./buat-zip-distribusi.sh
```

**Windows:**
```cmd
buat-zip-distribusi.bat
```

File `portal-inspektorat-distribusi.zip` akan dibuat secara otomatis.

### 2. **Upload ke Google Drive**
- Upload file ZIP ke Google Drive
- Set sharing permission ke "Anyone with the link can view"
- Bagikan link download ke teman

### 3. **Berikan Instruksi ke Teman**

Kirim pesan ini ke teman Anda:

---

**📥 CARA INSTALL PORTAL INSPEKTORAT**

1. **Download file ZIP** dari Google Drive
2. **Extract** ke folder (misal: `C:\xampp\htdocs\portal-inspektorat\`)
3. **Install prerequisites** dulu:
   - [XAMPP](https://www.apachefriends.org/download.html) (PHP + MySQL)
   - [Composer](https://getcomposer.org/download/)
   - [Node.js](https://nodejs.org/)

4. **Setup Database:**
   - Start XAMPP → Start Apache & MySQL
   - Buka phpMyAdmin: http://localhost/phpmyadmin
   - Buat database: `portal_inspektorat`
   - Import file: `database/portal_inspektorat_mysql.sql`

5. **Jalankan script instalasi:**
   - Buka Command Prompt/Terminal di folder project
   - Windows: `scripts\install-mysql.bat`
   - Linux/Mac: `./scripts/install-mysql.sh`

6. **Start server:** `php artisan serve`
7. **Buka browser:** http://localhost:8000

**Login Admin:**
- Email: `admin@inspektorat.go.id`
- Password: `admin123`

**Panduan lengkap ada di file `README.md` dan `PANDUAN_INSTALASI_VIA_ZIP.md`**

---

## 📋 Yang Sudah Disertakan dalam ZIP

✅ **Source code lengkap**  
✅ **Database MySQL siap import**  
✅ **Script instalasi otomatis** (Windows & Linux)  
✅ **File .env siap pakai (MySQL)**  
✅ **Panduan instalasi step-by-step**  
✅ **Dokumentasi terorganisir**  

## 🔧 Troubleshooting untuk Teman

**Jika ada masalah, suruh mereka:**

1. **Cek prerequisites** sudah install semua?
2. **Baca file `README.md`** di dalam ZIP
3. **Lihat `PANDUAN_INSTALASI_VIA_ZIP.md`** untuk solusi lengkap
4. **Restart terminal** setelah install prerequisites
5. **Pakai SQLite** (lebih mudah dari MySQL)

## 🎉 Keunggulan Setup Ini

- **Instalasi 1-click** dengan script otomatis
- **Database MySQL** production-ready
- **Panduan lengkap** dengan troubleshooting
- **Cross-platform** (Windows, Linux, macOS)
- **File konfigurasi** sudah siap
- **Struktur terorganisir** (scripts, docs terpisah)

**Teman Anda tinggal extract → run script → done!** 🚀
