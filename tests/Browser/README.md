# Laravel Dusk Tests - Portal Inspektorat Papua Tengah

## 📋 Overview

Comprehensive End-to-End (E2E) test suite for Portal Inspektorat Papua Tengah menggunakan Laravel Dusk. Test suite ini mencakup pengujian menyeluruh untuk seluruh fitur frontend dan backend yang saling terintegrasi.

## 🧪 Test Files

### 1. `DuskTestCase.php`
Base class untuk semua Dusk tests dengan helper methods:
- User creation dengan berbagai roles
- Login/logout helpers
- File upload utilities
- Wait methods untuk notifications
- Frontend-backend sync verification helpers

### 2. `AuthenticationTest.php`
Pengujian sistem autentikasi:
- ✅ Login/logout dengan berbagai credentials
- ✅ Role-based access control (Super Admin, Content Manager, OPD Manager, WBS Manager)
- ✅ Session management dan timeout
- ✅ Remember me functionality
- ✅ Account lockout setelah multiple failed attempts
- ✅ Navigation menu berdasarkan role

### 3. `PublicPortalTest.php`
Pengujian modul public portal:
- ✅ Homepage accessibility dan content
- ✅ News listing dan detail pages
- ✅ Organization profile page
- ✅ Public services functionality
- ✅ Documents page dengan download functionality
- ✅ Gallery dengan image management
- ✅ FAQ dengan accordion functionality
- ✅ Contact form submission
- ✅ Portal OPD listing
- ✅ Search functionality
- ✅ Responsive design (mobile viewport)
- ✅ SEO meta tags
- ✅ Accessibility features

### 4. `AdminBeritaTest.php`
Pengujian admin news management:
- ✅ News CRUD operations (Create, Read, Update, Delete)
- ✅ Form validation untuk required fields
- ✅ Image upload validation
- ✅ Draft/publish functionality
- ✅ Featured news functionality
- ✅ Search dan filtering dalam admin
- ✅ Bulk actions (publish multiple drafts)
- ✅ Categories functionality
- ✅ Role-based permissions untuk content management

### 5. `WbsTest.php`
Pengujian Whistleblowing System:
- ✅ Public WBS submission form
- ✅ Anonymous vs named report submission
- ✅ Form validation untuk WBS reports
- ✅ Multiple file upload functionality
- ✅ Admin WBS management (view, update status)
- ✅ WBS tracking untuk public
- ✅ Admin dashboard statistics
- ✅ Filtering dan search dalam admin WBS
- ✅ Report confidentiality testing
- ✅ Email notifications (jika implemented)

### 6. `FrontendBackendSyncTest.php`
Pengujian sinkronisasi frontend-backend:
- ✅ News creation sync antara admin dan public
- ✅ News update reflects immediately di frontend
- ✅ News deletion removes dari frontend immediately
- ✅ Draft to publish status change sync
- ✅ Gallery management sync
- ✅ Document upload sync
- ✅ OPD portal sync
- ✅ Services management sync
- ✅ Bulk operations sync dengan frontend
- ✅ Featured news toggle sync dengan homepage
- ✅ Cache invalidation pada content updates
- ✅ Real-time sync dengan multiple browser windows
- ✅ API endpoints sync dengan frontend data

### 7. `ValidationConsistencyTest.php`
Pengujian konsistensi validasi:
- ✅ News form validation consistency antara frontend dan backend
- ✅ WBS form validation consistency
- ✅ File upload validation consistency
- ✅ User management validation consistency
- ✅ Contact form validation consistency
- ✅ Validation error messages language consistency
- ✅ AJAX vs form submission validation consistency
- ✅ Client-side dan server-side validation consistency
- ✅ Validation consistency across different user roles
- ✅ Validation dengan special characters dan edge cases
- ✅ Validation performance under load
- ✅ Validation state persistence across page navigation

## 🚀 Setup & Installation

### 1. Install Laravel Dusk
```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

### 2. Configure Environment
Add ke `.env.dusk.local`:
```env
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_DATABASE=inspektorat_testing
DUSK_DRIVER_URL=http://localhost:9515
```

### 3. Install ChromeDriver
```bash
php artisan dusk:chrome-driver
```

## 🏃‍♂️ Running Tests

### Run All Dusk Tests
```bash
php artisan dusk
```

### Run Specific Test File
```bash
php artisan dusk tests/Browser/AuthenticationTest.php
php artisan dusk tests/Browser/PublicPortalTest.php
php artisan dusk tests/Browser/AdminBeritaTest.php
php artisan dusk tests/Browser/WbsTest.php
php artisan dusk tests/Browser/FrontendBackendSyncTest.php
php artisan dusk tests/Browser/ValidationConsistencyTest.php
```

### Run Specific Test Method
```bash
php artisan dusk --filter test_admin_can_create_news_article_successfully
```

### Run Tests dengan Headless Mode Disabled (untuk debugging)
```bash
DUSK_HEADLESS_DISABLED=true php artisan dusk
```

## 🔧 Test Configuration

### Browser Configuration
- **Default Resolution**: 1920x1080
- **Headless Mode**: Enabled (dapat disabled untuk debugging)
- **Screenshot**: Otomatis pada error
- **Chrome Options**: Optimized untuk CI/CD

### Database
- Menggunakan `DatabaseMigrations` trait
- Setiap test dijalankan dengan fresh database
- Factory data untuk testing

## 📊 Test Coverage

### Modules Tested:
- ✅ **Authentication & Authorization** (14 roles)
- ✅ **Public Portal** (Beranda, Berita, Profil, Layanan, Dokumen, Galeri, FAQ, Kontak, Portal OPD)
- ✅ **Admin Management** (Dashboard, CRUD operations)
- ✅ **WBS System** (Anonymous/Named reporting, Admin management)
- ✅ **Frontend-Backend Sync** (Real-time data synchronization)
- ✅ **Validation Consistency** (Form validation across layers)

### User Roles Tested:
- ✅ Super Admin (Level 100)
- ✅ Admin (Level 90)
- ✅ Content Manager (Level 80)
- ✅ OPD Manager (Level 80)
- ✅ WBS Manager (Level 80)
- ✅ Service Manager (Level 80)
- ✅ Public Users (Level 0)

## 🐛 Debugging

### Take Screenshots
```php
$browser->screenshot('debug_screenshot');
```

### Pause Test Execution
```php
$browser->pause(5000); // Pause for 5 seconds
```

### View Browser Console
```php
$logs = $browser->driver->manage()->getLog('browser');
foreach ($logs as $log) {
    echo $log['message'];
}
```

## 📝 Best Practices

### 1. Wait Strategies
```php
// Wait for element to appear
$browser->waitFor('.element-class', 10);

// Wait for text to appear
$browser->waitForText('Success message', 10);

// Wait for location change
$browser->waitForLocation('/expected-url');
```

### 2. Element Interaction
```php
// Scroll to element before interaction
$browser->scrollTo('.element')
        ->click('.element');

// Clear field before typing
$browser->clear('field_name')
        ->type('field_name', 'new_value');
```

### 3. File Upload Testing
```php
$this->uploadFile($browser, 'file_input', 'filename.jpg', 'file-content');
```

### 4. Database Verification
```php
$this->verifyFrontendBackendSync(
    $browser,
    'table_name',
    ['field' => 'value'],
    '.ui-selector',
    'Expected UI Text'
);
```

## 🚨 Common Issues & Solutions

### Issue: ChromeDriver not found
**Solution:**
```bash
php artisan dusk:chrome-driver --detect
```

### Issue: Tests timeout
**Solution:**
```php
// Increase timeout
$browser->waitFor('.element', 30); // 30 seconds timeout
```

### Issue: Element not clickable
**Solution:**
```php
// Scroll to element first
$browser->scrollTo('.element')
        ->pause(500)
        ->click('.element');
```

### Issue: Database not reset between tests
**Solution:**
```php
// Ensure DatabaseMigrations trait is used
use Illuminate\Foundation\Testing\DatabaseMigrations;
```

## 📈 Test Reports

Tests menghasilkan:
- **Screenshots** pada error (di `tests/Browser/screenshots/`)
- **Console logs** untuk debugging
- **Database state** verification
- **Performance metrics** untuk loading times

## 🔄 CI/CD Integration

Untuk menjalankan di CI/CD environment:

```yaml
# .github/workflows/dusk.yml
name: Laravel Dusk Tests
on: [push, pull_request]
jobs:
  dusk:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      - name: Install dependencies
        run: composer install
      - name: Setup Chrome
        run: |
          google-chrome --version
          php artisan dusk:chrome-driver
      - name: Run Dusk Tests
        run: php artisan dusk --without-tty
```

## 🎯 Test Goals Achieved

1. ✅ **Comprehensive E2E Coverage** - Semua modul utama tercovered
2. ✅ **Role-based Testing** - Semua 14 user roles teruji
3. ✅ **Frontend-Backend Integration** - Sinkronisasi data teruji
4. ✅ **Validation Consistency** - Konsistensi validasi across layers
5. ✅ **Real User Scenarios** - Simulasi interaksi pengguna nyata
6. ✅ **Performance Testing** - Loading times dan response verification
7. ✅ **Security Testing** - XSS protection dan access control
8. ✅ **Mobile Responsiveness** - Cross-device compatibility

## 📞 Support

Untuk pertanyaan atau issues terkait testing:
1. Check error screenshots di `tests/Browser/screenshots/`
2. Review browser console logs
3. Verify database state dengan `php artisan tinker`
4. Run tests dengan headless disabled untuk debugging visual

---

**✨ Portal Inspektorat Papua Tengah - Comprehensive E2E Testing Suite**  
*Generated with Laravel Dusk for Laravel 12.x*