#!/bin/bash

# Security Testing Script for Inspekorat System
# This script performs basic security tests on the web application

echo "====================================================================="
echo "         INSPEKORAT SYSTEM - SECURITY TESTING SCRIPT"
echo "====================================================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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
BASE_URL="http://localhost:8000"
RESULTS_DIR="security-test-results"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

# Create results directory
mkdir -p "$RESULTS_DIR"

print_status "Starting security testing..."
print_status "Base URL: $BASE_URL"
print_status "Results will be saved to: $RESULTS_DIR"

# Test 1: SQL Injection Testing
print_status "Testing SQL Injection vulnerabilities..."

sql_payloads=(
    "' OR '1'='1"
    "' OR '1'='1' --"
    "' OR '1'='1' /*"
    "'; DROP TABLE users; --"
    "' UNION SELECT * FROM users --"
    "admin'--"
    "admin' #"
    "' OR 1=1 --"
    "' OR 'a'='a"
    "') OR ('1'='1"
)

echo "SQL Injection Test Results - $(date)" > "$RESULTS_DIR/sql_injection_${TIMESTAMP}.txt"

for payload in "${sql_payloads[@]}"; do
    echo "Testing payload: $payload" >> "$RESULTS_DIR/sql_injection_${TIMESTAMP}.txt"
    
    # Test in search parameter
    response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/berita?search=$payload")
    echo "Search parameter - HTTP Code: $response" >> "$RESULTS_DIR/sql_injection_${TIMESTAMP}.txt"
    
    # Test in login form
    response=$(curl -s -o /dev/null -w "%{http_code}" -X POST -d "email=$payload&password=password" "$BASE_URL/admin/login")
    echo "Login form - HTTP Code: $response" >> "$RESULTS_DIR/sql_injection_${TIMESTAMP}.txt"
    
    # Test in WBS form
    response=$(curl -s -o /dev/null -w "%{http_code}" -X POST -d "nama=$payload&email=test@example.com&pesan=test" "$BASE_URL/wbs")
    echo "WBS form - HTTP Code: $response" >> "$RESULTS_DIR/sql_injection_${TIMESTAMP}.txt"
    
    echo "---" >> "$RESULTS_DIR/sql_injection_${TIMESTAMP}.txt"
done

print_success "SQL Injection testing completed"

# Test 2: XSS (Cross-Site Scripting) Testing
print_status "Testing XSS vulnerabilities..."

xss_payloads=(
    "<script>alert('XSS')</script>"
    "<img src=x onerror=alert('XSS')>"
    "<svg onload=alert('XSS')>"
    "javascript:alert('XSS')"
    "<iframe src=javascript:alert('XSS')></iframe>"
    "<body onload=alert('XSS')>"
    "<script>document.location='http://malicious.com'</script>"
    "'\"><script>alert('XSS')</script>"
    "<script>alert(document.cookie)</script>"
    "<img src=\"x\" onerror=\"alert('XSS')\">"
)

echo "XSS Test Results - $(date)" > "$RESULTS_DIR/xss_${TIMESTAMP}.txt"

for payload in "${xss_payloads[@]}"; do
    echo "Testing payload: $payload" >> "$RESULTS_DIR/xss_${TIMESTAMP}.txt"
    
    # Test in search parameter
    response=$(curl -s "$BASE_URL/berita?search=$payload" | grep -i script)
    echo "Search parameter - Script tags found: $response" >> "$RESULTS_DIR/xss_${TIMESTAMP}.txt"
    
    # Test in contact form
    response=$(curl -s -X POST -d "nama=$payload&email=test@example.com&subjek=test&pesan=test" "$BASE_URL/kontak")
    echo "Contact form - Response length: ${#response}" >> "$RESULTS_DIR/xss_${TIMESTAMP}.txt"
    
    echo "---" >> "$RESULTS_DIR/xss_${TIMESTAMP}.txt"
done

print_success "XSS testing completed"

# Test 3: CSRF (Cross-Site Request Forgery) Testing
print_status "Testing CSRF protection..."

echo "CSRF Test Results - $(date)" > "$RESULTS_DIR/csrf_${TIMESTAMP}.txt"

# Test forms without CSRF token
csrf_tests=(
    "POST /admin/login email=admin@example.com&password=password"
    "POST /wbs nama=test&email=test@example.com&pesan=test"
    "POST /kontak nama=test&email=test@example.com&subjek=test&pesan=test"
)

for test in "${csrf_tests[@]}"; do
    method=$(echo $test | cut -d' ' -f1)
    endpoint=$(echo $test | cut -d' ' -f2)
    data=$(echo $test | cut -d' ' -f3)
    
    echo "Testing CSRF: $method $endpoint" >> "$RESULTS_DIR/csrf_${TIMESTAMP}.txt"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" -X $method -d "$data" "$BASE_URL$endpoint")
    echo "HTTP Code: $response" >> "$RESULTS_DIR/csrf_${TIMESTAMP}.txt"
    
    if [ "$response" = "419" ]; then
        echo "CSRF protection working (419 error)" >> "$RESULTS_DIR/csrf_${TIMESTAMP}.txt"
    else
        echo "Possible CSRF vulnerability (no 419 error)" >> "$RESULTS_DIR/csrf_${TIMESTAMP}.txt"
    fi
    
    echo "---" >> "$RESULTS_DIR/csrf_${TIMESTAMP}.txt"
done

print_success "CSRF testing completed"

# Test 4: Directory Traversal Testing
print_status "Testing directory traversal vulnerabilities..."

traversal_payloads=(
    "../../../etc/passwd"
    "..\\..\\..\\windows\\system32\\drivers\\etc\\hosts"
    "../../../var/log/apache2/access.log"
    "....//....//....//etc/passwd"
    "..%2F..%2F..%2Fetc%2Fpasswd"
    "..%252F..%252F..%252Fetc%252Fpasswd"
)

echo "Directory Traversal Test Results - $(date)" > "$RESULTS_DIR/directory_traversal_${TIMESTAMP}.txt"

for payload in "${traversal_payloads[@]}"; do
    echo "Testing payload: $payload" >> "$RESULTS_DIR/directory_traversal_${TIMESTAMP}.txt"
    
    # Test in file parameters
    response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/dokumen?file=$payload")
    echo "File parameter - HTTP Code: $response" >> "$RESULTS_DIR/directory_traversal_${TIMESTAMP}.txt"
    
    # Test in image parameters
    response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/galeri?image=$payload")
    echo "Image parameter - HTTP Code: $response" >> "$RESULTS_DIR/directory_traversal_${TIMESTAMP}.txt"
    
    echo "---" >> "$RESULTS_DIR/directory_traversal_${TIMESTAMP}.txt"
done

print_success "Directory traversal testing completed"

# Test 5: Authentication Bypass Testing
print_status "Testing authentication bypass..."

echo "Authentication Bypass Test Results - $(date)" > "$RESULTS_DIR/auth_bypass_${TIMESTAMP}.txt"

# Test direct access to admin pages
admin_pages=(
    "/admin/dashboard"
    "/admin/wbs"
    "/admin/portal-papua-tengah"
    "/admin/portal-opd"
    "/admin/users"
)

for page in "${admin_pages[@]}"; do
    echo "Testing direct access to: $page" >> "$RESULTS_DIR/auth_bypass_${TIMESTAMP}.txt"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL$page")
    echo "HTTP Code: $response" >> "$RESULTS_DIR/auth_bypass_${TIMESTAMP}.txt"
    
    if [ "$response" = "302" ] || [ "$response" = "401" ] || [ "$response" = "403" ]; then
        echo "Authentication protection working" >> "$RESULTS_DIR/auth_bypass_${TIMESTAMP}.txt"
    else
        echo "Possible authentication bypass" >> "$RESULTS_DIR/auth_bypass_${TIMESTAMP}.txt"
    fi
    
    echo "---" >> "$RESULTS_DIR/auth_bypass_${TIMESTAMP}.txt"
done

print_success "Authentication bypass testing completed"

# Test 6: File Upload Security Testing
print_status "Testing file upload security..."

echo "File Upload Security Test Results - $(date)" > "$RESULTS_DIR/file_upload_${TIMESTAMP}.txt"

# Create malicious test files
echo "<?php phpinfo(); ?>" > "$RESULTS_DIR/test.php"
echo "<script>alert('XSS')</script>" > "$RESULTS_DIR/test.html"
echo "#!/bin/bash\necho 'Shell script'" > "$RESULTS_DIR/test.sh"

# Test file upload restrictions
test_files=(
    "test.php"
    "test.html"
    "test.sh"
    "test.exe"
)

for file in "${test_files[@]}"; do
    if [ -f "$RESULTS_DIR/$file" ]; then
        echo "Testing file upload: $file" >> "$RESULTS_DIR/file_upload_${TIMESTAMP}.txt"
        
        # Test upload to news/portal
        response=$(curl -s -o /dev/null -w "%{http_code}" -X POST -F "gambar=@$RESULTS_DIR/$file" "$BASE_URL/admin/portal-papua-tengah")
        echo "Upload response - HTTP Code: $response" >> "$RESULTS_DIR/file_upload_${TIMESTAMP}.txt"
        
        echo "---" >> "$RESULTS_DIR/file_upload_${TIMESTAMP}.txt"
    fi
done

print_success "File upload security testing completed"

# Test 7: Information Disclosure Testing
print_status "Testing information disclosure..."

echo "Information Disclosure Test Results - $(date)" > "$RESULTS_DIR/info_disclosure_${TIMESTAMP}.txt"

# Test for sensitive files
sensitive_files=(
    "/.env"
    "/.env.example"
    "/phpinfo.php"
    "/admin"
    "/backup"
    "/config"
    "/database"
    "/logs"
    "/storage"
    "/vendor"
    "/composer.json"
    "/composer.lock"
    "/package.json"
    "/README.md"
    "/artisan"
)

for file in "${sensitive_files[@]}"; do
    echo "Testing access to: $file" >> "$RESULTS_DIR/info_disclosure_${TIMESTAMP}.txt"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL$file")
    echo "HTTP Code: $response" >> "$RESULTS_DIR/info_disclosure_${TIMESTAMP}.txt"
    
    if [ "$response" = "200" ]; then
        echo "WARNING: Sensitive file accessible" >> "$RESULTS_DIR/info_disclosure_${TIMESTAMP}.txt"
    else
        echo "File properly protected" >> "$RESULTS_DIR/info_disclosure_${TIMESTAMP}.txt"
    fi
    
    echo "---" >> "$RESULTS_DIR/info_disclosure_${TIMESTAMP}.txt"
done

print_success "Information disclosure testing completed"

# Test 8: Brute Force Protection Testing
print_status "Testing brute force protection..."

echo "Brute Force Protection Test Results - $(date)" > "$RESULTS_DIR/brute_force_${TIMESTAMP}.txt"

# Test login brute force protection
for i in {1..20}; do
    echo "Attempt $i" >> "$RESULTS_DIR/brute_force_${TIMESTAMP}.txt"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" -X POST -d "email=admin@example.com&password=wrongpassword$i" "$BASE_URL/admin/login")
    echo "HTTP Code: $response" >> "$RESULTS_DIR/brute_force_${TIMESTAMP}.txt"
    
    if [ "$response" = "429" ]; then
        echo "Rate limiting activated" >> "$RESULTS_DIR/brute_force_${TIMESTAMP}.txt"
        break
    fi
    
    sleep 1
done

print_success "Brute force protection testing completed"

# Test 9: HTTP Security Headers Testing
print_status "Testing HTTP security headers..."

echo "HTTP Security Headers Test Results - $(date)" > "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt"

# Test for security headers
headers_response=$(curl -s -I "$BASE_URL/")
echo "Response headers:" >> "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt"
echo "$headers_response" >> "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt"

# Check for specific security headers
security_headers=(
    "X-Frame-Options"
    "X-XSS-Protection"
    "X-Content-Type-Options"
    "Strict-Transport-Security"
    "Content-Security-Policy"
    "Referrer-Policy"
)

for header in "${security_headers[@]}"; do
    if echo "$headers_response" | grep -i "$header" > /dev/null; then
        echo "$header: PRESENT" >> "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt"
    else
        echo "$header: MISSING" >> "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt"
    fi
done

print_success "HTTP security headers testing completed"

# Test 10: API Security Testing
print_status "Testing API security..."

echo "API Security Test Results - $(date)" > "$RESULTS_DIR/api_security_${TIMESTAMP}.txt"

# Test API without authentication
api_endpoints=(
    "/api/user"
    "/api/dashboard/stats"
    "/api/wbs"
    "/api/portal-papua-tengah"
)

for endpoint in "${api_endpoints[@]}"; do
    echo "Testing API endpoint: $endpoint" >> "$RESULTS_DIR/api_security_${TIMESTAMP}.txt"
    
    response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL$endpoint")
    echo "HTTP Code: $response" >> "$RESULTS_DIR/api_security_${TIMESTAMP}.txt"
    
    if [ "$response" = "401" ]; then
        echo "API properly protected" >> "$RESULTS_DIR/api_security_${TIMESTAMP}.txt"
    else
        echo "Possible API security issue" >> "$RESULTS_DIR/api_security_${TIMESTAMP}.txt"
    fi
    
    echo "---" >> "$RESULTS_DIR/api_security_${TIMESTAMP}.txt"
done

print_success "API security testing completed"

# Generate comprehensive security report
print_status "Generating security report..."

cat > "$RESULTS_DIR/security_report_${TIMESTAMP}.txt" << EOF
INSPEKORAT SYSTEM - SECURITY TEST REPORT
Generated: $(date)
=========================================

TESTS PERFORMED:
1. SQL Injection Testing
2. XSS (Cross-Site Scripting) Testing
3. CSRF (Cross-Site Request Forgery) Testing
4. Directory Traversal Testing
5. Authentication Bypass Testing
6. File Upload Security Testing
7. Information Disclosure Testing
8. Brute Force Protection Testing
9. HTTP Security Headers Testing
10. API Security Testing

SUMMARY:
- Total security tests: 10
- Results saved in: $RESULTS_DIR
- Timestamp: $TIMESTAMP

CRITICAL FINDINGS:
$(grep -l "WARNING\|MISSING\|vulnerability" "$RESULTS_DIR"/*${TIMESTAMP}*.txt | while read file; do echo "- Check $(basename $file)"; done)

RECOMMENDATIONS:
1. Review all WARNING messages in test results
2. Implement missing security headers
3. Fix any identified vulnerabilities
4. Regularly update dependencies
5. Implement Web Application Firewall (WAF)
6. Set up security monitoring
7. Conduct regular security audits
8. Train developers on secure coding practices

NEXT STEPS:
1. Review detailed results in each test file
2. Prioritize fixing critical vulnerabilities
3. Implement security best practices
4. Schedule regular security testing
5. Monitor security logs continuously

DETAILED RESULTS:
EOF

# Add summary of each test
for file in "$RESULTS_DIR"/*${TIMESTAMP}*.txt; do
    if [ -f "$file" ] && [ "$(basename "$file")" != "security_report_${TIMESTAMP}.txt" ]; then
        echo "- $(basename "$file"): $(wc -l < "$file") lines" >> "$RESULTS_DIR/security_report_${TIMESTAMP}.txt"
    fi
done

# Clean up test files
rm -f "$RESULTS_DIR"/test.*

# Display results
echo ""
echo "====================================================================="
echo "                    SECURITY TESTING COMPLETED"
echo "====================================================================="
echo ""

print_success "Security testing completed successfully!"
echo ""
echo "RESULTS LOCATION: $RESULTS_DIR"
echo "MAIN REPORT: $RESULTS_DIR/security_report_${TIMESTAMP}.txt"
echo ""

# Show critical findings
echo "CRITICAL FINDINGS:"
echo "=================="
if grep -q "WARNING\|vulnerability\|bypass" "$RESULTS_DIR"/*${TIMESTAMP}*.txt; then
    grep -l "WARNING\|vulnerability\|bypass" "$RESULTS_DIR"/*${TIMESTAMP}*.txt | while read file; do
        echo "- $(basename "$file"): $(grep -c "WARNING\|vulnerability\|bypass" "$file") issues found"
    done
else
    echo "No critical security issues found in automated tests"
fi

echo ""
echo "SECURITY HEADERS STATUS:"
echo "======================="
if [ -f "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt" ]; then
    grep -E "(PRESENT|MISSING)" "$RESULTS_DIR/security_headers_${TIMESTAMP}.txt" | head -6
fi

echo ""
echo "====================================================================="
echo "IMPORTANT: Manual security review is also recommended!"
echo "Review all detailed test results in: $RESULTS_DIR"
echo "====================================================================="
