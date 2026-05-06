# 🚀 Deployment Guide - Hostinger

## SSH Connection Details

**Hostname:** `my-kul-web2045.main-hosting.eu`
**Username:** `u953792975`
**Port:** `22` (or try `2222` if 22 doesn't work)

---

## Option 1: Using Terminal / SSH Client

### On Windows (PowerShell/CMD):
```bash
ssh u953792975@my-kul-web2045.main-hosting.eu
# Or if port 2222:
ssh u953792975@my-kul-web2045.main-hosting.eu -p 2222
```

### On Mac/Linux:
```bash
ssh u953792975@my-kul-web2045.main-hosting.eu
# Or if port 2222:
ssh u953792975@my-kul-web2045.main-hosting.eu -p 2222
```

**Enter password when prompted**

---

## Option 2: Using Hostinger Control Panel

1. Login to Hostinger: https://hpanel.hostinger.com
2. Go to **File Manager** or **Terminal** (if available)
3. Navigate to: `/public_html` (or `/inspektorat.papuatengahprov.cloud/public_html`)

---

## After SSH Connection, Run These Commands:

```bash
# Navigate to project
cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html

# Verify you're in the right folder
pwd
# Should output: /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html
```

### Step 1: Pull Latest Code
```bash
git pull origin main
```
**Expected output:**
```
remote: Enumerating objects: ...
From github.com:Rynrd113/inspekorat
 * branch            main       -> FETCH_HEAD
...
```

### Step 2: Clear Caches
```bash
php artisan config:clear && php artisan cache:clear && php artisan view:clear
```
**Expected output:**
```
Configuration cache cleared successfully.
Application cache cleared successfully.
Compiled views cleared successfully.
```

### Step 3: Verify Storage
```bash
ls -la public/storage
```
**Expected output:**
```
lrwxrwxrwx ... public/storage -> ../storage/app/public
```

### Step 4: Test Deployments (Optional)
```bash
# Test JavaScript build
curl -I https://inspektorat.papuatengahprov.cloud/build/assets/js/public.CIpbYAly.js

# Test Storage Access
curl -I https://inspektorat.papuatengahprov.cloud/storage/galeri/3DRdbOcLVrxgRb9yLf2F27JO4aG2EyAOkhpuKDEQ.jpg

# Test Hero Slider
curl -I https://inspektorat.papuatengahprov.cloud/storage/hero-sliders/wYsk44IDLaXjo5cpm7R3PvLeaxJUC20UGmE2prLa.jpg
```

**All should return: `HTTP/2 200` or `HTTP/1.1 200`**

---

## All Commands (Copy & Paste):

```bash
cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html

git pull origin main

php artisan config:clear && php artisan cache:clear && php artisan view:clear

echo "✅ Deployment Complete! Check website at: https://inspektorat.papuatengahprov.cloud/"
```

---

## What Was Deployed:

✅ Fixed `filterBerita is not defined` error
✅ Fixed `Unexpected end of input` syntax error  
✅ Fixed `/storage/galeri/` 404 errors
✅ Updated `.htaccess` for proper routing
✅ New minified JavaScript build (public.CIpbYAly.js)

---

## Post-Deployment Verification

1. Open: https://inspektorat.papuatengahprov.cloud/
2. Press **F12** to open Developer Console
3. Go to **Console** tab
4. Look for any RED errors
5. Click **TERBARU** button - should work without errors
6. Scroll down - gallery images should load
7. If all looks good ✅ you're done!

---

## Troubleshooting

**Connection timeout?** 
- Try port 2222: `ssh -p 2222 u953792975@my-kul-web2045.main-hosting.eu`
- Or use Hostinger File Manager / Terminal in hpanel

**git pull fails?**
```bash
git status
git log --oneline -3
```

**Still seeing 404 on storage?**
```bash
ls /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html/storage/app/public/galeri/ | wc -l
# Should show: 166
```

**Still seeing JavaScript errors?**
```bash
cat /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html/public/build/manifest.json | grep public
```

---

**Done! Website should now be fully functional! 🎉**

