#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================"
echo "   Portal Inspektorat Papua Tengah"
echo "       Quick Setup for ZIP Version"
echo -e "========================================${NC}"
echo

echo -e "${YELLOW}[INFO] Script ini akan menginstall Portal Inspektorat dengan MySQL${NC}"
echo -e "${YELLOW}[INFO] Pastikan MySQL sudah running${NC}"
echo

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check prerequisites
echo -e "${YELLOW}Checking prerequisites...${NC}"

if ! command_exists php; then
    echo -e "${RED}ERROR: PHP not found. Please install PHP 8.2+ first.${NC}"
    echo "Ubuntu/Debian: sudo apt install php8.2 php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd"
    echo "macOS: brew install php@8.2"
    exit 1
fi

if ! command_exists composer; then
    echo -e "${RED}ERROR: Composer not found. Please install Composer first.${NC}"
    echo "Download from: https://getcomposer.org/download/"
    exit 1
fi

if ! command_exists node; then
    echo -e "${RED}ERROR: Node.js not found. Please install Node.js 18+ first.${NC}"
    echo "Download from: https://nodejs.org/"
    exit 1
fi

if ! command_exists mysql; then
    echo -e "${RED}ERROR: MySQL not found. Please install MySQL first.${NC}"
    echo "Ubuntu/Debian: sudo apt install mysql-server"
    echo "macOS: brew install mysql"
    exit 1
fi

echo -e "${GREEN}✓ All prerequisites found${NC}"
echo

# Start installation
echo -e "${YELLOW}[1/8] Copying environment file...${NC}"
cp .env.example .env
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: Failed to copy .env.example${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Environment file copied${NC}"

echo -e "${YELLOW}[2/8] Configuring environment for MySQL...${NC}"
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=portal_inspektorat/' .env
sed -i 's/APP_NAME=Laravel/APP_NAME="Portal Inspektorat Papua Tengah"/' .env
echo -e "${GREEN}✓ Environment configured for MySQL${NC}"

echo -e "${YELLOW}[3/8] Installing PHP dependencies...${NC}"
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: composer install failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ PHP dependencies installed${NC}"

echo -e "${YELLOW}[4/8] Generating application key...${NC}"
php artisan key:generate
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: Failed to generate application key${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Application key generated${NC}"

echo -e "${YELLOW}[5/8] Installing JavaScript dependencies...${NC}"
npm install
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: npm install failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ JavaScript dependencies installed${NC}"

echo -e "${YELLOW}[6/8] Building assets...${NC}"
npm run build
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: npm run build failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Assets built successfully${NC}"

echo -e "${YELLOW}[7/8] Setting up database...${NC}"
echo -e "${BLUE}[INFO] Pastikan MySQL sudah running dan database 'portal_inspektorat' sudah dibuat${NC}"
echo -e "${BLUE}[INFO] Jika belum, jalankan: mysql -u root -p -e \"CREATE DATABASE portal_inspektorat;\"${NC}"
echo

php artisan migrate:fresh --seed
if [ $? -ne 0 ]; then
    echo
    echo -e "${RED}ERROR: Database migration gagal!${NC}"
    echo
    echo -e "${YELLOW}SOLUSI:${NC}"
    echo "1. Pastikan MySQL sudah running"
    echo "2. Buat database: mysql -u root -p -e \"CREATE DATABASE portal_inspektorat;\""
    echo "3. Atau import file: mysql -u root -p portal_inspektorat < database/portal_inspektorat_mysql.sql"
    echo "4. Jalankan script ini lagi"
    echo
    exit 1
fi
echo -e "${GREEN}✓ Database setup completed${NC}"

echo -e "${YELLOW}[8/8] Setting up storage permissions...${NC}"
mkdir -p storage/logs bootstrap/cache
chmod -R 755 storage bootstrap/cache
if command_exists chown && [ -w /var/www ]; then
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null
fi

php artisan config:clear
php artisan cache:clear
php artisan storage:link
echo -e "${GREEN}✓ Permissions and cache setup completed${NC}"

echo
echo -e "${GREEN}========================================"
echo "        INSTALASI BERHASIL! 🎉"
echo -e "========================================${NC}"
echo
echo -e "${BLUE}Database: MySQL (portal_inspektorat)${NC}"
echo
echo -e "${BLUE}Untuk menjalankan aplikasi:${NC}"
echo "  php artisan serve"
echo
echo -e "${BLUE}Kemudian buka browser dan akses:${NC}"
echo "  http://localhost:8000"
echo
echo -e "${BLUE}Login Admin:${NC}"
echo "  Email: admin@inspektorat.go.id"
echo "  Password: admin123"
echo
echo -e "${BLUE}Admin Panel:${NC} http://localhost:8000/admin"
echo -e "${GREEN}========================================${NC}"
echo
