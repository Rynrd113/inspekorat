#!/bin/bash

# Comprehensive Test Runner for Inspekorat System
# This script runs all tests and generates a detailed report

echo "====================================================================="
echo "        INSPEKORAT SYSTEM - COMPREHENSIVE TEST RUNNER"
echo "====================================================================="
echo ""

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

# Create test results directory
mkdir -p test-results
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
REPORT_DIR="test-results/test_run_${TIMESTAMP}"
mkdir -p "$REPORT_DIR"

# Check if we're in the correct directory
if [ ! -f "composer.json" ]; then
    print_error "composer.json not found. Please run this script from the project root directory."
    exit 1
fi

print_status "Starting comprehensive test suite..."
print_status "Results will be saved to: $REPORT_DIR"

# Step 1: Environment Setup
print_status "Step 1: Checking environment and dependencies"

# Check if PHPUnit is available
if ! command -v vendor/bin/phpunit &> /dev/null; then
    print_error "PHPUnit not found. Installing dependencies..."
    composer install --dev
fi

# Check if .env.testing exists
if [ ! -f ".env.testing" ]; then
    print_warning ".env.testing not found. Creating from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env.testing
        echo "DB_CONNECTION=sqlite" >> .env.testing
        echo "DB_DATABASE=:memory:" >> .env.testing
    else
        print_error ".env.example not found. Please create .env.testing manually."
        exit 1
    fi
fi

# Step 2: Database Setup
print_status "Step 2: Setting up test database"
touch database/database.sqlite 2>/dev/null || true
php artisan config:clear --env=testing
php artisan migrate:fresh --env=testing --seed

# Step 3: Clear caches
print_status "Step 3: Clearing application caches"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Step 4: Run Unit Tests
print_status "Step 4: Running Unit Tests"
echo "Running unit tests..." > "$REPORT_DIR/unit_tests.log"
vendor/bin/phpunit --testsuite=Unit --log-junit="$REPORT_DIR/unit_tests.xml" --coverage-text >> "$REPORT_DIR/unit_tests.log" 2>&1
UNIT_EXIT_CODE=$?

if [ $UNIT_EXIT_CODE -eq 0 ]; then
    print_success "Unit tests passed"
else
    print_error "Unit tests failed (exit code: $UNIT_EXIT_CODE)"
fi

# Step 5: Run Feature Tests
print_status "Step 5: Running Feature Tests"
echo "Running feature tests..." > "$REPORT_DIR/feature_tests.log"
vendor/bin/phpunit --testsuite=Feature --log-junit="$REPORT_DIR/feature_tests.xml" --coverage-text >> "$REPORT_DIR/feature_tests.log" 2>&1
FEATURE_EXIT_CODE=$?

if [ $FEATURE_EXIT_CODE -eq 0 ]; then
    print_success "Feature tests passed"
else
    print_error "Feature tests failed (exit code: $FEATURE_EXIT_CODE)"
fi

# Step 6: Run All Tests with Coverage
print_status "Step 6: Running full test suite with coverage"
echo "Running full test suite..." > "$REPORT_DIR/full_tests.log"
vendor/bin/phpunit --coverage-html="$REPORT_DIR/coverage" --coverage-text --log-junit="$REPORT_DIR/full_tests.xml" >> "$REPORT_DIR/full_tests.log" 2>&1
FULL_EXIT_CODE=$?

if [ $FULL_EXIT_CODE -eq 0 ]; then
    print_success "Full test suite passed"
else
    print_error "Full test suite failed (exit code: $FULL_EXIT_CODE)"
fi

# Step 7: Test Individual Components
print_status "Step 7: Testing individual components"

# Test Authentication
print_status "Testing authentication system..."
vendor/bin/phpunit --filter=AuthenticationTest --tap > "$REPORT_DIR/auth_tests.tap" 2>&1
AUTH_EXIT_CODE=$?

# Test Public Pages
print_status "Testing public pages..."
vendor/bin/phpunit --filter=PublicPagesTest --tap > "$REPORT_DIR/public_tests.tap" 2>&1
PUBLIC_EXIT_CODE=$?

# Test WBS Module
print_status "Testing WBS module..."
vendor/bin/phpunit --filter=WbsModuleTest --tap > "$REPORT_DIR/wbs_tests.tap" 2>&1
WBS_EXIT_CODE=$?

# Test Portal Papua Tengah
print_status "Testing Portal Papua Tengah..."
vendor/bin/phpunit --filter=PortalPapuaTengahTest --tap > "$REPORT_DIR/portal_tests.tap" 2>&1
PORTAL_EXIT_CODE=$?

# Test Role-based Access
print_status "Testing role-based access control..."
vendor/bin/phpunit --filter=RoleBasedAccessTest --tap > "$REPORT_DIR/rbac_tests.tap" 2>&1
RBAC_EXIT_CODE=$?

# Test API Endpoints
print_status "Testing API endpoints..."
vendor/bin/phpunit --filter=ApiEndpointsTest --tap > "$REPORT_DIR/api_tests.tap" 2>&1
API_EXIT_CODE=$?

# Test Error Handling
print_status "Testing error handling..."
vendor/bin/phpunit --filter=ErrorHandlingTest --tap > "$REPORT_DIR/error_tests.tap" 2>&1
ERROR_EXIT_CODE=$?

# Test Performance & Security
print_status "Testing performance and security..."
vendor/bin/phpunit --filter=PerformanceAndSecurityTest --tap > "$REPORT_DIR/security_tests.tap" 2>&1
SECURITY_EXIT_CODE=$?

# Step 8: Generate Summary Report
print_status "Step 8: Generating summary report"

cat > "$REPORT_DIR/test_summary.txt" << EOF
INSPEKORAT SYSTEM - TEST SUMMARY REPORT
Generated: $(date)
========================================

OVERALL RESULTS:
- Unit Tests: $([ $UNIT_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Feature Tests: $([ $FEATURE_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Full Test Suite: $([ $FULL_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")

COMPONENT TESTS:
- Authentication: $([ $AUTH_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Public Pages: $([ $PUBLIC_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- WBS Module: $([ $WBS_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Portal Papua Tengah: $([ $PORTAL_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Role-based Access: $([ $RBAC_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- API Endpoints: $([ $API_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Error Handling: $([ $ERROR_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")
- Performance & Security: $([ $SECURITY_EXIT_CODE -eq 0 ] && echo "PASSED" || echo "FAILED")

DETAILED RESULTS:
Check the following files for detailed results:
- unit_tests.log: Unit test results
- feature_tests.log: Feature test results
- full_tests.log: Full test suite results
- coverage/index.html: Coverage report
- *.tap files: Individual component test results

RECOMMENDATIONS:
$([ $UNIT_EXIT_CODE -ne 0 ] && echo "- Fix unit test failures before deployment")
$([ $FEATURE_EXIT_CODE -ne 0 ] && echo "- Address feature test issues")
$([ $FULL_EXIT_CODE -ne 0 ] && echo "- Resolve full test suite problems")
$([ $AUTH_EXIT_CODE -ne 0 ] && echo "- Fix authentication system issues")
$([ $PUBLIC_EXIT_CODE -ne 0 ] && echo "- Address public page problems")
$([ $WBS_EXIT_CODE -ne 0 ] && echo "- Fix WBS module issues")
$([ $PORTAL_EXIT_CODE -ne 0 ] && echo "- Resolve Portal Papua Tengah problems")
$([ $RBAC_EXIT_CODE -ne 0 ] && echo "- Fix role-based access control issues")
$([ $API_EXIT_CODE -ne 0 ] && echo "- Address API endpoint problems")
$([ $ERROR_EXIT_CODE -ne 0 ] && echo "- Improve error handling")
$([ $SECURITY_EXIT_CODE -ne 0 ] && echo "- Address security and performance issues")

EOF

# Step 9: Display Results
print_status "Step 9: Displaying test results"

echo ""
echo "====================================================================="
echo "                          TEST RESULTS"
echo "====================================================================="
echo ""

# Count total tests
TOTAL_TESTS=$(grep -c "test" "$REPORT_DIR/full_tests.log" 2>/dev/null || echo "0")
PASSED_TESTS=$(grep -c "✓" "$REPORT_DIR/full_tests.log" 2>/dev/null || echo "0")
FAILED_TESTS=$(grep -c "✗" "$REPORT_DIR/full_tests.log" 2>/dev/null || echo "0")

echo "Total Tests Run: $TOTAL_TESTS"
echo "Passed: $PASSED_TESTS"
echo "Failed: $FAILED_TESTS"
echo ""

# Display component results
echo "COMPONENT TEST RESULTS:"
echo "======================="
echo "Authentication:        $([ $AUTH_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Public Pages:          $([ $PUBLIC_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "WBS Module:            $([ $WBS_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Portal Papua Tengah:   $([ $PORTAL_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Role-based Access:     $([ $RBAC_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "API Endpoints:         $([ $API_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Error Handling:        $([ $ERROR_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"
echo "Performance & Security: $([ $SECURITY_EXIT_CODE -eq 0 ] && echo -e "${GREEN}PASSED${NC}" || echo -e "${RED}FAILED${NC}")"

echo ""
echo "====================================================================="
echo "                       DETAILED REPORT"
echo "====================================================================="
echo ""

# Show summary
cat "$REPORT_DIR/test_summary.txt"

echo ""
echo "====================================================================="
echo "                           NEXT STEPS"
echo "====================================================================="
echo ""

# Calculate overall result
OVERALL_RESULT=0
if [ $UNIT_EXIT_CODE -ne 0 ] || [ $FEATURE_EXIT_CODE -ne 0 ] || [ $FULL_EXIT_CODE -ne 0 ]; then
    OVERALL_RESULT=1
fi

if [ $OVERALL_RESULT -eq 0 ]; then
    print_success "All tests passed! System is ready for deployment."
    echo "- Coverage report: $REPORT_DIR/coverage/index.html"
    echo "- Full report: $REPORT_DIR/test_summary.txt"
else
    print_error "Some tests failed. Please review the detailed logs."
    echo "- Check failed tests in: $REPORT_DIR/"
    echo "- Review error logs for specific issues"
    echo "- Fix issues and run tests again"
fi

echo ""
echo "Test results saved to: $REPORT_DIR"
echo "====================================================================="

# Exit with appropriate code
exit $OVERALL_RESULT
