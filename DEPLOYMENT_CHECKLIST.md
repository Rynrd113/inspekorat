## Storage 404 Fix - Quick Deployment Checklist

### ✅ Local Changes Completed
- [x] Updated `.env` to use `FILESYSTEM_DISK=public` (was `local`)
- [x] Cleaned up `.htaccess` - removed problematic storage rewrite rule
- [x] Created `.env.production.example` template
- [x] Created `STORAGE_404_FIX_GUIDE.md` with complete documentation

### 📋 TODO on Production Server

**Step 1: SSH to Server**
```bash
ssh u953792975@my-kul-web2045
cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html
```

**Step 2: Pull Latest Changes**
```bash
git pull origin main
```
> This will get the updated .env and .htaccess files

**Step 3: Update Environment Variables**
```bash
# Edit the production .env file with the template as reference
nano .env

# Key updates needed:
FILESYSTEM_DISK=public              # Changed from 'local'
APP_ENV=production                  # Set to production
APP_DEBUG=false                      # Disable debug mode
APP_URL=https://inspektorat.papuatengahprov.cloud  # Update URL
DB_PASSWORD=YOUR_DB_PASSWORD        # Update if needed
```

**Step 4: Verify Symlink**
```bash
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

**Step 5: Fix Permissions**
```bash
chmod -R 755 storage/
chmod -R 755 storage/app/
chmod -R 755 storage/app/public/
chmod -R 755 public/storage
```

**Step 6: Clear Caches**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Step 7: Test URLs**
```bash
# Test image URL - should return 200, not 404
curl -I https://inspektorat.papuatengahprov.cloud/storage/galeri/nPpU0Y6LA4jX0bug3ITotuyKSX85zWCEHmkj2WTr.jpg

# Should output: HTTP/2 200
# NOT: HTTP/2 404
```

**Step 8: Check Laravel Logs**
```bash
tail -f storage/logs/laravel.log
# Look for any errors
```

---

### 🔍 If Still Getting 404 After Fix

Run the diagnostic commands:
```bash
# Verify filesystem disk setting
grep FILESYSTEM_DISK .env

# Verify symlink exists
ls -la public/storage

# Verify files exist
ls -la storage/app/public/galeri/ | wc -l

# Check permissions
stat public/storage/galeri/
```

### 📞 Support Resources

- **Complete Guide**: See `STORAGE_404_FIX_GUIDE.md` for detailed troubleshooting
- **Production Template**: Use `.env.production.example` as reference
- **Laravel Docs**: https://laravel.com/docs/filesystem#public-disk

### ⚡ Fast Track (If Familiar with Server)

```bash
cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html
git pull origin main
sed -i 's/FILESYSTEM_DISK=local/FILESYSTEM_DISK=public/' .env
sed -i 's/APP_ENV=local/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
php artisan config:clear && php artisan cache:clear
chmod -R 755 storage/ storage/app/ storage/app/public/ public/storage
curl -I https://inspektorat.papuatengahprov.cloud/storage/galeri/nPpU0Y6LA4jX0bug3ITotuyKSX85zWCEHmkj2WTr.jpg
```

### 🎯 Expected Result

After deployment:
- ✅ Image URLs should return HTTP 200 (not 404)
- ✅ All storage subdirectories should be accessible
- ✅ New uploads should be stored in `storage/app/public/` and accessible via `/storage/` URL

---

**Last Updated**: May 6, 2026  
**Status**: Ready for Deployment

