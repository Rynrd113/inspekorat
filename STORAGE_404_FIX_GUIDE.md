# Storage 404 Error Fix - Deployment Guide

## Problem Summary
Images stored in `storage/galeri/` and other storage directories were returning HTTP 404 errors, even though:
- The symlink from `public/storage` to `storage/app/public` existed
- Files were physically present on the server
- The `.htaccess` was configured

## Root Causes

1. **Wrong Filesystem Disk Configuration**: The `.env` was set to `FILESYSTEM_DISK=local` instead of `FILESYSTEM_DISK=public`. This made Laravel use the wrong storage path.
2. **Problematic .htaccess Rewrite Rule**: The storage rewrite rule was interfering with the symlink mechanism.
3. **Incorrect APP_URL**: Production `.env` needs the correct HTTPS URL.

## Changes Made Locally

### 1. Updated `.env`
```diff
- FILESYSTEM_DISK=local
+ FILESYSTEM_DISK=public
```

### 2. Improved `.htaccess`
- Removed the problematic `/storage/` rewrite rule that was interfering with the symlink
- The symlink should be handled directly by the web server, not by rewrite rules

## Production Deployment Steps

### Step 1: Update Production `.env`

Copy the `.env.production.example` template and update the production `.env`:

```bash
cd /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html

# Update these key settings:
FILESYSTEM_DISK=public
APP_URL=https://inspektorat.papuatengahprov.cloud
APP_ENV=production
APP_DEBUG=false

# Update database credentials (if different)
DB_HOST=localhost
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Update mail settings (if using SMTP)
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
```

### Step 2: Verify Storage Symlink

```bash
# Check if symlink exists
ls -la public/storage

# Should output:
# public/storage -> ../storage/app/public

# If it doesn't exist or points to the wrong location, recreate it:
rm -rf public/storage
ln -s ../storage/app/public public/storage
chmod -R 755 storage/app/public
```

### Step 3: Verify File Permissions

```bash
# Ensure storage directories have proper permissions
chmod -R 755 storage/
chmod -R 755 storage/app/
chmod -R 755 storage/app/public/
chmod -R 755 public/storage

# If web server needs to write files:
chmod -R 775 storage/app/public/
```

### Step 4: Clear Laravel Cache

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Step 5: Deploy Updated Files

```bash
# Pull the latest changes
git pull origin main

# Update production files
php artisan migrate --force
php artisan config:cache
php artisan view:cache

# Restart queue workers if applicable
php artisan queue:restart
```

## Testing the Fix

### Test 1: Verify URLs
```bash
# Should return the image file (HTTP 200)
curl -I https://inspektorat.papuatengahprov.cloud/storage/galeri/nPpU0Y6LA4jX0bug3ITotuyKSX85zWCEHmkj2WTr.jpg

# Should return HTTP 200, not 404
curl -I https://inspektorat.papuatengahprov.cloud/storage/berita/example.jpg
```

### Test 2: Verify Files Exist
```bash
ls -la public/storage/galeri/ | wc -l
ls -la storage/app/public/galeri/ | wc -l

# Both should show the same number of files
```

### Test 3: Upload New File
Upload a test image through the admin panel and verify it's accessible at the `/storage/` URL.

## Troubleshooting

### 404 Still Occurring After Fix?

1. **Check filesystem disk setting**
   ```bash
   grep FILESYSTEM_DISK /home/u953792975/domains/inspektorat.papuatengahprov.cloud/public_html/.env
   # Should output: FILESYSTEM_DISK=public
   ```

2. **Verify symlink**
   ```bash
   ls -la public/storage
   # Should show: public/storage -> ../storage/app/public
   ```

3. **Check file permissions**
   ```bash
   stat public/storage/galeri/
   # Check if accessible and has proper permissions
   ```

4. **Clear OPcache**
   If using OPcache on the server, opcache might cache old Laravel configuration:
   ```bash
   # Contact hosting provider or restart PHP service
   # Or create a cache clear script
   ```

5. **Check .htaccess**
   - Ensure `FollowSymLinks` is enabled in `.htaccess`
   - Current .htaccess should have: `Options -MultiViews -Indexes +FollowSymLinks`

### Common Scenarios

**Scenario**: Files in `storage/app/public` but symlink doesn't exist
```bash
# Recreate symlink
cd public_html
rm -rf public/storage 2>/dev/null
ln -s ../storage/app/public public/storage
```

**Scenario**: `APP_URL` contains `/public` suffix
```bash
# WRONG: APP_URL=https://inspektorat.papuatengahprov.cloud/public
# RIGHT: APP_URL=https://inspektorat.papuatengahprov.cloud
```

**Scenario**: Different disk configuration needed for Hostinger
If the above doesn't work, contact Hostinger and ask about:
- PHP version (should be 8.2+)
- Symlink support in shared hosting
- Any .htaccess restrictions

## Files Changed

### Local changes:
1. `.env` - Changed `FILESYSTEM_DISK=local` to `FILESYSTEM_DISK=public`
2. `.htaccess` - Removed problematic `/storage/` rewrite rule
3. `.env.production.example` - New template for production deployment

## Configuration Reference

### Laravel Filesystem Disks (config/filesystems.php)

```php
// 'local' disk - for private files
'local' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'serve' => true,
],

// 'public' disk - for public files via symlink
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

When `FILESYSTEM_DISK=public`, Laravel will:
- Store uploads in `storage/app/public/`
- Generate URLs like `https://domain.com/storage/galeri/filename.jpg`
- Use the symlink from `public/storage` to `storage/app/public`

## Next Steps

1. Git push these changes
2. SSH into the server
3. Follow the "Production Deployment Steps" section above
4. Test the fixes
5. Monitor logs for any errors

## Support

If issues persist:
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Check PHP error logs: `tail -f /var/log/php-errors.log` (or check cPanel)
3. Contact Hostinger support with the specific 404 error URL

