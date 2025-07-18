# 🧪 DUSK TESTING GUIDE - Portal Inspektorat Papua Tengah

**Generated:** 2025-07-18  
**Author:** Claude AI Assistant  
**Version:** 1.0  

---

## 📋 **OVERVIEW**

Panduan lengkap untuk menjalankan Laravel Dusk tests menggunakan script automasi yang telah dibuat. Portal Inspektorat Papua Tengah memiliki sistem testing yang komprehensif dengan 3 jenis script testing.

---

## 🚀 **SCRIPT TESTING YANG TERSEDIA**

### **1. Full Test Script (`run_full_dusk_tests.sh`)**
- **Durasi:** 2-3 jam
- **Cakupan:** Semua test lengkap
- **Fitur:** Setup otomatis, report lengkap, screenshots
- **Recommended:** Production deployment validation

### **2. Quick Test Script (`run_quick_dusk_tests.sh`)**
- **Durasi:** 15-20 menit
- **Cakupan:** Test essentials saja
- **Fitur:** Test cepat untuk development
- **Recommended:** Daily development testing

### **3. Specific Test Script (`run_specific_dusk_test.sh`)**
- **Durasi:** 1-10 menit
- **Cakupan:** Test specific sesuai kebutuhan
- **Fitur:** Flexible testing options
- **Recommended:** Debugging dan development

---

## 📂 **FILE STRUCTURE**

```
inspekorat/
├── run_full_dusk_tests.sh          # Full test script
├── run_quick_dusk_tests.sh         # Quick test script  
├── run_specific_dusk_test.sh       # Specific test script
├── DUSK_TESTING_GUIDE.md           # Panduan ini
├── tests/Browser/                  # Test files
│   ├── SystemImprovementsVerificationTest.php
│   ├── AdminCrudDataVerificationTest.php
│   ├── ComprehensiveAdminCrudTest.php
│   ├── WbsWorkflowTest.php
│   ├── DocumentManagementTest.php
│   ├── UserManagementTest.php
│   ├── ComprehensiveFinalSystemTest.php
│   └── screenshots/                # Screenshots hasil test
└── storage/logs/                   # Log files
```

---

## 🔧 **PERSIAPAN SEBELUM MENJALANKAN TEST**

### **1. Pastikan Dependencies Terinstall**
```bash
# Check PHP version (minimum 8.1)
php --version

# Check Composer
composer --version

# Check MySQL
mysql --version

# Check Google Chrome
google-chrome --version
```

### **2. Setup Environment**
```bash
# Masuk ke direktori project
cd /home/rynrd/Documents/Project/agent/inspekorat

# Install dependencies
composer install
npm install

# Copy environment file
cp .env .env.dusk.local
```

### **3. Konfigurasi Database Testing**
```bash
# Edit .env.dusk.local
nano .env.dusk.local

# Pastikan konfigurasi berikut:
APP_ENV=dusk.local
DB_DATABASE=inspekorat_dusk_test
DUSK_HEADLESS_DISABLED=1
```

### **4. Buat Database Testing**
```bash
# Login ke MySQL
mysql -u root -p

# Buat database testing
CREATE DATABASE inspekorat_dusk_test;
EXIT;
```

---

## 🎯 **CARA MENJALANKAN TEST**

### **1. FULL TEST (Comprehensive)**

```bash
# Jalankan full test dengan UI visible
./run_full_dusk_tests.sh

# Jalankan dengan MySQL password
./run_full_dusk_tests.sh --mysql-password yourpassword

# Lihat help
./run_full_dusk_tests.sh --help
```

**Fitur Full Test:**
- ✅ Setup environment otomatis
- ✅ Database migration dan seeding
- ✅ Chrome driver installation
- ✅ Semua test suite (7 test suites)
- ✅ Screenshot generation
- ✅ HTML report generation
- ✅ Cleanup otomatis

**Output Full Test:**
- `FULL_TEST_REPORT_YYYYMMDD_HHMMSS.md` - Report lengkap
- `storage/logs/dusk_full_test_YYYYMMDD_HHMMSS.log` - Log detail
- `tests/Browser/screenshots/` - Screenshots UI
- `screenshots_report.html` - HTML report

### **2. QUICK TEST (Essential)**

```bash
# Jalankan quick test
./run_quick_dusk_tests.sh
```

**Fitur Quick Test:**
- ✅ Test essentials (6 test methods)
- ✅ Database structure verification
- ✅ Public features testing
- ✅ User roles testing
- ✅ Search functionality testing
- ✅ WBS workflow testing
- ✅ System health check

**Output Quick Test:**
- `QUICK_TEST_REPORT_YYYYMMDD_HHMMSS.md` - Report singkat
- `storage/logs/dusk_quick_test_YYYYMMDD_HHMMSS.log` - Log

### **3. SPECIFIC TEST (Targeted)**

```bash
# Lihat available tests
./run_specific_dusk_test.sh --list

# Jalankan test file specific
./run_specific_dusk_test.sh --file SystemImprovementsVerificationTest.php

# Jalankan test method specific
./run_specific_dusk_test.sh --method test_portal_opd_crud_10_plus_data

# Jalankan test dengan filter
./run_specific_dusk_test.sh --filter crud

# Jalankan test dengan options
./run_specific_dusk_test.sh --filter search --verbose --headless

# Lihat help
./run_specific_dusk_test.sh --help
```

**Options Specific Test:**
- `--file FILE` - Test file tertentu
- `--filter PATTERN` - Filter test dengan pattern
- `--method METHOD` - Test method tertentu
- `--headless` - Jalankan tanpa UI
- `--verbose` - Output detail
- `--list` - List available tests

---

## 📊 **TEST SUITES YANG TERSEDIA**

### **1. SystemImprovementsVerificationTest.php**
- **Durasi:** 15-20 menit
- **Cakupan:** Verifikasi semua perbaikan sistem
- **Test Methods:**
  - `test_extended_user_roles_functionality`
  - `test_comprehensive_search_functionality`
  - `test_pagination_functionality`
  - `test_wbs_status_enum_functionality`
  - `test_file_upload_validation`
  - `test_comprehensive_system_health`

### **2. AdminCrudDataVerificationTest.php**
- **Durasi:** 20-25 menit
- **Cakupan:** CRUD operations dengan 10+ data
- **Test Methods:**
  - `test_portal_opd_crud_10_plus_data`
  - `test_pelayanan_crud_10_plus_data`
  - `test_faq_crud_10_plus_data`
  - `test_news_crud_10_plus_data`
  - `test_wbs_crud_10_plus_data`

### **3. ComprehensiveAdminCrudTest.php**
- **Durasi:** 30-40 menit
- **Cakupan:** Semua admin roles dengan fitur lengkap
- **Test Methods:**
  - `test_admin_portal_opd_complete_crud_and_features`
  - `test_admin_pelayanan_complete_crud_and_features`
  - `test_admin_faq_complete_crud_and_features`
  - `test_super_admin_all_features_access`

### **4. WbsWorkflowTest.php**
- **Durasi:** 5-10 menit
- **Cakupan:** Whistleblower System workflow
- **Test Methods:**
  - `test_wbs_end_to_end_workflow`

### **5. DocumentManagementTest.php**
- **Durasi:** 10-15 menit
- **Cakupan:** Document management system
- **Test Methods:**
  - `test_document_upload_and_management`

### **6. UserManagementTest.php**
- **Durasi:** 10-15 menit
- **Cakupan:** User management system
- **Test Methods:**
  - `test_user_crud_operations`

### **7. ComprehensiveFinalSystemTest.php**
- **Durasi:** 15-20 menit
- **Cakupan:** Final system integration test
- **Test Methods:**
  - `test_complete_system_integration`

---

## 🎨 **OUTPUT DAN HASIL TEST**

### **1. Screenshots**
- **Location:** `tests/Browser/screenshots/`
- **Format:** PNG files
- **Naming:** `test_method_step_description.png`
- **Auto-Generated:** Ya, untuk setiap test step

### **2. Reports**
- **Full Test Report:** `FULL_TEST_REPORT_YYYYMMDD_HHMMSS.md`
- **Quick Test Report:** `QUICK_TEST_REPORT_YYYYMMDD_HHMMSS.md`
- **HTML Screenshot Report:** `screenshots_report.html`

### **3. Logs**
- **Location:** `storage/logs/`
- **Format:** Log files dengan timestamp
- **Content:** Detailed command execution dan errors

### **4. Database**
- **Test Database:** `inspekorat_dusk_test`
- **Data:** Test data dengan 10+ records per module
- **Reset:** Automatic untuk setiap test run

---

## 🔍 **TROUBLESHOOTING**

### **1. Chrome Driver Issues**
```bash
# Update Chrome driver
php artisan dusk:chrome-driver --detect

# Manual installation
php artisan dusk:chrome-driver 114
```

### **2. Database Connection Issues**
```bash
# Check database connection
php artisan tinker --env=dusk.local
>>> DB::connection()->getPdo()

# Reset database
php artisan migrate:fresh --env=dusk.local --seed
```

### **3. Permission Issues**
```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chmod -R 775 tests/Browser/screenshots
```

### **4. Memory Issues**
```bash
# Increase PHP memory limit
php -d memory_limit=2G artisan dusk
```

### **5. Port Issues**
```bash
# Check if port 9515 is available
netstat -tlnp | grep 9515

# Kill existing ChromeDriver processes
pkill -f chromedriver
```

---

## 📈 **BEST PRACTICES**

### **1. Development Testing**
- Use **Quick Test** untuk daily development
- Use **Specific Test** untuk debugging
- Jalankan test sebelum commit

### **2. Production Testing**
- Use **Full Test** sebelum deployment
- Verify semua screenshots
- Check report lengkap

### **3. Performance Testing**
- Monitor test execution time
- Check memory usage
- Optimize test data

### **4. Error Handling**
- Check log files untuk errors
- Verify database state
- Review screenshots untuk UI issues

---

## 🎯 **CONTOH WORKFLOW TESTING**

### **1. Daily Development**
```bash
# Setiap hari development
./run_quick_dusk_tests.sh

# Jika ada error, debug dengan specific test
./run_specific_dusk_test.sh --method test_yang_error
```

### **2. Feature Development**
```bash
# Test specific feature yang sedang dikembangkan
./run_specific_dusk_test.sh --filter portal_opd
./run_specific_dusk_test.sh --filter search
./run_specific_dusk_test.sh --filter crud
```

### **3. Pre-Deployment**
```bash
# Full test sebelum deployment
./run_full_dusk_tests.sh

# Review hasil:
# - Check report markdown
# - Review screenshots
# - Verify success rate 100%
```

### **4. Debugging**
```bash
# Debug specific issue
./run_specific_dusk_test.sh --method test_problematic_method --verbose

# Debug dengan UI visible
./run_specific_dusk_test.sh --method test_problematic_method

# Debug tanpa UI (faster)
./run_specific_dusk_test.sh --method test_problematic_method --headless
```

---

## 🏆 **EXPECTED RESULTS**

### **Success Criteria:**
- ✅ All tests pass (100% success rate)
- ✅ No errors in logs
- ✅ Screenshots show proper UI
- ✅ Database contains test data
- ✅ All features working correctly

### **Performance Benchmarks:**
- **Quick Test:** 15-20 minutes
- **Full Test:** 2-3 hours
- **Specific Test:** 1-10 minutes per test
- **Success Rate:** 95-100%

### **Output Quality:**
- **Screenshots:** Clear UI captures
- **Reports:** Detailed markdown
- **Logs:** Comprehensive error tracking
- **Database:** Proper test data

---

## 🚀 **READY TO TEST!**

Portal Inspektorat Papua Tengah siap untuk comprehensive testing dengan semua fitur yang telah diimplementasikan dan diperbaiki. Script testing yang telah dibuat akan memastikan sistem berjalan dengan sempurna sebelum production deployment.

**Happy Testing!** 🎉