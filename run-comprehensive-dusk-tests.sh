#!/bin/bash

# Laravel Dusk Comprehensive Test Runner
# Portal Inspektorat Papua Tengah - Complete Testing Suite
# Version: 1.0.0
# Author: Claude AI Assistant
# Date: $(date +"%Y-%m-%d")

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR=$(pwd)
LOG_DIR="$PROJECT_DIR/storage/logs"
REPORT_DIR="$PROJECT_DIR/storage/dusk-reports"
TEST_ENV_FILE="$PROJECT_DIR/.env.dusk.local"
BACKUP_DIR="$PROJECT_DIR/storage/backups"

# Create necessary directories
mkdir -p "$LOG_DIR" "$REPORT_DIR" "$BACKUP_DIR"

# Functions
print_header() {
    echo -e "${BLUE}"
    echo "================================================================"
    echo "  LARAVEL DUSK COMPREHENSIVE TEST SUITE"
    echo "  Portal Inspektorat Papua Tengah"
    echo "================================================================"
    echo -e "${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

setup_test_environment() {
    print_info "Setting up test environment..."
    
    # Backup current database
    if [ -f "$PROJECT_DIR/.env" ]; then
        cp "$PROJECT_DIR/.env" "$BACKUP_DIR/.env.backup.$(date +%Y%m%d_%H%M%S)"
        print_success "Database backup created"
    fi
    
    # Setup test environment
    if [ ! -f "$TEST_ENV_FILE" ]; then
        print_warning "Creating .env.dusk.local file"
        cp "$PROJECT_DIR/.env.example" "$TEST_ENV_FILE"
        
        # Configure test database
        sed -i 's/DB_DATABASE=.*/DB_DATABASE=inspektorat_test/' "$TEST_ENV_FILE"
        sed -i 's/DB_USERNAME=.*/DB_USERNAME=root/' "$TEST_ENV_FILE"
        sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=/' "$TEST_ENV_FILE"
        
        # Configure test mail
        sed -i 's/MAIL_DRIVER=.*/MAIL_DRIVER=log/' "$TEST_ENV_FILE"
        sed -i 's/QUEUE_CONNECTION=.*/QUEUE_CONNECTION=sync/' "$TEST_ENV_FILE"
        
        print_success "Test environment configured"
    fi
    
    # Clear and rebuild caches
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    
    # Setup test database
    php artisan migrate:fresh --env=dusk.local --force
    php artisan db:seed --env=dusk.local --force
    
    print_success "Test environment setup complete"
}

run_test_suite() {
    local test_type=$1
    local test_file=$2
    local description=$3
    
    print_info "Running $description..."
    
    local start_time=$(date +%s)
    local log_file="$LOG_DIR/dusk-${test_type}-$(date +%Y%m%d_%H%M%S).log"
    
    if [ -n "$test_file" ]; then
        if php artisan dusk --env=dusk.local "$test_file" > "$log_file" 2>&1; then
            print_success "$description completed successfully"
            local end_time=$(date +%s)
            local duration=$((end_time - start_time))
            echo "Duration: ${duration}s" >> "$log_file"
            return 0
        else
            print_error "$description failed"
            echo "Check log file: $log_file"
            return 1
        fi
    else
        if php artisan dusk --env=dusk.local > "$log_file" 2>&1; then
            print_success "$description completed successfully"
            local end_time=$(date +%s)
            local duration=$((end_time - start_time))
            echo "Duration: ${duration}s" >> "$log_file"
            return 0
        else
            print_error "$description failed"
            echo "Check log file: $log_file"
            return 1
        fi
    fi
}

run_phase_tests() {
    local phase=$1
    
    case $phase in
        "foundation"|"phase1")
            print_info "Running Phase 1: Foundation Tests"
            run_test_suite "foundation" "tests/Browser/Authentication" "Authentication Tests"
            run_test_suite "foundation" "tests/Browser/Pages" "Page Object Tests"
            run_test_suite "foundation" "tests/Browser/Components" "Component Tests"
            ;;
        "core"|"phase2")
            print_info "Running Phase 2: Core Module Tests"
            run_test_suite "core" "tests/Browser/WBSManagementTest.php" "WBS Management Tests"
            run_test_suite "core" "tests/Browser/BeritaManagementTest.php" "Berita Management Tests"
            run_test_suite "core" "tests/Browser/UserManagementTest.php" "User Management Tests"
            run_test_suite "core" "tests/Browser/DocumentManagementTest.php" "Document Management Tests"
            run_test_suite "core" "tests/Browser/FormValidationTest.php" "Form Validation Tests"
            ;;
        "extended"|"phase3")
            print_info "Running Phase 3: Extended Coverage Tests"
            run_test_suite "extended" "tests/Browser/FileUploadTest.php" "File Upload Tests"
            run_test_suite "extended" "tests/Browser/SearchFilterTest.php" "Search & Filter Tests"
            run_test_suite "extended" "tests/Browser/ResponsiveDesignTest.php" "Responsive Design Tests"
            run_test_suite "extended" "tests/Browser/PerformanceTest.php" "Performance Tests"
            run_test_suite "extended" "tests/Browser/IntegrationTest.php" "Integration Tests"
            ;;
        "advanced"|"phase4")
            print_info "Running Phase 4: Advanced Features Tests"
            run_test_suite "advanced" "tests/Browser/Security/SecurityTestingComprehensiveTest.php" "Security Tests"
            run_test_suite "advanced" "tests/Browser/Accessibility/AccessibilityTestingComprehensiveTest.php" "Accessibility Tests"
            run_test_suite "advanced" "tests/Browser/API/APITestingComprehensiveTest.php" "API Tests"
            run_test_suite "advanced" "tests/Browser/Workflow/WorkflowTestingComprehensiveTest.php" "Workflow Tests"
            run_test_suite "advanced" "tests/Browser/Compatibility/BrowserCompatibilityTestingComprehensiveTest.php" "Browser Compatibility Tests"
            run_test_suite "advanced" "tests/Browser/DataIntegrity/DataIntegrityTestingComprehensiveTest.php" "Data Integrity Tests"
            run_test_suite "advanced" "tests/Browser/Email/EmailTestingComprehensiveTest.php" "Email Tests"
            ;;
        *)
            print_error "Unknown phase: $phase"
            return 1
            ;;
    esac
}

run_specific_tests() {
    local test_type=$1
    
    case $test_type in
        "smoke")
            print_info "Running Smoke Tests (Critical Path)"
            run_test_suite "smoke" "tests/Browser/TestSuiteRunner.php::test_smoke_test_suite" "Smoke Tests"
            ;;
        "regression")
            print_info "Running Regression Tests (Full Application)"
            run_test_suite "regression" "tests/Browser/TestSuiteRunner.php::test_regression_test_suite" "Regression Tests"
            ;;
        "performance")
            print_info "Running Performance Tests"
            run_test_suite "performance" "tests/Browser/TestSuiteRunner.php::test_performance_test_suite" "Performance Tests"
            ;;
        "security")
            print_info "Running Security Tests"
            run_test_suite "security" "tests/Browser/TestSuiteRunner.php::test_security_test_suite" "Security Tests"
            ;;
        "accessibility")
            print_info "Running Accessibility Tests"
            run_test_suite "accessibility" "tests/Browser/TestSuiteRunner.php::test_accessibility_test_suite" "Accessibility Tests"
            ;;
        "mobile")
            print_info "Running Mobile Tests"
            run_test_suite "mobile" "tests/Browser/TestSuiteRunner.php::test_mobile_test_suite" "Mobile Tests"
            ;;
        "compatibility")
            print_info "Running Browser Compatibility Tests"
            run_test_suite "compatibility" "tests/Browser/TestSuiteRunner.php::test_browser_compatibility_test_suite" "Browser Compatibility Tests"
            ;;
        "data")
            print_info "Running Data Integrity Tests"
            run_test_suite "data" "tests/Browser/TestSuiteRunner.php::test_data_integrity_test_suite" "Data Integrity Tests"
            ;;
        *)
            print_error "Unknown test type: $test_type"
            return 1
            ;;
    esac
}

generate_report() {
    print_info "Generating test report..."
    
    local report_file="$REPORT_DIR/test-report-$(date +%Y%m%d_%H%M%S).html"
    
    cat > "$report_file" << EOF
<!DOCTYPE html>
<html>
<head>
    <title>Laravel Dusk Test Report - Portal Inspektorat Papua Tengah</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 5px; }
        .summary { background: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .info { color: #17a2b8; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laravel Dusk Test Report</h1>
        <p>Portal Inspektorat Papua Tengah - $(date)</p>
    </div>
    
    <div class="summary">
        <h2>Test Summary</h2>
        <p>Total Tests: <span id="total-tests">0</span></p>
        <p>Passed: <span class="success" id="passed-tests">0</span></p>
        <p>Failed: <span class="error" id="failed-tests">0</span></p>
        <p>Duration: <span id="total-duration">0</span>s</p>
    </div>
    
    <h2>Test Details</h2>
    <table>
        <tr>
            <th>Test Suite</th>
            <th>Status</th>
            <th>Duration</th>
            <th>Details</th>
        </tr>
EOF

    # Process log files and add to report
    for log_file in "$LOG_DIR"/dusk-*.log; do
        if [ -f "$log_file" ]; then
            local test_name=$(basename "$log_file" .log)
            local status="Unknown"
            local duration="0s"
            
            if grep -q "OK" "$log_file"; then
                status="<span class='success'>PASSED</span>"
            elif grep -q "FAILURES" "$log_file"; then
                status="<span class='error'>FAILED</span>"
            fi
            
            if grep -q "Duration:" "$log_file"; then
                duration=$(grep "Duration:" "$log_file" | cut -d' ' -f2)
            fi
            
            echo "<tr><td>$test_name</td><td>$status</td><td>$duration</td><td><a href='$log_file'>View Log</a></td></tr>" >> "$report_file"
        fi
    done
    
    cat >> "$report_file" << EOF
    </table>
    
    <h2>System Information</h2>
    <p>PHP Version: $(php --version | head -n1)</p>
    <p>Laravel Version: $(php artisan --version)</p>
    <p>Environment: Testing</p>
    <p>Browser: Chrome/ChromeDriver</p>
    <p>Generated: $(date)</p>
</body>
</html>
EOF
    
    print_success "Test report generated: $report_file"
}

cleanup() {
    print_info "Cleaning up test environment..."
    
    # Clean up test files
    find "$PROJECT_DIR/tests/Browser/screenshots" -name "*.png" -mtime +7 -delete 2>/dev/null || true
    find "$PROJECT_DIR/tests/Browser/console" -name "*.log" -mtime +7 -delete 2>/dev/null || true
    
    # Clean up old logs
    find "$LOG_DIR" -name "dusk-*.log" -mtime +30 -delete 2>/dev/null || true
    
    # Clear Laravel caches
    php artisan config:clear
    php artisan cache:clear
    
    print_success "Cleanup completed"
}

show_help() {
    echo "Usage: $0 [OPTIONS] [COMMAND]"
    echo ""
    echo "Commands:"
    echo "  all                 Run all test phases (default)"
    echo "  phase1|foundation   Run Phase 1: Foundation Tests"
    echo "  phase2|core         Run Phase 2: Core Module Tests"
    echo "  phase3|extended     Run Phase 3: Extended Coverage Tests"
    echo "  phase4|advanced     Run Phase 4: Advanced Features Tests"
    echo "  smoke               Run Smoke Tests (Critical Path)"
    echo "  regression          Run Regression Tests (Full Application)"
    echo "  performance         Run Performance Tests"
    echo "  security            Run Security Tests"
    echo "  accessibility       Run Accessibility Tests"
    echo "  mobile              Run Mobile Tests"
    echo "  compatibility       Run Browser Compatibility Tests"
    echo "  data                Run Data Integrity Tests"
    echo ""
    echo "Options:"
    echo "  -h, --help          Show this help message"
    echo "  -s, --setup         Setup test environment only"
    echo "  -c, --cleanup       Cleanup test environment only"
    echo "  -r, --report        Generate test report only"
    echo "  -q, --quiet         Suppress output"
    echo "  -v, --verbose       Verbose output"
    echo ""
    echo "Examples:"
    echo "  $0 all              Run all tests"
    echo "  $0 smoke            Run smoke tests only"
    echo "  $0 phase1           Run foundation tests only"
    echo "  $0 --setup          Setup test environment"
    echo "  $0 --cleanup        Cleanup test environment"
}

# Main execution
main() {
    local command=${1:-"all"}
    local quiet=false
    local verbose=false
    local setup_only=false
    local cleanup_only=false
    local report_only=false
    
    # Parse arguments
    while [[ $# -gt 0 ]]; do
        case $1 in
            -h|--help)
                show_help
                exit 0
                ;;
            -s|--setup)
                setup_only=true
                shift
                ;;
            -c|--cleanup)
                cleanup_only=true
                shift
                ;;
            -r|--report)
                report_only=true
                shift
                ;;
            -q|--quiet)
                quiet=true
                shift
                ;;
            -v|--verbose)
                verbose=true
                shift
                ;;
            *)
                command=$1
                shift
                ;;
        esac
    done
    
    # Redirect output if quiet
    if [ "$quiet" = true ]; then
        exec > /dev/null 2>&1
    fi
    
    print_header
    
    # Handle specific actions
    if [ "$setup_only" = true ]; then
        setup_test_environment
        exit 0
    fi
    
    if [ "$cleanup_only" = true ]; then
        cleanup
        exit 0
    fi
    
    if [ "$report_only" = true ]; then
        generate_report
        exit 0
    fi
    
    # Run tests
    local start_time=$(date +%s)
    local failed_tests=0
    
    trap cleanup EXIT
    
    setup_test_environment
    
    case $command in
        "all")
            print_info "Running ALL test phases..."
            run_phase_tests "foundation" || ((failed_tests++))
            run_phase_tests "core" || ((failed_tests++))
            run_phase_tests "extended" || ((failed_tests++))
            run_phase_tests "advanced" || ((failed_tests++))
            ;;
        "phase1"|"foundation")
            run_phase_tests "foundation" || ((failed_tests++))
            ;;
        "phase2"|"core")
            run_phase_tests "core" || ((failed_tests++))
            ;;
        "phase3"|"extended")
            run_phase_tests "extended" || ((failed_tests++))
            ;;
        "phase4"|"advanced")
            run_phase_tests "advanced" || ((failed_tests++))
            ;;
        "smoke"|"regression"|"performance"|"security"|"accessibility"|"mobile"|"compatibility"|"data")
            run_specific_tests "$command" || ((failed_tests++))
            ;;
        *)
            print_error "Unknown command: $command"
            show_help
            exit 1
            ;;
    esac
    
    local end_time=$(date +%s)
    local total_duration=$((end_time - start_time))
    
    generate_report
    
    # Final summary
    echo ""
    echo "================================================================"
    if [ $failed_tests -eq 0 ]; then
        print_success "ALL TESTS PASSED! 🎉"
        echo -e "${GREEN}Total Duration: ${total_duration}s${NC}"
        echo -e "${GREEN}Failed Tests: $failed_tests${NC}"
    else
        print_error "Some tests failed! ❌"
        echo -e "${RED}Total Duration: ${total_duration}s${NC}"
        echo -e "${RED}Failed Tests: $failed_tests${NC}"
    fi
    echo "================================================================"
    
    exit $failed_tests
}

# Run main function
main "$@"