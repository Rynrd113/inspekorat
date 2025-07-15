#!/bin/bash

# Start Laravel Server and Run Complete Testing Suite
# This script will start the Laravel development server and run all tests

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Configuration
BASE_URL="http://localhost:8000"
LARAVEL_LOG="storage/logs/laravel.log"
SERVER_PID=""

# Function to print styled messages
print_message() {
    local type=$1
    local message=$2
    
    case $type in
        "header")
            echo -e "\n${PURPLE}================================================================${NC}"
            echo -e "${PURPLE}$message${NC}"
            echo -e "${PURPLE}================================================================${NC}"
            ;;
        "success")
            echo -e "${GREEN}✓ $message${NC}"
            ;;
        "error")
            echo -e "${RED}✗ $message${NC}"
            ;;
        "warning")
            echo -e "${YELLOW}⚠ $message${NC}"
            ;;
        "info")
            echo -e "${CYAN}ℹ $message${NC}"
            ;;
        "section")
            echo -e "\n${BLUE}═══ $message ═══${NC}"
            ;;
    esac
}

# Function to check if server is running
check_server() {
    if curl -s --connect-timeout 3 "$BASE_URL" > /dev/null 2>&1; then
        return 0
    else
        return 1
    fi
}

# Function to start Laravel server
start_laravel_server() {
    print_section "Starting Laravel Development Server"
    
    # Check if server is already running
    if check_server; then
        print_message "warning" "Server is already running at $BASE_URL"
        return 0
    fi
    
    # Clear Laravel cache
    print_message "info" "Clearing Laravel cache..."
    php artisan cache:clear > /dev/null 2>&1
    php artisan config:clear > /dev/null 2>&1
    php artisan route:clear > /dev/null 2>&1
    php artisan view:clear > /dev/null 2>&1
    
    # Start server in background
    print_message "info" "Starting Laravel server at $BASE_URL..."
    nohup php artisan serve --host=0.0.0.0 --port=8000 > "$LARAVEL_LOG" 2>&1 &
    SERVER_PID=$!
    
    # Wait for server to start
    local attempts=0
    local max_attempts=30
    
    while [ $attempts -lt $max_attempts ]; do
        if check_server; then
            print_message "success" "Laravel server is now running at $BASE_URL (PID: $SERVER_PID)"
            return 0
        fi
        
        sleep 1
        attempts=$((attempts + 1))
        echo -n "."
    done
    
    print_message "error" "Failed to start Laravel server after $max_attempts seconds"
    return 1
}

# Function to stop Laravel server
stop_laravel_server() {
    print_section "Stopping Laravel Server"
    
    if [ -n "$SERVER_PID" ]; then
        print_message "info" "Stopping Laravel server (PID: $SERVER_PID)..."
        kill $SERVER_PID > /dev/null 2>&1
        
        # Wait for server to stop
        local attempts=0
        while [ $attempts -lt 10 ]; do
            if ! ps -p $SERVER_PID > /dev/null 2>&1; then
                print_message "success" "Laravel server stopped successfully"
                return 0
            fi
            sleep 1
            attempts=$((attempts + 1))
        done
        
        # Force kill if still running
        kill -9 $SERVER_PID > /dev/null 2>&1
        print_message "warning" "Laravel server force stopped"
    else
        print_message "info" "No server PID to stop"
    fi
}

# Function to run database migrations
run_migrations() {
    print_section "Running Database Migrations"
    
    if [ -f "artisan" ]; then
        print_message "info" "Running Laravel migrations..."
        
        # Run migrations
        if php artisan migrate --force > /dev/null 2>&1; then
            print_message "success" "Database migrations completed"
        else
            print_message "warning" "Database migrations failed or already up to date"
        fi
        
        # Run seeders
        print_message "info" "Running database seeders..."
        if php artisan db:seed --force > /dev/null 2>&1; then
            print_message "success" "Database seeders completed"
        else
            print_message "warning" "Database seeders failed or already run"
        fi
    else
        print_message "error" "Laravel artisan not found"
    fi
}

# Function to create test users
create_test_users() {
    print_section "Creating Test Users"
    
    # Create a PHP script to add test users
    cat > create_test_users.php << 'EOF'
<?php
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = new PDO(
        'mysql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? 'portal_inspektorat'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test users data
    $users = [
        [
            'name' => 'Admin',
            'email' => 'admin@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin'
        ],
        [
            'name' => 'Super Admin',
            'email' => 'superadmin@inspektorat.go.id',
            'password' => password_hash('superadmin123', PASSWORD_DEFAULT),
            'role' => 'superadmin'
        ],
        [
            'name' => 'WBS Admin',
            'email' => 'admin_wbs@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_wbs'
        ],
        [
            'name' => 'News Admin',
            'email' => 'admin_berita@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_berita'
        ],
        [
            'name' => 'Portal OPD Admin',
            'email' => 'admin_portal_opd@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_portal_opd'
        ],
        [
            'name' => 'Service Admin',
            'email' => 'admin_pelayanan@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_pelayanan'
        ],
        [
            'name' => 'Document Admin',
            'email' => 'admin_dokumen@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_dokumen'
        ],
        [
            'name' => 'Gallery Admin',
            'email' => 'admin_galeri@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_galeri'
        ],
        [
            'name' => 'FAQ Admin',
            'email' => 'admin_faq@inspektorat.go.id',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin_faq'
        ]
    ];
    
    $created = 0;
    $existing = 0;
    
    foreach ($users as $user) {
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$user['email']]);
        
        if ($stmt->rowCount() == 0) {
            // Create new user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$user['name'], $user['email'], $user['password'], $user['role']]);
            $created++;
        } else {
            $existing++;
        }
    }
    
    echo "Test users created: $created\n";
    echo "Test users already existing: $existing\n";
    echo "Total test users: " . ($created + $existing) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
EOF
    
    if php create_test_users.php; then
        print_message "success" "Test users created successfully"
    else
        print_message "warning" "Failed to create test users"
    fi
    
    # Clean up
    rm -f create_test_users.php
}

# Function to run comprehensive tests
run_comprehensive_tests() {
    print_section "Running Comprehensive Tests"
    
    # Make sure testing script is executable
    chmod +x final_comprehensive_testing.sh
    
    # Run the comprehensive testing suite
    print_message "info" "Starting comprehensive testing suite..."
    if ./final_comprehensive_testing.sh "$BASE_URL"; then
        print_message "success" "Comprehensive testing completed successfully"
    else
        print_message "error" "Comprehensive testing failed"
        return 1
    fi
}

# Function to display final summary
display_summary() {
    print_message "header" "TESTING SUMMARY"
    
    echo -e "\n${GREEN}🎉 TESTING COMPLETED! 🎉${NC}"
    
    echo -e "\n${CYAN}Server Information:${NC}"
    echo -e "${BLUE}  • Server URL: $BASE_URL${NC}"
    echo -e "${BLUE}  • Server PID: ${SERVER_PID:-'Not running'}${NC}"
    echo -e "${BLUE}  • Server Log: $LARAVEL_LOG${NC}"
    
    echo -e "\n${CYAN}Test Results:${NC}"
    if [ -d "final_test_results" ]; then
        echo -e "${BLUE}  • Results Directory: final_test_results/${NC}"
        echo -e "${BLUE}  • Generated Files:${NC}"
        find final_test_results -type f -name "*.html" -o -name "*.json" -o -name "*.txt" | head -10 | while read file; do
            echo -e "${BLUE}    → $(basename "$file")${NC}"
        done
        
        # Find the main report
        local main_report=$(find final_test_results -name "final_comprehensive_report_*.html" | head -1)
        if [ -n "$main_report" ]; then
            echo -e "\n${GREEN}📊 Main Report: $main_report${NC}"
        fi
    fi
    
    echo -e "\n${CYAN}Next Steps:${NC}"
    echo -e "${BLUE}  1. Review test results in the reports${NC}"
    echo -e "${BLUE}  2. Fix any identified issues${NC}"
    echo -e "${BLUE}  3. Run tests again to verify fixes${NC}"
    echo -e "${BLUE}  4. Check server logs if needed: tail -f $LARAVEL_LOG${NC}"
}

# Function to handle cleanup on exit
cleanup_on_exit() {
    print_section "Cleaning Up"
    stop_laravel_server
    exit 0
}

# Trap signals for cleanup
trap cleanup_on_exit INT TERM EXIT

# Main execution
main() {
    print_message "header" "LARAVEL SERVER & COMPREHENSIVE TESTING"
    
    # Check if we're in a Laravel project
    if [ ! -f "artisan" ] || [ ! -f "composer.json" ]; then
        print_message "error" "This doesn't appear to be a Laravel project directory"
        exit 1
    fi
    
    # Setup phase
    print_message "info" "Starting setup phase..."
    
    # Install dependencies if needed
    if [ ! -d "vendor" ]; then
        print_message "info" "Installing Composer dependencies..."
        composer install --no-dev --optimize-autoloader
    fi
    
    # Run setup script if available
    if [ -f "setup_testing.sh" ]; then
        print_message "info" "Running setup script..."
        chmod +x setup_testing.sh
        ./setup_testing.sh > /dev/null 2>&1
    fi
    
    # Start Laravel server
    if ! start_laravel_server; then
        print_message "error" "Failed to start Laravel server"
        exit 1
    fi
    
    # Run database migrations
    run_migrations
    
    # Create test users
    create_test_users
    
    # Wait a moment for everything to be ready
    sleep 3
    
    # Run comprehensive tests
    run_comprehensive_tests
    
    # Display summary
    display_summary
    
    # Ask if user wants to keep server running
    echo -e "\n${YELLOW}Do you want to keep the server running? (y/n): ${NC}"
    read -r keep_running
    
    if [ "$keep_running" != "y" ] && [ "$keep_running" != "Y" ]; then
        stop_laravel_server
    else
        print_message "info" "Server will continue running at $BASE_URL"
        print_message "info" "Press Ctrl+C to stop the server"
        
        # Keep script running to maintain server
        while true; do
            if ! check_server; then
                print_message "warning" "Server appears to have stopped"
                break
            fi
            sleep 30
        done
    fi
}

# Run main function
main "$@"
