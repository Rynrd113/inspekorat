#!/bin/bash

# ================================================================
# HEADLESS DUSK TESTS RUNNER - Portal Inspektorat Papua Tengah
# XAMPP COMPATIBLE VERSION - NO UI/BROWSER
# ================================================================
# Script untuk menjalankan test Dusk headless (tanpa UI)
# Author: Claude AI Assistant
# Date: 2025-07-18
# Compatible with: XAMPP on Linux Fedora
# ================================================================

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

# Test configuration
PROJECT_PATH="/home/rynrd/Documents/Project/agent/inspekorat"
TEST_DATABASE="inspekorat_dusk_test"
LOG_FILE="$PROJECT_PATH/storage/logs/dusk_headless_test_$(date +%Y%m%d_%H%M%S).log"
REPORT_FILE="$PROJECT_PATH/HEADLESS_TEST_REPORT_$(date +%Y%m%d_%H%M%S).md"

# XAMPP paths for Linux Fedora
XAMPP_PATH="/opt/lampp"
XAMPP_MYSQL="$XAMPP_PATH/bin/mysql"

# Function to print colored output
print_header() {
    echo -e "\n${BLUE}================================================================${NC}"
    echo -e "${WHITE}$1${NC}"
    echo -e "${BLUE}================================================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "${CYAN}ℹ️  $1${NC}"
}

print_step() {
    echo -e "${PURPLE}🔄 $1${NC}"
}

# Function to check if XAMPP is running
check_xampp_status() {
    print_step "Checking XAMPP status..."
    
    if [ ! -d "$XAMPP_PATH" ]; then
        print_error "XAMPP not found in $XAMPP_PATH"
        exit 1
    fi
    
    if ! pgrep -x "mysqld" > /dev/null; then
        print_warning "MySQL is not running"
        print_step "Starting XAMPP MySQL..."
        sudo "$XAMPP_PATH/xampp" startmysql
        sleep 3
        
        if ! pgrep -x "mysqld" > /dev/null; then
            print_error "Failed to start MySQL"
            exit 1
        fi
    fi
    
    print_success "XAMPP MySQL is running"
}

# Function to run command with error handling
run_command() {
    local cmd="$1"
    local desc="$2"
    
    print_step "$desc"
    echo "Running: $cmd" >> "$LOG_FILE"
    
    if eval "$cmd" >> "$LOG_FILE" 2>&1; then
        print_success "$desc completed successfully"
        return 0
    else
        print_error "$desc failed"
        echo "Error occurred while: $desc" >> "$LOG_FILE"
        return 1
    fi
}

# Function to setup environment without seeding
setup_environment() {
    print_header "SETTING UP HEADLESS TEST ENVIRONMENT"
    
    # Change to project directory
    cd "$PROJECT_PATH" || {
        print_error "Cannot change to project directory: $PROJECT_PATH"
        exit 1
    }
    
    # Check XAMPP status
    check_xampp_status
    
    # Create logs directory
    mkdir -p "$(dirname "$LOG_FILE")"
    
    # Initialize log file
    echo "=== HEADLESS DUSK TEST LOG - $(date) ===" > "$LOG_FILE"
    echo "=== XAMPP Environment - No UI Mode ===" >> "$LOG_FILE"
    
    print_success "Environment setup completed"
}

# Function to prepare database without seeding
prepare_database() {
    print_header "PREPARING TEST DATABASE (NO SEEDING)"
    
    # Check if .env.dusk.local exists
    if [ ! -f ".env.dusk.local" ]; then
        print_step "Creating .env.dusk.local file..."
        cp .env .env.dusk.local
        
        # Update database configuration for XAMPP
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$TEST_DATABASE/" .env.dusk.local
        sed -i "s/APP_ENV=.*/APP_ENV=dusk.local/" .env.dusk.local
        sed -i "s/DB_HOST=.*/DB_HOST=127.0.0.1/" .env.dusk.local
        sed -i "s/DB_PORT=.*/DB_PORT=3306/" .env.dusk.local
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=root/" .env.dusk.local
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=/" .env.dusk.local
        
        # Add Dusk specific settings for headless mode
        echo "" >> .env.dusk.local
        echo "# Dusk Testing Configuration - Headless Mode" >> .env.dusk.local
        echo "DUSK_HEADLESS_DISABLED=0" >> .env.dusk.local
        echo "DUSK_DRIVER_URL=http://localhost:9515" >> .env.dusk.local
        echo "APP_URL=http://localhost:8000" >> .env.dusk.local
        
        print_success ".env.dusk.local created and configured for headless mode"
    fi
    
    # Create test database using XAMPP MySQL
    print_step "Setting up test database..."
    local mysql_cmd="${XAMPP_MYSQL}"
    
    if [ -f "$mysql_cmd" ]; then
        # Try to create database
        if $mysql_cmd -u root -h 127.0.0.1 -e "DROP DATABASE IF EXISTS $TEST_DATABASE; CREATE DATABASE $TEST_DATABASE;" 2>/dev/null; then
            print_success "Test database created successfully"
        else
            print_warning "Database creation may have failed. Continuing with existing database..."
        fi
    else
        # Use system mysql if available
        if command -v mysql &> /dev/null; then
            if mysql -u root -h 127.0.0.1 -e "DROP DATABASE IF EXISTS $TEST_DATABASE; CREATE DATABASE $TEST_DATABASE;" 2>/dev/null; then
                print_success "Test database created successfully"
            else
                print_warning "Database creation may have failed. Continuing with existing database..."
            fi
        else
            print_warning "MySQL client not found. Skipping database creation."
        fi
    fi
    
    # Run migrations only (no seeding)
    run_command "php artisan migrate:fresh --env=dusk.local --force" "Running database migrations"
    
    print_success "Database preparation completed (no seeding)"
}

# Function to install Chrome driver
install_chrome_driver() {
    print_header "INSTALLING CHROME DRIVER"
    
    # Install Dusk
    run_command "php artisan dusk:install" "Installing Laravel Dusk"
    
    # Update Chrome driver
    run_command "php artisan dusk:chrome-driver --detect" "Updating Chrome driver"
    
    print_success "Chrome driver installation completed"
}

# Function to create minimal test data
create_minimal_test_data() {
    print_header "CREATING MINIMAL TEST DATA"
    
    print_step "Creating minimal test data via tinker..."
    
    # Create test data using tinker
    cat > /tmp/create_test_data.php << 'EOF'
<?php

use App\Models\User;
use App\Models\PortalOpd;
use App\Models\Pelayanan;
use App\Models\Faq;
use App\Models\Wbs;

// Create test users
$users = [
    ['name' => 'Super Admin Test', 'email' => 'superadmin@test.com', 'password' => bcrypt('password123'), 'role' => 'super_admin', 'status' => 'active'],
    ['name' => 'Admin Test', 'email' => 'admin@test.com', 'password' => bcrypt('password123'), 'role' => 'admin', 'status' => 'active'],
    ['name' => 'Content Manager Test', 'email' => 'content@test.com', 'password' => bcrypt('password123'), 'role' => 'content_manager', 'status' => 'active'],
];

foreach ($users as $userData) {
    if (!User::where('email', $userData['email'])->exists()) {
        User::create($userData);
        echo "Created user: " . $userData['email'] . "\n";
    }
}

// Create test Portal OPD
for ($i = 1; $i <= 5; $i++) {
    $opd = [
        'nama_opd' => "Test OPD $i",
        'singkatan' => "TO$i",
        'deskripsi' => "Test OPD description $i",
        'alamat' => "Test Address $i",
        'telepon' => "12345678$i",
        'email' => "opd$i@test.com",
        'kepala_opd' => "Test Head $i",
        'nip_kepala' => "12345678$i",
        'visi' => "Test Vision $i",
        'misi' => ["Test Mission $i"],
        'status' => true,
        'created_by' => 1,
    ];
    
    if (!PortalOpd::where('email', $opd['email'])->exists()) {
        PortalOpd::create($opd);
        echo "Created Portal OPD: " . $opd['nama_opd'] . "\n";
    }
}

// Create test Pelayanan
for ($i = 1; $i <= 5; $i++) {
    $pelayanan = [
        'nama' => "Test Service $i",
        'deskripsi' => "Test service description $i",
        'prosedur' => "Test procedure $i",
        'persyaratan' => "Test requirements $i",
        'waktu_penyelesaian' => "$i hari",
        'biaya' => "Rp " . ($i * 10000),
        'kategori' => 'administrasi',
        'status' => true,
        'created_by' => 1,
    ];
    
    if (!Pelayanan::where('nama', $pelayanan['nama'])->exists()) {
        Pelayanan::create($pelayanan);
        echo "Created Pelayanan: " . $pelayanan['nama'] . "\n";
    }
}

// Create test FAQ
for ($i = 1; $i <= 5; $i++) {
    $faq = [
        'pertanyaan' => "Test FAQ Question $i?",
        'jawaban' => "Test FAQ answer $i",
        'kategori' => 'umum',
        'status' => true,
        'created_by' => 1,
    ];
    
    if (!Faq::where('pertanyaan', $faq['pertanyaan'])->exists()) {
        Faq::create($faq);
        echo "Created FAQ: " . $faq['pertanyaan'] . "\n";
    }
}

// Create test WBS
for ($i = 1; $i <= 3; $i++) {
    $wbs = [
        'nama_pelapor' => "Test Reporter $i",
        'email' => "reporter$i@test.com",
        'subjek' => "Test WBS $i",
        'deskripsi' => "Test WBS description $i",
        'status' => 'pending',
        'is_anonymous' => false,
        'created_by' => 1,
    ];
    
    if (!Wbs::where('email', $wbs['email'])->exists()) {
        Wbs::create($wbs);
        echo "Created WBS: " . $wbs['subjek'] . "\n";
    }
}

echo "Test data creation completed!\n";
EOF

    # Execute the test data creation
    if php artisan tinker --env=dusk.local < /tmp/create_test_data.php >> "$LOG_FILE" 2>&1; then
        print_success "Minimal test data created successfully"
    else
        print_warning "Test data creation may have failed, but continuing..."
    fi
    
    # Clean up
    rm -f /tmp/create_test_data.php
}

# Function to start Laravel development server
start_laravel_server() {
    print_header "STARTING LARAVEL DEVELOPMENT SERVER"
    
    # Check if server is already running
    if pgrep -f "php artisan serve" > /dev/null; then
        print_success "Laravel development server is already running"
        return 0
    fi
    
    print_step "Starting Laravel development server on port 8000..."
    nohup php artisan serve --port=8000 > /dev/null 2>&1 &
    
    # Wait for server to start
    sleep 5
    
    # Check if server started successfully
    if curl -s http://localhost:8000 > /dev/null 2>&1; then
        print_success "Laravel development server started successfully"
        return 0
    else
        print_warning "Laravel development server may not have started properly"
        return 1
    fi
}

# Function to run headless tests
run_headless_tests() {
    print_header "RUNNING HEADLESS DUSK TESTS"
    
    # Initialize report file
    echo "# 🧪 HEADLESS DUSK TEST REPORT - Portal Inspektorat Papua Tengah" > "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "**Generated:** $(date)" >> "$REPORT_FILE"
    echo "**Environment:** XAMPP on Linux Fedora (Headless Mode)" >> "$REPORT_FILE"
    echo "**Test Mode:** No UI/Browser" >> "$REPORT_FILE"
    echo "**Database:** $TEST_DATABASE" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "## 📊 Test Results Summary" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    
    local total_tests=0
    local passed_tests=0
    local failed_tests=0
    
    # Define essential tests that work well in headless mode
    declare -a essential_tests=(
        "test_database_structure_complete:Database Structure Test"
        "test_extended_user_roles_functionality:Extended User Roles Test"
        "test_comprehensive_system_health:System Health Test"
    )
    
    # Run essential tests
    for test_info in "${essential_tests[@]}"; do
        IFS=':' read -r test_method test_name <<< "$test_info"
        
        print_step "Running $test_name..."
        local start_time=$(date +%s)
        
        # Run test in headless mode
        if php artisan dusk --filter="$test_method" --verbose >> "$LOG_FILE" 2>&1; then
            local end_time=$(date +%s)
            local duration=$((end_time - start_time))
            print_success "$test_name completed in ${duration}s"
            echo "✅ $test_name - PASSED (${duration}s)" >> "$REPORT_FILE"
            ((passed_tests++))
        else
            local end_time=$(date +%s)
            local duration=$((end_time - start_time))
            print_error "$test_name failed after ${duration}s"
            echo "❌ $test_name - FAILED (${duration}s)" >> "$REPORT_FILE"
            ((failed_tests++))
        fi
        ((total_tests++))
    done
    
    # Try to run some comprehensive tests if available
    if [ -f "tests/Browser/SystemImprovementsVerificationTest.php" ]; then
        print_step "Running System Improvements Test..."
        local start_time=$(date +%s)
        
        if php artisan dusk tests/Browser/SystemImprovementsVerificationTest.php --verbose >> "$LOG_FILE" 2>&1; then
            local end_time=$(date +%s)
            local duration=$((end_time - start_time))
            print_success "System Improvements Test completed in ${duration}s"
            echo "✅ System Improvements Test - PASSED (${duration}s)" >> "$REPORT_FILE"
            ((passed_tests++))
        else
            local end_time=$(date +%s)
            local duration=$((end_time - start_time))
            print_warning "System Improvements Test failed after ${duration}s"
            echo "⚠️ System Improvements Test - FAILED (${duration}s)" >> "$REPORT_FILE"
            ((failed_tests++))
        fi
        ((total_tests++))
    fi
    
    # Generate final report
    echo "" >> "$REPORT_FILE"
    echo "## 🎯 Final Results" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "- **Total Tests:** $total_tests" >> "$REPORT_FILE"
    echo "- **Passed:** $passed_tests" >> "$REPORT_FILE"
    echo "- **Failed:** $failed_tests" >> "$REPORT_FILE"
    
    if [ $total_tests -gt 0 ]; then
        echo "- **Success Rate:** $(( passed_tests * 100 / total_tests ))%" >> "$REPORT_FILE"
    else
        echo "- **Success Rate:** 0% (No tests executed)" >> "$REPORT_FILE"
    fi
    
    echo "" >> "$REPORT_FILE"
    echo "## 📝 Full Log" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "Complete log saved to: \`$LOG_FILE\`" >> "$REPORT_FILE"
    
    # Print summary
    print_header "HEADLESS TEST EXECUTION SUMMARY"
    print_info "Total Tests: $total_tests"
    print_info "Passed: $passed_tests"
    print_info "Failed: $failed_tests"
    
    if [ $total_tests -gt 0 ]; then
        print_info "Success Rate: $(( passed_tests * 100 / total_tests ))%"
    else
        print_warning "No tests were executed"
    fi
    
    if [ $failed_tests -eq 0 ] && [ $total_tests -gt 0 ]; then
        print_success "ALL TESTS PASSED! 🎉"
        return 0
    else
        print_error "Some tests failed or no tests were executed."
        return 1
    fi
}

# Function to cleanup
cleanup() {
    print_header "CLEANING UP"
    
    # Clear cache
    run_command "php artisan cache:clear" "Clearing cache"
    run_command "php artisan config:clear" "Clearing config cache"
    
    # Stop Laravel server if we started it
    if pgrep -f "php artisan serve" > /dev/null; then
        print_step "Stopping Laravel development server..."
        pkill -f "php artisan serve"
    fi
    
    print_success "Cleanup completed"
}

# Function to show final results
show_final_results() {
    print_header "HEADLESS TEST EXECUTION COMPLETED"
    
    print_info "Report File: $REPORT_FILE"
    print_info "Log File: $LOG_FILE"
    
    # Display report content
    if [ -f "$REPORT_FILE" ]; then
        print_step "Displaying final report..."
        cat "$REPORT_FILE"
    fi
    
    print_success "Headless test execution completed!"
}

# Main execution function
main() {
    local start_time=$(date +%s)
    
    print_header "HEADLESS DUSK TESTS EXECUTION - Portal Inspektorat Papua Tengah"
    print_info "Starting headless test execution at $(date)"
    print_info "Environment: XAMPP on Linux Fedora"
    print_info "Mode: Headless (No UI/Browser)"
    print_info "Estimated duration: 10-15 minutes"
    
    # Setup environment
    setup_environment
    
    # Prepare database
    prepare_database
    
    # Install Chrome driver
    install_chrome_driver
    
    # Create minimal test data
    create_minimal_test_data
    
    # Start Laravel server
    start_laravel_server
    
    # Run headless tests
    run_headless_tests
    
    # Cleanup
    cleanup
    
    # Show final results
    show_final_results
    
    local end_time=$(date +%s)
    local total_duration=$((end_time - start_time))
    local minutes=$((total_duration / 60))
    local seconds=$((total_duration % 60))
    
    print_success "Total execution time: ${minutes}m ${seconds}s"
    
    return 0
}

# Handle script interruption
cleanup_on_exit() {
    print_warning "Script interrupted. Cleaning up..."
    cleanup
    exit 1
}

# Set trap for cleanup on exit
trap cleanup_on_exit INT TERM

# Check if script is run with proper permissions
if [ "$EUID" -eq 0 ]; then
    print_error "Don't run this script as root"
    exit 1
fi

# Run main function
main "$@"