#!/bin/bash
# setup-dev.sh - Development Environment Setup Script
# Portal Inspektorat Papua Tengah

set -e

echo "🚀 Setting up development environment for Portal Inspektorat Papua Tengah..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory!"
    exit 1
fi

# Check PHP version
print_status "Checking PHP version..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
if php -r "exit(version_compare(PHP_VERSION, '8.2.0', '<') ? 1 : 0);"; then
    print_error "PHP 8.2 or higher is required. Current version: $PHP_VERSION"
    exit 1
fi
print_success "PHP version: $PHP_VERSION ✓"

# Check Composer
print_status "Checking Composer..."
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed. Please install Composer first."
    exit 1
fi
COMPOSER_VERSION=$(composer --version | cut -d ' ' -f 3)
print_success "Composer version: $COMPOSER_VERSION ✓"

# Check Node.js
print_status "Checking Node.js..."
if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Please install Node.js 18+ first."
    exit 1
fi
NODE_VERSION=$(node --version)
print_success "Node.js version: $NODE_VERSION ✓"

# Check NPM
print_status "Checking NPM..."
if ! command -v npm &> /dev/null; then
    print_error "NPM is not installed. Please install NPM first."
    exit 1
fi
NPM_VERSION=$(npm --version)
print_success "NPM version: $NPM_VERSION ✓"

# Install Composer dependencies
print_status "Installing Composer dependencies..."
composer install
print_success "Composer dependencies installed ✓"

# Install NPM dependencies
print_status "Installing NPM dependencies..."
npm install
print_success "NPM dependencies installed ✓"

# Setup environment file
print_status "Setting up environment file..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    print_success ".env file created from .env.example ✓"
else
    print_warning ".env file already exists, skipping..."
fi

# Generate application key
print_status "Generating application key..."
php artisan key:generate
print_success "Application key generated ✓"

# Setup database
print_status "Setting up database..."
read -p "Do you want to run database migrations? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if php artisan migrate; then
        print_success "Database migrations completed ✓"
        
        read -p "Do you want to seed the database with sample data? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan db:seed
            print_success "Database seeded with sample data ✓"
        fi
    else
        print_warning "Database migrations failed. Please check your database configuration."
    fi
else
    print_warning "Skipping database setup. You can run 'php artisan migrate' later."
fi

# Create storage link
print_status "Creating storage link..."
php artisan storage:link
print_success "Storage link created ✓"

# Set proper permissions (for Linux/macOS)
if [[ "$OSTYPE" == "linux-gnu"* ]] || [[ "$OSTYPE" == "darwin"* ]]; then
    print_status "Setting proper permissions..."
    chmod -R 775 storage bootstrap/cache
    print_success "Permissions set ✓"
fi

# Build frontend assets
print_status "Building frontend assets..."
npm run build
print_success "Frontend assets built ✓"

# Final success message
echo
print_success "🎉 Development environment setup completed!"
echo
echo -e "${BLUE}Next steps:${NC}"
echo "1. Configure your database settings in the .env file"
echo "2. Run 'php artisan migrate --seed' if you skipped database setup"
echo "3. Start the development server with 'php artisan serve'"
echo "4. Start the frontend development server with 'npm run dev'"
echo
echo -e "${BLUE}Access points:${NC}"
echo "- Frontend: http://localhost:8000"
echo "- Admin Panel: http://localhost:8000/admin"
echo "  Email: admin@papuatengah.go.id"
echo "  Password: password"
echo
echo -e "${GREEN}Happy coding! 🚀${NC}"
