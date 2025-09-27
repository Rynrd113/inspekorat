# 🎨 Sistem Branding Terbatas & Ter-standarisasi

## Filosofi Desain

Sistem branding ini dibuat berdasarkan **rekomendasi untuk website pemerintahan** dengan prinsip:

1. **Konsistensi** - Standarisasi visual untuk menghindari kebingungan masyarakat
2. **Aksesibilitas** - Semua warna divalidasi sesuai standar WCAG
3. **Keamanan** - Upload file dengan validasi ketat untuk mencegah celah keamanan
4. **Maintenance** - Opsi terbatas untuk mengurangi beban maintenance

## 🎯 Fitur Utama

### 1. Palet Warna Ter-standarisasi
- **6 Preset Warna** yang telah divalidasi aksesibilitas
- **Pemerintah Biru** (default & direkomendasikan)
- **Inspektorat Navy** untuk nuansa profesional
- **Papua Hijau** terinspirasi alam Papua
- **Netral Abu-abu** untuk website yang konservatif
- **Opsi Custom Terbatas** dengan validasi ketat

### 2. Logo Upload dengan Validasi Ketat
- **Format terbatas**: SVG, PNG, WebP, ICO
- **Ukuran maksimal** sesuai standar pemerintahan
- **Validasi keamanan** untuk mencegah upload file berbahaya
- **Optimasi otomatis** untuk performa website

### 3. Sistem Keamanan Berlapis
- Middleware khusus untuk akses branding
- Rate limiting untuk mencegah spam perubahan
- Validasi MIME type dan ekstensi file
- Pemeriksaan konten berbahaya dalam SVG

## 📐 Spesifikasi Teknis

### Logo Constraints
```
Header Logo: 250x80px, max 512KB
Footer Logo: 150x50px, max 256KB  
Icon Logo:   64x64px,  max 64KB
Favicon:     32x32px,  max 32KB
```

### Warna yang Diizinkan
- Semua preset telah divalidasi kontras minimum 4.5:1
- Custom color harus lulus validasi aksesibilitas
- Warna terlalu terang akan ditolak sistem

### Format File yang Didukung
- **SVG**: Dengan validasi konten untuk mencegah XSS
- **PNG**: Format terbaik untuk logo dengan transparansi
- **WebP**: Format modern untuk optimasi ukuran
- **ICO**: Khusus untuk favicon

## 🚫 Pembatasan yang Diberlakukan

### Yang TIDAK Diperbolehkan:
- Warna dengan kontras rendah (< 4.5:1)
- File berukuran lebih dari batas maksimal
- Format file selain yang didukung
- SVG dengan embedded script/JavaScript
- Logo dengan rasio aspek yang tidak sesuai
- Nama file dengan ekstensi ganda
- Upload lebih dari 10 kali per jam per user

### Mengapa Dibatasi?
1. **Konsistensi Visual** - Masyarakat dapat dengan mudah mengenali website pemerintahan resmi
2. **Aksesibilitas** - Memastikan website dapat diakses oleh penyandang disabilitas
3. **Keamanan** - Mencegah upload file berbahaya yang dapat membahayakan sistem
4. **Performa** - File yang dioptimasi akan meningkatkan kecepatan loading

## 💡 Rekomendasi Penggunaan

### Untuk Website Inspektorat:
```
Gunakan preset "Inspektorat Navy" atau "Pemerintah Biru"
- Warna utama: #1e40af (Navy Blue)  
- Warna sekunder: #059669 (Green)
- Warna aksen: #dc2626 (Red)
```

### Untuk Website Papua Tengah:
```
Gunakan preset "Papua Hijau"
- Warna utama: #166534 (Dark Green)
- Warna sekunder: #0369a1 (Sky Blue) 
- Warna aksen: #b91c1c (Red)
```

## 🔧 Implementasi

### 1. Controller dengan Validasi Ketat
```php
// Menggunakan Form Request dengan validasi berlapis
public function updateBranding(BrandingConfigurationRequest $request)
{
    // Validasi preset atau custom colors
    // Validasi aksesibilitas
    // Validasi kombinasi warna
}
```

### 2. Middleware Keamanan
```php
// Rate limiting dan validasi akses
BrandingSecurityMiddleware::class
```

### 3. Service Layer
```php
// Validasi file dan optimasi
BrandingPresetService::validateImageSecurity()
BrandingService::uploadBrandingImage()
```

## 🎨 CSS Variables Dinamis

Sistem menghasilkan CSS variables otomatis:
```css
:root {
    --brand-primary: #1e40af;
    --brand-secondary: #059669;  
    --brand-accent: #dc2626;
    --brand-gradient: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
}
```

## 📊 Monitoring & Audit

### Yang Dicatat:
- Setiap perubahan branding dengan user ID
- Upload file dengan validasi hasil
- Percobaan upload file berbahaya
- Rate limiting yang terlampaui

### Dashboard Admin:
- Preview real-time perubahan warna
- History perubahan branding
- Alert untuk file yang ditolak
- Statistik penggunaan preset

## 🔄 Migration Path

Untuk sistem existing:
1. Backup konfigurasi branding lama
2. Migrate ke preset yang paling sesuai
3. Re-upload logo sesuai constraint baru
4. Test aksesibilitas dengan tool WAVE/axe

## 📞 Support

Untuk pertanyaan terkait sistem branding:
- Dokumentasi lengkap: `/docs/guides/BRANDING_GUIDE.md`
- Troubleshooting: `/docs/troubleshooting/BRANDING_ISSUES.md`
- Contact: Developer Team

---

> **Catatan**: Sistem ini dirancang khusus untuk website pemerintahan dengan prioritas aksesibilitas, keamanan, dan konsistensi visual. Semua pembatasan dibuat berdasarkan best practices dan standar web pemerintahan.