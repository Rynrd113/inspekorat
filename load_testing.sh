#!/bin/bash

# Load Testing Script for Inspekorat System
# This script performs load testing on the web application

echo "====================================================================="
echo "          INSPEKORAT SYSTEM - LOAD TESTING SCRIPT"
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
CONCURRENT_USERS=10
TEST_DURATION=60
RESULTS_DIR="load-test-results"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

# Create results directory
mkdir -p "$RESULTS_DIR"

# Check if Apache Bench is installed
if ! command -v ab &> /dev/null; then
    print_error "Apache Bench (ab) not found. Please install it:"
    echo "Ubuntu/Debian: sudo apt-get install apache2-utils"
    echo "CentOS/RHEL: sudo yum install httpd-tools"
    echo "macOS: brew install httpd"
    exit 1
fi

# Check if curl is installed
if ! command -v curl &> /dev/null; then
    print_error "curl not found. Please install it."
    exit 1
fi

print_status "Starting load testing..."
print_status "Base URL: $BASE_URL"
print_status "Concurrent Users: $CONCURRENT_USERS"
print_status "Test Duration: $TEST_DURATION seconds"
print_status "Results will be saved to: $RESULTS_DIR"

# Function to test endpoint
test_endpoint() {
    local endpoint=$1
    local method=$2
    local data=$3
    local description=$4
    
    print_status "Testing $description..."
    
    if [ "$method" = "GET" ]; then
        ab -n 1000 -c $CONCURRENT_USERS -g "$RESULTS_DIR/${endpoint//\//_}_${TIMESTAMP}.gnuplot" \
           "$BASE_URL$endpoint" > "$RESULTS_DIR/${endpoint//\//_}_${TIMESTAMP}.txt" 2>&1
    elif [ "$method" = "POST" ]; then
        ab -n 100 -c $CONCURRENT_USERS -p "$data" -T "application/x-www-form-urlencoded" \
           "$BASE_URL$endpoint" > "$RESULTS_DIR/${endpoint//\//_}_post_${TIMESTAMP}.txt" 2>&1
    fi
    
    if [ $? -eq 0 ]; then
        print_success "$description completed"
    else
        print_error "$description failed"
    fi
}

# Function to create POST data files
create_post_data() {
    # WBS form data
    cat > "$RESULTS_DIR/wbs_data.txt" << EOF
nama=Load Test User&email=loadtest@example.com&telepon=081234567890&pesan=Load testing message
EOF

    # Contact form data
    cat > "$RESULTS_DIR/contact_data.txt" << EOF
nama=Load Test User&email=loadtest@example.com&subjek=Load Test Subject&pesan=Load testing message
EOF

    # Login data
    cat > "$RESULTS_DIR/login_data.txt" << EOF
email=admin@example.com&password=password
EOF
}

# Create POST data files
create_post_data

# Test 1: Homepage Load Test
test_endpoint "/" "GET" "" "Homepage Load Test"

# Test 2: Public Pages Load Test
print_status "Testing public pages..."

endpoints=(
    "/berita"
    "/wbs"
    "/profil"
    "/pelayanan"
    "/dokumen"
    "/galeri"
    "/faq"
    "/kontak"
    "/portal-opd"
)

for endpoint in "${endpoints[@]}"; do
    test_endpoint "$endpoint" "GET" "" "Public page: $endpoint"
done

# Test 3: API Endpoints Load Test
print_status "Testing API endpoints..."

api_endpoints=(
    "/api/portal-papua-tengah/public"
    "/api/info-kantor/public"
    "/api/v1/portal-papua-tengah"
    "/api/v1/info-kantor"
)

for endpoint in "${api_endpoints[@]}"; do
    test_endpoint "$endpoint" "GET" "" "API endpoint: $endpoint"
done

# Test 4: Form Submission Load Test
print_status "Testing form submissions..."

test_endpoint "/wbs" "POST" "$RESULTS_DIR/wbs_data.txt" "WBS Form Submission"
test_endpoint "/kontak" "POST" "$RESULTS_DIR/contact_data.txt" "Contact Form Submission"

# Test 5: Concurrent User Simulation
print_status "Running concurrent user simulation..."

concurrent_test() {
    local user_id=$1
    local log_file="$RESULTS_DIR/concurrent_user_${user_id}_${TIMESTAMP}.log"
    
    {
        echo "User $user_id starting at $(date)"
        
        # Simulate user journey
        curl -s "$BASE_URL/" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/berita" > /dev/null
        sleep 2
        
        curl -s "$BASE_URL/wbs" > /dev/null
        sleep 1
        
        curl -s -X POST -d "nama=User$user_id&email=user$user_id@example.com&telepon=081234567890&pesan=Test message" \
             "$BASE_URL/wbs" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/profil" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/pelayanan" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/dokumen" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/galeri" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/faq" > /dev/null
        sleep 1
        
        curl -s "$BASE_URL/kontak" > /dev/null
        
        echo "User $user_id completed at $(date)"
    } >> "$log_file" 2>&1
}

# Start concurrent users
for i in $(seq 1 $CONCURRENT_USERS); do
    concurrent_test $i &
done

# Wait for all concurrent tests to complete
wait

# Test 6: Database Load Test
print_status "Testing database load..."

# Create multiple API requests to test database performance
for i in $(seq 1 100); do
    curl -s "$BASE_URL/api/portal-papua-tengah/public" > /dev/null &
    curl -s "$BASE_URL/api/info-kantor/public" > /dev/null &
    
    # Limit concurrent processes
    if [ $((i % 20)) -eq 0 ]; then
        wait
    fi
done

wait

# Test 7: Memory and CPU Usage Test
print_status "Monitoring system resources..."

# Monitor system resources during load test
{
    echo "Resource monitoring started at $(date)"
    for i in $(seq 1 60); do
        echo "Time: ${i}s"
        echo "Memory: $(free -h | grep Mem)"
        echo "CPU: $(top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk '{print 100 - $1"%"}')"
        echo "---"
        sleep 1
    done
    echo "Resource monitoring ended at $(date)"
} > "$RESULTS_DIR/resource_monitoring_${TIMESTAMP}.log" &

# Run load test while monitoring
ab -n 1000 -c 50 "$BASE_URL/" > "$RESULTS_DIR/resource_load_test_${TIMESTAMP}.txt" 2>&1

# Wait for monitoring to complete
wait

# Test 8: Error Rate Test
print_status "Testing error handling under load..."

# Test with invalid requests
ab -n 500 -c 20 "$BASE_URL/non-existent-page" > "$RESULTS_DIR/error_test_${TIMESTAMP}.txt" 2>&1

# Test with malformed requests
curl -s -X POST -d "invalid=data" "$BASE_URL/wbs" > "$RESULTS_DIR/malformed_request_${TIMESTAMP}.txt" 2>&1

# Generate summary report
print_status "Generating load test summary..."

cat > "$RESULTS_DIR/load_test_summary_${TIMESTAMP}.txt" << EOF
INSPEKORAT SYSTEM - LOAD TEST SUMMARY
Generated: $(date)
======================================

TEST CONFIGURATION:
- Base URL: $BASE_URL
- Concurrent Users: $CONCURRENT_USERS
- Test Duration: $TEST_DURATION seconds
- Test Timestamp: $TIMESTAMP

TESTS PERFORMED:
1. Homepage Load Test
2. Public Pages Load Test
3. API Endpoints Load Test
4. Form Submission Load Test
5. Concurrent User Simulation
6. Database Load Test
7. Memory and CPU Usage Test
8. Error Rate Test

RESULTS FILES:
$(ls -la "$RESULTS_DIR"/*${TIMESTAMP}*)

RECOMMENDATIONS:
1. Review Apache Bench results for response times
2. Check resource monitoring for system bottlenecks
3. Analyze error rates and fix any issues
4. Optimize database queries if necessary
5. Consider implementing caching strategies
6. Monitor server resources during peak loads

NEXT STEPS:
1. Review all result files in $RESULTS_DIR
2. Identify performance bottlenecks
3. Optimize code and database queries
4. Run tests again to verify improvements
5. Set up continuous performance monitoring

EOF

# Display summary
echo ""
echo "====================================================================="
echo "                      LOAD TEST COMPLETED"
echo "====================================================================="
echo ""

print_success "Load testing completed successfully!"
echo ""
echo "RESULTS LOCATION: $RESULTS_DIR"
echo "SUMMARY REPORT: $RESULTS_DIR/load_test_summary_${TIMESTAMP}.txt"
echo ""

# Show quick statistics from homepage test
if [ -f "$RESULTS_DIR/_${TIMESTAMP}.txt" ]; then
    echo "QUICK STATS (Homepage):"
    echo "======================="
    grep -E "(Requests per second|Time per request|Transfer rate)" "$RESULTS_DIR/_${TIMESTAMP}.txt" | head -3
    echo ""
fi

# Show resource usage summary
if [ -f "$RESULTS_DIR/resource_monitoring_${TIMESTAMP}.log" ]; then
    echo "RESOURCE USAGE:"
    echo "==============="
    echo "Peak Memory Usage:"
    grep "Memory:" "$RESULTS_DIR/resource_monitoring_${TIMESTAMP}.log" | tail -1
    echo "Peak CPU Usage:"
    grep "CPU:" "$RESULTS_DIR/resource_monitoring_${TIMESTAMP}.log" | tail -1
    echo ""
fi

echo "====================================================================="
echo "Review the detailed results in: $RESULTS_DIR"
echo "====================================================================="
