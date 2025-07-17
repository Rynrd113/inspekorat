#!/bin/bash

# Test Automation Scripts for Portal Inspektorat Papua Tengah
# Author: Laravel Dusk Testing Suite
# Version: 1.0

echo "=== Portal Inspektorat Papua Tengah - Test Automation ==="
echo "Starting comprehensive test suite..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test configuration
TEST_ENV="testing"
BROWSER="chrome"
HEADLESS="true"
PARALLEL_TESTS="true"
SCREENSHOT_ON_FAILURE="true"
RETRY_FAILED_TESTS="true"

# Load environment variables
source .env.testing 2>/dev/null || echo "Warning: .env.testing not found"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_section() {
    echo -e "${BLUE}[SECTION]${NC} $1"
}

# Pre-test setup
setup_test_environment() {
    print_section "Setting up test environment..."
    
    # Clear Laravel cache
    php artisan cache:clear --env=$TEST_ENV
    php artisan config:clear --env=$TEST_ENV
    php artisan route:clear --env=$TEST_ENV
    php artisan view:clear --env=$TEST_ENV
    
    # Fresh database migration
    php artisan migrate:fresh --env=$TEST_ENV --force
    php artisan db:seed --env=$TEST_ENV --force
    
    # Install/update ChromeDriver
    php artisan dusk:chrome-driver
    
    print_status "Test environment setup complete"
}

# Phase 1: Foundation Tests
run_phase1_tests() {
    print_section "Running Phase 1 - Foundation Tests..."
    
    php artisan dusk tests/Browser/Auth/AuthenticationTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless} \
        ${PARALLEL_TESTS:+--parallel}
    
    php artisan dusk tests/Browser/Navigation/NavigationTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Dashboard/DashboardTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    print_status "Phase 1 tests completed"
}

# Phase 2: Core Module Tests
run_phase2_tests() {
    print_section "Running Phase 2 - Core Module Tests..."
    
    php artisan dusk tests/Browser/Berita/BeritaTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/WBS/WBSTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Documents/DocumentsTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Users/UsersTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Profile/ProfileTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Settings/SettingsTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    print_status "Phase 2 tests completed"
}

# Phase 3: Extended Coverage Tests
run_phase3_tests() {
    print_section "Running Phase 3 - Extended Coverage Tests..."
    
    php artisan dusk tests/Browser/FileUpload/FileUploadTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/SearchFilter/SearchFilterTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/ResponsiveDesign/ResponsiveDesignTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Performance/PerformanceTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Integration/IntegrationTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    print_status "Phase 3 tests completed"
}

# Phase 4: Advanced Features Tests
run_phase4_tests() {
    print_section "Running Phase 4 - Advanced Features Tests..."
    
    php artisan dusk tests/Browser/Security/SecurityTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Accessibility/AccessibilityTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/API/APITest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    php artisan dusk tests/Browser/Workflow/WorkflowTest.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    print_status "Phase 4 tests completed"
}

# Run test suites
run_test_suites() {
    print_section "Running Test Suites..."
    
    php artisan dusk tests/Browser/Suites/TestSuiteRunner.php \
        --env=$TEST_ENV \
        --browser=$BROWSER \
        ${HEADLESS:+--headless}
    
    print_status "Test suites completed"
}

# Generate test reports
generate_reports() {
    print_section "Generating test reports..."
    
    # Create reports directory
    mkdir -p storage/testing/reports
    
    # Generate HTML report
    php artisan dusk:report --format=html --output=storage/testing/reports/test-report.html
    
    # Generate XML report for CI/CD
    php artisan dusk:report --format=xml --output=storage/testing/reports/test-report.xml
    
    # Generate coverage report
    php artisan dusk:coverage --output=storage/testing/reports/coverage-report.html
    
    print_status "Reports generated in storage/testing/reports/"
}

# Cleanup after tests
cleanup_test_environment() {
    print_section "Cleaning up test environment..."
    
    # Remove test files
    rm -rf storage/testing/temp/*
    rm -rf storage/testing/uploads/*
    
    # Clear test cache
    php artisan cache:clear --env=$TEST_ENV
    
    print_status "Cleanup completed"
}

# Handle failed tests
handle_failed_tests() {
    if [ "$RETRY_FAILED_TESTS" = "true" ]; then
        print_warning "Retrying failed tests..."
        php artisan dusk:retry --env=$TEST_ENV
    fi
}

# Main execution
main() {
    echo "Test Configuration:"
    echo "- Environment: $TEST_ENV"
    echo "- Browser: $BROWSER"
    echo "- Headless: $HEADLESS"
    echo "- Parallel: $PARALLEL_TESTS"
    echo "- Screenshot on failure: $SCREENSHOT_ON_FAILURE"
    echo "- Retry failed tests: $RETRY_FAILED_TESTS"
    echo ""
    
    # Check if specific phase is requested
    case "$1" in
        "phase1")
            setup_test_environment
            run_phase1_tests
            ;;
        "phase2")
            setup_test_environment
            run_phase2_tests
            ;;
        "phase3")
            setup_test_environment
            run_phase3_tests
            ;;
        "phase4")
            setup_test_environment
            run_phase4_tests
            ;;
        "suites")
            setup_test_environment
            run_test_suites
            ;;
        "all"|"")
            setup_test_environment
            run_phase1_tests
            run_phase2_tests
            run_phase3_tests
            run_phase4_tests
            run_test_suites
            ;;
        "smoke")
            setup_test_environment
            php artisan dusk tests/Browser/Suites/TestSuiteRunner.php::test_smoke_test_all_main_features
            ;;
        "regression")
            setup_test_environment
            php artisan dusk tests/Browser/Suites/TestSuiteRunner.php::test_complete_regression_suite
            ;;
        "performance")
            setup_test_environment
            php artisan dusk tests/Browser/Performance/PerformanceTest.php
            php artisan dusk tests/Browser/Suites/TestSuiteRunner.php::test_performance_test_suite
            ;;
        "security")
            setup_test_environment
            php artisan dusk tests/Browser/Security/SecurityTest.php
            php artisan dusk tests/Browser/Suites/TestSuiteRunner.php::test_security_test_suite
            ;;
        "mobile")
            setup_test_environment
            php artisan dusk tests/Browser/ResponsiveDesign/ResponsiveDesignTest.php
            php artisan dusk tests/Browser/Suites/TestSuiteRunner.php::test_mobile_compatibility_suite
            ;;
        *)
            echo "Usage: $0 [phase1|phase2|phase3|phase4|suites|all|smoke|regression|performance|security|mobile]"
            echo ""
            echo "Available test phases:"
            echo "  phase1      - Foundation tests (Authentication, Navigation, Dashboard)"
            echo "  phase2      - Core module tests (Berita, WBS, Documents, Users, Profile, Settings)"
            echo "  phase3      - Extended coverage tests (File Upload, Search, Responsive, Performance, Integration)"
            echo "  phase4      - Advanced features tests (Security, Accessibility, API, Workflow)"
            echo "  suites      - Test suites runner"
            echo "  all         - Run all phases and suites (default)"
            echo "  smoke       - Quick smoke test"
            echo "  regression  - Complete regression test"
            echo "  performance - Performance-focused tests"
            echo "  security    - Security-focused tests"
            echo "  mobile      - Mobile compatibility tests"
            exit 1
            ;;
    esac
    
    # Handle any test failures
    if [ $? -ne 0 ]; then
        print_error "Some tests failed!"
        handle_failed_tests
    else
        print_status "All tests passed!"
    fi
    
    # Generate reports
    generate_reports
    
    # Cleanup
    cleanup_test_environment
    
    print_status "Test automation completed!"
}

# Execute main function with all arguments
main "$@"
