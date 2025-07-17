# Laravel Dusk Testing Documentation
## Portal Inspektorat Papua Tengah

### Daftar Isi
1. [Pendahuluan](#pendahuluan)
2. [Instalasi dan Setup](#instalasi-dan-setup)
3. [Struktur Testing](#struktur-testing)
4. [Menjalankan Tests](#menjalankan-tests)
5. [Page Objects](#page-objects)
6. [Components](#components)
7. [Traits](#traits)
8. [Test Suites](#test-suites)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)

## Pendahuluan

Dokumentasi ini menjelaskan implementasi Laravel Dusk testing untuk Portal Inspektorat Papua Tengah. Testing ini mencakup:

- **Authentication & Authorization**: Login, logout, role-based access
- **CRUD Operations**: Create, Read, Update, Delete untuk semua modul
- **File Upload**: Image dan document upload testing
- **Form Validation**: Client-side dan server-side validation
- **Responsive Design**: Testing pada berbagai ukuran layar
- **Data Tables**: Search, filter, pagination, bulk actions

## Instalasi dan Setup

### Prerequisites
- PHP 8.2+
- Laravel 12.x
- Chrome/Chromium browser
- MySQL database

### Instalasi Dusk

```bash
# Install Laravel Dusk
composer require --dev laravel/dusk

# Install Dusk
php artisan dusk:install

# Download ChromeDriver
php artisan dusk:chrome-driver
```

### Konfigurasi Environment

File `.env.dusk.local`:
```env
APP_ENV=dusk.local
APP_URL=http://localhost:8000
DB_DATABASE=portal_inspektorat_testing
DUSK_HEADLESS=true
DUSK_WINDOW_SIZE=1920x1080
```

### Database Setup

```bash
# Create test database
mysql -u root -p -e "CREATE DATABASE portal_inspektorat_testing;"

# Run migrations
php artisan migrate:fresh --seed --env=dusk.local
```

## Struktur Testing

```
tests/
├── DuskTestCase.php           # Base test case dengan helper methods
├── Browser/
│   ├── Authentication/        # Authentication tests
│   │   ├── LoginTest.php
│   │   └── AuthorizationTest.php
│   ├── Modules/              # Module-specific tests
│   │   ├── WBS/
│   │   │   └── WBSManagementTest.php
│   │   └── Berita/
│   │       └── BeritaManagementTest.php
│   ├── Pages/                # Page Object classes
│   │   ├── LoginPage.php
│   │   └── DashboardPage.php
│   ├── Components/           # UI Component classes
│   │   ├── DataTable.php
│   │   └── FileUpload.php
│   └── Traits/               # Reusable behavior traits
│       ├── InteractsWithAuthentication.php
│       ├── InteractsWithForms.php
│       ├── InteractsWithFiles.php
│       └── InteractsWithDataTables.php
```

## Menjalankan Tests

### Using Test Runner Script

```bash
# Run all tests
./run-dusk-tests.sh

# Run specific test suite
./run-dusk-tests.sh auth
./run-dusk-tests.sh wbs
./run-dusk-tests.sh berita

# Run with browser visible (debug mode)
./run-dusk-tests.sh -d auth

# Run with filter
./run-dusk-tests.sh -f login

# Setup environment only
./run-dusk-tests.sh --setup

# Cleanup only
./run-dusk-tests.sh --cleanup
```

### Using Artisan Commands

```bash
# Run all browser tests
php artisan dusk

# Run specific test file
php artisan dusk tests/Browser/Authentication/LoginTest.php

# Run specific test method
php artisan dusk tests/Browser/Authentication/LoginTest::test_user_dapat_login_dengan_credentials_valid

# Run with options
php artisan dusk --stop-on-failure
php artisan dusk --verbose
```

## Page Objects

Page Objects menyediakan abstraksi untuk interaksi dengan halaman web:

### LoginPage

```php
use Tests\Browser\Pages\LoginPage;

$browser->visit(new LoginPage)
        ->login('user@example.com', 'password')
        ->assertLoginSuccess();
```

### DashboardPage

```php
use Tests\Browser\Pages\DashboardPage;

$browser->visit(new DashboardPage)
        ->navigateToWbs()
        ->assertMenuVisibilityForRole('admin');
```

## Components

Components menyediakan abstraksi untuk UI elements yang dapat digunakan kembali:

### DataTable

```php
use Tests\Browser\Components\DataTable;

$browser->within(new DataTable, function ($table) {
    $table->search('keyword')
          ->filterBy('status', 'active')
          ->selectRow(1)
          ->bulkAction('delete');
});
```

### FileUpload

```php
use Tests\Browser\Components\FileUpload;

$browser->within(new FileUpload, function ($upload) {
    $upload->uploadFile('/path/to/file.pdf')
           ->waitForUploadComplete()
           ->assertFileUploaded('file.pdf');
});
```

## Traits

Traits menyediakan behavior yang dapat digunakan kembali:

### InteractsWithAuthentication

```php
use Tests\Browser\Traits\InteractsWithAuthentication;

class MyTest extends DuskTestCase
{
    use InteractsWithAuthentication;
    
    public function test_example()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsSuperAdmin($browser);
            $this->assertCanAccess($browser, '/admin/users');
        });
    }
}
```

### InteractsWithForms

```php
use Tests\Browser\Traits\InteractsWithForms;

$this->fillForm($browser, [
    'title' => 'Test Title',
    'description' => 'Test Description',
    'status' => ['type' => 'select', 'value' => 'active']
]);

$this->submitForm($browser, 'Save');
```

## Test Suites

### Authentication Tests

**LoginTest.php**
- Login dengan credentials valid/invalid
- Form validation
- Remember me functionality
- Session timeout
- Brute force protection

**AuthorizationTest.php**
- Role-based access control
- Menu visibility per role
- Action button availability
- Middleware protection

### Module Tests

**WBSManagementTest.php**
- CRUD operations untuk WBS
- File upload/download
- Search dan filter
- Bulk actions
- Role-based permissions

**BeritaManagementTest.php**
- CRUD operations untuk Berita
- Rich text editor
- Image upload
- Publish/unpublish
- SEO fields

## Best Practices

### 1. Test Isolation
- Gunakan `RefreshDatabase` trait
- Buat data test yang independent
- Cleanup setelah test selesai

### 2. Naming Convention
```php
// Format: test_[role]_dapat_[action]_[module]_[condition]
public function test_content_manager_dapat_membuat_berita_dengan_gambar()
public function test_admin_tidak_dapat_akses_user_management()
```

### 3. Assertions
```php
// Gunakan assertions yang deskriptif
$browser->assertSee('User created successfully');
$browser->assertPresent('.success-message');
$browser->assertPathIs('/admin/users');
```

### 4. Wait Strategies
```php
// Gunakan wait methods yang tepat
$browser->waitForText('Loading complete');
$browser->waitForLocation('/admin/dashboard');
$browser->waitFor('.datatable', 10);
```

### 5. Error Handling
```php
// Handle errors dengan graceful
$browser->whenAvailable('.error-modal', function ($modal) {
    $modal->assertSee('Error occurred');
    $modal->press('OK');
});
```

## Troubleshooting

### Common Issues

**1. ChromeDriver Issues**
```bash
# Update ChromeDriver
php artisan dusk:chrome-driver

# Check Chrome version
google-chrome --version

# Manual ChromeDriver download
php artisan dusk:chrome-driver --detect
```

**2. Database Issues**
```bash
# Reset test database
php artisan migrate:fresh --seed --env=dusk.local

# Check database connection
php artisan tinker --env=dusk.local
DB::connection()->getPdo();
```

**3. Timeout Issues**
```php
// Increase timeout
$browser->waitFor('.element', 30);

// Use more specific selectors
$browser->waitFor('.datatable tbody tr', 10);
```

**4. Element Not Found**
```php
// Check element exists before interaction
$browser->assertPresent('.element');

// Use when available
$browser->whenAvailable('.element', function ($element) {
    $element->click();
});
```

**5. Screenshot for Debugging**
```php
// Take screenshot
$browser->screenshot('debug-screenshot');

// Auto screenshot on failure
protected function setUp(): void
{
    parent::setUp();
    if (env('DUSK_SCREENSHOT_ON_FAILURE', true)) {
        $this->afterApplication = function () {
            $this->browse(function (Browser $browser) {
                $browser->screenshot('failure-' . date('Y-m-d-H-i-s'));
            });
        };
    }
}
```

### Performance Tips

**1. Parallel Testing**
```bash
# Run tests in parallel (if supported)
php artisan dusk --parallel
```

**2. Selective Testing**
```bash
# Run only critical tests
php artisan dusk tests/Browser/Authentication/LoginTest.php
```

**3. Headless Mode**
```bash
# Use headless mode for faster execution
export DUSK_HEADLESS=true
php artisan dusk
```

### CI/CD Integration

**GitHub Actions Example:**
```yaml
name: Browser Tests
on: [push, pull_request]

jobs:
  dusk:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          
      - name: Install dependencies
        run: composer install
        
      - name: Setup environment
        run: |
          cp .env.dusk.local .env
          php artisan key:generate
          
      - name: Run Dusk tests
        run: |
          ./run-dusk-tests.sh --setup
          ./run-dusk-tests.sh all
```

## Maintenance

### Regular Tasks

1. **Update ChromeDriver**
   ```bash
   php artisan dusk:chrome-driver --detect
   ```

2. **Clean Screenshots**
   ```bash
   find tests/Browser/screenshots -name "*.png" -mtime +7 -delete
   ```

3. **Update Test Data**
   ```bash
   php artisan migrate:fresh --seed --env=dusk.local
   ```

4. **Review Test Results**
   - Check for flaky tests
   - Update selectors if UI changes
   - Optimize slow tests

### Monitoring

- Monitor test execution time
- Track success/failure rates
- Review screenshot logs
- Update documentation

---

**Catatan**: Dokumentasi ini akan terus diperbarui seiring dengan perkembangan aplikasi dan penambahan test cases baru.
