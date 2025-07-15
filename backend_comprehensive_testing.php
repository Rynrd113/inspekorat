<?php
/**
 * Comprehensive PHP Backend Testing Script for Inspektorat Web Application
 * Tests all backend functionality, database operations, and Laravel features
 */

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\PortalPapuaTengah;
use App\Models\PortalOpd;
use App\Models\Wbs;
use App\Models\Pelayanan;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Faq;

class ComprehensiveBackendTester
{
    private $results = [];
    private $errors = [];
    private $testUsers = [];
    private $baseUrl;
    
    public function __construct($baseUrl = 'http://localhost:8000')
    {
        $this->baseUrl = $baseUrl;
        $this->initializeTestUsers();
    }
    
    private function initializeTestUsers()
    {
        $this->testUsers = [
            'admin' => [
                'email' => 'admin@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            'superadmin' => [
                'email' => 'superadmin@inspektorat.go.id',
                'password' => 'superadmin123',
                'role' => 'superadmin'
            ],
            'admin_wbs' => [
                'email' => 'admin_wbs@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_wbs'
            ],
            'admin_berita' => [
                'email' => 'admin_berita@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_berita'
            ],
            'admin_portal_opd' => [
                'email' => 'admin_portal_opd@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_portal_opd'
            ],
            'admin_pelayanan' => [
                'email' => 'admin_pelayanan@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_pelayanan'
            ],
            'admin_dokumen' => [
                'email' => 'admin_dokumen@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_dokumen'
            ],
            'admin_galeri' => [
                'email' => 'admin_galeri@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_galeri'
            ],
            'admin_faq' => [
                'email' => 'admin_faq@inspektorat.go.id',
                'password' => 'admin123',
                'role' => 'admin_faq'
            ],
        ];
    }
    
    private function log($message, $type = 'INFO')
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$type] $message";
        echo $logMessage . PHP_EOL;
        
        if ($type === 'ERROR') {
            $this->errors[] = $logMessage;
        }
    }
    
    private function addResult($testName, $status, $message = '', $details = [])
    {
        $this->results[] = [
            'test_name' => $testName,
            'status' => $status,
            'message' => $message,
            'details' => $details,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    private function makeHttpRequest($method, $url, $data = [], $headers = [])
    {
        $ch = curl_init();
        
        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest'
        ];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: $error");
        }
        
        return [
            'status_code' => $httpCode,
            'body' => $response,
            'data' => json_decode($response, true)
        ];
    }
    
    private function login($userType = 'admin')
    {
        if (!isset($this->testUsers[$userType])) {
            throw new Exception("Unknown user type: $userType");
        }
        
        $credentials = $this->testUsers[$userType];
        
        try {
            // Get login page first to get CSRF token
            $loginPageResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/login');
            
            // Extract CSRF token from response
            preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPageResponse['body'], $matches);
            $csrfToken = $matches[1] ?? '';
            
            // Perform login
            $loginResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/login', [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
                '_token' => $csrfToken
            ]);
            
            if ($loginResponse['status_code'] === 200 || $loginResponse['status_code'] === 302) {
                $this->log("Successfully logged in as $userType");
                return true;
            } else {
                $this->log("Failed to login as $userType: HTTP " . $loginResponse['status_code'], 'ERROR');
                return false;
            }
            
        } catch (Exception $e) {
            $this->log("Login error for $userType: " . $e->getMessage(), 'ERROR');
            return false;
        }
    }
    
    private function logout()
    {
        try {
            $response = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/logout');
            return $response['status_code'] === 200 || $response['status_code'] === 302;
        } catch (Exception $e) {
            $this->log("Logout error: " . $e->getMessage(), 'ERROR');
            return false;
        }
    }
    
    public function testDatabaseConnection()
    {
        $this->log("Testing database connection...");
        
        try {
            // Test basic connection
            $pdo = new PDO("mysql:host=localhost;dbname=portal_inspektorat", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Test table existence
            $tables = [
                'users', 'portal_papua_tengahs', 'portal_opds', 'wbs', 
                'pelayanans', 'dokumens', 'galeris', 'faqs'
            ];
            
            foreach ($tables as $table) {
                $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
                $stmt->execute([$table]);
                
                if ($stmt->rowCount() > 0) {
                    $this->log("✓ Table '$table' exists");
                } else {
                    $this->log("✗ Table '$table' missing", 'ERROR');
                }
            }
            
            $this->addResult('Database Connection', 'PASS', 'Database connection successful');
            
        } catch (Exception $e) {
            $this->log("Database connection failed: " . $e->getMessage(), 'ERROR');
            $this->addResult('Database Connection', 'FAIL', $e->getMessage());
        }
    }
    
    public function testUserAuthentication()
    {
        $this->log("Testing user authentication...");
        
        foreach ($this->testUsers as $userType => $credentials) {
            try {
                $loginSuccess = $this->login($userType);
                
                if ($loginSuccess) {
                    // Test dashboard access
                    $dashboardResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/dashboard');
                    
                    if ($dashboardResponse['status_code'] === 200) {
                        $this->addResult("Authentication - $userType", 'PASS', 'Login and dashboard access successful');
                        $this->log("✓ $userType authentication successful");
                    } else {
                        $this->addResult("Authentication - $userType", 'FAIL', 'Dashboard access failed');
                        $this->log("✗ $userType dashboard access failed", 'ERROR');
                    }
                    
                    $this->logout();
                } else {
                    $this->addResult("Authentication - $userType", 'FAIL', 'Login failed');
                    $this->log("✗ $userType login failed", 'ERROR');
                }
                
            } catch (Exception $e) {
                $this->addResult("Authentication - $userType", 'FAIL', $e->getMessage());
                $this->log("✗ $userType authentication error: " . $e->getMessage(), 'ERROR');
            }
        }
    }
    
    public function testWbsCrud()
    {
        $this->log("Testing WBS CRUD operations...");
        
        if (!$this->login('admin_wbs')) {
            $this->addResult('WBS CRUD', 'FAIL', 'Could not login as admin_wbs');
            return;
        }
        
        try {
            // Test CREATE
            $createData = [
                'nama_pelapor' => 'Test Reporter',
                'email' => 'test@example.com',
                'subjek' => 'Test Subject',
                'pesan' => 'Test message for WBS CRUD',
                'status' => 'pending'
            ];
            
            $createResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/wbs', $createData);
            
            if ($createResponse['status_code'] === 200 || $createResponse['status_code'] === 302) {
                $this->log("✓ WBS CREATE successful");
                
                // Test READ
                $readResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/wbs');
                
                if ($readResponse['status_code'] === 200) {
                    $this->log("✓ WBS READ successful");
                    
                    $this->addResult('WBS CRUD', 'PASS', 'CREATE and READ operations successful');
                } else {
                    $this->addResult('WBS CRUD', 'FAIL', 'READ operation failed');
                    $this->log("✗ WBS READ failed", 'ERROR');
                }
                
            } else {
                $this->addResult('WBS CRUD', 'FAIL', 'CREATE operation failed');
                $this->log("✗ WBS CREATE failed", 'ERROR');
            }
            
        } catch (Exception $e) {
            $this->addResult('WBS CRUD', 'FAIL', $e->getMessage());
            $this->log("✗ WBS CRUD error: " . $e->getMessage(), 'ERROR');
        }
        
        $this->logout();
    }
    
    public function testNewsCrud()
    {
        $this->log("Testing Portal Papua Tengah (News) CRUD operations...");
        
        if (!$this->login('admin_berita')) {
            $this->addResult('News CRUD', 'FAIL', 'Could not login as admin_berita');
            return;
        }
        
        try {
            // Test CREATE
            $createData = [
                'judul' => 'Test News Title',
                'konten' => 'Test news content for CRUD testing',
                'kategori' => 'umum',
                'penulis' => 'Test Author',
                'ringkasan' => 'Test summary',
                'is_published' => true
            ];
            
            $createResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/portal-papua-tengah', $createData);
            
            if ($createResponse['status_code'] === 200 || $createResponse['status_code'] === 302) {
                $this->log("✓ News CREATE successful");
                
                // Test READ
                $readResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/portal-papua-tengah');
                
                if ($readResponse['status_code'] === 200) {
                    $this->log("✓ News READ successful");
                    
                    $this->addResult('News CRUD', 'PASS', 'CREATE and READ operations successful');
                } else {
                    $this->addResult('News CRUD', 'FAIL', 'READ operation failed');
                    $this->log("✗ News READ failed", 'ERROR');
                }
                
            } else {
                $this->addResult('News CRUD', 'FAIL', 'CREATE operation failed');
                $this->log("✗ News CREATE failed", 'ERROR');
            }
            
        } catch (Exception $e) {
            $this->addResult('News CRUD', 'FAIL', $e->getMessage());
            $this->log("✗ News CRUD error: " . $e->getMessage(), 'ERROR');
        }
        
        $this->logout();
    }
    
    public function testPortalOpdCrud()
    {
        $this->log("Testing Portal OPD CRUD operations...");
        
        if (!$this->login('admin_portal_opd')) {
            $this->addResult('Portal OPD CRUD', 'FAIL', 'Could not login as admin_portal_opd');
            return;
        }
        
        try {
            // Test CREATE
            $createData = [
                'nama_opd' => 'Test OPD Name',
                'singkatan' => 'TEST',
                'kepala_opd' => 'Test Head',
                'alamat' => 'Test Address',
                'telepon' => '08123456789',
                'email' => 'test@opd.go.id',
                'website' => 'https://test.opd.go.id',
                'status' => true
            ];
            
            $createResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/portal-opd', $createData);
            
            if ($createResponse['status_code'] === 200 || $createResponse['status_code'] === 302) {
                $this->log("✓ Portal OPD CREATE successful");
                
                // Test READ
                $readResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/portal-opd');
                
                if ($readResponse['status_code'] === 200) {
                    $this->log("✓ Portal OPD READ successful");
                    
                    $this->addResult('Portal OPD CRUD', 'PASS', 'CREATE and READ operations successful');
                } else {
                    $this->addResult('Portal OPD CRUD', 'FAIL', 'READ operation failed');
                    $this->log("✗ Portal OPD READ failed", 'ERROR');
                }
                
            } else {
                $this->addResult('Portal OPD CRUD', 'FAIL', 'CREATE operation failed');
                $this->log("✗ Portal OPD CREATE failed", 'ERROR');
            }
            
        } catch (Exception $e) {
            $this->addResult('Portal OPD CRUD', 'FAIL', $e->getMessage());
            $this->log("✗ Portal OPD CRUD error: " . $e->getMessage(), 'ERROR');
        }
        
        $this->logout();
    }
    
    public function testPelayananCrud()
    {
        $this->log("Testing Pelayanan CRUD operations...");
        
        if (!$this->login('admin_pelayanan')) {
            $this->addResult('Pelayanan CRUD', 'FAIL', 'Could not login as admin_pelayanan');
            return;
        }
        
        try {
            // Test CREATE
            $createData = [
                'nama_layanan' => 'Test Service',
                'deskripsi' => 'Test service description',
                'kategori' => 'umum',
                'persyaratan' => 'Test requirements',
                'biaya' => 'Gratis',
                'waktu_penyelesaian' => '3 hari kerja',
                'status' => 'aktif'
            ];
            
            $createResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/pelayanan', $createData);
            
            if ($createResponse['status_code'] === 200 || $createResponse['status_code'] === 302) {
                $this->log("✓ Pelayanan CREATE successful");
                
                // Test READ
                $readResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/pelayanan');
                
                if ($readResponse['status_code'] === 200) {
                    $this->log("✓ Pelayanan READ successful");
                    
                    $this->addResult('Pelayanan CRUD', 'PASS', 'CREATE and READ operations successful');
                } else {
                    $this->addResult('Pelayanan CRUD', 'FAIL', 'READ operation failed');
                    $this->log("✗ Pelayanan READ failed", 'ERROR');
                }
                
            } else {
                $this->addResult('Pelayanan CRUD', 'FAIL', 'CREATE operation failed');
                $this->log("✗ Pelayanan CREATE failed", 'ERROR');
            }
            
        } catch (Exception $e) {
            $this->addResult('Pelayanan CRUD', 'FAIL', $e->getMessage());
            $this->log("✗ Pelayanan CRUD error: " . $e->getMessage(), 'ERROR');
        }
        
        $this->logout();
    }
    
    public function testFaqCrud()
    {
        $this->log("Testing FAQ CRUD operations...");
        
        if (!$this->login('admin_faq')) {
            $this->addResult('FAQ CRUD', 'FAIL', 'Could not login as admin_faq');
            return;
        }
        
        try {
            // Test CREATE
            $createData = [
                'pertanyaan' => 'Test Question?',
                'jawaban' => 'Test answer for the question.',
                'kategori' => 'umum',
                'is_active' => true,
                'urutan' => 1
            ];
            
            $createResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/admin/faq', $createData);
            
            if ($createResponse['status_code'] === 200 || $createResponse['status_code'] === 302) {
                $this->log("✓ FAQ CREATE successful");
                
                // Test READ
                $readResponse = $this->makeHttpRequest('GET', $this->baseUrl . '/admin/faq');
                
                if ($readResponse['status_code'] === 200) {
                    $this->log("✓ FAQ read successful");
                    
                    $this->addResult('FAQ CRUD', 'PASS', 'CREATE and READ operations successful');
                } else {
                    $this->addResult('FAQ CRUD', 'FAIL', 'READ operation failed');
                    $this->log("✗ FAQ read failed", 'ERROR');
                }
                
            } else {
                $this->addResult('FAQ CRUD', 'FAIL', 'CREATE operation failed');
                $this->log("✗ FAQ CREATE failed", 'ERROR');
            }
            
        } catch (Exception $e) {
            $this->addResult('FAQ CRUD', 'FAIL', $e->getMessage());
            $this->log("✗ FAQ CRUD error: " . $e->getMessage(), 'ERROR');
        }
        
        $this->logout();
    }
    
    public function testApiEndpoints()
    {
        $this->log("Testing API endpoints...");
        
        $apiTests = [
            ['GET', '/api/v1/portal-papua-tengah', [], 'News API'],
            ['GET', '/api/v1/info-kantor', [], 'Office Info API'],
            ['POST', '/api/v1/wbs', [
                'nama_pelapor' => 'Test Reporter',
                'email' => 'test@example.com',
                'subjek' => 'Test Subject',
                'pesan' => 'Test message'
            ], 'WBS API'],
            ['POST', '/api/auth/login', [
                'email' => 'admin@inspektorat.go.id',
                'password' => 'admin123'
            ], 'Auth API']
        ];
        
        foreach ($apiTests as $test) {
            [$method, $endpoint, $data, $name] = $test;
            
            try {
                $response = $this->makeHttpRequest($method, $this->baseUrl . $endpoint, $data);
                
                if ($response['status_code'] === 200 || $response['status_code'] === 201) {
                    $this->log("✓ $name successful");
                    $this->addResult("API - $name", 'PASS', 'API endpoint accessible');
                } else {
                    $this->log("✗ $name failed with status: " . $response['status_code'], 'ERROR');
                    $this->addResult("API - $name", 'FAIL', 'HTTP ' . $response['status_code']);
                }
                
            } catch (Exception $e) {
                $this->log("✗ $name error: " . $e->getMessage(), 'ERROR');
                $this->addResult("API - $name", 'FAIL', $e->getMessage());
            }
        }
    }
    
    public function testPublicFormSubmissions()
    {
        $this->log("Testing public form submissions...");
        
        try {
            // Test WBS form submission
            $wbsData = [
                'nama_pelapor' => 'Test Reporter',
                'email' => 'test@example.com',
                'subjek' => 'Test WBS Subject',
                'pesan' => 'Test WBS message'
            ];
            
            $wbsResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/wbs', $wbsData);
            
            if ($wbsResponse['status_code'] === 200 || $wbsResponse['status_code'] === 302) {
                $this->log("✓ WBS form submission successful");
                $this->addResult('WBS Form Submission', 'PASS', 'Form submitted successfully');
            } else {
                $this->log("✗ WBS form submission failed", 'ERROR');
                $this->addResult('WBS Form Submission', 'FAIL', 'Form submission failed');
            }
            
            // Test Contact form submission
            $contactData = [
                'nama' => 'Test Contact',
                'email' => 'test@example.com',
                'subjek' => 'Test Contact Subject',
                'pesan' => 'Test contact message'
            ];
            
            $contactResponse = $this->makeHttpRequest('POST', $this->baseUrl . '/kontak', $contactData);
            
            if ($contactResponse['status_code'] === 200 || $contactResponse['status_code'] === 302) {
                $this->log("✓ Contact form submission successful");
                $this->addResult('Contact Form Submission', 'PASS', 'Form submitted successfully');
            } else {
                $this->log("✗ Contact form submission failed", 'ERROR');
                $this->addResult('Contact Form Submission', 'FAIL', 'Form submission failed');
            }
            
        } catch (Exception $e) {
            $this->log("✗ Form submission error: " . $e->getMessage(), 'ERROR');
            $this->addResult('Form Submissions', 'FAIL', $e->getMessage());
        }
    }
    
    public function testRoleBasedAccess()
    {
        $this->log("Testing role-based access control...");
        
        $roleTests = [
            ['admin_wbs', '/admin/wbs', true],
            ['admin_wbs', '/admin/portal-papua-tengah', false],
            ['admin_berita', '/admin/portal-papua-tengah', true],
            ['admin_berita', '/admin/wbs', false],
            ['admin_portal_opd', '/admin/portal-opd', true],
            ['admin_portal_opd', '/admin/users', false],
            ['superadmin', '/admin/users', true],
            ['superadmin', '/admin/configurations', true],
        ];
        
        foreach ($roleTests as $test) {
            [$role, $endpoint, $shouldAccess] = $test;
            
            if (!$this->login($role)) {
                continue;
            }
            
            try {
                $response = $this->makeHttpRequest('GET', $this->baseUrl . $endpoint);
                
                if ($shouldAccess) {
                    if ($response['status_code'] === 200) {
                        $this->log("✓ $role correctly has access to $endpoint");
                        $this->addResult("Role Access - $role to $endpoint", 'PASS', 'Access granted as expected');
                    } else {
                        $this->log("✗ $role should have access to $endpoint but was denied", 'ERROR');
                        $this->addResult("Role Access - $role to $endpoint", 'FAIL', 'Access denied unexpectedly');
                    }
                } else {
                    if ($response['status_code'] === 403 || $response['status_code'] === 401) {
                        $this->log("✓ $role correctly denied access to $endpoint");
                        $this->addResult("Role Access - $role to $endpoint", 'PASS', 'Access denied as expected');
                    } else {
                        $this->log("✗ $role should be denied access to $endpoint but was granted", 'ERROR');
                        $this->addResult("Role Access - $role to $endpoint", 'FAIL', 'Access granted unexpectedly');
                    }
                }
                
            } catch (Exception $e) {
                $this->log("✗ Role access test error for $role to $endpoint: " . $e->getMessage(), 'ERROR');
                $this->addResult("Role Access - $role to $endpoint", 'FAIL', $e->getMessage());
            }
            
            $this->logout();
        }
    }
    
    public function testSecurityVulnerabilities()
    {
        $this->log("Testing security vulnerabilities...");
        
        // Test SQL Injection
        $sqlPayloads = [
            "' OR '1'='1",
            "1' UNION SELECT * FROM users--",
            "'; DROP TABLE users;--"
        ];
        
        foreach ($sqlPayloads as $payload) {
            try {
                $response = $this->makeHttpRequest('GET', $this->baseUrl . '/berita?search=' . urlencode($payload));
                
                if ($response['status_code'] === 500) {
                    $this->log("⚠ Possible SQL injection vulnerability with payload: $payload", 'ERROR');
                    $this->addResult('SQL Injection Test', 'VULNERABLE', "SQL injection possible with payload: $payload");
                } else {
                    $this->log("✓ SQL injection test passed for payload: $payload");
                    $this->addResult('SQL Injection Test', 'PASS', "Protected against SQL injection");
                }
                
            } catch (Exception $e) {
                $this->log("✗ SQL injection test error: " . $e->getMessage(), 'ERROR');
            }
        }
        
        // Test XSS
        $xssPayloads = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "javascript:alert('XSS')"
        ];
        
        foreach ($xssPayloads as $payload) {
            try {
                $response = $this->makeHttpRequest('GET', $this->baseUrl . '/berita?search=' . urlencode($payload));
                
                if (strpos($response['body'], $payload) !== false) {
                    $this->log("⚠ Possible XSS vulnerability with payload: $payload", 'ERROR');
                    $this->addResult('XSS Test', 'VULNERABLE', "XSS possible with payload: $payload");
                } else {
                    $this->log("✓ XSS test passed for payload: $payload");
                    $this->addResult('XSS Test', 'PASS', "Protected against XSS");
                }
                
            } catch (Exception $e) {
                $this->log("✗ XSS test error: " . $e->getMessage(), 'ERROR');
            }
        }
    }
    
    public function generateReport()
    {
        $this->log("Generating test report...");
        
        $totalTests = count($this->results);
        $passedTests = count(array_filter($this->results, function($r) { return $r['status'] === 'PASS'; }));
        $failedTests = count(array_filter($this->results, function($r) { return $r['status'] === 'FAIL'; }));
        $vulnerableTests = count(array_filter($this->results, function($r) { return $r['status'] === 'VULNERABLE'; }));
        
        $report = [
            'summary' => [
                'total_tests' => $totalTests,
                'passed' => $passedTests,
                'failed' => $failedTests,
                'vulnerable' => $vulnerableTests,
                'success_rate' => $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0
            ],
            'results' => $this->results,
            'errors' => $this->errors,
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        // Save JSON report
        file_put_contents('backend_test_report.json', json_encode($report, JSON_PRETTY_PRINT));
        
        // Save HTML report
        $htmlContent = $this->generateHtmlReport($report);
        file_put_contents('backend_test_report.html', $htmlContent);
        
        $this->log("Test report generated: backend_test_report.json and backend_test_report.html");
        
        return $report;
    }
    
    private function generateHtmlReport($report)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Backend Testing Report - Inspektorat</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .summary { background-color: #f0f0f0; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .pass { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
        .vulnerable { color: orange; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .error-section { background-color: #ffe6e6; padding: 15px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Backend Testing Report - Inspektorat Web Application</h1>
    <p>Generated on: ' . $report['generated_at'] . '</p>
    
    <div class="summary">
        <h2>Test Summary</h2>
        <p><strong>Total Tests:</strong> ' . $report['summary']['total_tests'] . '</p>
        <p><strong class="pass">Passed:</strong> ' . $report['summary']['passed'] . '</p>
        <p><strong class="fail">Failed:</strong> ' . $report['summary']['failed'] . '</p>
        <p><strong class="vulnerable">Vulnerable:</strong> ' . $report['summary']['vulnerable'] . '</p>
        <p><strong>Success Rate:</strong> ' . $report['summary']['success_rate'] . '%</p>
    </div>';
        
        if (!empty($report['errors'])) {
            $html .= '<div class="error-section">
                <h2>Errors Summary</h2>
                <ul>';
            foreach ($report['errors'] as $error) {
                $html .= '<li>' . htmlspecialchars($error) . '</li>';
            }
            $html .= '</ul></div>';
        }
        
        $html .= '<h2>Detailed Test Results</h2>
            <table>
                <tr>
                    <th>Test Name</th>
                    <th>Status</th>
                    <th>Message</th>
                    <th>Timestamp</th>
                </tr>';
        
        foreach ($report['results'] as $result) {
            $statusClass = strtolower($result['status']);
            $html .= '<tr>
                <td>' . htmlspecialchars($result['test_name']) . '</td>
                <td class="' . $statusClass . '">' . $result['status'] . '</td>
                <td>' . htmlspecialchars($result['message']) . '</td>
                <td>' . $result['timestamp'] . '</td>
            </tr>';
        }
        
        $html .= '</table>
</body>
</html>';
        
        return $html;
    }
    
    public function runAllTests()
    {
        $this->log("Starting comprehensive backend testing...");
        
        // Test database connection
        $this->testDatabaseConnection();
        
        // Test user authentication
        $this->testUserAuthentication();
        
        // Test CRUD operations
        $this->testWbsCrud();
        $this->testNewsCrud();
        $this->testPortalOpdCrud();
        $this->testPelayananCrud();
        $this->testFaqCrud();
        
        // Test API endpoints
        $this->testApiEndpoints();
        
        // Test public form submissions
        $this->testPublicFormSubmissions();
        
        // Test role-based access control
        $this->testRoleBasedAccess();
        
        // Test security vulnerabilities
        $this->testSecurityVulnerabilities();
        
        // Generate report
        $report = $this->generateReport();
        
        // Print summary
        $this->log("=====================================");
        $this->log("BACKEND TESTING COMPLETE");
        $this->log("=====================================");
        $this->log("Total Tests: " . $report['summary']['total_tests']);
        $this->log("Passed: " . $report['summary']['passed']);
        $this->log("Failed: " . $report['summary']['failed']);
        $this->log("Vulnerable: " . $report['summary']['vulnerable']);
        $this->log("Success Rate: " . $report['summary']['success_rate'] . "%");
        $this->log("=====================================");
        
        return $report;
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    $baseUrl = $argv[1] ?? 'http://localhost:8000';
    echo "Starting comprehensive backend testing for: $baseUrl\n";
    
    $tester = new ComprehensiveBackendTester($baseUrl);
    $tester->runAllTests();
}
