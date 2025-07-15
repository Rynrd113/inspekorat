#!/bin/bash

# Configuration and Setup Script for Inspektorat Testing Suite
# This script sets up the testing environment and configures all necessary components

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================================================${NC}"
echo -e "${BLUE}            INSPEKTORAT TESTING SUITE SETUP${NC}"
echo -e "${BLUE}================================================================${NC}"

# Function to print status
print_status() {
    local status=$1
    local message=$2
    
    case $status in
        "PASS")
            echo -e "${GREEN}✓ $message${NC}"
            ;;
        "FAIL")
            echo -e "${RED}✗ $message${NC}"
            ;;
        "WARN")
            echo -e "${YELLOW}⚠ $message${NC}"
            ;;
        "INFO")
            echo -e "${CYAN}ℹ $message${NC}"
            ;;
    esac
}

# Make all scripts executable
echo -e "\n${YELLOW}Making scripts executable...${NC}"
chmod +x final_comprehensive_testing.sh
chmod +x master_test_automation.sh
chmod +x run_comprehensive_tests.sh
chmod +x load_testing.sh
chmod +x security_testing.sh
chmod +x fix_admin_consistency.sh
chmod +x update_admin_consistency.sh
print_status "PASS" "All scripts made executable"

# Create necessary directories
echo -e "\n${YELLOW}Creating directories...${NC}"
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p public/storage
mkdir -p tests/Browser/screenshots
mkdir -p database/backups
print_status "PASS" "Directories created"

# Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    echo -e "\n${YELLOW}Creating .env file...${NC}"
    cat > .env << 'EOF'
APP_NAME="Portal Inspektorat"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF
    print_status "PASS" ".env file created"
else
    print_status "INFO" ".env file already exists"
fi

# Create testing configuration file
echo -e "\n${YELLOW}Creating testing configuration...${NC}"
cat > testing_config.json << 'EOF'
{
    "app": {
        "name": "Portal Inspektorat",
        "url": "http://localhost:8000",
        "timeout": 30
    },
    "database": {
        "host": "127.0.0.1",
        "port": 3306,
        "database": "portal_inspektorat",
        "username": "root",
        "password": ""
    },
    "test_users": {
        "admin": {
            "email": "admin@inspektorat.go.id",
            "password": "admin123",
            "role": "admin"
        },
        "superadmin": {
            "email": "superadmin@inspektorat.go.id",
            "password": "superadmin123",
            "role": "superadmin"
        },
        "admin_wbs": {
            "email": "admin_wbs@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_wbs"
        },
        "admin_berita": {
            "email": "admin_berita@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_berita"
        },
        "admin_portal_opd": {
            "email": "admin_portal_opd@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_portal_opd"
        },
        "admin_pelayanan": {
            "email": "admin_pelayanan@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_pelayanan"
        },
        "admin_dokumen": {
            "email": "admin_dokumen@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_dokumen"
        },
        "admin_galeri": {
            "email": "admin_galeri@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_galeri"
        },
        "admin_faq": {
            "email": "admin_faq@inspektorat.go.id",
            "password": "admin123",
            "role": "admin_faq"
        }
    },
    "test_modules": {
        "portal_papua_tengah": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/portal-papua-tengah"]
        },
        "portal_opd": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/portal-opd"]
        },
        "wbs": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/wbs"]
        },
        "pelayanan": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/pelayanan"]
        },
        "dokumen": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/dokumen"]
        },
        "galeri": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/galeri"]
        },
        "faq": {
            "enabled": true,
            "crud_operations": ["create", "read", "update", "delete"],
            "api_endpoints": ["/api/v1/faq"]
        }
    },
    "test_settings": {
        "run_frontend_tests": true,
        "run_backend_tests": true,
        "run_api_tests": true,
        "run_security_tests": true,
        "run_performance_tests": true,
        "run_database_tests": true,
        "generate_reports": true,
        "cleanup_after_tests": true
    }
}
EOF
print_status "PASS" "Testing configuration created"

# Create requirements.txt for Python dependencies
echo -e "\n${YELLOW}Creating Python requirements...${NC}"
cat > requirements.txt << 'EOF'
selenium==4.15.2
webdriver-manager==4.0.1
requests==2.31.0
pandas==2.1.3
beautifulsoup4==4.12.2
lxml==4.9.3
openpyxl==3.1.2
matplotlib==3.8.2
seaborn==0.13.0
plotly==5.17.0
jinja2==3.1.2
python-dotenv==1.0.0
PyMySQL==1.1.0
faker==20.1.0
pytest==7.4.3
pytest-html==4.1.1
pytest-cov==4.1.0
pyyaml==6.0.1
jsonschema==4.20.0
EOF
print_status "PASS" "Python requirements created"

# Install Python dependencies
echo -e "\n${YELLOW}Installing Python dependencies...${NC}"
if command -v python3 &> /dev/null; then
    if python3 -m pip install -r requirements.txt --quiet; then
        print_status "PASS" "Python dependencies installed"
    else
        print_status "WARN" "Failed to install Python dependencies"
    fi
else
    print_status "WARN" "Python3 not found"
fi

# Generate application key if needed
echo -e "\n${YELLOW}Generating application key...${NC}"
if [ -f "artisan" ]; then
    php artisan key:generate --ansi
    print_status "PASS" "Application key generated"
else
    print_status "WARN" "Laravel artisan not found"
fi

# Create test database if needed
echo -e "\n${YELLOW}Setting up test database...${NC}"
if command -v mysql &> /dev/null; then
    mysql -u root -p"${DB_PASSWORD:-}" -e "CREATE DATABASE IF NOT EXISTS portal_inspektorat_test;" 2>/dev/null
    if [ $? -eq 0 ]; then
        print_status "PASS" "Test database created"
    else
        print_status "WARN" "Could not create test database"
    fi
else
    print_status "WARN" "MySQL not found"
fi

# Create phpunit.xml for Laravel testing
echo -e "\n${YELLOW}Creating PHPUnit configuration...${NC}"
if [ ! -f "phpunit.xml" ]; then
    cat > phpunit.xml << 'EOF'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </coverage>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
EOF
    print_status "PASS" "PHPUnit configuration created"
else
    print_status "INFO" "PHPUnit configuration already exists"
fi

# Create README for testing
echo -e "\n${YELLOW}Creating testing documentation...${NC}"
cat > TESTING_INSTRUCTIONS.md << 'EOF'
# Inspektorat Testing Suite

## Overview
This comprehensive testing suite is designed to test all aspects of the Inspektorat web application including frontend, backend, API, security, and performance.

## Prerequisites
- PHP 8.0+
- MySQL 5.7+
- Python 3.8+
- Node.js 16+
- Chrome/Chromium browser

## Quick Start

### 1. Setup Environment
```bash
# Make setup script executable and run
chmod +x setup_testing.sh
./setup_testing.sh
```

### 2. Run All Tests
```bash
# Run the comprehensive testing suite
./final_comprehensive_testing.sh
```

### 3. Run Specific Test Categories
```bash
# Python frontend tests only
python3 automated_comprehensive_testing.py

# PHP backend tests only
php backend_comprehensive_testing.php

# Security tests only
./security_testing.sh

# Performance tests only
./load_testing.sh
```

## Test Users
The following test users are available:
- **Admin**: admin@inspektorat.go.id / admin123
- **Super Admin**: superadmin@inspektorat.go.id / superadmin123
- **WBS Admin**: admin_wbs@inspektorat.go.id / admin123
- **News Admin**: admin_berita@inspektorat.go.id / admin123
- **Portal OPD Admin**: admin_portal_opd@inspektorat.go.id / admin123
- **Service Admin**: admin_pelayanan@inspektorat.go.id / admin123
- **Document Admin**: admin_dokumen@inspektorat.go.id / admin123
- **Gallery Admin**: admin_galeri@inspektorat.go.id / admin123
- **FAQ Admin**: admin_faq@inspektorat.go.id / admin123

## Test Modules
The testing suite covers:
- **Portal Papua Tengah**: News and article management
- **Portal OPD**: Government office portal
- **WBS**: Whistleblowing system
- **Pelayanan**: Service management
- **Dokumen**: Document management
- **Galeri**: Gallery management
- **FAQ**: FAQ management

## Test Categories
- **Frontend Tests**: UI/UX, form validation, navigation
- **Backend Tests**: CRUD operations, business logic
- **API Tests**: REST endpoints, data validation
- **Security Tests**: SQL injection, XSS, authentication
- **Performance Tests**: Load testing, response times
- **Database Tests**: Data integrity, queries

## Reports
After running tests, reports are generated in:
- `final_test_results/` - Main results directory
- `final_comprehensive_report_[timestamp].html` - Main HTML report
- `test_summary.txt` - Quick summary
- Individual test reports in various formats

## Troubleshooting
- Ensure all dependencies are installed
- Check database connection settings
- Verify application is running on localhost:8000
- Check file permissions for test directories

## Configuration
Edit `testing_config.json` to customize:
- Test users and credentials
- Module settings
- Test parameters
- Database connection

## Support
For issues or questions about testing:
1. Check the troubleshooting section
2. Review generated log files
3. Ensure all prerequisites are met
4. Verify application configuration
EOF
print_status "PASS" "Testing documentation created"

# Final status
echo -e "\n${GREEN}================================================================${NC}"
echo -e "${GREEN}              SETUP COMPLETED SUCCESSFULLY!${NC}"
echo -e "${GREEN}================================================================${NC}"

echo -e "\n${CYAN}To run comprehensive tests:${NC}"
echo -e "${YELLOW}  ./final_comprehensive_testing.sh${NC}"

echo -e "\n${CYAN}Available test scripts:${NC}"
echo -e "${BLUE}  • final_comprehensive_testing.sh     - Complete testing suite${NC}"
echo -e "${BLUE}  • automated_comprehensive_testing.py - Python frontend tests${NC}"
echo -e "${BLUE}  • backend_comprehensive_testing.php  - PHP backend tests${NC}"
echo -e "${BLUE}  • security_testing.sh               - Security tests${NC}"
echo -e "${BLUE}  • load_testing.sh                   - Performance tests${NC}"

echo -e "\n${CYAN}Configuration files:${NC}"
echo -e "${BLUE}  • testing_config.json              - Main configuration${NC}"
echo -e "${BLUE}  • requirements.txt                 - Python dependencies${NC}"
echo -e "${BLUE}  • phpunit.xml                      - PHPUnit configuration${NC}"
echo -e "${BLUE}  • TESTING_INSTRUCTIONS.md          - Documentation${NC}"

echo -e "\n${GREEN}Ready to start testing! 🚀${NC}"
