# 🚀 Portal Inspektorat Papua Tengah - Quick Setup

## Instalasi Super Cepat (⚡ Optimized)

### Windows:
```cmd
git clone https://github.com/Rynrd113/inspekorat.git portal-inspektorat
cd portal-inspektorat
quick-install.bat
```

### Linux/macOS:
```bash
git clone https://github.com/Rynrd113/inspekorat.git portal-inspektorat
cd portal-inspektorat
chmod +x quick-install.sh && ./quick-install.sh
```

## ⚡ Perintah Composer yang Dioptimasi

```bash
# Install cepat tanpa dev dependencies
composer run install-fast

# Install untuk production (dengan cache)
composer run install-prod

# Clear semua cache
composer run clear-all
```

## 📱 NPM Commands yang Dioptimasi

```bash
# Install dependencies dengan cache offline
npm run install-fast

# Build untuk production
npm run build
```

## 🔧 Tips Optimasi Instalasi

1. **Gunakan SSD** - Proses I/O akan jauh lebih cepat
2. **Koneksi Internet Stabil** - untuk download dependencies
3. **Tutup aplikasi lain** - untuk memaksimalkan RAM dan CPU
4. **Gunakan Composer cache** - sudah dikonfigurasi otomatis
5. **Gunakan NPM offline cache** - sudah dikonfigurasi di .npmrc

## 📊 Perbandingan Waktu Instalasi

| Metode | Waktu (Est.) | Keterangan |
|--------|--------------|------------|
| `composer install` | 5-10 menit | Metode standard |
| `composer run install-fast` | 2-4 menit | Tanpa dev dependencies |
| `quick-install.sh/bat` | 3-6 menit | Complete setup dengan optimasi |

## 🌐 Setelah Instalasi

```bash
# Start server
php artisan serve

# Buka browser ke:
http://localhost:8000
```

## 📝 Admin Panel

- URL: `http://localhost:8000/admin`
- Credentials: Lihat dokumentasi lengkap di `INSTALL.md`

---

Untuk dokumentasi lengkap, baca file `INSTALL.md`
