#!/bin/bash

# ================================================================
# QUICK DUSK TESTS RUNNER - Portal Inspektorat Papua Tengah
# XAMPP COMPATIBLE VERSION FOR LINUX FEDORA
# ================================================================
# Script untuk menjalankan test Dusk cepat (essential tests only)
# Author: Claude AI Assistant
# Date: 2025-07-18
# Duration: 15-20 minutes
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
LOG_FILE="$PROJECT_PATH/storage/logs/dusk_quick_test_$(date +%Y%m%d_%H%M%S).log"
REPORT_FILE="$PROJECT_PATH/QUICK_TEST_REPORT_$(date +%Y%m%d_%H%M%S).md"

# XAMPP paths for Linux Fedora
XAMPP_PATH="/opt/lampp"
XAMPP_MYSQL="$XAMPP_PATH/bin/mysql"
XAMPP_PHP="$XAMPP_PATH/bin/php"

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
        return 1
    fi
}

# Function to run quick test
run_quick_test() {
    local test_filter="$1"
    local test_name="$2"
    
    print_header "RUNNING QUICK TEST: $test_name"
    
    local start_time=$(date +%s)
    
    if DUSK_HEADLESS_DISABLED=1 php artisan dusk --filter="$test_filter" --verbose; then
        local end_time=$(date +%s)
        local duration=$((end_time - start_time))
        print_success "$test_name completed in ${duration}s"
        echo "✅ $test_name - PASSED (${duration}s)" >> "$REPORT_FILE"
        return 0
    else
        local end_time=$(date +%s)
        local duration=$((end_time - start_time))
        print_error "$test_name failed after ${duration}s"
        echo "❌ $test_name - FAILED (${duration}s)" >> "$REPORT_FILE"
        return 1
    fi
}

# Function to setup quick environment
setup_quick_environment() {
    print_header "QUICK SETUP FOR XAMPP"
    
    cd "$PROJECT_PATH" || {
        print_error "Cannot change to project directory: $PROJECT_PATH"
        exit 1
    }
    
    check_xampp_status
    
    mkdir -p "$(dirname "$LOG_FILE")"
    echo "=== QUICK DUSK TEST LOG - $(date) ===" > "$LOG_FILE"
    
    # Quick cache clear
    php artisan cache:clear > /dev/null 2>&1
    php artisan config:clear > /dev/null 2>&1
    
    print_success "Quick setup completed"
}

# Function to start Laravel server
start_laravel_server() {
    if ! pgrep -f "php artisan serve" > /dev/null; then
        print_step "Starting Laravel development server..."
        nohup php artisan serve --port=8000 > /dev/null 2>&1 &
        sleep 3
    fi
}

# Main function
main() {
    local start_time=$(date +%s)
    
    print_header "QUICK DUSK TESTS - Portal Inspektorat Papua Tengah (XAMPP)"
    print_info "Starting quick test execution at $(date)"
    print_info "Environment: XAMPP on Linux Fedora"
    print_info "Estimated duration: 15-20 minutes"
    
    setup_quick_environment
    start_laravel_server
    
    # Initialize report
    echo "# 🧪 QUICK DUSK TEST REPORT - Portal Inspektorat Papua Tengah" > "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "**Generated:** $(date)" >> "$REPORT_FILE"
    echo "**Environment:** XAMPP on Linux Fedora" >> "$REPORT_FILE"
    echo "**Test Type:** Quick Essential Tests" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "## 📊 Test Results" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    
    local total_tests=0
    local passed_tests=0
    
    # Essential Tests
    print_step "Running essential system tests..."
    
    # Test 1: Database Structure
    if run_quick_test "test_database_structure_complete" "Database Structure"; then
        ((passed_tests++))
    fi
    ((total_tests++))
    
    # Test 2: Public Features
    if run_quick_test "test_key_public_features_working" "Key Public Features"; then
        ((passed_tests++))
    fi
    ((total_tests++))
    
    # Test 3: Extended User Roles
    if run_quick_test "test_extended_user_roles_functionality" "Extended User Roles"; then
        ((passed_tests++))
    fi
    ((total_tests++))
    
    # Test 4: Search Functionality
    if run_quick_test "test_comprehensive_search_functionality" "Search Functionality"; then
        ((passed_tests++))
    fi
    ((total_tests++))
    
    # Test 5: WBS Workflow
    if run_quick_test "test_wbs_end_to_end_workflow" "WBS Workflow"; then
        ((passed_tests++))
    fi
    ((total_tests++))
    
    # Test 6: System Health
    if run_quick_test "test_comprehensive_system_health" "System Health"; then
        ((passed_tests++))
    fi
    ((total_tests++))
    
    # Generate report
    echo "" >> "$REPORT_FILE"
    echo "## 🎯 Quick Test Results" >> "$REPORT_FILE"
    echo "" >> "$REPORT_FILE"
    echo "- **Total Tests:** $total_tests" >> "$REPORT_FILE"
    echo "- **Passed:** $passed_tests" >> "$REPORT_FILE"
    echo "- **Failed:** $((total_tests - passed_tests))" >> "$REPORT_FILE"
    echo "- **Success Rate:** $(( passed_tests * 100 / total_tests ))%" >> "$REPORT_FILE"
    
    local end_time=$(date +%s)
    local total_duration=$((end_time - start_time))
    local minutes=$((total_duration / 60))
    local seconds=$((total_duration % 60))
    
    print_header "QUICK TEST EXECUTION SUMMARY"
    print_info "Total Tests: $total_tests"
    print_info "Passed: $passed_tests"
    print_info "Failed: $((total_tests - passed_tests))"
    print_info "Success Rate: $(( passed_tests * 100 / total_tests ))%"
    print_info "Execution Time: ${minutes}m ${seconds}s"
    
    if [ $passed_tests -eq $total_tests ]; then
        print_success "ALL QUICK TESTS PASSED! 🎉"
        return 0
    else
        print_error "Some tests failed. Check the report for details."
        return 1
    fi
}

# Run main function
main "$@"