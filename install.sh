#!/bin/bash

# ============================================
# Portal Inspektorat Papua Tengah
# Auto Installation Script
# ============================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DB_NAME="portal_inspektorat"
DB_USER="root"
DB_PASSWORD=""
APP_URL="http://localhost:8000"

# Functions
print_header() {
    echo ""
    echo "============================================"
    echo "🏛️  Portal Inspektorat Papua Tengah"
    echo "    Auto Installation Script"
    echo "============================================"
    echo ""
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

check_command() {
    if ! command -v $1 &> /dev/null; then
        print_error "$1 is not installed. Please install $1 first."
        exit 1
    fi
}

# Check if running as root (not recommended)
if [[ $EUID -eq 0 ]]; then
   print_warning "This script should not be run as root for security reasons."
   read -p "Continue anyway? (y/N): " -n 1 -r
   echo
   if [[ ! $REPLY =~ ^[Yy]$ ]]; then
       exit 1
   fi
fi

print_header

print_step "Checking system requirements..."

# Check required commands
check_command "php"
check_command "composer"
check_command "node"
check_command "npm"

# Check PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
if [[ $(echo "$PHP_VERSION >= 8.3" | bc -l) -eq 0 ]]; then
    print_error "PHP 8.3+ required. Current version: $PHP_VERSION"
    exit 1
fi

print_success "System requirements check passed"

# Database setup
print_step "Setting up database..."

# Check if MySQL is available
if command -v mysql &> /dev/null; then
    DB_ENGINE="mysql"
    print_success "MySQL detected"
elif command -v mariadb &> /dev/null; then
    DB_ENGINE="mariadb"
    print_success "MariaDB detected"
else
    print_warning "MySQL/MariaDB not found. Will use SQLite as fallback."
    DB_ENGINE="sqlite"
fi

if [[ "$DB_ENGINE" != "sqlite" ]]; then
    # Test database connection
    print_step "Testing database connection..."
    
    if mysql -u "$DB_USER" ${DB_PASSWORD:+-p"$DB_PASSWORD"} -e "SELECT 1;" &> /dev/null; then
        print_success "Database connection successful"
        
        # Create database
        print_step "Creating database '$DB_NAME'..."
        mysql -u "$DB_USER" ${DB_PASSWORD:+-p"$DB_PASSWORD"} -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || {
            print_error "Failed to create database. Please check your MySQL credentials."
            print_warning "You can manually create the database and run the script again."
            exit 1
        }
        print_success "Database '$DB_NAME' created successfully"
    else
        print_error "Cannot connect to MySQL. Please check your MySQL service and credentials."
        print_warning "Falling back to SQLite..."
        DB_ENGINE="sqlite"
    fi
fi

# Install PHP dependencies
print_step "Installing PHP dependencies..."
if ! composer install --no-interaction --prefer-dist --optimize-autoloader; then
    print_error "Failed to install PHP dependencies"
    exit 1
fi
print_success "PHP dependencies installed"

# Install Node.js dependencies
print_step "Installing Node.js dependencies..."
if ! npm ci; then
    print_warning "npm ci failed, trying npm install..."
    if ! npm install; then
        print_error "Failed to install Node.js dependencies"
        exit 1
    fi
fi
print_success "Node.js dependencies installed"

# Setup environment file
print_step "Setting up environment configuration..."
if [[ ! -f .env ]]; then
    cp .env.example .env
    print_success "Environment file created from template"
else
    print_warning "Environment file already exists, backing up..."
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

# Update .env file based on detected database
if [[ "$DB_ENGINE" == "sqlite" ]]; then
    # Configure for SQLite
    sed -i.bak \
        -e "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" \
        -e "s/DB_DATABASE=.*/DB_DATABASE=database\/database.sqlite/" \
        .env
    print_success "Environment configured for SQLite"
else
    # Configure for MySQL/MariaDB
    sed -i.bak \
        -e "s/DB_CONNECTION=.*/DB_CONNECTION=mysql/" \
        -e "s/DB_HOST=.*/DB_HOST=127.0.0.1/" \
        -e "s/DB_PORT=.*/DB_PORT=3306/" \
        -e "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" \
        -e "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" \
        -e "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" \
        .env
    print_success "Environment configured for MySQL"
fi

# Update APP_URL
sed -i.bak "s#APP_URL=.*#APP_URL=$APP_URL#" .env

# Generate application key
print_step "Generating application key..."
if ! php artisan key:generate --no-interaction; then
    print_error "Failed to generate application key"
    exit 1
fi
print_success "Application key generated"

# Create SQLite database file if needed
if [[ "$DB_ENGINE" == "sqlite" ]]; then
    print_step "Creating SQLite database file..."
    mkdir -p database
    touch database/database.sqlite
    print_success "SQLite database file created"
fi

# Test database connection
print_step "Testing Laravel database connection..."
if ! php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" 2>/dev/null; then
    print_error "Laravel cannot connect to database"
    exit 1
fi
print_success "Laravel database connection successful"

# Run database migrations
print_step "Running database migrations..."
if ! php artisan migrate --no-interaction --force; then
    print_error "Database migration failed"
    exit 1
fi
print_success "Database migrations completed"

# Run database seeders
print_step "Seeding database with sample data..."
if ! php artisan db:seed --no-interaction --force; then
    print_warning "Database seeding failed or no seeders available"
else
    print_success "Database seeding completed"
fi

# Create storage link
print_step "Creating storage symbolic link..."
if ! php artisan storage:link; then
    print_warning "Failed to create storage link (may already exist)"
else
    print_success "Storage symbolic link created"
fi

# Set proper permissions
print_step "Setting file permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || {
    print_warning "Could not set permissions. You may need to run: chmod -R 775 storage bootstrap/cache"
}
print_success "File permissions set"

# Build frontend assets
print_step "Building frontend assets..."
if ! npm run build; then
    print_warning "Production build failed, trying development build..."
    if ! npm run dev; then
        print_error "Failed to build frontend assets"
        exit 1
    fi
fi
print_success "Frontend assets built"

# Clear application cache
print_step "Clearing application cache..."
php artisan config:clear &>/dev/null || true
php artisan cache:clear &>/dev/null || true
php artisan view:clear &>/dev/null || true
php artisan route:clear &>/dev/null || true
print_success "Application cache cleared"

# Final verification
print_step "Running final verification..."

# Test if application is working
if php artisan tinker --execute="echo 'Laravel is working';" &>/dev/null; then
    print_success "Laravel application is ready"
else
    print_error "Laravel verification failed"
    exit 1
fi

# Installation completed
echo ""
echo "============================================"
echo -e "${GREEN}🎉 INSTALLATION COMPLETED SUCCESSFULLY! 🎉${NC}"
echo "============================================"
echo ""
echo "📋 Installation Summary:"
echo "  • Database Engine: $DB_ENGINE"
if [[ "$DB_ENGINE" != "sqlite" ]]; then
    echo "  • Database Name: $DB_NAME"
    echo "  • Database User: $DB_USER"
fi
echo "  • Application URL: $APP_URL"
echo ""
echo "🚀 Next Steps:"
echo "  1. Start the development server:"
echo "     php artisan serve"
echo ""
echo "  2. Open your browser and visit:"
echo "     $APP_URL"
echo ""
echo "  3. Admin Panel Access:"
echo "     URL: $APP_URL/admin/login"
echo "     Email: admin@admin.com"
echo "     Password: password"
echo ""
echo "⚠️  IMPORTANT SECURITY NOTES:"
echo "  • Change the default admin password immediately"
echo "  • Review and secure your .env file"
echo "  • Set proper file permissions for production"
echo ""
echo "📚 Documentation:"
echo "  • INSTALL.md - Detailed installation guide"
echo "  • DATABASE.md - Database setup and management"
echo "  • DEVELOPER.md - Development documentation"
echo ""
echo "✅ Ready to use Portal Inspektorat Papua Tengah!"
echo ""
