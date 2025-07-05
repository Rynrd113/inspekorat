#!/bin/bash
# dev-tools.sh - Development utilities for Portal Inspektorat Papua Tengah

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Function to print colored output
print_header() {
    echo
    echo -e "${PURPLE}=== $1 ===${NC}"
    echo
}

print_info() {
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
check_directory() {
    if [ ! -f "artisan" ]; then
        print_error "This script must be run from the Laravel project root directory!"
        exit 1
    fi
}

# Show main menu
show_menu() {
    clear
    echo -e "${CYAN}"
    echo "╔══════════════════════════════════════════════════════════════╗"
    echo "║             Portal Inspektorat Papua Tengah                 ║"
    echo "║                Development Tools Menu                        ║"
    echo "╚══════════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
    echo
    echo "Please select an option:"
    echo
    echo -e "${GREEN}Environment & Setup:${NC}"
    echo "  1) 🚀 Setup development environment"
    echo "  2) 🔧 Install/Update dependencies"
    echo "  3) 🔑 Generate application key"
    echo "  4) 🔗 Create storage link"
    echo
    echo -e "${GREEN}Database:${NC}"
    echo "  5) 🗄️  Run migrations"
    echo "  6) 🌱 Run seeders"
    echo "  7) 🔄 Reset database (migrate:fresh --seed)"
    echo "  8) 📊 Database status"
    echo
    echo -e "${GREEN}Cache & Optimization:${NC}"
    echo "  9) 🧹 Clear all caches"
    echo " 10) ⚡ Optimize for production"
    echo " 11) 🔍 Clear specific cache"
    echo
    echo -e "${GREEN}Frontend:${NC}"
    echo " 12) 🎨 Build frontend assets"
    echo " 13) 👀 Watch frontend assets"
    echo " 14) 🖼️  Generate favicons"
    echo " 15) 📱 Build for production"
    echo
    echo -e "${GREEN}Testing & Quality:${NC}"
    echo " 16) 🧪 Run tests"
    echo " 17) 📏 Code style check"
    echo " 18) 🔧 Fix code style"
    echo " 19) 📊 Generate coverage report"
    echo
    echo -e "${GREEN}Server & Logs:${NC}"
    echo " 20) 🌐 Start development server"
    echo " 21) 📋 View application logs"
    echo " 22) 🔍 Monitor logs (real-time)"
    echo " 23) 📈 System information"
    echo
    echo -e "${GREEN}Backup & Maintenance:${NC}"
    echo " 24) 💾 Create backup"
    echo " 25) 🧽 Clean up old files"
    echo " 26) 📦 Update project"
    echo
    echo -e "${RED}27) 🚪 Exit${NC}"
    echo
    echo -n "Enter your choice [1-27]: "
}

# Function implementations
setup_environment() {
    print_header "Setting up development environment"
    ./scripts/setup-dev.sh
}

install_dependencies() {
    print_header "Installing/Updating dependencies"
    print_info "Updating Composer dependencies..."
    composer install
    print_info "Updating NPM dependencies..."
    npm install
    print_success "Dependencies updated!"
}

generate_key() {
    print_header "Generating application key"
    php artisan key:generate
    print_success "Application key generated!"
}

create_storage_link() {
    print_header "Creating storage link"
    php artisan storage:link
    print_success "Storage link created!"
}

run_migrations() {
    print_header "Running database migrations"
    php artisan migrate
    print_success "Migrations completed!"
}

run_seeders() {
    print_header "Running database seeders"
    php artisan db:seed
    print_success "Seeders completed!"
}

reset_database() {
    print_header "Resetting database"
    print_warning "This will delete all data in the database!"
    read -p "Are you sure? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan migrate:fresh --seed
        print_success "Database reset completed!"
    else
        print_info "Database reset cancelled."
    fi
}

database_status() {
    print_header "Database status"
    print_info "Checking migration status..."
    php artisan migrate:status
    echo
    print_info "Database connection test..."
    php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection: OK';"
}

clear_caches() {
    print_header "Clearing all caches"
    php artisan optimize:clear
    composer dump-autoload
    print_success "All caches cleared!"
}

optimize_production() {
    print_header "Optimizing for production"
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    composer dump-autoloader --optimize --no-dev
    print_success "Production optimization completed!"
}

clear_specific_cache() {
    print_header "Clear specific cache"
    echo "Select cache to clear:"
    echo "1) Config cache"
    echo "2) Route cache"
    echo "3) View cache"
    echo "4) Application cache"
    echo "5) All caches"
    echo
    read -p "Enter choice [1-5]: " cache_choice
    
    case $cache_choice in
        1) php artisan config:clear; print_success "Config cache cleared!" ;;
        2) php artisan route:clear; print_success "Route cache cleared!" ;;
        3) php artisan view:clear; print_success "View cache cleared!" ;;
        4) php artisan cache:clear; print_success "Application cache cleared!" ;;
        5) php artisan optimize:clear; print_success "All caches cleared!" ;;
        *) print_error "Invalid choice!" ;;
    esac
}

build_frontend() {
    print_header "Building frontend assets"
    npm run build
    print_success "Frontend assets built!"
}

watch_frontend() {
    print_header "Watching frontend assets"
    print_info "Starting Vite development server..."
    print_info "Press Ctrl+C to stop"
    npm run dev
}

generate_favicons() {
    print_header "Generating favicons"
    if [ -f "./scripts/generate-favicons.sh" ]; then
        ./scripts/generate-favicons.sh
    else
        print_error "Favicon generation script not found!"
    fi
}

build_production() {
    print_header "Building for production"
    print_info "Installing production dependencies..."
    npm ci --prefer-offline --no-audit
    print_info "Building assets..."
    npm run build
    print_success "Production build completed!"
}

run_tests() {
    print_header "Running tests"
    if [ -f "vendor/bin/phpunit" ]; then
        vendor/bin/phpunit
    else
        print_error "PHPUnit not found! Install with: composer require --dev phpunit/phpunit"
    fi
}

code_style_check() {
    print_header "Code style check"
    if [ -f "vendor/bin/php-cs-fixer" ]; then
        vendor/bin/php-cs-fixer fix --dry-run --diff
    else
        print_warning "PHP CS Fixer not installed. Install with:"
        print_info "composer require --dev friendsofphp/php-cs-fixer"
    fi
}

fix_code_style() {
    print_header "Fixing code style"
    if [ -f "vendor/bin/php-cs-fixer" ]; then
        vendor/bin/php-cs-fixer fix
        print_success "Code style fixed!"
    else
        print_warning "PHP CS Fixer not installed. Install with:"
        print_info "composer require --dev friendsofphp/php-cs-fixer"
    fi
}

generate_coverage() {
    print_header "Generating coverage report"
    if [ -f "vendor/bin/phpunit" ]; then
        vendor/bin/phpunit --coverage-html coverage/
        print_success "Coverage report generated in coverage/ directory"
    else
        print_error "PHPUnit not found!"
    fi
}

start_server() {
    print_header "Starting development server"
    print_info "Starting Laravel development server on http://localhost:8000"
    print_info "Press Ctrl+C to stop"
    php artisan serve
}

view_logs() {
    print_header "Application logs"
    if [ -f "storage/logs/laravel.log" ]; then
        tail -n 50 storage/logs/laravel.log
    else
        print_warning "No log file found."
    fi
}

monitor_logs() {
    print_header "Monitoring logs (real-time)"
    print_info "Press Ctrl+C to stop monitoring"
    if [ -f "storage/logs/laravel.log" ]; then
        tail -f storage/logs/laravel.log
    else
        print_warning "No log file found."
    fi
}

system_info() {
    print_header "System information"
    echo -e "${BLUE}PHP Version:${NC} $(php -r 'echo PHP_VERSION;')"
    echo -e "${BLUE}Laravel Version:${NC} $(php artisan --version | cut -d ' ' -f 3)"
    echo -e "${BLUE}Composer Version:${NC} $(composer --version | cut -d ' ' -f 3)"
    echo -e "${BLUE}Node.js Version:${NC} $(node --version)"
    echo -e "${BLUE}NPM Version:${NC} $(npm --version)"
    echo
    echo -e "${BLUE}Database Connection:${NC}"
    php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Connected to: ' . DB::connection()->getDatabaseName(); } catch(Exception \$e) { echo 'Connection failed: ' . \$e->getMessage(); }"
    echo
    echo -e "${BLUE}Disk Usage:${NC}"
    df -h . | tail -1
    echo
    echo -e "${BLUE}Memory Usage:${NC}"
    free -h 2>/dev/null || echo "Memory info not available on this system"
}

create_backup() {
    print_header "Creating backup"
    DATE=$(date +%Y%m%d_%H%M%S)
    BACKUP_DIR="backups"
    
    mkdir -p $BACKUP_DIR
    
    print_info "Creating database backup..."
    php artisan db:backup
    
    print_info "Creating files backup..."
    tar -czf $BACKUP_DIR/files_$DATE.tar.gz storage/app/public
    
    print_success "Backup created in $BACKUP_DIR directory"
}

cleanup_files() {
    print_header "Cleaning up old files"
    
    print_info "Cleaning Laravel caches..."
    php artisan optimize:clear
    
    print_info "Cleaning old log files..."
    find storage/logs/ -name "*.log" -mtime +30 -delete 2>/dev/null || true
    
    print_info "Cleaning node_modules cache..."
    npm cache clean --force
    
    print_info "Cleaning composer cache..."
    composer clear-cache
    
    print_success "Cleanup completed!"
}

update_project() {
    print_header "Updating project"
    
    print_info "Pulling latest changes..."
    git pull origin main
    
    print_info "Updating dependencies..."
    composer install
    npm install
    
    print_info "Running migrations..."
    php artisan migrate
    
    print_info "Optimizing..."
    php artisan optimize:clear
    npm run build
    
    print_success "Project updated!"
}

# Main script execution
main() {
    check_directory
    
    while true; do
        show_menu
        read choice
        
        case $choice in
            1) setup_environment ;;
            2) install_dependencies ;;
            3) generate_key ;;
            4) create_storage_link ;;
            5) run_migrations ;;
            6) run_seeders ;;
            7) reset_database ;;
            8) database_status ;;
            9) clear_caches ;;
            10) optimize_production ;;
            11) clear_specific_cache ;;
            12) build_frontend ;;
            13) watch_frontend ;;
            14) generate_favicons ;;
            15) build_production ;;
            16) run_tests ;;
            17) code_style_check ;;
            18) fix_code_style ;;
            19) generate_coverage ;;
            20) start_server ;;
            21) view_logs ;;
            22) monitor_logs ;;
            23) system_info ;;
            24) create_backup ;;
            25) cleanup_files ;;
            26) update_project ;;
            27) 
                echo
                print_success "Thank you for using Portal Inspektorat development tools!"
                exit 0 
                ;;
            *)
                print_error "Invalid choice. Please try again."
                ;;
        esac
        
        echo
        read -p "Press Enter to continue..."
    done
}

# Run main function
main
