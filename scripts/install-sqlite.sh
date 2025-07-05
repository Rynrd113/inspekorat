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

echo -e "${GREEN}✓ All prerequisites found${NC}"
echo

# Start installation
echo -e "${YELLOW}[1/7] Copying environment file...${NC}"
cp .env.example .env
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: Failed to copy .env.example${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Environment file copied${NC}"

echo -e "${YELLOW}[2/7] Installing PHP dependencies...${NC}"
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: composer install failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ PHP dependencies installed${NC}"

echo -e "${YELLOW}[3/7] Generating application key...${NC}"
php artisan key:generate
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: Failed to generate application key${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Application key generated${NC}"

echo -e "${YELLOW}[4/7] Installing JavaScript dependencies...${NC}"
npm install
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: npm install failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ JavaScript dependencies installed${NC}"

echo -e "${YELLOW}[5/7] Building assets...${NC}"
npm run build
if [ $? -ne 0 ]; then
    echo -e "${RED}ERROR: npm run build failed${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Assets built successfully${NC}"

echo -e "${YELLOW}[6/7] Setting up storage permissions...${NC}"
mkdir -p storage/logs bootstrap/cache
chmod -R 755 storage bootstrap/cache
if command_exists chown && [ -w /var/www ]; then
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null
fi
echo -e "${GREEN}✓ Permissions set${NC}"

echo -e "${YELLOW}[7/7] Clearing cache...${NC}"
php artisan config:clear
php artisan cache:clear
echo -e "${GREEN}✓ Cache cleared${NC}"

echo
echo -e "${GREEN}========================================"
echo "        INSTALASI BERHASIL! 🎉"
echo -e "========================================${NC}"
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
