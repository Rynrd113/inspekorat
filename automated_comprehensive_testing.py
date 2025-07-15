#!/usr/bin/env python3
"""
Comprehensive Automated Testing Script for Inspektorat Web Application
Test all features: CRUD operations, authentication, frontend, backend, API endpoints
"""

import os
import sys
import time
import json
import requests
import logging
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from selenium.common.exceptions import TimeoutException, WebDriverException
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import sqlite3

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('test_results.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class InspektoratTester:
    def __init__(self, base_url="http://localhost:8000"):
        self.base_url = base_url
        self.session = requests.Session()
        self.driver = None
        self.test_results = []
        self.errors = []
        
        # Test credentials for different user roles
        self.test_users = {
            'admin': {'email': 'admin@inspektorat.go.id', 'password': 'admin123'},
            'superadmin': {'email': 'superadmin@inspektorat.go.id', 'password': 'superadmin123'},
            'admin_wbs': {'email': 'admin_wbs@inspektorat.go.id', 'password': 'admin123'},
            'admin_berita': {'email': 'admin_berita@inspektorat.go.id', 'password': 'admin123'},
            'admin_portal_opd': {'email': 'admin_portal_opd@inspektorat.go.id', 'password': 'admin123'},
            'admin_pelayanan': {'email': 'admin_pelayanan@inspektorat.go.id', 'password': 'admin123'},
            'admin_dokumen': {'email': 'admin_dokumen@inspektorat.go.id', 'password': 'admin123'},
            'admin_galeri': {'email': 'admin_galeri@inspektorat.go.id', 'password': 'admin123'},
            'admin_faq': {'email': 'admin_faq@inspektorat.go.id', 'password': 'admin123'},
        }
        
        # Initialize SQLite database for storing test results
        self.init_test_database()
    
    def init_test_database(self):
        """Initialize SQLite database to store test results"""
        conn = sqlite3.connect('test_results.db')
        cursor = conn.cursor()
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS test_results (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                test_name TEXT NOT NULL,
                test_type TEXT NOT NULL,
                status TEXT NOT NULL,
                url TEXT,
                user_role TEXT,
                response_time REAL,
                error_message TEXT,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        conn.commit()
        conn.close()
    
    def save_test_result(self, test_name, test_type, status, url=None, user_role=None, response_time=None, error_message=None):
        """Save test result to database"""
        conn = sqlite3.connect('test_results.db')
        cursor = conn.cursor()
        cursor.execute('''
            INSERT INTO test_results (test_name, test_type, status, url, user_role, response_time, error_message)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ''', (test_name, test_type, status, url, user_role, response_time, error_message))
        conn.commit()
        conn.close()
        
        # Also add to in-memory results
        self.test_results.append({
            'test_name': test_name,
            'test_type': test_type,
            'status': status,
            'url': url,
            'user_role': user_role,
            'response_time': response_time,
            'error_message': error_message,
            'timestamp': datetime.now()
        })
    
    def setup_selenium(self):
        """Setup Selenium WebDriver"""
        try:
            chrome_options = Options()
            chrome_options.add_argument('--headless')
            chrome_options.add_argument('--no-sandbox')
            chrome_options.add_argument('--disable-dev-shm-usage')
            chrome_options.add_argument('--disable-gpu')
            chrome_options.add_argument('--window-size=1920,1080')
            
            self.driver = webdriver.Chrome(options=chrome_options)
            self.driver.implicitly_wait(10)
            logger.info("Selenium WebDriver initialized successfully")
            return True
        except Exception as e:
            logger.error(f"Failed to initialize Selenium: {e}")
            return False
    
    def teardown_selenium(self):
        """Cleanup Selenium WebDriver"""
        if self.driver:
            self.driver.quit()
            logger.info("Selenium WebDriver closed")
    
    def test_server_availability(self):
        """Test if server is running and accessible"""
        logger.info("Testing server availability...")
        
        try:
            start_time = time.time()
            response = self.session.get(self.base_url, timeout=10)
            response_time = time.time() - start_time
            
            if response.status_code == 200:
                self.save_test_result("Server Availability", "Infrastructure", "PASS", 
                                    self.base_url, None, response_time)
                logger.info("✓ Server is accessible")
                return True
            else:
                self.save_test_result("Server Availability", "Infrastructure", "FAIL", 
                                    self.base_url, None, response_time, 
                                    f"HTTP {response.status_code}")
                logger.error(f"✗ Server returned status code: {response.status_code}")
                return False
        except Exception as e:
            self.save_test_result("Server Availability", "Infrastructure", "FAIL", 
                                self.base_url, None, None, str(e))
            logger.error(f"✗ Server is not accessible: {e}")
            return False
    
    def test_public_pages(self):
        """Test all public pages accessibility"""
        logger.info("Testing public pages...")
        
        public_routes = [
            ('/', 'Homepage'),
            ('/berita', 'News Index'),
            ('/wbs', 'WBS Form'),
            ('/profil', 'Profile'),
            ('/pelayanan', 'Services'),
            ('/dokumen', 'Documents'),
            ('/galeri', 'Gallery'),
            ('/faq', 'FAQ'),
            ('/kontak', 'Contact'),
            ('/pengaduan', 'Complaints'),
            ('/portal-opd', 'Portal OPD'),
        ]
        
        for route, name in public_routes:
            try:
                start_time = time.time()
                response = self.session.get(f"{self.base_url}{route}", timeout=10)
                response_time = time.time() - start_time
                
                if response.status_code == 200:
                    self.save_test_result(f"Public Page: {name}", "Frontend", "PASS", 
                                        f"{self.base_url}{route}", None, response_time)
                    logger.info(f"✓ {name} page accessible")
                else:
                    self.save_test_result(f"Public Page: {name}", "Frontend", "FAIL", 
                                        f"{self.base_url}{route}", None, response_time, 
                                        f"HTTP {response.status_code}")
                    logger.error(f"✗ {name} page failed with status: {response.status_code}")
            except Exception as e:
                self.save_test_result(f"Public Page: {name}", "Frontend", "FAIL", 
                                    f"{self.base_url}{route}", None, None, str(e))
                logger.error(f"✗ {name} page error: {e}")
    
    def test_admin_authentication(self):
        """Test admin authentication for all user roles"""
        logger.info("Testing admin authentication...")
        
        for role, credentials in self.test_users.items():
            try:
                # Test login page accessibility
                start_time = time.time()
                response = self.session.get(f"{self.base_url}/admin/login", timeout=10)
                response_time = time.time() - start_time
                
                if response.status_code != 200:
                    self.save_test_result(f"Admin Login Page", "Authentication", "FAIL", 
                                        f"{self.base_url}/admin/login", role, response_time, 
                                        f"HTTP {response.status_code}")
                    continue
                
                # Test login process
                login_data = {
                    'email': credentials['email'],
                    'password': credentials['password']
                }
                
                start_time = time.time()
                response = self.session.post(f"{self.base_url}/admin/login", data=login_data, timeout=10)
                response_time = time.time() - start_time
                
                if response.status_code == 200 or response.status_code == 302:
                    self.save_test_result(f"Admin Login: {role}", "Authentication", "PASS", 
                                        f"{self.base_url}/admin/login", role, response_time)
                    logger.info(f"✓ {role} login successful")
                else:
                    self.save_test_result(f"Admin Login: {role}", "Authentication", "FAIL", 
                                        f"{self.base_url}/admin/login", role, response_time, 
                                        f"HTTP {response.status_code}")
                    logger.error(f"✗ {role} login failed with status: {response.status_code}")
                
                # Test dashboard access
                start_time = time.time()
                response = self.session.get(f"{self.base_url}/admin/dashboard", timeout=10)
                response_time = time.time() - start_time
                
                if response.status_code == 200:
                    self.save_test_result(f"Admin Dashboard: {role}", "Authentication", "PASS", 
                                        f"{self.base_url}/admin/dashboard", role, response_time)
                    logger.info(f"✓ {role} dashboard accessible")
                else:
                    self.save_test_result(f"Admin Dashboard: {role}", "Authentication", "FAIL", 
                                        f"{self.base_url}/admin/dashboard", role, response_time, 
                                        f"HTTP {response.status_code}")
                    logger.error(f"✗ {role} dashboard failed with status: {response.status_code}")
                
                # Test logout
                start_time = time.time()
                response = self.session.post(f"{self.base_url}/admin/logout", timeout=10)
                response_time = time.time() - start_time
                
                if response.status_code == 200 or response.status_code == 302:
                    self.save_test_result(f"Admin Logout: {role}", "Authentication", "PASS", 
                                        f"{self.base_url}/admin/logout", role, response_time)
                    logger.info(f"✓ {role} logout successful")
                else:
                    self.save_test_result(f"Admin Logout: {role}", "Authentication", "FAIL", 
                                        f"{self.base_url}/admin/logout", role, response_time, 
                                        f"HTTP {response.status_code}")
                    logger.error(f"✗ {role} logout failed with status: {response.status_code}")
                
            except Exception as e:
                self.save_test_result(f"Admin Auth: {role}", "Authentication", "FAIL", 
                                    None, role, None, str(e))
                logger.error(f"✗ {role} authentication error: {e}")
    
    def test_admin_pages(self):
        """Test admin pages accessibility for each user role"""
        logger.info("Testing admin pages...")
        
        admin_routes = {
            'admin_wbs': ['/admin/wbs', '/admin/wbs/create'],
            'admin_berita': ['/admin/portal-papua-tengah', '/admin/portal-papua-tengah/create'],
            'admin_portal_opd': ['/admin/portal-opd', '/admin/portal-opd/create'],
            'admin_pelayanan': ['/admin/pelayanan', '/admin/pelayanan/create'],
            'admin_dokumen': ['/admin/dokumen', '/admin/dokumen/create'],
            'admin_galeri': ['/admin/galeri', '/admin/galeri/create'],
            'admin_faq': ['/admin/faq', '/admin/faq/create'],
            'superadmin': ['/admin/users', '/admin/users/create', '/admin/configurations', '/admin/audit-logs'],
        }
        
        for role, routes in admin_routes.items():
            if role in self.test_users:
                # Login first
                credentials = self.test_users[role]
                login_data = {
                    'email': credentials['email'],
                    'password': credentials['password']
                }
                
                try:
                    self.session.post(f"{self.base_url}/admin/login", data=login_data)
                    
                    for route in routes:
                        try:
                            start_time = time.time()
                            response = self.session.get(f"{self.base_url}{route}", timeout=10)
                            response_time = time.time() - start_time
                            
                            if response.status_code == 200:
                                self.save_test_result(f"Admin Page: {route}", "Backend", "PASS", 
                                                    f"{self.base_url}{route}", role, response_time)
                                logger.info(f"✓ {role} can access {route}")
                            elif response.status_code == 403:
                                self.save_test_result(f"Admin Page: {route}", "Backend", "EXPECTED_FAIL", 
                                                    f"{self.base_url}{route}", role, response_time, 
                                                    "Access denied (as expected)")
                                logger.info(f"✓ {role} correctly denied access to {route}")
                            else:
                                self.save_test_result(f"Admin Page: {route}", "Backend", "FAIL", 
                                                    f"{self.base_url}{route}", role, response_time, 
                                                    f"HTTP {response.status_code}")
                                logger.error(f"✗ {role} page {route} failed with status: {response.status_code}")
                        except Exception as e:
                            self.save_test_result(f"Admin Page: {route}", "Backend", "FAIL", 
                                                f"{self.base_url}{route}", role, None, str(e))
                            logger.error(f"✗ {role} page {route} error: {e}")
                    
                    # Logout
                    self.session.post(f"{self.base_url}/admin/logout")
                    
                except Exception as e:
                    logger.error(f"✗ Error testing {role} pages: {e}")
    
    def test_api_endpoints(self):
        """Test API endpoints"""
        logger.info("Testing API endpoints...")
        
        api_endpoints = [
            ('GET', '/api/v1/portal-papua-tengah', 'News API'),
            ('GET', '/api/v1/info-kantor', 'Office Info API'),
            ('POST', '/api/v1/wbs', 'WBS API'),
            ('POST', '/api/auth/login', 'Auth API'),
        ]
        
        for method, endpoint, name in api_endpoints:
            try:
                start_time = time.time()
                
                if method == 'GET':
                    response = self.session.get(f"{self.base_url}{endpoint}", timeout=10)
                elif method == 'POST':
                    if 'wbs' in endpoint:
                        data = {
                            'nama_pelapor': 'Test User',
                            'email': 'test@example.com',
                            'subjek': 'Test Subject',
                            'pesan': 'Test message'
                        }
                    elif 'login' in endpoint:
                        data = {
                            'email': 'admin@inspektorat.go.id',
                            'password': 'admin123'
                        }
                    else:
                        data = {}
                    
                    response = self.session.post(f"{self.base_url}{endpoint}", json=data, timeout=10)
                
                response_time = time.time() - start_time
                
                if response.status_code in [200, 201]:
                    self.save_test_result(f"API: {name}", "API", "PASS", 
                                        f"{self.base_url}{endpoint}", None, response_time)
                    logger.info(f"✓ {name} API working")
                else:
                    self.save_test_result(f"API: {name}", "API", "FAIL", 
                                        f"{self.base_url}{endpoint}", None, response_time, 
                                        f"HTTP {response.status_code}")
                    logger.error(f"✗ {name} API failed with status: {response.status_code}")
                
            except Exception as e:
                self.save_test_result(f"API: {name}", "API", "FAIL", 
                                    f"{self.base_url}{endpoint}", None, None, str(e))
                logger.error(f"✗ {name} API error: {e}")
    
    def test_crud_operations(self):
        """Test CRUD operations using Selenium"""
        logger.info("Testing CRUD operations...")
        
        if not self.setup_selenium():
            logger.error("Cannot perform CRUD tests without Selenium")
            return
        
        # Test WBS CRUD
        self.test_wbs_crud()
        
        # Test Portal Papua Tengah CRUD
        self.test_news_crud()
        
        # Test Portal OPD CRUD
        self.test_portal_opd_crud()
        
        # Test Pelayanan CRUD
        self.test_pelayanan_crud()
        
        # Test Dokumen CRUD
        self.test_dokumen_crud()
        
        # Test Galeri CRUD
        self.test_galeri_crud()
        
        # Test FAQ CRUD
        self.test_faq_crud()
        
        self.teardown_selenium()
    
    def login_admin(self, role='admin'):
        """Login as admin using Selenium"""
        try:
            credentials = self.test_users[role]
            self.driver.get(f"{self.base_url}/admin/login")
            
            # Wait for login form
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "email"))
            )
            
            # Fill login form
            self.driver.find_element(By.NAME, "email").send_keys(credentials['email'])
            self.driver.find_element(By.NAME, "password").send_keys(credentials['password'])
            
            # Submit form
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            # Wait for redirect to dashboard
            WebDriverWait(self.driver, 10).until(
                EC.url_contains("/admin/dashboard")
            )
            
            logger.info(f"Successfully logged in as {role}")
            return True
            
        except Exception as e:
            logger.error(f"Failed to login as {role}: {e}")
            return False
    
    def test_wbs_crud(self):
        """Test WBS CRUD operations"""
        logger.info("Testing WBS CRUD operations...")
        
        if not self.login_admin('admin_wbs'):
            logger.error("Cannot test WBS CRUD without login")
            return
        
        try:
            # Test CREATE
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/wbs/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "nama_pelapor"))
            )
            
            # Fill form
            self.driver.find_element(By.NAME, "nama_pelapor").send_keys("Test Reporter")
            self.driver.find_element(By.NAME, "email").send_keys("test@example.com")
            self.driver.find_element(By.NAME, "subjek").send_keys("Test Subject")
            self.driver.find_element(By.NAME, "pesan").send_keys("Test message for WBS")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check if redirected to index
            WebDriverWait(self.driver, 10).until(
                EC.url_contains("/admin/wbs")
            )
            
            self.save_test_result("WBS CREATE", "CRUD", "PASS", 
                                f"{self.base_url}/admin/wbs/create", "admin_wbs", response_time)
            logger.info("✓ WBS CREATE successful")
            
            # Test READ
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/wbs")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("WBS READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/wbs", "admin_wbs", response_time)
            logger.info("✓ WBS READ successful")
            
        except Exception as e:
            self.save_test_result("WBS CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/wbs", "admin_wbs", None, str(e))
            logger.error(f"✗ WBS CRUD error: {e}")
    
    def test_news_crud(self):
        """Test Portal Papua Tengah (News) CRUD operations"""
        logger.info("Testing News CRUD operations...")
        
        if not self.login_admin('admin_berita'):
            logger.error("Cannot test News CRUD without login")
            return
        
        try:
            # Test CREATE
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/portal-papua-tengah/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "judul"))
            )
            
            # Fill form
            self.driver.find_element(By.NAME, "judul").send_keys("Test News Title")
            self.driver.find_element(By.NAME, "konten").send_keys("Test news content")
            self.driver.find_element(By.NAME, "kategori").send_keys("umum")
            self.driver.find_element(By.NAME, "penulis").send_keys("Test Author")
            self.driver.find_element(By.NAME, "ringkasan").send_keys("Test summary")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check if redirected to index
            WebDriverWait(self.driver, 10).until(
                EC.url_contains("/admin/portal-papua-tengah")
            )
            
            self.save_test_result("News CREATE", "CRUD", "PASS", 
                                f"{self.base_url}/admin/portal-papua-tengah/create", "admin_berita", response_time)
            logger.info("✓ News CREATE successful")
            
            # Test READ
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/portal-papua-tengah")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("News READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/portal-papua-tengah", "admin_berita", response_time)
            logger.info("✓ News READ successful")
            
        except Exception as e:
            self.save_test_result("News CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/portal-papua-tengah", "admin_berita", None, str(e))
            logger.error(f"✗ News CRUD error: {e}")
    
    def test_portal_opd_crud(self):
        """Test Portal OPD CRUD operations"""
        logger.info("Testing Portal OPD CRUD operations...")
        
        if not self.login_admin('admin_portal_opd'):
            logger.error("Cannot test Portal OPD CRUD without login")
            return
        
        try:
            # Test CREATE
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/portal-opd/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "nama_opd"))
            )
            
            # Fill form
            self.driver.find_element(By.NAME, "nama_opd").send_keys("Test OPD Name")
            self.driver.find_element(By.NAME, "singkatan").send_keys("TEST")
            self.driver.find_element(By.NAME, "kepala_opd").send_keys("Test Head")
            self.driver.find_element(By.NAME, "alamat").send_keys("Test Address")
            self.driver.find_element(By.NAME, "telepon").send_keys("08123456789")
            self.driver.find_element(By.NAME, "email").send_keys("test@opd.go.id")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check if redirected to index
            WebDriverWait(self.driver, 10).until(
                EC.url_contains("/admin/portal-opd")
            )
            
            self.save_test_result("Portal OPD CREATE", "CRUD", "PASS", 
                                f"{self.base_url}/admin/portal-opd/create", "admin_portal_opd", response_time)
            logger.info("✓ Portal OPD CREATE successful")
            
            # Test READ
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/portal-opd")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("Portal OPD READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/portal-opd", "admin_portal_opd", response_time)
            logger.info("✓ Portal OPD READ successful")
            
        except Exception as e:
            self.save_test_result("Portal OPD CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/portal-opd", "admin_portal_opd", None, str(e))
            logger.error(f"✗ Portal OPD CRUD error: {e}")
    
    def test_pelayanan_crud(self):
        """Test Pelayanan CRUD operations"""
        logger.info("Testing Pelayanan CRUD operations...")
        
        if not self.login_admin('admin_pelayanan'):
            logger.error("Cannot test Pelayanan CRUD without login")
            return
        
        try:
            # Test CREATE
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/pelayanan/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "nama_layanan"))
            )
            
            # Fill form
            self.driver.find_element(By.NAME, "nama_layanan").send_keys("Test Service")
            self.driver.find_element(By.NAME, "deskripsi").send_keys("Test service description")
            self.driver.find_element(By.NAME, "kategori").send_keys("umum")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check if redirected to index
            WebDriverWait(self.driver, 10).until(
                EC.url_contains("/admin/pelayanan")
            )
            
            self.save_test_result("Pelayanan CREATE", "CRUD", "PASS", 
                                f"{self.base_url}/admin/pelayanan/create", "admin_pelayanan", response_time)
            logger.info("✓ Pelayanan CREATE successful")
            
            # Test READ
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/pelayanan")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("Pelayanan READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/pelayanan", "admin_pelayanan", response_time)
            logger.info("✓ Pelayanan READ successful")
            
        except Exception as e:
            self.save_test_result("Pelayanan CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/pelayanan", "admin_pelayanan", None, str(e))
            logger.error(f"✗ Pelayanan CRUD error: {e}")
    
    def test_dokumen_crud(self):
        """Test Dokumen CRUD operations"""
        logger.info("Testing Dokumen CRUD operations...")
        
        if not self.login_admin('admin_dokumen'):
            logger.error("Cannot test Dokumen CRUD without login")
            return
        
        try:
            # Test READ (CREATE might require file upload)
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/dokumen")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("Dokumen READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/dokumen", "admin_dokumen", response_time)
            logger.info("✓ Dokumen READ successful")
            
            # Test CREATE page accessibility
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/dokumen/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "judul"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("Dokumen CREATE Page", "CRUD", "PASS", 
                                f"{self.base_url}/admin/dokumen/create", "admin_dokumen", response_time)
            logger.info("✓ Dokumen CREATE page accessible")
            
        except Exception as e:
            self.save_test_result("Dokumen CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/dokumen", "admin_dokumen", None, str(e))
            logger.error(f"✗ Dokumen CRUD error: {e}")
    
    def test_galeri_crud(self):
        """Test Galeri CRUD operations"""
        logger.info("Testing Galeri CRUD operations...")
        
        if not self.login_admin('admin_galeri'):
            logger.error("Cannot test Galeri CRUD without login")
            return
        
        try:
            # Test READ
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/galeri")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("Galeri READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/galeri", "admin_galeri", response_time)
            logger.info("✓ Galeri READ successful")
            
            # Test CREATE page accessibility
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/galeri/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "judul"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("Galeri CREATE Page", "CRUD", "PASS", 
                                f"{self.base_url}/admin/galeri/create", "admin_galeri", response_time)
            logger.info("✓ Galeri CREATE page accessible")
            
        except Exception as e:
            self.save_test_result("Galeri CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/galeri", "admin_galeri", None, str(e))
            logger.error(f"✗ Galeri CRUD error: {e}")
    
    def test_faq_crud(self):
        """Test FAQ CRUD operations"""
        logger.info("Testing FAQ CRUD operations...")
        
        if not self.login_admin('admin_faq'):
            logger.error("Cannot test FAQ CRUD without login")
            return
        
        try:
            # Test CREATE
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/faq/create")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "pertanyaan"))
            )
            
            # Fill form
            self.driver.find_element(By.NAME, "pertanyaan").send_keys("Test Question?")
            self.driver.find_element(By.NAME, "jawaban").send_keys("Test answer for the question.")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check if redirected to index
            WebDriverWait(self.driver, 10).until(
                EC.url_contains("/admin/faq")
            )
            
            self.save_test_result("FAQ CREATE", "CRUD", "PASS", 
                                f"{self.base_url}/admin/faq/create", "admin_faq", response_time)
            logger.info("✓ FAQ CREATE successful")
            
            # Test READ
            start_time = time.time()
            self.driver.get(f"{self.base_url}/admin/faq")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.TAG_NAME, "table"))
            )
            
            response_time = time.time() - start_time
            
            self.save_test_result("FAQ READ", "CRUD", "PASS", 
                                f"{self.base_url}/admin/faq", "admin_faq", response_time)
            logger.info("✓ FAQ READ successful")
            
        except Exception as e:
            self.save_test_result("FAQ CRUD", "CRUD", "FAIL", 
                                f"{self.base_url}/admin/faq", "admin_faq", None, str(e))
            logger.error(f"✗ FAQ CRUD error: {e}")
    
    def test_form_submissions(self):
        """Test form submissions on public pages"""
        logger.info("Testing form submissions...")
        
        if not self.setup_selenium():
            logger.error("Cannot perform form tests without Selenium")
            return
        
        try:
            # Test WBS form submission
            start_time = time.time()
            self.driver.get(f"{self.base_url}/wbs")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "nama_pelapor"))
            )
            
            # Fill WBS form
            self.driver.find_element(By.NAME, "nama_pelapor").send_keys("Test Reporter")
            self.driver.find_element(By.NAME, "email").send_keys("test@example.com")
            self.driver.find_element(By.NAME, "subjek").send_keys("Test WBS Subject")
            self.driver.find_element(By.NAME, "pesan").send_keys("Test WBS message")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check for success message or redirect
            WebDriverWait(self.driver, 10).until(
                lambda driver: "berhasil" in driver.page_source.lower() or 
                               "success" in driver.page_source.lower() or
                               driver.current_url != f"{self.base_url}/wbs"
            )
            
            self.save_test_result("WBS Form Submission", "Form", "PASS", 
                                f"{self.base_url}/wbs", None, response_time)
            logger.info("✓ WBS form submission successful")
            
            # Test Contact form submission
            start_time = time.time()
            self.driver.get(f"{self.base_url}/kontak")
            
            WebDriverWait(self.driver, 10).until(
                EC.presence_of_element_located((By.NAME, "nama"))
            )
            
            # Fill contact form
            self.driver.find_element(By.NAME, "nama").send_keys("Test Contact")
            self.driver.find_element(By.NAME, "email").send_keys("test@example.com")
            self.driver.find_element(By.NAME, "subjek").send_keys("Test Contact Subject")
            self.driver.find_element(By.NAME, "pesan").send_keys("Test contact message")
            
            # Submit
            self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            
            response_time = time.time() - start_time
            
            # Check for success message or redirect
            WebDriverWait(self.driver, 10).until(
                lambda driver: "berhasil" in driver.page_source.lower() or 
                               "success" in driver.page_source.lower() or
                               driver.current_url != f"{self.base_url}/kontak"
            )
            
            self.save_test_result("Contact Form Submission", "Form", "PASS", 
                                f"{self.base_url}/kontak", None, response_time)
            logger.info("✓ Contact form submission successful")
            
        except Exception as e:
            self.save_test_result("Form Submission", "Form", "FAIL", 
                                None, None, None, str(e))
            logger.error(f"✗ Form submission error: {e}")
        
        finally:
            self.teardown_selenium()
    
    def test_security_vulnerabilities(self):
        """Test for common security vulnerabilities"""
        logger.info("Testing security vulnerabilities...")
        
        # Test SQL Injection
        sql_payloads = [
            "' OR '1'='1",
            "1' UNION SELECT * FROM users--",
            "'; DROP TABLE users;--"
        ]
        
        for payload in sql_payloads:
            try:
                start_time = time.time()
                response = self.session.get(f"{self.base_url}/berita", 
                                          params={'search': payload}, timeout=10)
                response_time = time.time() - start_time
                
                if response.status_code == 500:
                    self.save_test_result("SQL Injection Test", "Security", "VULNERABLE", 
                                        f"{self.base_url}/berita", None, response_time, 
                                        f"SQL injection possible with payload: {payload}")
                    logger.warning(f"⚠ Possible SQL injection vulnerability with payload: {payload}")
                else:
                    self.save_test_result("SQL Injection Test", "Security", "PASS", 
                                        f"{self.base_url}/berita", None, response_time)
                    logger.info(f"✓ SQL injection test passed for payload: {payload}")
            except Exception as e:
                logger.error(f"✗ SQL injection test error: {e}")
        
        # Test XSS
        xss_payloads = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "javascript:alert('XSS')"
        ]
        
        for payload in xss_payloads:
            try:
                start_time = time.time()
                response = self.session.get(f"{self.base_url}/berita", 
                                          params={'search': payload}, timeout=10)
                response_time = time.time() - start_time
                
                if payload in response.text:
                    self.save_test_result("XSS Test", "Security", "VULNERABLE", 
                                        f"{self.base_url}/berita", None, response_time, 
                                        f"XSS possible with payload: {payload}")
                    logger.warning(f"⚠ Possible XSS vulnerability with payload: {payload}")
                else:
                    self.save_test_result("XSS Test", "Security", "PASS", 
                                        f"{self.base_url}/berita", None, response_time)
                    logger.info(f"✓ XSS test passed for payload: {payload}")
            except Exception as e:
                logger.error(f"✗ XSS test error: {e}")
    
    def test_performance_load(self):
        """Test basic performance and load"""
        logger.info("Testing performance and load...")
        
        def make_request(url):
            try:
                start_time = time.time()
                response = requests.get(url, timeout=30)
                response_time = time.time() - start_time
                return {
                    'url': url,
                    'status_code': response.status_code,
                    'response_time': response_time
                }
            except Exception as e:
                return {
                    'url': url,
                    'status_code': 0,
                    'response_time': None,
                    'error': str(e)
                }
        
        # Test concurrent requests
        urls = [
            f"{self.base_url}/",
            f"{self.base_url}/berita",
            f"{self.base_url}/pelayanan",
            f"{self.base_url}/dokumen",
            f"{self.base_url}/galeri",
            f"{self.base_url}/faq",
            f"{self.base_url}/profil",
            f"{self.base_url}/kontak"
        ]
        
        # Test with 10 concurrent requests
        with ThreadPoolExecutor(max_workers=10) as executor:
            results = list(executor.map(make_request, urls * 5))
        
        # Analyze results
        successful_requests = [r for r in results if r['status_code'] == 200]
        failed_requests = [r for r in results if r['status_code'] != 200]
        
        if successful_requests:
            avg_response_time = sum(r['response_time'] for r in successful_requests) / len(successful_requests)
            max_response_time = max(r['response_time'] for r in successful_requests)
            min_response_time = min(r['response_time'] for r in successful_requests)
            
            self.save_test_result("Performance Load Test", "Performance", "PASS", 
                                self.base_url, None, avg_response_time, 
                                f"Avg: {avg_response_time:.2f}s, Max: {max_response_time:.2f}s, Min: {min_response_time:.2f}s")
            logger.info(f"✓ Performance test - Avg: {avg_response_time:.2f}s, Max: {max_response_time:.2f}s, Min: {min_response_time:.2f}s")
        
        if failed_requests:
            self.save_test_result("Performance Load Test", "Performance", "FAIL", 
                                self.base_url, None, None, 
                                f"Failed requests: {len(failed_requests)}/{len(results)}")
            logger.error(f"✗ Performance test - Failed requests: {len(failed_requests)}/{len(results)}")
    
    def generate_report(self):
        """Generate comprehensive test report"""
        logger.info("Generating test report...")
        
        # Get results from database
        conn = sqlite3.connect('test_results.db')
        df = pd.read_sql_query("SELECT * FROM test_results ORDER BY timestamp DESC", conn)
        conn.close()
        
        # Generate summary
        total_tests = len(df)
        passed_tests = len(df[df['status'] == 'PASS'])
        failed_tests = len(df[df['status'] == 'FAIL'])
        vulnerable_tests = len(df[df['status'] == 'VULNERABLE'])
        
        # Generate HTML report
        html_report = f"""
        <!DOCTYPE html>
        <html>
        <head>
            <title>Inspektorat Web Testing Report</title>
            <style>
                body {{ font-family: Arial, sans-serif; margin: 20px; }}
                .summary {{ background-color: #f0f0f0; padding: 20px; margin-bottom: 20px; }}
                .pass {{ color: green; }}
                .fail {{ color: red; }}
                .vulnerable {{ color: orange; }}
                table {{ border-collapse: collapse; width: 100%; }}
                th, td {{ border: 1px solid #ddd; padding: 8px; text-align: left; }}
                th {{ background-color: #f2f2f2; }}
                .chart {{ margin: 20px 0; }}
            </style>
        </head>
        <body>
            <h1>Inspektorat Web Application Testing Report</h1>
            <p>Generated on: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}</p>
            
            <div class="summary">
                <h2>Test Summary</h2>
                <p><strong>Total Tests:</strong> {total_tests}</p>
                <p><strong class="pass">Passed:</strong> {passed_tests}</p>
                <p><strong class="fail">Failed:</strong> {failed_tests}</p>
                <p><strong class="vulnerable">Vulnerable:</strong> {vulnerable_tests}</p>
                <p><strong>Success Rate:</strong> {(passed_tests/total_tests)*100:.1f}%</p>
            </div>
            
            <h2>Test Results by Category</h2>
            <table>
                <tr><th>Category</th><th>Total</th><th>Passed</th><th>Failed</th><th>Success Rate</th></tr>
        """
        
        # Add category summary
        for category in df['test_type'].unique():
            category_df = df[df['test_type'] == category]
            category_total = len(category_df)
            category_passed = len(category_df[category_df['status'] == 'PASS'])
            category_failed = len(category_df[category_df['status'] == 'FAIL'])
            category_rate = (category_passed / category_total) * 100 if category_total > 0 else 0
            
            html_report += f"""
                <tr>
                    <td>{category}</td>
                    <td>{category_total}</td>
                    <td class="pass">{category_passed}</td>
                    <td class="fail">{category_failed}</td>
                    <td>{category_rate:.1f}%</td>
                </tr>
            """
        
        html_report += """
            </table>
            
            <h2>Detailed Test Results</h2>
            <table>
                <tr>
                    <th>Test Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>URL</th>
                    <th>User Role</th>
                    <th>Response Time</th>
                    <th>Error Message</th>
                    <th>Timestamp</th>
                </tr>
        """
        
        # Add detailed results
        for _, row in df.iterrows():
            status_class = 'pass' if row['status'] == 'PASS' else 'fail' if row['status'] == 'FAIL' else 'vulnerable'
            response_time = f"{row['response_time']:.3f}s" if row['response_time'] else "N/A"
            
            html_report += f"""
                <tr>
                    <td>{row['test_name']}</td>
                    <td>{row['test_type']}</td>
                    <td class="{status_class}">{row['status']}</td>
                    <td>{row['url'] or 'N/A'}</td>
                    <td>{row['user_role'] or 'N/A'}</td>
                    <td>{response_time}</td>
                    <td>{row['error_message'] or 'N/A'}</td>
                    <td>{row['timestamp']}</td>
                </tr>
            """
        
        html_report += """
            </table>
            
            <h2>Recommendations</h2>
            <ul>
        """
        
        # Add recommendations based on results
        if failed_tests > 0:
            html_report += f"<li>Fix {failed_tests} failed tests to improve system reliability</li>"
        
        if vulnerable_tests > 0:
            html_report += f"<li>Address {vulnerable_tests} security vulnerabilities immediately</li>"
        
        # Performance recommendations
        perf_results = df[df['test_type'] == 'Performance']
        if not perf_results.empty:
            avg_response = perf_results['response_time'].mean()
            if avg_response > 2.0:
                html_report += f"<li>Optimize performance - average response time is {avg_response:.2f}s</li>"
        
        html_report += """
            </ul>
        </body>
        </html>
        """
        
        # Save HTML report
        with open('test_report.html', 'w') as f:
            f.write(html_report)
        
        # Save CSV report
        df.to_csv('test_results.csv', index=False)
        
        logger.info("Test report generated: test_report.html and test_results.csv")
        
        return df
    
    def run_all_tests(self):
        """Run all tests"""
        logger.info("Starting comprehensive testing...")
        
        # Test infrastructure
        if not self.test_server_availability():
            logger.error("Server not available, stopping tests")
            return
        
        # Test public pages
        self.test_public_pages()
        
        # Test authentication
        self.test_admin_authentication()
        
        # Test admin pages
        self.test_admin_pages()
        
        # Test API endpoints
        self.test_api_endpoints()
        
        # Test CRUD operations
        self.test_crud_operations()
        
        # Test form submissions
        self.test_form_submissions()
        
        # Test security
        self.test_security_vulnerabilities()
        
        # Test performance
        self.test_performance_load()
        
        # Generate report
        results_df = self.generate_report()
        
        # Print summary
        total_tests = len(results_df)
        passed_tests = len(results_df[results_df['status'] == 'PASS'])
        failed_tests = len(results_df[results_df['status'] == 'FAIL'])
        
        logger.info(f"""
        
        =====================================
        TESTING COMPLETE
        =====================================
        Total Tests: {total_tests}
        Passed: {passed_tests}
        Failed: {failed_tests}
        Success Rate: {(passed_tests/total_tests)*100:.1f}%
        
        Reports generated:
        - test_report.html
        - test_results.csv
        - test_results.db
        - test_results.log
        =====================================
        """)


def main():
    """Main function"""
    if len(sys.argv) > 1:
        base_url = sys.argv[1]
    else:
        base_url = "http://localhost:8000"
    
    print(f"Starting comprehensive testing for: {base_url}")
    
    tester = InspektoratTester(base_url)
    tester.run_all_tests()


if __name__ == "__main__":
    main()
