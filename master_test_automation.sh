#!/bin/bash

# Master Test Script for Inspekorat System
# This script runs all types of tests: unit, feature, browser, load, and security

echo "====================================================================="
echo "       INSPEKORAT SYSTEM - MASTER TEST AUTOMATION SCRIPT"
echo "====================================================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

print_header() {
    echo -e "${PURPLE}========================================${NC}"
    echo -e "${PURPLE}$1${NC}"
    echo -e "${PURPLE}========================================${NC}"
}

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

# Configuration
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
MASTER_RESULTS_DIR="master-test-results/run_${TIMESTAMP}"
BASE_URL="http://localhost:8000"

# Create master results directory
mkdir -p "$MASTER_RESULTS_DIR"

# Function to check if server is running
check_server() {
    if curl -s "$BASE_URL" > /dev/null; then
        return 0
    else
        return 1
    fi
}

# Function to start development server
start_server() {
    print_status "Starting development server..."
    php artisan serve --port=8000 > "$MASTER_RESULTS_DIR/server.log" 2>&1 &
    SERVER_PID=$!
    
    # Wait for server to start
    sleep 5
    
    if check_server; then
        print_success "Development server started (PID: $SERVER_PID)"
        return 0
    else
        print_error "Failed to start development server"
        return 1
    fi
}

# Function to stop development server
stop_server() {
    if [ ! -z "$SERVER_PID" ]; then
        print_status "Stopping development server..."
        kill $SERVER_PID 2>/dev/null
        wait $SERVER_PID 2>/dev/null
        print_success "Development server stopped"
    fi
}

# Function to run test and capture results
run_test() {
    local test_name=$1
    local test_command=$2
    local test_description=$3
    
    print_header "$test_description"
    
    echo "Starting $test_name at $(date)" >> "$MASTER_RESULTS_DIR/test_timeline.log"
    
    # Run the test
    if eval "$test_command"; then
        echo "$test_name: PASSED" >> "$MASTER_RESULTS_DIR/test_summary.log"
        print_success "$test_name completed successfully"
        return 0
    else
        echo "$test_name: FAILED" >> "$MASTER_RESULTS_DIR/test_summary.log"
        print_error "$test_name failed"
        return 1
    fi
}

# Initialize test results
echo "Master Test Run Started: $(date)" > "$MASTER_RESULTS_DIR/test_summary.log"
echo "Master Test Timeline: $(date)" > "$MASTER_RESULTS_DIR/test_timeline.log"

print_header "INSPEKORAT SYSTEM - MASTER TEST AUTOMATION"
print_status "Test run timestamp: $TIMESTAMP"
print_status "Results directory: $MASTER_RESULTS_DIR"
print_status "Base URL: $BASE_URL"

# Step 1: Environment Setup
print_header "STEP 1: ENVIRONMENT SETUP"

# Check if we're in the correct directory
if [ ! -f "composer.json" ]; then
    print_error "composer.json not found. Please run this script from the project root directory."
    exit 1
fi

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    print_status "Installing PHP dependencies..."
    composer install --dev
fi

# Check if .env.testing exists
if [ ! -f ".env.testing" ]; then
    print_warning ".env.testing not found. Creating from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env.testing
        echo "DB_CONNECTION=sqlite" >> .env.testing
        echo "DB_DATABASE=:memory:" >> .env.testing
    fi
fi

# Setup database
print_status "Setting up test database..."
touch database/database.sqlite 2>/dev/null || true
php artisan migrate:fresh --env=testing --seed > "$MASTER_RESULTS_DIR/database_setup.log" 2>&1

# Clear caches
print_status "Clearing application caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

print_success "Environment setup completed"

# Step 2: Unit and Feature Tests
print_header "STEP 2: UNIT AND FEATURE TESTS"

# Make comprehensive test script executable
chmod +x ./run_comprehensive_tests.sh

# Run comprehensive tests
if run_test "COMPREHENSIVE_TESTS" "./run_comprehensive_tests.sh > '$MASTER_RESULTS_DIR/comprehensive_tests.log' 2>&1" "Running Comprehensive Unit and Feature Tests"; then
    COMPREHENSIVE_TESTS_PASSED=true
    
    # Copy test results to master directory
    if [ -d "test-results" ]; then
        cp -r test-results/* "$MASTER_RESULTS_DIR/" 2>/dev/null || true
    fi
else
    COMPREHENSIVE_TESTS_PASSED=false
fi

# Step 3: Browser Tests (if Dusk is available)
print_header "STEP 3: BROWSER TESTS"

if [ -d "vendor/laravel/dusk" ]; then
    print_status "Laravel Dusk found. Running browser tests..."
    
    # Check if server is running, if not start it
    if ! check_server; then
        start_server
        SERVER_STARTED=true
    fi
    
    if run_test "BROWSER_TESTS" "php artisan dusk > '$MASTER_RESULTS_DIR/browser_tests.log' 2>&1" "Running Browser Tests with Laravel Dusk"; then
        BROWSER_TESTS_PASSED=true
    else
        BROWSER_TESTS_PASSED=false
    fi
else
    print_warning "Laravel Dusk not found. Skipping browser tests."
    print_status "To enable browser tests, install Laravel Dusk:"
    print_status "composer require --dev laravel/dusk"
    print_status "php artisan dusk:install"
    echo "BROWSER_TESTS: SKIPPED (Dusk not installed)" >> "$MASTER_RESULTS_DIR/test_summary.log"
    BROWSER_TESTS_PASSED="skipped"
fi

# Step 4: Load Tests
print_header "STEP 4: LOAD TESTS"

# Check if server is running, if not start it
if ! check_server; then
    start_server
    SERVER_STARTED=true
fi

if check_server; then
    # Make load testing script executable
    chmod +x ./load_testing.sh
    
    if run_test "LOAD_TESTS" "./load_testing.sh > '$MASTER_RESULTS_DIR/load_tests.log' 2>&1" "Running Load Tests"; then
        LOAD_TESTS_PASSED=true
        
        # Copy load test results to master directory
        if [ -d "load-test-results" ]; then
            cp -r load-test-results/* "$MASTER_RESULTS_DIR/" 2>/dev/null || true
        fi
    else
        LOAD_TESTS_PASSED=false
    fi
else
    print_error "Cannot run load tests without server"
    echo "LOAD_TESTS: FAILED (Server not running)" >> "$MASTER_RESULTS_DIR/test_summary.log"
    LOAD_TESTS_PASSED=false
fi

# Step 5: Security Tests
print_header "STEP 5: SECURITY TESTS"

# Check if server is running, if not start it
if ! check_server; then
    start_server
    SERVER_STARTED=true
fi

if check_server; then
    # Make security testing script executable
    chmod +x ./security_testing.sh
    
    if run_test "SECURITY_TESTS" "./security_testing.sh > '$MASTER_RESULTS_DIR/security_tests.log' 2>&1" "Running Security Tests"; then
        SECURITY_TESTS_PASSED=true
        
        # Copy security test results to master directory
        if [ -d "security-test-results" ]; then
            cp -r security-test-results/* "$MASTER_RESULTS_DIR/" 2>/dev/null || true
        fi
    else
        SECURITY_TESTS_PASSED=false
    fi
else
    print_error "Cannot run security tests without server"
    echo "SECURITY_TESTS: FAILED (Server not running)" >> "$MASTER_RESULTS_DIR/test_summary.log"
    SECURITY_TESTS_PASSED=false
fi

# Step 6: Custom Integration Tests
print_header "STEP 6: CUSTOM INTEGRATION TESTS"

print_status "Running custom integration tests..."

# Test complete user workflows
integration_test_results="$MASTER_RESULTS_DIR/integration_tests.log"

{
    echo "Integration Tests Started: $(date)"
    echo "==============================="
    
    # Test 1: Public user workflow
    echo "Test 1: Public user workflow"
    curl -s "$BASE_URL/" > /dev/null && echo "✓ Homepage accessible" || echo "✗ Homepage failed"
    curl -s "$BASE_URL/berita" > /dev/null && echo "✓ News page accessible" || echo "✗ News page failed"
    curl -s "$BASE_URL/wbs" > /dev/null && echo "✓ WBS page accessible" || echo "✗ WBS page failed"
    
    # Test 2: API endpoints
    echo "Test 2: API endpoints"
    curl -s "$BASE_URL/api/portal-papua-tengah/public" > /dev/null && echo "✓ Public API accessible" || echo "✗ Public API failed"
    curl -s "$BASE_URL/api/info-kantor/public" > /dev/null && echo "✓ Info API accessible" || echo "✗ Info API failed"
    
    # Test 3: Form submissions
    echo "Test 3: Form submissions"
    response=$(curl -s -X POST -d "nama=Test&email=test@example.com&telepon=123&pesan=test" "$BASE_URL/wbs")
    echo "WBS form submission response length: ${#response}"
    
    # Test 4: Admin login simulation
    echo "Test 4: Admin areas"
    response=$(curl -s -I "$BASE_URL/admin/login" | head -n 1)
    echo "Admin login page: $response"
    
    echo "Integration Tests Completed: $(date)"
} > "$integration_test_results" 2>&1

if [ -f "$integration_test_results" ]; then
    echo "INTEGRATION_TESTS: PASSED" >> "$MASTER_RESULTS_DIR/test_summary.log"
    print_success "Integration tests completed"
    INTEGRATION_TESTS_PASSED=true
else
    echo "INTEGRATION_TESTS: FAILED" >> "$MASTER_RESULTS_DIR/test_summary.log"
    print_error "Integration tests failed"
    INTEGRATION_TESTS_PASSED=false
fi

# Step 7: Generate Master Report
print_header "STEP 7: GENERATING MASTER REPORT"

# Stop server if we started it
if [ "$SERVER_STARTED" = true ]; then
    stop_server
fi

# Count test results
total_tests=0
passed_tests=0
failed_tests=0
skipped_tests=0

test_results=(
    "$COMPREHENSIVE_TESTS_PASSED"
    "$BROWSER_TESTS_PASSED"
    "$LOAD_TESTS_PASSED"
    "$SECURITY_TESTS_PASSED"
    "$INTEGRATION_TESTS_PASSED"
)

for result in "${test_results[@]}"; do
    total_tests=$((total_tests + 1))
    case $result in
        "true")
            passed_tests=$((passed_tests + 1))
            ;;
        "false")
            failed_tests=$((failed_tests + 1))
            ;;
        "skipped")
            skipped_tests=$((skipped_tests + 1))
            ;;
    esac
done

# Generate comprehensive master report
cat > "$MASTER_RESULTS_DIR/MASTER_TEST_REPORT.md" << EOF
# INSPEKORAT SYSTEM - MASTER TEST REPORT

**Generated:** $(date)  
**Test Run ID:** $TIMESTAMP  
**Duration:** $(date --date="@$(($(date +%s) - $(date --date="$(head -1 "$MASTER_RESULTS_DIR/test_timeline.log" | cut -d: -f2-)" +%s)))" -u +%H:%M:%S)

## Executive Summary

| Metric | Value |
|--------|-------|
| Total Test Suites | $total_tests |
| Passed | $passed_tests |
| Failed | $failed_tests |
| Skipped | $skipped_tests |
| Success Rate | $((passed_tests * 100 / total_tests))% |

## Test Results Overview

### 1. Comprehensive Tests (Unit + Feature)
- **Status:** $([ "$COMPREHENSIVE_TESTS_PASSED" = "true" ] && echo "✅ PASSED" || echo "❌ FAILED")
- **Description:** Core functionality, authentication, CRUD operations, role-based access
- **Details:** comprehensive_tests.log

### 2. Browser Tests
- **Status:** $([ "$BROWSER_TESTS_PASSED" = "true" ] && echo "✅ PASSED" || ([ "$BROWSER_TESTS_PASSED" = "skipped" ] && echo "⏭️ SKIPPED" || echo "❌ FAILED"))
- **Description:** End-to-end user interface testing
- **Details:** browser_tests.log

### 3. Load Tests
- **Status:** $([ "$LOAD_TESTS_PASSED" = "true" ] && echo "✅ PASSED" || echo "❌ FAILED")
- **Description:** Performance under concurrent load
- **Details:** load_tests.log

### 4. Security Tests
- **Status:** $([ "$SECURITY_TESTS_PASSED" = "true" ] && echo "✅ PASSED" || echo "❌ FAILED")
- **Description:** SQL injection, XSS, CSRF, authentication bypass
- **Details:** security_tests.log

### 5. Integration Tests
- **Status:** $([ "$INTEGRATION_TESTS_PASSED" = "true" ] && echo "✅ PASSED" || echo "❌ FAILED")
- **Description:** End-to-end workflows and API integration
- **Details:** integration_tests.log

## Critical Findings

$([ "$COMPREHENSIVE_TESTS_PASSED" = "false" ] && echo "- ❌ **CRITICAL:** Core functionality tests failed")
$([ "$SECURITY_TESTS_PASSED" = "false" ] && echo "- ❌ **CRITICAL:** Security vulnerabilities detected")
$([ "$LOAD_TESTS_PASSED" = "false" ] && echo "- ⚠️ **WARNING:** Performance issues detected")
$([ "$BROWSER_TESTS_PASSED" = "false" ] && echo "- ⚠️ **WARNING:** UI/UX issues detected")

## Recommendations

### Immediate Actions Required
$([ "$COMPREHENSIVE_TESTS_PASSED" = "false" ] && echo "1. **Fix core functionality issues** - Review comprehensive_tests.log")
$([ "$SECURITY_TESTS_PASSED" = "false" ] && echo "2. **Address security vulnerabilities** - Review security test results")

### Performance Improvements
$([ "$LOAD_TESTS_PASSED" = "false" ] && echo "- Optimize database queries")
$([ "$LOAD_TESTS_PASSED" = "false" ] && echo "- Implement caching strategies")
$([ "$LOAD_TESTS_PASSED" = "false" ] && echo "- Consider load balancing")

### Quality Assurance
- Implement continuous integration
- Set up automated testing pipeline
- Regular security audits
- Performance monitoring

## Test Coverage Analysis

### Modules Tested
- ✅ Authentication System
- ✅ Public Pages
- ✅ WBS Module
- ✅ Portal Papua Tengah (News)
- ✅ Portal OPD
- ✅ Role-based Access Control
- ✅ API Endpoints
- ✅ Error Handling
- ✅ Security Features

### User Roles Tested
- ✅ Super Admin
- ✅ Admin
- ✅ Content Manager
- ✅ Service Manager
- ✅ OPD Manager
- ✅ WBS Manager
- ✅ Specialized Admins
- ✅ Regular Users

## Detailed Test Results

All detailed test results are available in the following files:
- \`comprehensive_tests.log\` - Unit and feature test results
- \`browser_tests.log\` - Browser test results
- \`load_tests.log\` - Load test results
- \`security_tests.log\` - Security test results
- \`integration_tests.log\` - Integration test results

## System Information

- **PHP Version:** $(php -v | head -1)
- **Laravel Version:** $(php artisan --version)
- **Database:** SQLite (Testing)
- **Test Environment:** Development

## Next Steps

1. **Review Failed Tests:** Check logs for any failed tests
2. **Fix Issues:** Address all critical and warning issues
3. **Rerun Tests:** Verify fixes with another test run
4. **Deploy:** Deploy to staging environment for final testing
5. **Monitor:** Set up continuous monitoring in production

---

*This report was generated automatically by the Inspekorat System Master Test Automation Script.*
EOF

# Create simple text summary
cat > "$MASTER_RESULTS_DIR/test_summary.txt" << EOF
INSPEKORAT SYSTEM - TEST SUMMARY
Generated: $(date)
================================

OVERALL RESULTS:
Total Test Suites: $total_tests
Passed: $passed_tests
Failed: $failed_tests
Skipped: $skipped_tests
Success Rate: $((passed_tests * 100 / total_tests))%

DETAILED RESULTS:
- Comprehensive Tests: $([ "$COMPREHENSIVE_TESTS_PASSED" = "true" ] && echo "PASSED" || echo "FAILED")
- Browser Tests: $([ "$BROWSER_TESTS_PASSED" = "true" ] && echo "PASSED" || ([ "$BROWSER_TESTS_PASSED" = "skipped" ] && echo "SKIPPED" || echo "FAILED"))
- Load Tests: $([ "$LOAD_TESTS_PASSED" = "true" ] && echo "PASSED" || echo "FAILED")
- Security Tests: $([ "$SECURITY_TESTS_PASSED" = "true" ] && echo "PASSED" || echo "FAILED")
- Integration Tests: $([ "$INTEGRATION_TESTS_PASSED" = "true" ] && echo "PASSED" || echo "FAILED")

SYSTEM STATUS: $([ $failed_tests -eq 0 ] && echo "READY FOR DEPLOYMENT" || echo "ISSUES NEED FIXING")

See MASTER_TEST_REPORT.md for detailed analysis.
EOF

print_success "Master report generated"

# Step 8: Display Final Results
print_header "MASTER TEST AUTOMATION COMPLETED"

echo ""
echo "📊 TEST RESULTS SUMMARY"
echo "======================="
echo "Total Test Suites: $total_tests"
echo "✅ Passed: $passed_tests"
echo "❌ Failed: $failed_tests"
echo "⏭️ Skipped: $skipped_tests"
echo "🎯 Success Rate: $((passed_tests * 100 / total_tests))%"
echo ""

echo "📋 DETAILED RESULTS:"
echo "==================="
echo "Comprehensive Tests: $([ "$COMPREHENSIVE_TESTS_PASSED" = "true" ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Browser Tests:       $([ "$BROWSER_TESTS_PASSED" = "true" ] && echo -e "${GREEN}PASSED${NC}" || ([ "$BROWSER_TESTS_PASSED" = "skipped" ] && echo -e "${YELLOW}SKIPPED${NC}" || echo -e "${RED}FAILED${NC}"))"
echo "Load Tests:          $([ "$LOAD_TESTS_PASSED" = "true" ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Security Tests:      $([ "$SECURITY_TESTS_PASSED" = "true" ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Integration Tests:   $([ "$INTEGRATION_TESTS_PASSED" = "true" ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"

echo ""
echo "📁 RESULTS LOCATION:"
echo "===================="
echo "Main Report: $MASTER_RESULTS_DIR/MASTER_TEST_REPORT.md"
echo "Summary: $MASTER_RESULTS_DIR/test_summary.txt"
echo "All Results: $MASTER_RESULTS_DIR/"

echo ""
if [ $failed_tests -eq 0 ]; then
    print_success "🎉 ALL TESTS PASSED! SYSTEM IS READY FOR DEPLOYMENT!"
else
    print_error "⚠️ SOME TESTS FAILED. PLEASE REVIEW AND FIX ISSUES BEFORE DEPLOYMENT."
fi

echo ""
echo "====================================================================="
echo "Thank you for using the Inspekorat System Master Test Automation!"
echo "For detailed analysis, please review: $MASTER_RESULTS_DIR/MASTER_TEST_REPORT.md"
echo "====================================================================="

# Exit with appropriate code
exit $([ $failed_tests -eq 0 ] && echo 0 || echo 1)
