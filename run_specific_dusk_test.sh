#!/bin/bash

# ================================================================
# SPECIFIC DUSK TEST RUNNER - Portal Inspektorat Papua Tengah
# ================================================================
# Script untuk menjalankan test Dusk specific dengan opsi lengkap
# Author: Claude AI Assistant
# Date: 2025-07-18
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

print_info() {
    echo -e "${CYAN}ℹ️  $1${NC}"
}

print_step() {
    echo -e "${PURPLE}🔄 $1${NC}"
}

# Function to show help
show_help() {
    echo "Usage: $0 [OPTIONS] [TEST_PATTERN]"
    echo ""
    echo "Options:"
    echo "  --file FILE          Run specific test file"
    echo "  --filter PATTERN     Run tests matching pattern"
    echo "  --method METHOD      Run specific test method"
    echo "  --headless          Run in headless mode (no UI)"
    echo "  --verbose           Verbose output"
    echo "  --help              Show this help"
    echo ""
    echo "Available Test Files:"
    echo "  - SystemImprovementsVerificationTest.php"
    echo "  - AdminCrudDataVerificationTest.php"
    echo "  - ComprehensiveAdminCrudTest.php"
    echo "  - WbsWorkflowTest.php"
    echo "  - DocumentManagementTest.php"
    echo "  - UserManagementTest.php"
    echo "  - ComprehensiveFinalSystemTest.php"
    echo ""
    echo "Available Test Methods:"
    echo "  - test_extended_user_roles_functionality"
    echo "  - test_comprehensive_search_functionality"
    echo "  - test_pagination_functionality"
    echo "  - test_portal_opd_crud_10_plus_data"
    echo "  - test_faq_crud_10_plus_data"
    echo "  - test_wbs_end_to_end_workflow"
    echo "  - test_database_structure_complete"
    echo "  - test_key_public_features_working"
    echo ""
    echo "Examples:"
    echo "  $0 --file SystemImprovementsVerificationTest.php"
    echo "  $0 --filter test_search"
    echo "  $0 --method test_portal_opd_crud_10_plus_data"
    echo "  $0 --filter crud --verbose"
    echo "  $0 --headless --filter test_database"
}

# Function to run specific test
run_specific_test() {
    local test_command="DUSK_HEADLESS_DISABLED=1 php artisan dusk"
    local test_description="Specific Test"
    
    # Build command based on options
    if [ "$HEADLESS" = "true" ]; then
        test_command="php artisan dusk"
    fi
    
    if [ "$VERBOSE" = "true" ]; then
        test_command="$test_command --verbose"
    fi
    
    if [ -n "$TEST_FILE" ]; then
        test_command="$test_command tests/Browser/$TEST_FILE"
        test_description="Test File: $TEST_FILE"
    fi
    
    if [ -n "$TEST_FILTER" ]; then
        test_command="$test_command --filter=\"$TEST_FILTER\""
        test_description="Test Filter: $TEST_FILTER"
    fi
    
    if [ -n "$TEST_METHOD" ]; then
        test_command="$test_command --filter=\"$TEST_METHOD\""
        test_description="Test Method: $TEST_METHOD"
    fi
    
    print_header "RUNNING SPECIFIC DUSK TEST"
    print_info "Description: $test_description"
    print_info "Command: $test_command"
    print_info "Started at: $(date)"
    
    # Change to project directory
    cd "$PROJECT_PATH" || {
        print_error "Cannot change to project directory: $PROJECT_PATH"
        exit 1
    }
    
    # Run the test
    local start_time=$(date +%s)
    
    if eval "$test_command"; then
        local end_time=$(date +%s)
        local duration=$((end_time - start_time))
        print_success "Test completed successfully in ${duration}s"
        return 0
    else
        local end_time=$(date +%s)
        local duration=$((end_time - start_time))
        print_error "Test failed after ${duration}s"
        return 1
    fi
}

# Function to list available tests
list_tests() {
    print_header "AVAILABLE DUSK TESTS"
    
    cd "$PROJECT_PATH" || exit 1
    
    if [ -d "tests/Browser" ]; then
        print_step "Test Files in tests/Browser/:"
        find tests/Browser -name "*Test.php" -type f | sort | while read -r file; do
            echo "  - $(basename "$file")"
        done
        
        print_step "Test Methods (examples):"
        grep -r "public function test_" tests/Browser/ | head -20 | while read -r line; do
            method=$(echo "$line" | sed -n 's/.*public function \(test_[^(]*\).*/\1/p')
            if [ -n "$method" ]; then
                echo "  - $method"
            fi
        done
    else
        print_error "tests/Browser directory not found"
    fi
}

# Function to setup test environment quickly
quick_setup() {
    print_header "QUICK SETUP FOR SPECIFIC TEST"
    
    # Change to project directory
    cd "$PROJECT_PATH" || {
        print_error "Cannot change to project directory: $PROJECT_PATH"
        exit 1
    }
    
    # Clear caches
    print_step "Clearing caches..."
    php artisan cache:clear > /dev/null 2>&1
    php artisan config:clear > /dev/null 2>&1
    php artisan route:clear > /dev/null 2>&1
    php artisan view:clear > /dev/null 2>&1
    
    # Check if Chrome driver is installed
    if [ ! -f "vendor/laravel/dusk/bin/chromedriver-linux" ]; then
        print_step "Installing Chrome driver..."
        php artisan dusk:chrome-driver --detect > /dev/null 2>&1
    fi
    
    print_success "Quick setup completed"
}

# Parse command line arguments
HEADLESS=false
VERBOSE=false
TEST_FILE=""
TEST_FILTER=""
TEST_METHOD=""

while [[ $# -gt 0 ]]; do
    case $1 in
        --file)
            TEST_FILE="$2"
            shift 2
            ;;
        --filter)
            TEST_FILTER="$2"
            shift 2
            ;;
        --method)
            TEST_METHOD="$2"
            shift 2
            ;;
        --headless)
            HEADLESS=true
            shift
            ;;
        --verbose)
            VERBOSE=true
            shift
            ;;
        --list)
            list_tests
            exit 0
            ;;
        --help)
            show_help
            exit 0
            ;;
        *)
            # Treat as test pattern
            if [ -z "$TEST_FILTER" ]; then
                TEST_FILTER="$1"
            fi
            shift
            ;;
    esac
done

# If no specific test specified, show help
if [ -z "$TEST_FILE" ] && [ -z "$TEST_FILTER" ] && [ -z "$TEST_METHOD" ]; then
    show_help
    exit 0
fi

# Run quick setup
quick_setup

# Run the specific test
run_specific_test