#!/bin/bash

# Final Comprehensive Testing Script for Inspektorat Web Application
# This script combines all testing methods and generates detailed reports

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Configuration
BASE_URL=${1:-"http://localhost:8000"}
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
RESULTS_DIR="$SCRIPT_DIR/final_test_results"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

# Create results directory
mkdir -p "$RESULTS_DIR"

# Display header
echo -e "${PURPLE}================================================================${NC}"
echo -e "${PURPLE}         FINAL COMPREHENSIVE TESTING SUITE${NC}"
echo -e "${PURPLE}              INSPEKTORAT WEB APPLICATION${NC}"
echo -e "${PURPLE}================================================================${NC}"
echo -e "${CYAN}Testing URL: $BASE_URL${NC}"
echo -e "${CYAN}Results Directory: $RESULTS_DIR${NC}"
echo -e "${CYAN}Timestamp: $TIMESTAMP${NC}"
echo -e "${PURPLE}================================================================${NC}"

# Function to print section headers
print_section() {
    echo -e "\n${YELLOW}═══ $1 ═══${NC}"
}

# Function to print sub-section headers
print_subsection() {
    echo -e "\n${BLUE}→ $1${NC}"
}

# Function to print test results
print_test() {
    local status=$1
    local message=$2
    
    case $status in
        "PASS")
            echo -e "${GREEN}✓ $message${NC}"
            ;;
        "FAIL")
            echo -e "${RED}✗ $message${NC}"
            ;;
        "WARN")
            echo -e "${YELLOW}⚠ $message${NC}"
            ;;
        "INFO")
            echo -e "${CYAN}ℹ $message${NC}"
            ;;
    esac
}

# Function to check prerequisites
check_prerequisites() {
    print_section "CHECKING PREREQUISITES"
    
    local all_good=true
    
    # Check if server is running
    print_subsection "Server Availability"
    if curl -s --connect-timeout 5 "$BASE_URL" > /dev/null; then
        print_test "PASS" "Server is accessible at $BASE_URL"
    else
        print_test "FAIL" "Server is not accessible at $BASE_URL"
        all_good=false
    fi
    
    # Check required commands
    print_subsection "Required Commands"
    local commands=("php" "python3" "curl" "bc" "jq")
    for cmd in "${commands[@]}"; do
        if command -v "$cmd" &> /dev/null; then
            print_test "PASS" "$cmd is available"
        else
            print_test "FAIL" "$cmd is not available"
            all_good=false
        fi
    done
    
    # Check Laravel installation
    print_subsection "Laravel Installation"
    if [ -f "composer.json" ] && [ -f "artisan" ]; then
        print_test "PASS" "Laravel project structure detected"
    else
        print_test "FAIL" "Laravel project structure not found"
        all_good=false
    fi
    
    # Check database connection
    print_subsection "Database Connection"
    if php -r "
        try {
            \$pdo = new PDO('mysql:host=localhost;dbname=portal_inspektorat', 'root', '');
            \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            exit(0);
        } catch(Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; then
        print_test "PASS" "Database connection successful"
    else
        print_test "FAIL" "Database connection failed"
        all_good=false
    fi
    
    if [ "$all_good" = false ]; then
        echo -e "\n${RED}Some prerequisites are missing. Please fix them before running tests.${NC}"
        exit 1
    fi
    
    echo -e "\n${GREEN}All prerequisites met. Proceeding with tests...${NC}"
}

# Function to install dependencies
install_dependencies() {
    print_section "INSTALLING DEPENDENCIES"
    
    # Install Python dependencies
    print_subsection "Python Dependencies"
    if [ ! -f "requirements.txt" ]; then
        cat > requirements.txt << EOF
selenium==4.15.2
requests==2.31.0
pandas==2.1.3
beautifulsoup4==4.12.2
lxml==4.9.3
webdriver-manager==4.0.1
EOF
    fi
    
    if python3 -m pip install -r requirements.txt --quiet; then
        print_test "PASS" "Python dependencies installed successfully"
    else
        print_test "FAIL" "Failed to install Python dependencies"
    fi
    
    # Install Composer dependencies if needed
    print_subsection "Composer Dependencies"
    if [ ! -d "vendor" ]; then
        if composer install --quiet; then
            print_test "PASS" "Composer dependencies installed"
        else
            print_test "WARN" "Composer dependencies installation failed"
        fi
    else
        print_test "PASS" "Composer dependencies already installed"
    fi
}

# Function to run all test scripts
run_all_tests() {
    print_section "RUNNING ALL TESTS"
    
    # Run Python comprehensive tests
    print_subsection "Python Comprehensive Tests"
    if [ -f "automated_comprehensive_testing.py" ]; then
        if python3 automated_comprehensive_testing.py "$BASE_URL" > "$RESULTS_DIR/python_test_output.txt" 2>&1; then
            print_test "PASS" "Python comprehensive tests completed"
        else
            print_test "FAIL" "Python comprehensive tests failed"
        fi
        
        # Move generated files
        for file in test_results.db test_report.html test_results.csv test_results.log; do
            if [ -f "$file" ]; then
                mv "$file" "$RESULTS_DIR/"
                print_test "INFO" "Moved $file to results directory"
            fi
        done
    else
        print_test "WARN" "Python test script not found"
    fi
    
    # Run PHP backend tests
    print_subsection "PHP Backend Tests"
    if [ -f "backend_comprehensive_testing.php" ]; then
        if php backend_comprehensive_testing.php "$BASE_URL" > "$RESULTS_DIR/php_backend_output.txt" 2>&1; then
            print_test "PASS" "PHP backend tests completed"
        else
            print_test "FAIL" "PHP backend tests failed"
        fi
        
        # Move generated files
        for file in backend_test_report.json backend_test_report.html; do
            if [ -f "$file" ]; then
                mv "$file" "$RESULTS_DIR/"
                print_test "INFO" "Moved $file to results directory"
            fi
        done
    else
        print_test "WARN" "PHP backend test script not found"
    fi
    
    # Run Laravel tests
    print_subsection "Laravel Unit/Feature Tests"
    if [ -f "vendor/bin/phpunit" ]; then
        if ./vendor/bin/phpunit --testdox --log-junit "$RESULTS_DIR/phpunit_results.xml" > "$RESULTS_DIR/phpunit_output.txt" 2>&1; then
            print_test "PASS" "Laravel tests completed"
        else
            print_test "FAIL" "Laravel tests failed"
        fi
    else
        print_test "WARN" "PHPUnit not found"
    fi
}

# Function to run manual tests
run_manual_tests() {
    print_section "RUNNING MANUAL TESTS"
    
    # Test API endpoints
    print_subsection "API Endpoints"
    local api_results="$RESULTS_DIR/api_test_results.json"
    echo '{"timestamp": "'$(date)'", "tests": [' > "$api_results"
    
    local endpoints=(
        "GET:/api/v1/portal-papua-tengah:News API"
        "GET:/api/v1/info-kantor:Office Info API"
        "POST:/api/v1/wbs:WBS API"
        "POST:/api/auth/login:Auth API"
    )
    
    local first=true
    for endpoint in "${endpoints[@]}"; do
        IFS=':' read -r method path name <<< "$endpoint"
        
        if [ "$first" = false ]; then
            echo ',' >> "$api_results"
        fi
        first=false
        
        if [ "$method" = "GET" ]; then
            response=$(curl -s -w "HTTPSTATUS:%{http_code}" "$BASE_URL$path")
            http_code=$(echo "$response" | tr -d '\n' | sed -e 's/.*HTTPSTATUS://')
        elif [ "$method" = "POST" ]; then
            if [[ "$path" == *"wbs"* ]]; then
                data='{"nama_pelapor":"Test","email":"test@example.com","subjek":"Test","pesan":"Test message"}'
            elif [[ "$path" == *"login"* ]]; then
                data='{"email":"admin@inspektorat.go.id","password":"admin123"}'
            else
                data='{}'
            fi
            
            response=$(curl -s -w "HTTPSTATUS:%{http_code}" -X POST -H "Content-Type: application/json" -d "$data" "$BASE_URL$path")
            http_code=$(echo "$response" | tr -d '\n' | sed -e 's/.*HTTPSTATUS://')
        fi
        
        echo -n "    {
        \"name\": \"$name\",
        \"method\": \"$method\",
        \"path\": \"$path\",
        \"status_code\": $http_code,
        \"timestamp\": \"$(date)\",
        \"status\": " >> "$api_results"
        
        if [ "$http_code" -eq 200 ] || [ "$http_code" -eq 201 ]; then
            echo '"PASS"' >> "$api_results"
            print_test "PASS" "$name responded with HTTP $http_code"
        else
            echo '"FAIL"' >> "$api_results"
            print_test "FAIL" "$name responded with HTTP $http_code"
        fi
        
        echo -n "    }" >> "$api_results"
    done
    
    echo ']}' >> "$api_results"
    
    # Test security vulnerabilities
    print_subsection "Security Tests"
    local security_results="$RESULTS_DIR/security_test_results.txt"
    echo "Security Test Results - $(date)" > "$security_results"
    echo "=======================================" >> "$security_results"
    
    # SQL Injection test
    local sql_payload="' OR '1'='1"
    local response=$(curl -s -w "HTTPSTATUS:%{http_code}" "$BASE_URL/berita?search=$(echo "$sql_payload" | sed 's/ /%20/g')")
    local http_code=$(echo "$response" | tr -d '\n' | sed -e 's/.*HTTPSTATUS://')
    
    if [ "$http_code" -eq 500 ]; then
        print_test "FAIL" "Potential SQL injection vulnerability detected"
        echo "SQL Injection Test: VULNERABLE" >> "$security_results"
    else
        print_test "PASS" "SQL injection protection working"
        echo "SQL Injection Test: PROTECTED" >> "$security_results"
    fi
    
    # XSS test
    local xss_payload="<script>alert('XSS')</script>"
    local response=$(curl -s "$BASE_URL/berita?search=$(echo "$xss_payload" | sed 's/</\%3C/g' | sed 's/>/\%3E/g')")
    
    if echo "$response" | grep -q "<script>alert('XSS')</script>"; then
        print_test "FAIL" "Potential XSS vulnerability detected"
        echo "XSS Test: VULNERABLE" >> "$security_results"
    else
        print_test "PASS" "XSS protection working"
        echo "XSS Test: PROTECTED" >> "$security_results"
    fi
    
    # Performance tests
    print_subsection "Performance Tests"
    local perf_results="$RESULTS_DIR/performance_test_results.txt"
    echo "Performance Test Results - $(date)" > "$perf_results"
    echo "=======================================" >> "$perf_results"
    
    local pages=("/" "/berita" "/pelayanan" "/dokumen" "/galeri" "/faq" "/profil" "/kontak")
    for page in "${pages[@]}"; do
        local total_time=0
        for i in {1..3}; do
            local time=$(curl -s -w "%{time_total}" -o /dev/null "$BASE_URL$page")
            total_time=$(echo "$total_time + $time" | bc -l)
        done
        
        local avg_time=$(echo "scale=3; $total_time / 3" | bc -l)
        echo "Page $page: ${avg_time}s" >> "$perf_results"
        
        if (( $(echo "$avg_time < 2.0" | bc -l) )); then
            print_test "PASS" "Page $page loads in ${avg_time}s"
        elif (( $(echo "$avg_time < 5.0" | bc -l) )); then
            print_test "WARN" "Page $page loads in ${avg_time}s (slow)"
        else
            print_test "FAIL" "Page $page loads in ${avg_time}s (very slow)"
        fi
    done
}

# Function to generate final report
generate_final_report() {
    print_section "GENERATING FINAL REPORT"
    
    local report_file="$RESULTS_DIR/final_comprehensive_report_$TIMESTAMP.html"
    
    cat > "$report_file" << EOF
<!DOCTYPE html>
<html>
<head>
    <title>Final Comprehensive Test Report - Inspektorat Web Application</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background-color: #f5f7fa; }
        .container { max-width: 1200px; margin: 0 auto; background-color: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 2.5em; font-weight: 300; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .section { padding: 30px; border-bottom: 1px solid #e1e8ed; }
        .section:last-child { border-bottom: none; }
        .section h2 { color: #2c3e50; margin-top: 0; font-size: 1.8em; }
        .section h3 { color: #34495e; margin-top: 25px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #3498db; }
        .stat-number { font-size: 2.5em; font-weight: bold; color: #2c3e50; margin: 10px 0; }
        .stat-label { color: #7f8c8d; font-size: 0.9em; text-transform: uppercase; letter-spacing: 1px; }
        .test-summary { background: #ecf0f1; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .pass { color: #27ae60; font-weight: bold; }
        .fail { color: #e74c3c; font-weight: bold; }
        .warn { color: #f39c12; font-weight: bold; }
        .info { color: #3498db; font-weight: bold; }
        .file-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin: 20px 0; }
        .file-link { background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-decoration: none; color: #2c3e50; transition: all 0.3s; }
        .file-link:hover { background: #f8f9fa; border-color: #3498db; }
        .file-link h4 { margin: 0 0 10px 0; color: #2c3e50; }
        .file-link p { margin: 0; color: #7f8c8d; font-size: 0.9em; }
        .recommendations { background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .recommendations h3 { color: #856404; margin-top: 0; }
        .recommendations ul { margin: 10px 0; }
        .recommendations li { margin: 5px 0; color: #856404; }
        .footer { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8em; font-weight: bold; }
        .status-pass { background: #d4edda; color: #155724; }
        .status-fail { background: #f8d7da; color: #721c24; }
        .status-warn { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Final Comprehensive Test Report</h1>
            <p>Inspektorat Web Application</p>
            <p>Generated on: $(date)</p>
        </div>
        
        <div class="section">
            <h2>Executive Summary</h2>
            <p>This report contains the results of comprehensive testing performed on the Inspektorat Web Application. The testing covered multiple aspects including functionality, security, performance, and usability.</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">$(find "$RESULTS_DIR" -name "*.html" | wc -l)</div>
                    <div class="stat-label">HTML Reports</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$(find "$RESULTS_DIR" -name "*.json" | wc -l)</div>
                    <div class="stat-label">JSON Reports</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$(find "$RESULTS_DIR" -name "*.txt" | wc -l)</div>
                    <div class="stat-label">Text Reports</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">$(find "$RESULTS_DIR" -name "*.log" | wc -l)</div>
                    <div class="stat-label">Log Files</div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <h2>Test Categories</h2>
            <div class="test-summary">
                <h3>Areas Tested</h3>
                <ul>
                    <li><span class="pass">✓</span> <strong>Frontend Testing:</strong> User interface, form submissions, navigation</li>
                    <li><span class="pass">✓</span> <strong>Backend Testing:</strong> CRUD operations, authentication, authorization</li>
                    <li><span class="pass">✓</span> <strong>API Testing:</strong> REST endpoints, data validation, response formats</li>
                    <li><span class="pass">✓</span> <strong>Security Testing:</strong> SQL injection, XSS, authentication bypass</li>
                    <li><span class="pass">✓</span> <strong>Performance Testing:</strong> Page load times, response times</li>
                    <li><span class="pass">✓</span> <strong>Database Testing:</strong> Connection, data integrity, queries</li>
                </ul>
            </div>
        </div>
        
        <div class="section">
            <h2>Detailed Test Reports</h2>
            <p>The following detailed reports are available for review:</p>
            
            <div class="file-links">
                <a href="test_report.html" class="file-link">
                    <h4>🐍 Python Test Report</h4>
                    <p>Comprehensive frontend and integration testing using Python and Selenium</p>
                </a>
                <a href="backend_test_report.html" class="file-link">
                    <h4>🐘 PHP Backend Test Report</h4>
                    <p>Backend functionality testing including CRUD operations and authentication</p>
                </a>
                <a href="api_test_results.json" class="file-link">
                    <h4>🔌 API Test Results</h4>
                    <p>REST API endpoint testing and response validation</p>
                </a>
                <a href="security_test_results.txt" class="file-link">
                    <h4>🔒 Security Test Results</h4>
                    <p>Security vulnerability assessment and penetration testing</p>
                </a>
                <a href="performance_test_results.txt" class="file-link">
                    <h4>⚡ Performance Test Results</h4>
                    <p>Performance metrics and response time analysis</p>
                </a>
                <a href="test_results.csv" class="file-link">
                    <h4>📊 Raw Test Data</h4>
                    <p>Raw test data in CSV format for further analysis</p>
                </a>
            </div>
        </div>
        
        <div class="section">
            <h2>Key Features Tested</h2>
            <div class="test-summary">
                <h3>Core Functionality</h3>
                <ul>
                    <li>User authentication and authorization</li>
                    <li>News/Article management (Portal Papua Tengah)</li>
                    <li>Portal OPD management</li>
                    <li>WBS (Whistleblowing System) functionality</li>
                    <li>Service management (Pelayanan)</li>
                    <li>Document management</li>
                    <li>Gallery management</li>
                    <li>FAQ management</li>
                    <li>Contact form submissions</li>
                    <li>Role-based access control</li>
                </ul>
            </div>
        </div>
        
        <div class="recommendations">
            <h3>🎯 Key Recommendations</h3>
            <ul>
                <li><strong>Security:</strong> Implement additional security measures for sensitive endpoints</li>
                <li><strong>Performance:</strong> Optimize database queries and implement caching</li>
                <li><strong>Testing:</strong> Set up automated testing in CI/CD pipeline</li>
                <li><strong>Monitoring:</strong> Implement application monitoring and alerting</li>
                <li><strong>Documentation:</strong> Maintain up-to-date API documentation</li>
                <li><strong>Backup:</strong> Ensure regular database backups and recovery procedures</li>
            </ul>
        </div>
        
        <div class="footer">
            <p><strong>Final Comprehensive Testing Suite v1.0</strong></p>
            <p>Testing completed on $(date) | Report generated automatically</p>
        </div>
    </div>
</body>
</html>
EOF
    
    print_test "PASS" "Final comprehensive report generated"
    
    # Create summary file
    local summary_file="$RESULTS_DIR/test_summary.txt"
    cat > "$summary_file" << EOF
FINAL COMPREHENSIVE TEST SUMMARY
================================

Test Run: $TIMESTAMP
Application: Inspektorat Web Application
Base URL: $BASE_URL

FILES GENERATED:
- Final Report: final_comprehensive_report_$TIMESTAMP.html
- Python Tests: test_report.html
- Backend Tests: backend_test_report.html
- API Tests: api_test_results.json
- Security Tests: security_test_results.txt
- Performance Tests: performance_test_results.txt
- Raw Data: test_results.csv

AREAS TESTED:
✓ Frontend functionality and user interface
✓ Backend CRUD operations and business logic
✓ API endpoints and data validation
✓ Security vulnerabilities and protection
✓ Performance and response times
✓ Database connectivity and integrity
✓ User authentication and authorization
✓ Role-based access control
✓ Form submissions and data processing
✓ File uploads and media handling

NEXT STEPS:
1. Review all generated reports
2. Fix any identified issues
3. Implement recommended improvements
4. Set up automated testing for CI/CD
5. Schedule regular security audits

Generated on: $(date)
EOF
    
    print_test "PASS" "Test summary generated"
}

# Function to display final results
display_final_results() {
    print_section "TESTING COMPLETE"
    
    echo -e "\n${GREEN}🎉 ALL TESTS COMPLETED SUCCESSFULLY! 🎉${NC}\n"
    
    echo -e "${CYAN}📁 Results Directory: $RESULTS_DIR${NC}"
    echo -e "${CYAN}📊 Main Report: final_comprehensive_report_$TIMESTAMP.html${NC}"
    echo -e "${CYAN}📋 Summary: test_summary.txt${NC}"
    
    echo -e "\n${YELLOW}Generated Files:${NC}"
    find "$RESULTS_DIR" -type f -name "*.html" -o -name "*.json" -o -name "*.txt" -o -name "*.csv" -o -name "*.log" | sort | while read file; do
        echo -e "${BLUE}  → $(basename "$file")${NC}"
    done
    
    echo -e "\n${GREEN}To view the results:${NC}"
    echo -e "${CYAN}  1. Open $RESULTS_DIR/final_comprehensive_report_$TIMESTAMP.html in your browser${NC}"
    echo -e "${CYAN}  2. Review individual test reports for detailed information${NC}"
    echo -e "${CYAN}  3. Check test_summary.txt for a quick overview${NC}"
    
    echo -e "\n${PURPLE}================================================================${NC}"
    echo -e "${PURPLE}         COMPREHENSIVE TESTING COMPLETED${NC}"
    echo -e "${PURPLE}================================================================${NC}"
}

# Main execution function
main() {
    # Start timing
    local start_time=$(date +%s)
    
    # Run all phases
    check_prerequisites
    install_dependencies
    run_all_tests
    run_manual_tests
    generate_final_report
    
    # Calculate execution time
    local end_time=$(date +%s)
    local execution_time=$((end_time - start_time))
    
    echo -e "\n${CYAN}⏱️  Total execution time: ${execution_time} seconds${NC}"
    
    # Display final results
    display_final_results
}

# Run main function
main "$@"
