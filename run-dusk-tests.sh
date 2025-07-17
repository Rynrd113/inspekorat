#!/bin/bash

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fungsi untuk menampilkan header
print_header() {
    echo -e "${BLUE}================================${NC}"
    echo -e "${BLUE}  Portal Inspektorat Dusk Tests${NC}"
    echo -e "${BLUE}================================${NC}"
    echo ""
}

# Fungsi untuk menampilkan pesan
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Fungsi untuk mengecek prerequisites
check_prerequisites() {
    print_message "Checking prerequisites..."
    
    # Check PHP
    if ! command -v php &> /dev/null; then
        print_error "PHP is not installed"
        exit 1
    fi
    
    # Check Composer
    if ! command -v composer &> /dev/null; then
        print_error "Composer is not installed"
        exit 1
    fi
    
    # Check Chrome/Chromium
    if ! command -v google-chrome &> /dev/null && ! command -v chromium &> /dev/null; then
        print_warning "Chrome/Chromium not found, installing..."
        # Install Chrome if not found
        if [[ "$OSTYPE" == "linux-gnu"* ]]; then
            wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | sudo apt-key add -
            echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" | sudo tee /etc/apt/sources.list.d/google-chrome.list
            sudo apt-get update
            sudo apt-get install -y google-chrome-stable
        fi
    fi
    
    print_message "Prerequisites check completed"
}

# Fungsi untuk setup environment
setup_environment() {
    print_message "Setting up test environment..."
    
    # Copy environment file
    if [ ! -f .env.dusk.local ]; then
        cp .env.example .env.dusk.local
        print_message "Created .env.dusk.local file"
    fi
    
    # Install dependencies
    if [ ! -d "vendor" ]; then
        print_message "Installing dependencies..."
        composer install --no-interaction --prefer-dist
    fi
    
    # Generate application key
    php artisan key:generate --env=dusk.local
    
    # Create test database
    print_message "Setting up test database..."
    php artisan migrate:fresh --seed --env=dusk.local
    
    print_message "Environment setup completed"
}

# Fungsi untuk menjalankan test
run_tests() {
    local test_suite=$1
    local options=$2
    
    print_message "Running tests: $test_suite"
    
    # Start Laravel server in background
    print_message "Starting Laravel server..."
    php artisan serve --env=dusk.local &
    SERVER_PID=$!
    sleep 5
    
    # Run the tests
    case $test_suite in
        "all")
            php artisan dusk $options
            ;;
        "auth")
            php artisan dusk tests/Browser/Authentication $options
            ;;
        "wbs")
            php artisan dusk tests/Browser/Modules/WBS $options
            ;;
        "berita")
            php artisan dusk tests/Browser/Modules/Berita $options
            ;;
        "smoke")
            php artisan dusk tests/Browser/Authentication/LoginTest::test_user_dapat_login_dengan_credentials_valid $options
            ;;
        *)
            php artisan dusk $test_suite $options
            ;;
    esac
    
    local exit_code=$?
    
    # Stop Laravel server
    print_message "Stopping Laravel server..."
    kill $SERVER_PID 2>/dev/null
    
    return $exit_code
}

# Fungsi untuk cleanup
cleanup() {
    print_message "Cleaning up..."
    
    # Remove test files
    find tests/Browser/screenshots -name "*.png" -mtime +7 -delete 2>/dev/null
    find tests/Browser/console -name "*.log" -mtime +7 -delete 2>/dev/null
    
    # Kill any remaining Chrome processes
    pkill -f "chrome.*--headless" 2>/dev/null
    
    print_message "Cleanup completed"
}

# Fungsi untuk menampilkan bantuan
show_help() {
    echo "Usage: $0 [OPTIONS] [TEST_SUITE]"
    echo ""
    echo "Options:"
    echo "  -h, --help       Show this help message"
    echo "  -v, --verbose    Verbose output"
    echo "  -d, --debug      Debug mode (show browser)"
    echo "  -f, --filter     Filter tests by name"
    echo "  -s, --stop       Stop on first failure"
    echo "  --setup          Setup environment only"
    echo "  --cleanup        Cleanup only"
    echo ""
    echo "Test Suites:"
    echo "  all              Run all tests (default)"
    echo "  auth             Run authentication tests"
    echo "  wbs              Run WBS module tests"
    echo "  berita           Run Berita module tests"
    echo "  smoke            Run smoke tests"
    echo "  [specific_test]  Run specific test file"
    echo ""
    echo "Examples:"
    echo "  $0                           # Run all tests"
    echo "  $0 auth                      # Run authentication tests"
    echo "  $0 -d wbs                    # Run WBS tests with browser visible"
    echo "  $0 -f login                  # Run tests containing 'login'"
    echo "  $0 --setup                   # Setup environment only"
    echo "  $0 --cleanup                 # Cleanup only"
}

# Main function
main() {
    # Default values
    test_suite="all"
    options=""
    debug=false
    verbose=false
    setup_only=false
    cleanup_only=false
    
    # Parse arguments
    while [[ $# -gt 0 ]]; do
        case $1 in
            -h|--help)
                show_help
                exit 0
                ;;
            -v|--verbose)
                verbose=true
                options="$options --verbose"
                shift
                ;;
            -d|--debug)
                debug=true
                export DUSK_HEADLESS=false
                shift
                ;;
            -f|--filter)
                options="$options --filter $2"
                shift 2
                ;;
            -s|--stop)
                options="$options --stop-on-failure"
                shift
                ;;
            --setup)
                setup_only=true
                shift
                ;;
            --cleanup)
                cleanup_only=true
                shift
                ;;
            *)
                test_suite=$1
                shift
                ;;
        esac
    done
    
    # Print header
    print_header
    
    # Handle special modes
    if [ "$cleanup_only" = true ]; then
        cleanup
        exit 0
    fi
    
    if [ "$setup_only" = true ]; then
        check_prerequisites
        setup_environment
        exit 0
    fi
    
    # Set debug mode environment
    if [ "$debug" = true ]; then
        export DUSK_HEADLESS=false
        export DUSK_WINDOW_SIZE=1920x1080
        print_message "Debug mode enabled - browser will be visible"
    fi
    
    # Run full test process
    check_prerequisites
    setup_environment
    
    # Run tests
    if run_tests "$test_suite" "$options"; then
        print_message "Tests completed successfully!"
        exit_code=0
    else
        print_error "Tests failed!"
        exit_code=1
    fi
    
    # Cleanup
    cleanup
    
    exit $exit_code
}

# Handle script interruption
trap 'echo ""; print_warning "Script interrupted"; cleanup; exit 1' INT TERM

# Run main function
main "$@"
