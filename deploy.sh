#!/bin/bash

# ============================================
# Portal Inspektorat Papua Tengah
# Deployment Script untuk Production Server
# ============================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="portal-inspektorat"
BACKUP_DIR="/var/backups/$PROJECT_NAME"
PROJECT_DIR="/var/www/$PROJECT_NAME"
NGINX_CONFIG="/etc/nginx/sites-available/$PROJECT_NAME"

echo -e "${BLUE}=== Portal Inspektorat Papua Tengah Deployment ===${NC}"
echo -e "${YELLOW}Starting deployment process...${NC}"

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   echo -e "${RED}This script should not be run as root${NC}" 
   exit 1
fi

# Function to print status
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."
    
    # Check if git is installed
    if ! command -v git &> /dev/null; then
        print_error "Git is not installed"
        exit 1
    fi
    
    # Check if composer is installed
    if ! command -v composer &> /dev/null; then
        print_error "Composer is not installed"
        exit 1
    fi
    
    # Check if npm is installed
    if ! command -v npm &> /dev/null; then
        print_error "NPM is not installed"
        exit 1
    fi
    
    # Check if php is installed
    if ! command -v php &> /dev/null; then
        print_error "PHP is not installed"
        exit 1
    fi
    
    print_status "All prerequisites satisfied"
}

# Create backup
create_backup() {
    print_status "Creating backup..."
    
    if [ -d "$PROJECT_DIR" ]; then
        TIMESTAMP=$(date +%Y%m%d_%H%M%S)
        sudo mkdir -p "$BACKUP_DIR"
        
        # Backup database
        if [ -f "$PROJECT_DIR/.env" ]; then
            DB_NAME=$(grep DB_DATABASE "$PROJECT_DIR/.env" | cut -d '=' -f2)
            DB_USER=$(grep DB_USERNAME "$PROJECT_DIR/.env" | cut -d '=' -f2)
            DB_PASS=$(grep DB_PASSWORD "$PROJECT_DIR/.env" | cut -d '=' -f2)
            
            if [ ! -z "$DB_NAME" ]; then
                print_status "Backing up database: $DB_NAME"
                mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_DIR/db_backup_$TIMESTAMP.sql"
            fi
        fi
        
        # Backup files
        print_status "Backing up files..."
        sudo tar -czf "$BACKUP_DIR/files_backup_$TIMESTAMP.tar.gz" -C "/var/www" "$PROJECT_NAME" --exclude=node_modules --exclude=vendor
        
        print_status "Backup completed: $BACKUP_DIR/backup_$TIMESTAMP"
    fi
}

# Deploy application
deploy_application() {
    print_status "Deploying application..."
    
    cd "$PROJECT_DIR"
    
    # Pull latest changes
    print_status "Pulling latest changes from repository..."
    git pull origin main
    
    # Install/update composer dependencies
    print_status "Installing PHP dependencies..."
    composer install --optimize-autoloader --no-dev --no-interaction
    
    # Install/update npm dependencies and build assets
    print_status "Installing Node.js dependencies..."
    npm ci --silent
    
    print_status "Building production assets..."
    npm run build
    
    # Run database migrations
    print_status "Running database migrations..."
    php artisan migrate --force --no-interaction
    
    # Clear and optimize caches
    print_status "Optimizing application..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link
    
    # Set proper permissions
    print_status "Setting file permissions..."
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 755 storage bootstrap/cache
    
    print_status "Application deployed successfully"
}

# Restart services
restart_services() {
    print_status "Restarting services..."
    
    # Restart PHP-FPM
    sudo systemctl reload php8.3-fpm
    
    # Restart Nginx
    sudo systemctl reload nginx
    
    # Check if services are running
    if systemctl is-active --quiet nginx; then
        print_status "Nginx is running"
    else
        print_error "Nginx is not running"
        exit 1
    fi
    
    if systemctl is-active --quiet php8.3-fpm; then
        print_status "PHP-FPM is running"
    else
        print_error "PHP-FPM is not running"
        exit 1
    fi
}

# Health check
health_check() {
    print_status "Performing health check..."
    
    # Check if application responds
    if curl -f -s http://localhost > /dev/null; then
        print_status "Application is responding"
    else
        print_warning "Application may not be responding properly"
    fi
    
    # Check log for errors
    if [ -f "$PROJECT_DIR/storage/logs/laravel.log" ]; then
        ERROR_COUNT=$(tail -100 "$PROJECT_DIR/storage/logs/laravel.log" | grep -c "ERROR" || true)
        if [ $ERROR_COUNT -gt 0 ]; then
            print_warning "Found $ERROR_COUNT recent errors in application log"
        else
            print_status "No recent errors found in application log"
        fi
    fi
}

# Cleanup old backups
cleanup_backups() {
    print_status "Cleaning up old backups..."
    
    # Keep only last 7 days of backups
    find "$BACKUP_DIR" -name "*.sql" -mtime +7 -delete 2>/dev/null || true
    find "$BACKUP_DIR" -name "*.tar.gz" -mtime +7 -delete 2>/dev/null || true
    
    print_status "Backup cleanup completed"
}

# Main deployment process
main() {
    echo -e "${BLUE}Starting deployment at $(date)${NC}"
    
    check_prerequisites
    create_backup
    deploy_application
    restart_services
    health_check
    cleanup_backups
    
    echo -e "${GREEN}=== Deployment completed successfully ===${NC}"
    echo -e "${GREEN}Portal Inspektorat Papua Tengah is now updated!${NC}"
    echo -e "${BLUE}Deployment finished at $(date)${NC}"
}

# Handle script arguments
case "${1:-deploy}" in
    "deploy")
        main
        ;;
    "backup")
        create_backup
        ;;
    "health")
        health_check
        ;;
    "restart")
        restart_services
        ;;
    *)
        echo "Usage: $0 {deploy|backup|health|restart}"
        echo "  deploy  - Full deployment process (default)"
        echo "  backup  - Create backup only"
        echo "  health  - Health check only"
        echo "  restart - Restart services only"
        exit 1
        ;;
esac
