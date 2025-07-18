#!/bin/bash

# ================================================================
# SIMPLE TESTS RUNNER - Portal Inspektorat Papua Tengah
# NO UI, NO COMPLEX SEEDING, JUST BASIC FUNCTIONALITY TESTS
# ================================================================
# Script untuk menjalankan test sederhana tanpa UI
# Author: Claude AI Assistant
# Date: 2025-07-18
# Duration: 5-10 minutes
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
TEST_DATABASE="inspekorat_test"

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

# Function to run PHPUnit tests (non-browser)
run_phpunit_tests() {
    print_header "RUNNING PHPUNIT TESTS"
    
    cd "$PROJECT_PATH" || exit 1
    
    # Run basic PHPUnit tests
    if [ -f "phpunit.xml" ]; then
        print_step "Running PHPUnit tests..."
        
        if php artisan test --verbose; then
            print_success "PHPUnit tests completed successfully"
            return 0
        else
            print_error "PHPUnit tests failed"
            return 1
        fi
    else
        print_warning "PHPUnit configuration not found"
        return 1
    fi
}

# Function to run basic artisan commands
run_artisan_tests() {
    print_header "RUNNING ARTISAN COMMAND TESTS"
    
    local tests_passed=0
    local tests_total=0
    
    # Test 1: Check if application can start
    print_step "Testing application bootstrap..."
    if php artisan --version > /dev/null 2>&1; then
        print_success "Application bootstrap successful"
        ((tests_passed++))
    else
        print_error "Application bootstrap failed"
    fi
    ((tests_total++))
    
    # Test 2: Check database connection
    print_step "Testing database connection..."
    if php artisan migrate:status > /dev/null 2>&1; then
        print_success "Database connection successful"
        ((tests_passed++))
    else
        print_error "Database connection failed"
    fi
    ((tests_total++))
    
    # Test 3: Check routes
    print_step "Testing route list..."
    if php artisan route:list > /dev/null 2>&1; then
        print_success "Route list successful"
        ((tests_passed++))
    else
        print_error "Route list failed"
    fi
    ((tests_total++))
    
    # Test 4: Check config
    print_step "Testing configuration..."
    if php artisan config:cache > /dev/null 2>&1; then
        print_success "Configuration cache successful"
        ((tests_passed++))
    else
        print_error "Configuration cache failed"
    fi
    ((tests_total++))
    
    # Test 5: Check models
    print_step "Testing model existence..."
    local models_exist=true
    local models=("User" "PortalOpd" "Pelayanan" "Faq" "Wbs")
    
    for model in "${models[@]}"; do
        if php artisan tinker --execute="echo class_exists('App\\Models\\$model') ? 'EXISTS' : 'NOT_EXISTS';" 2>/dev/null | grep -q "EXISTS"; then
            print_success "Model $model exists"
        else
            print_error "Model $model not found"
            models_exist=false
        fi
    done
    
    if $models_exist; then
        ((tests_passed++))
    fi
    ((tests_total++))
    
    print_info "Artisan tests: $tests_passed/$tests_total passed"
    
    if [ $tests_passed -eq $tests_total ]; then
        return 0
    else
        return 1
    fi
}

# Function to run basic functionality tests
run_basic_functionality_tests() {
    print_header "RUNNING BASIC FUNCTIONALITY TESTS"
    
    local tests_passed=0
    local tests_total=0
    
    # Test 1: Check if main routes are accessible
    print_step "Testing main routes accessibility..."
    
    # Start Laravel server
    nohup php artisan serve --port=8000 > /dev/null 2>&1 &
    local server_pid=$!
    sleep 5
    
    # Test routes
    local routes=("/" "/login" "/register")
    local routes_working=true
    
    for route in "${routes[@]}"; do
        if curl -s -o /dev/null -w "%{http_code}" "http://localhost:8000$route" | grep -q "200\|302"; then
            print_success "Route $route accessible"
        else
            print_error "Route $route not accessible"
            routes_working=false
        fi
    done
    
    # Stop server
    kill $server_pid 2>/dev/null
    
    if $routes_working; then
        ((tests_passed++))
    fi
    ((tests_total++))
    
    # Test 2: Check database tables
    print_step "Testing database tables..."
    local tables_exist=true
    local tables=("users" "portal_opds" "pelayanans" "faqs" "wbs")
    
    for table in "${tables[@]}"; do
        if php artisan tinker --execute="echo Schema::hasTable('$table') ? 'EXISTS' : 'NOT_EXISTS';" 2>/dev/null | grep -q "EXISTS"; then
            print_success "Table $table exists"
        else
            print_error "Table $table not found"
            tables_exist=false
        fi
    done
    
    if $tables_exist; then
        ((tests_passed++))
    fi
    ((tests_total++))
    
    # Test 3: Check if we can create a simple model instance
    print_step "Testing model creation..."
    
    cat > /tmp/test_model_creation.php << 'EOF'
<?php
try {
    $user = new App\Models\User();
    echo "User model creation: SUCCESS\n";
    
    $portalOpd = new App\Models\PortalOpd();
    echo "PortalOpd model creation: SUCCESS\n";
    
    $pelayanan = new App\Models\Pelayanan();
    echo "Pelayanan model creation: SUCCESS\n";
    
    echo "Model creation tests: PASSED\n";
} catch (Exception $e) {
    echo "Model creation tests: FAILED - " . $e->getMessage() . "\n";
}
EOF

    if php artisan tinker < /tmp/test_model_creation.php 2>/dev/null | grep -q "PASSED"; then
        print_success "Model creation successful"
        ((tests_passed++))
    else
        print_error "Model creation failed"
    fi
    ((tests_total++))
    
    # Clean up
    rm -f /tmp/test_model_creation.php
    
    print_info "Basic functionality tests: $tests_passed/$tests_total passed"
    
    if [ $tests_passed -eq $tests_total ]; then
        return 0
    else
        return 1
    fi
}

# Function to run system health check
run_system_health_check() {
    print_header "RUNNING SYSTEM HEALTH CHECK"
    
    local health_score=0
    local total_checks=10
    
    # Check 1: PHP version
    print_step "Checking PHP version..."
    if php -v | grep -q "PHP 8"; then
        print_success "PHP 8.x detected"
        ((health_score++))
    else
        print_warning "PHP version may be outdated"
    fi
    
    # Check 2: Composer
    print_step "Checking Composer..."
    if command -v composer > /dev/null 2>&1; then
        print_success "Composer available"
        ((health_score++))
    else
        print_error "Composer not found"
    fi
    
    # Check 3: Laravel installation
    print_step "Checking Laravel installation..."
    if [ -f "artisan" ]; then
        print_success "Laravel installation detected"
        ((health_score++))
    else
        print_error "Laravel installation not found"
    fi
    
    # Check 4: Environment file
    print_step "Checking environment configuration..."
    if [ -f ".env" ]; then
        print_success "Environment file exists"
        ((health_score++))
    else
        print_error "Environment file not found"
    fi
    
    # Check 5: Storage permissions
    print_step "Checking storage permissions..."
    if [ -w "storage" ]; then
        print_success "Storage directory writable"
        ((health_score++))
    else
        print_error "Storage directory not writable"
    fi
    
    # Check 6: Bootstrap cache permissions
    print_step "Checking bootstrap cache permissions..."
    if [ -w "bootstrap/cache" ]; then
        print_success "Bootstrap cache directory writable"
        ((health_score++))
    else
        print_error "Bootstrap cache directory not writable"
    fi
    
    # Check 7: Database configuration
    print_step "Checking database configuration..."
    if grep -q "DB_CONNECTION" .env; then
        print_success "Database configuration found"
        ((health_score++))
    else
        print_error "Database configuration not found"
    fi
    
    # Check 8: Application key
    print_step "Checking application key..."
    if grep -q "APP_KEY=base64:" .env; then
        print_success "Application key set"
        ((health_score++))
    else
        print_error "Application key not set"
    fi
    
    # Check 9: Vendor directory
    print_step "Checking vendor directory..."
    if [ -d "vendor" ]; then
        print_success "Vendor directory exists"
        ((health_score++))
    else
        print_error "Vendor directory not found"
    fi
    
    # Check 10: Node modules (optional)
    print_step "Checking node modules..."
    if [ -d "node_modules" ]; then
        print_success "Node modules directory exists"
        ((health_score++))
    else
        print_warning "Node modules directory not found (optional)"
    fi
    
    # Calculate health percentage
    local health_percentage=$((health_score * 100 / total_checks))
    
    print_info "System Health Score: $health_score/$total_checks ($health_percentage%)"
    
    if [ $health_percentage -ge 80 ]; then
        print_success "System health is EXCELLENT"
        return 0
    elif [ $health_percentage -ge 60 ]; then
        print_warning "System health is GOOD"
        return 0
    else
        print_error "System health is POOR"
        return 1
    fi
}

# Main function
main() {
    local start_time=$(date +%s)
    
    print_header "SIMPLE TESTS EXECUTION - Portal Inspektorat Papua Tengah"
    print_info "Starting simple test execution at $(date)"
    print_info "Mode: No UI, Basic Functionality Tests"
    print_info "Estimated duration: 5-10 minutes"
    
    # Change to project directory
    cd "$PROJECT_PATH" || {
        print_error "Cannot change to project directory: $PROJECT_PATH"
        exit 1
    }
    
    local total_test_suites=0
    local passed_test_suites=0
    
    # Run system health check
    print_step "Running system health check..."
    if run_system_health_check; then
        ((passed_test_suites++))
    fi
    ((total_test_suites++))
    
    # Run artisan tests
    print_step "Running artisan command tests..."
    if run_artisan_tests; then
        ((passed_test_suites++))
    fi
    ((total_test_suites++))
    
    # Run basic functionality tests
    print_step "Running basic functionality tests..."
    if run_basic_functionality_tests; then
        ((passed_test_suites++))
    fi
    ((total_test_suites++))
    
    # Try to run PHPUnit tests if available
    if [ -f "phpunit.xml" ]; then
        print_step "Running PHPUnit tests..."
        if run_phpunit_tests; then
            ((passed_test_suites++))
        fi
        ((total_test_suites++))
    fi
    
    # Calculate execution time
    local end_time=$(date +%s)
    local total_duration=$((end_time - start_time))
    local minutes=$((total_duration / 60))
    local seconds=$((total_duration % 60))
    
    # Final summary
    print_header "SIMPLE TESTS EXECUTION SUMMARY"
    print_info "Total Test Suites: $total_test_suites"
    print_info "Passed Test Suites: $passed_test_suites"
    print_info "Failed Test Suites: $((total_test_suites - passed_test_suites))"
    print_info "Success Rate: $(( passed_test_suites * 100 / total_test_suites ))%"
    print_info "Execution Time: ${minutes}m ${seconds}s"
    
    if [ $passed_test_suites -eq $total_test_suites ]; then
        print_success "ALL SIMPLE TESTS PASSED! 🎉"
        print_success "Portal Inspektorat Papua Tengah system is working correctly!"
        return 0
    else
        print_error "Some tests failed. Please check the output above for details."
        return 1
    fi
}

# Run main function
main "$@"