# 🚀 Quick Hostinger Deployment Commands

Copy and paste these commands into your Hostinger SSH terminal:

```bash
cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html

# Step 1: Pull latest code
git pull origin main

# Step 2: Clear all caches
php artisan config:clear && php artisan cache:clear

# Step 3: Verify deployment - test key URLs
echo "Testing Public JS Build..."
curl -I https://inspektorat.papuatengahprov.cloud/build/assets/js/public.CIpbYAly.js

echo ""
echo "Testing Storage Galeri..."
curl -I https://inspektorat.papuatengahprov.cloud/storage/galeri/3DRdbOcLVrxgRb9yLf2F27JO4aG2EyAOkhpuKDEQ.jpg

echo ""
echo "Testing Hero Slider..."
curl -I https://inspektorat.papuatengahprov.cloud/storage/hero-sliders/wYsk44IDLaXjo5cpm7R3PvLeaxJUC20UGmE2prLa.jpg
```

## ✅ Expected Results

All three tests should return **HTTP/2 200** or **HTTP/1.1 200**

If you get **404**, something needs adjustment.

---

## 🌐 Final Verification

1. Open browser: https://inspektorat.papuatengahprov.cloud/
2. Open Developer Console (F12)
3. Go to Console tab
4. You should see NO errors like:
   - ❌ `filterBerita is not defined` 
   - ❌ `Unexpected end of input`
5. Click "TERBARU" button - should work without errors
6. Gallery images should display (no 404s)

---

## If You See 404s Still:

Check symlink:
```bash
ls -la /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html/public/storage
# Should show: public/storage -> ../storage/app/public
```

Check files exist:
```bash
ls /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html/storage/app/public/galeri/ | wc -l
# Should show: 166
```

Check .htaccess is applied:
```bash
cat /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html/.htaccess | head -20
```

---

## Deployment Complete ✅

Once all HTTP 200s appear and no console errors are shown, deployment is successful!

