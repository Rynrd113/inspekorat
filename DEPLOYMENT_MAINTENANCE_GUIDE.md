# 🚀 Deployment & Maintenance Guide

**Portal Inspektorat Papua Tengah**  
**Production Deployment & Maintenance Documentation**

---

## 📋 Daftar Isi

1. [Server Requirements](#server-requirements)
2. [Production Setup](#production-setup)
3. [Deployment Process](#deployment-process)
4. [Security Configuration](#security-configuration)
5. [Performance Optimization](#performance-optimization)
6. [Monitoring & Logging](#monitoring--logging)
7. [Backup Strategy](#backup-strategy)
8. [Maintenance Tasks](#maintenance-tasks)
9. [Troubleshooting](#troubleshooting)
10. [SSL Certificate](#ssl-certificate)

---

## 🖥️ Server Requirements

### 1. Minimum System Requirements

```bash
# Operating System
Ubuntu 20.04 LTS or higher
CentOS 8 or higher
Debian 10 or higher

# Hardware
CPU: 2 cores minimum (4 cores recommended)
RAM: 4GB minimum (8GB recommended)
Storage: 50GB minimum (SSD recommended)
Network: 100Mbps minimum

# Software Stack
PHP 8.2+
Nginx 1.18+
MySQL 8.0+ or PostgreSQL 13+
Redis 6.0+
Node.js 18.0+
Composer 2.0+
Git 2.25+
```

### 2. PHP Extensions Required

```bash
# Install required PHP extensions
sudo apt update
sudo apt install -y php8.2-fpm php8.2-common php8.2-mysql php8.2-xml php8.2-xmlrpc php8.2-curl php8.2-gd php8.2-imagick php8.2-cli php8.2-dev php8.2-imap php8.2-mbstring php8.2-opcache php8.2-soap php8.2-zip php8.2-intl php8.2-bcmath php8.2-redis php8.2-sqlite3

# Verify installation
php -m | grep -E "(mysql|gd|curl|mbstring|xml|zip|opcache|redis)"
```

### 3. Database Setup

```sql
-- Create database and user
CREATE DATABASE portal_inspektorat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'inspektorat_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON portal_inspektorat.* TO 'inspektorat_user'@'localhost';
FLUSH PRIVILEGES;

-- Optimize MySQL for production
-- Add to /etc/mysql/mysql.conf.d/mysqld.cnf
[mysqld]
innodb_buffer_pool_size = 2G
innodb_log_file_size = 512M
max_connections = 200
query_cache_type = 1
query_cache_size = 256M
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

---

## 🏗️ Production Setup

### 1. Server Initial Setup

```bash
#!/bin/bash
# server-setup.sh

# Update system
sudo apt update && sudo apt upgrade -y

# Install essential packages
sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates

# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y

# Install Nginx
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx

# Install PHP 8.2
sudo apt install php8.2-fpm php8.2-common php8.2-mysql php8.2-xml php8.2-curl php8.2-gd php8.2-cli php8.2-mbstring php8.2-opcache php8.2-soap php8.2-zip php8.2-intl php8.2-bcmath php8.2-redis -y

# Install MySQL
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

# Secure MySQL installation
sudo mysql_secure_installation

# Install Redis
sudo apt install redis-server -y
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Create web directory
sudo mkdir -p /var/www/inspektorat
sudo chown -R www-data:www-data /var/www/inspektorat

echo "Server setup completed!"
```

### 2. Application Directory Structure

```bash
# Production directory structure
/var/www/inspektorat/
├── current/                    # Current active deployment
├── releases/                   # Previous releases (for rollback)
│   ├── 20250101_120000/
│   ├── 20250115_140000/
│   └── 20250130_160000/
├── shared/                     # Shared files between releases
│   ├── .env
│   ├── storage/
│   └── public/uploads/
├── scripts/                    # Deployment scripts
│   ├── deploy.sh
│   ├── rollback.sh
│   └── backup.sh
└── logs/                      # Deployment logs
```

### 3. Environment Configuration

```bash
# Create production .env file
sudo cp /var/www/inspektorat/current/.env.example /var/www/inspektorat/shared/.env

# Edit environment variables
sudo nano /var/www/inspektorat/shared/.env
```

```env
# Production .env configuration
APP_NAME="Portal Inspektorat Papua Tengah"
APP_ENV=production
APP_KEY=base64:your-generated-key-here
APP_DEBUG=false
APP_URL=https://inspektorat.papuatengah.go.id

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_inspektorat
DB_USERNAME=inspektorat_user
DB_PASSWORD=your_secure_database_password

# Cache & Session
BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=public
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@papuatengah.go.id
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@papuatengah.go.id"
MAIL_FROM_NAME="${APP_NAME}"

# Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
SANCTUM_STATEFUL_DOMAINS=inspektorat.papuatengah.go.id

# File Upload
UPLOAD_MAX_SIZE=10240
ALLOWED_FILE_TYPES=jpg,jpeg,png,pdf,doc,docx
```

---

## 🚀 Deployment Process

### 1. Automated Deployment Script

```bash
#!/bin/bash
# deploy.sh

set -e

# Configuration
PROJECT_ROOT="/var/www/inspektorat"
REPOSITORY="https://github.com/Rynrd113/inspekorat.git"
BRANCH="main"
PHP_VERSION="8.2"
DATE=$(date +%Y%m%d_%H%M%S)
RELEASE_DIR="$PROJECT_ROOT/releases/$DATE"
CURRENT_DIR="$PROJECT_ROOT/current"
SHARED_DIR="$PROJECT_ROOT/shared"

echo "🚀 Starting deployment..."

# Create release directory
mkdir -p $RELEASE_DIR

# Clone repository
echo "📥 Cloning repository..."
git clone --branch $BRANCH --depth 1 $REPOSITORY $RELEASE_DIR

# Remove .git directory
rm -rf $RELEASE_DIR/.git

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
cd $RELEASE_DIR
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node.js dependencies and build assets
echo "🏗️ Building frontend assets..."
npm ci --prefer-offline --no-audit
npm run build

# Create symbolic links to shared directories
echo "🔗 Creating symbolic links..."
ln -nfs $SHARED_DIR/.env $RELEASE_DIR/.env
ln -nfs $SHARED_DIR/storage $RELEASE_DIR/storage

# Create public uploads symlink
if [ ! -d "$SHARED_DIR/public/uploads" ]; then
    mkdir -p $SHARED_DIR/public/uploads
fi
ln -nfs $SHARED_DIR/public/uploads $RELEASE_DIR/public/uploads

# Set permissions
echo "🔒 Setting permissions..."
sudo chown -R www-data:www-data $RELEASE_DIR
sudo chmod -R 755 $RELEASE_DIR
sudo chmod -R 775 $RELEASE_DIR/bootstrap/cache

# Laravel optimizations
echo "⚡ Optimizing Laravel..."
php$PHP_VERSION artisan config:cache
php$PHP_VERSION artisan route:cache
php$PHP_VERSION artisan view:cache
php$PHP_VERSION artisan event:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php$PHP_VERSION artisan migrate --force

# Switch to new release
echo "🔄 Switching to new release..."
ln -nfs $RELEASE_DIR $CURRENT_DIR

# Reload PHP-FPM and Nginx
echo "🔄 Reloading services..."
sudo systemctl reload php$PHP_VERSION-fpm
sudo systemctl reload nginx

# Clean up old releases (keep last 5)
echo "🧹 Cleaning up old releases..."
cd $PROJECT_ROOT/releases && ls -t | tail -n +6 | xargs -r rm -rf

echo "✅ Deployment completed successfully!"
echo "📍 Release: $DATE"
echo "🌐 URL: https://inspektorat.papuatengah.go.id"
```

### 2. Rollback Script

```bash
#!/bin/bash
# rollback.sh

set -e

PROJECT_ROOT="/var/www/inspektorat"
CURRENT_DIR="$PROJECT_ROOT/current"
RELEASES_DIR="$PROJECT_ROOT/releases"

echo "🔄 Starting rollback..."

# Get previous release
PREVIOUS_RELEASE=$(cd $RELEASES_DIR && ls -t | head -n 2 | tail -n 1)

if [ -z "$PREVIOUS_RELEASE" ]; then
    echo "❌ No previous release found!"
    exit 1
fi

PREVIOUS_RELEASE_DIR="$RELEASES_DIR/$PREVIOUS_RELEASE"

echo "📍 Rolling back to: $PREVIOUS_RELEASE"

# Switch to previous release
ln -nfs $PREVIOUS_RELEASE_DIR $CURRENT_DIR

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Reload services
echo "🔄 Reloading services..."
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

echo "✅ Rollback completed successfully!"
echo "📍 Active release: $PREVIOUS_RELEASE"
```

### 3. Zero-Downtime Deployment with Nginx

```nginx
# /etc/nginx/sites-available/inspektorat.papuatengah.go.id
server {
    listen 80;
    listen [::]:80;
    server_name inspektorat.papuatengah.go.id www.inspektorat.papuatengah.go.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name inspektorat.papuatengah.go.id www.inspektorat.papuatengah.go.id;
    
    root /var/www/inspektorat/current/public;
    index index.php index.html index.htm;
    
    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/inspektorat.papuatengah.go.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/inspektorat.papuatengah.go.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    
    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: ws: wss: data: blob: 'unsafe-inline'; frame-ancestors 'self';" always;
    add_header Permissions-Policy "interest-cohort=()" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    
    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types
        application/atom+xml
        application/javascript
        application/json
        application/rss+xml
        application/vnd.ms-fontobject
        application/x-font-ttf
        application/x-web-app-manifest+json
        application/xhtml+xml
        application/xml
        font/opentype
        image/svg+xml
        image/x-icon
        text/css
        text/plain
        text/x-component;
    
    # Rate Limiting
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
    limit_req_zone $binary_remote_addr zone=api:10m rate=30r/m;
    
    # Main location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP Processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        
        # Increase timeout for long-running requests
        fastcgi_read_timeout 300;
    }
    
    # Rate limiting for login
    location ~ ^/(admin/login|api/auth) {
        limit_req zone=login burst=3 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Rate limiting for API
    location ~ ^/api/ {
        limit_req zone=api burst=50 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Static Assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, no-transform, immutable";
        access_log off;
        
        # CORS for fonts
        location ~* \.(woff|woff2|ttf|eot)$ {
            add_header Access-Control-Allow-Origin "*";
        }
    }
    
    # Security
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    location ~ ^/(\.env|\.git|composer\.|phpunit\.xml|\.htaccess) {
        deny all;
    }
    
    location ~ /storage/app {
        deny all;
    }
    
    # Health check endpoint
    location /health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }
    
    # Error pages
    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;
    
    location = /404.html {
        root /var/www/inspektorat/current/public;
        internal;
    }
    
    location = /50x.html {
        root /var/www/inspektorat/current/public;
        internal;
    }
}
```

---

## 🔒 Security Configuration

### 1. Firewall Setup (UFW)

```bash
# Enable UFW
sudo ufw enable

# Allow SSH (change port if custom)
sudo ufw allow 22/tcp

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Allow MySQL (only from localhost)
sudo ufw allow from 127.0.0.1 to any port 3306

# Check status
sudo ufw status verbose
```

### 2. Fail2Ban Configuration

```bash
# Install Fail2Ban
sudo apt install fail2ban -y

# Create jail configuration
sudo nano /etc/fail2ban/jail.local
```

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5
ignoreip = 127.0.0.1/8 ::1

[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log
maxretry = 3

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
port = http,https
logpath = /var/log/nginx/error.log

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
port = http,https
logpath = /var/log/nginx/error.log
maxretry = 10

[nginx-botsearch]
enabled = true
filter = nginx-botsearch
port = http,https
logpath = /var/log/nginx/access.log
maxretry = 2
```

### 3. Laravel Security Configuration

```php
// config/app.php - Production security settings
'debug' => env('APP_DEBUG', false),
'log_level' => env('LOG_LEVEL', 'warning'),

// config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => env('SESSION_HTTP_ONLY', true),
'same_site' => env('SESSION_SAME_SITE', 'strict'),

// config/cors.php
'supports_credentials' => false,
'allowed_origins' => ['https://inspektorat.papuatengah.go.id'],
'allowed_headers' => ['Content-Type', 'Authorization'],
```

### 4. File Permissions

```bash
# Set correct ownership
sudo chown -R www-data:www-data /var/www/inspektorat

# Set directory permissions
sudo find /var/www/inspektorat -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/inspektorat -type f -exec chmod 644 {} \;

# Special permissions for Laravel
sudo chmod -R 775 /var/www/inspektorat/shared/storage
sudo chmod -R 775 /var/www/inspektorat/current/bootstrap/cache
```

---

## ⚡ Performance Optimization

### 1. PHP-FPM Optimization

```ini
# /etc/php/8.2/fpm/pool.d/www.conf
[www]
user = www-data
group = www-data

listen = /var/run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data

pm = dynamic
pm.max_children = 20
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500

request_terminate_timeout = 300
request_slowlog_timeout = 10
slowlog = /var/log/php8.2-fpm-slow.log

php_admin_value[error_log] = /var/log/php8.2-fpm.log
php_admin_flag[log_errors] = on
```

```ini
# /etc/php/8.2/fpm/php.ini
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
post_max_size = 50M
upload_max_filesize = 50M
max_file_uploads = 20

opcache.enable = 1
opcache.enable_cli = 1
opcache.memory_consumption = 256
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 10000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

### 2. Redis Configuration

```bash
# /etc/redis/redis.conf
maxmemory 1gb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### 3. Laravel Optimization Commands

```bash
# Production optimization script
#!/bin/bash
# optimize.sh

cd /var/www/inspektorat/current

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize Composer autoloader
composer dump-autoload --optimize

# Clear and warm up OPcache
sudo systemctl reload php8.2-fpm

echo "Laravel optimization completed!"
```

---

## 📊 Monitoring & Logging

### 1. Log Rotation Configuration

```bash
# /etc/logrotate.d/laravel
/var/www/inspektorat/shared/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload php8.2-fpm
    endscript
}

# /etc/logrotate.d/nginx
/var/log/nginx/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        systemctl reload nginx
    endscript
}
```

### 2. System Monitoring Script

```bash
#!/bin/bash
# monitor.sh

# Configuration
ALERT_EMAIL="admin@papuatengah.go.id"
CPU_THRESHOLD=80
MEMORY_THRESHOLD=80
DISK_THRESHOLD=85

# Check CPU usage
CPU_USAGE=$(top -bn1 | grep "Cpu(s)" | sed "s/.*, *\([0-9.]*\)%* id.*/\1/" | awk '{print 100 - $1}')
if (( $(echo "$CPU_USAGE > $CPU_THRESHOLD" | bc -l) )); then
    echo "High CPU usage: $CPU_USAGE%" | mail -s "Server Alert: High CPU" $ALERT_EMAIL
fi

# Check Memory usage
MEMORY_USAGE=$(free | grep Mem | awk '{printf("%.2f", $3/$2 * 100.0)}')
if (( $(echo "$MEMORY_USAGE > $MEMORY_THRESHOLD" | bc -l) )); then
    echo "High Memory usage: $MEMORY_USAGE%" | mail -s "Server Alert: High Memory" $ALERT_EMAIL
fi

# Check Disk usage
DISK_USAGE=$(df / | grep / | awk '{print $5}' | sed 's/%//g')
if [ $DISK_USAGE -gt $DISK_THRESHOLD ]; then
    echo "High Disk usage: $DISK_USAGE%" | mail -s "Server Alert: High Disk Usage" $ALERT_EMAIL
fi

# Check if services are running
SERVICES=("nginx" "php8.2-fpm" "mysql" "redis-server")
for service in "${SERVICES[@]}"; do
    if ! systemctl is-active --quiet $service; then
        echo "Service $service is not running!" | mail -s "Server Alert: Service Down" $ALERT_EMAIL
        systemctl restart $service
    fi
done

# Check website availability
if ! curl -f -s http://localhost/health > /dev/null; then
    echo "Website is not responding!" | mail -s "Server Alert: Website Down" $ALERT_EMAIL
fi
```

### 3. Application Monitoring

```php
// Add to app/Http/Middleware/MonitoringMiddleware.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MonitoringMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        // Log slow requests
        if ($duration > 2.0) {
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration' => $duration,
                'memory' => memory_get_peak_usage(true),
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
            ]);
        }
        
        return $response;
    }
}
```

---

## 💾 Backup Strategy

### 1. Comprehensive Backup Script

```bash
#!/bin/bash
# full-backup.sh

set -e

# Configuration
BACKUP_ROOT="/var/backups/inspektorat"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30
DB_NAME="portal_inspektorat"
DB_USER="inspektorat_user"
DB_PASS="your_secure_password"
PROJECT_ROOT="/var/www/inspektorat"

# Create backup directory
mkdir -p $BACKUP_ROOT/$DATE

echo "🗄️ Starting full backup - $DATE"

# Database backup
echo "📀 Backing up database..."
mysqldump \
    --user=$DB_USER \
    --password=$DB_PASS \
    --single-transaction \
    --routines \
    --triggers \
    --events \
    --add-drop-database \
    --databases $DB_NAME > $BACKUP_ROOT/$DATE/database.sql

# Files backup
echo "📁 Backing up files..."
tar -czf $BACKUP_ROOT/$DATE/uploads.tar.gz -C $PROJECT_ROOT/shared/public uploads/
tar -czf $BACKUP_ROOT/$DATE/storage.tar.gz -C $PROJECT_ROOT/shared storage/

# Configuration backup
echo "⚙️ Backing up configuration..."
cp $PROJECT_ROOT/shared/.env $BACKUP_ROOT/$DATE/
tar -czf $BACKUP_ROOT/$DATE/nginx.tar.gz -C /etc/nginx sites-available/ sites-enabled/

# Create backup manifest
echo "📋 Creating backup manifest..."
cat > $BACKUP_ROOT/$DATE/manifest.txt << EOF
Backup Date: $DATE
Database: $DB_NAME
Project Root: $PROJECT_ROOT
Files Included:
- database.sql (Database dump)
- uploads.tar.gz (User uploads)
- storage.tar.gz (Application storage)
- .env (Environment configuration)
- nginx.tar.gz (Nginx configuration)

Backup Size: $(du -sh $BACKUP_ROOT/$DATE | cut -f1)
EOF

# Compress entire backup
echo "🗜️ Compressing backup..."
cd $BACKUP_ROOT
tar -czf $DATE.tar.gz $DATE/
rm -rf $DATE/

# Remove old backups
echo "🧹 Cleaning old backups..."
find $BACKUP_ROOT -name "*.tar.gz" -mtime +$RETENTION_DAYS -delete

# Upload to remote storage (optional)
# aws s3 cp $BACKUP_ROOT/$DATE.tar.gz s3://your-backup-bucket/inspektorat/

echo "✅ Backup completed: $BACKUP_ROOT/$DATE.tar.gz"
echo "📊 Backup size: $(du -sh $BACKUP_ROOT/$DATE.tar.gz | cut -f1)"
```

### 2. Automated Backup Schedule

```bash
# Add to crontab: sudo crontab -e
# Daily backup at 2 AM
0 2 * * * /var/www/inspektorat/scripts/full-backup.sh >> /var/log/backup.log 2>&1

# Weekly database backup at 3 AM on Sunday
0 3 * * 0 /var/www/inspektorat/scripts/db-backup.sh >> /var/log/backup.log 2>&1

# Monthly system backup at 4 AM on 1st day
0 4 1 * * /var/www/inspektorat/scripts/system-backup.sh >> /var/log/backup.log 2>&1
```

---

## 🔧 Maintenance Tasks

### 1. Daily Maintenance Script

```bash
#!/bin/bash
# daily-maintenance.sh

cd /var/www/inspektorat/current

echo "🧹 Starting daily maintenance..."

# Clear expired sessions
php artisan session:gc

# Clear old logs (keep last 30 days)
find storage/logs/ -name "*.log" -mtime +30 -delete

# Optimize database
mysql -u$DB_USER -p$DB_PASS -e "OPTIMIZE TABLE portal_papua_tengahs, wbs, users, sessions, cache, jobs;"

# Clear OPcache
php artisan opcache:clear

# Update search index (if using full-text search)
php artisan scout:flush "App\Models\PortalPapuaTengah"
php artisan scout:import "App\Models\PortalPapuaTengah"

# Check disk space
DISK_USAGE=$(df / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 85 ]; then
    echo "Warning: Disk usage is at $DISK_USAGE%" | mail -s "Disk Space Warning" admin@papuatengah.go.id
fi

echo "✅ Daily maintenance completed"
```

### 2. Weekly Maintenance Script

```bash
#!/bin/bash
# weekly-maintenance.sh

echo "📊 Starting weekly maintenance..."

# Update packages
sudo apt update && sudo apt list --upgradable

# Analyze MySQL tables
mysql -u$DB_USER -p$DB_PASS -e "ANALYZE TABLE portal_papua_tengahs, wbs, users;"

# Check for Laravel updates
cd /var/www/inspektorat/current
composer outdated "laravel/*"

# Generate performance report
cat > /tmp/performance_report.txt << EOF
Weekly Performance Report - $(date)

Disk Usage:
$(df -h /)

Memory Usage:
$(free -h)

Top Processes:
$(ps aux --sort=-%cpu | head -10)

MySQL Status:
$(mysql -u$DB_USER -p$DB_PASS -e "SHOW STATUS LIKE 'Slow_queries'")

Redis Info:
$(redis-cli info memory | grep used_memory_human)
EOF

# Send report
mail -s "Weekly Performance Report" admin@papuatengah.go.id < /tmp/performance_report.txt

echo "✅ Weekly maintenance completed"
```

### 3. Security Updates

```bash
#!/bin/bash
# security-updates.sh

echo "🔒 Checking for security updates..."

# Update package lists
sudo apt update

# Check for security updates
SECURITY_UPDATES=$(apt list --upgradable 2>/dev/null | grep -i security | wc -l)

if [ $SECURITY_UPDATES -gt 0 ]; then
    echo "Found $SECURITY_UPDATES security updates"
    
    # Apply security updates
    sudo apt upgrade -y
    
    # Restart services if needed
    sudo systemctl restart nginx
    sudo systemctl restart php8.2-fpm
    
    # Send notification
    echo "Security updates applied: $SECURITY_UPDATES updates" | mail -s "Security Updates Applied" admin@papuatengah.go.id
else
    echo "No security updates available"
fi
```

---

## 🔧 Troubleshooting

### 1. Common Issues and Solutions

```bash
# 1. High CPU Usage
# Check top processes
htop
# Check PHP-FPM processes
ps aux | grep php-fpm
# Restart PHP-FPM if needed
sudo systemctl restart php8.2-fpm

# 2. High Memory Usage
# Check memory usage
free -h
# Clear Laravel caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# 3. Database Issues
# Check MySQL processes
mysqladmin -u$DB_USER -p$DB_PASS processlist
# Optimize database
mysqlcheck -u$DB_USER -p$DB_PASS --optimize --all-databases

# 4. File Permission Issues
sudo chown -R www-data:www-data /var/www/inspektorat
sudo chmod -R 755 /var/www/inspektorat
sudo chmod -R 775 /var/www/inspektorat/shared/storage

# 5. Nginx Issues
# Test configuration
sudo nginx -t
# Check error logs
sudo tail -f /var/log/nginx/error.log
```

### 2. Log Analysis Tools

```bash
# Analyze Nginx access logs
awk '{print $1}' /var/log/nginx/access.log | sort | uniq -c | sort -nr | head -10

# Check for errors in Laravel logs
grep -i error /var/www/inspektorat/shared/storage/logs/laravel.log | tail -20

# Monitor real-time logs
tail -f /var/log/nginx/access.log /var/log/nginx/error.log /var/www/inspektorat/shared/storage/logs/laravel.log
```

---

## 🔐 SSL Certificate

### 1. Let's Encrypt SSL Setup

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Generate SSL certificate
sudo certbot --nginx -d inspektorat.papuatengah.go.id -d www.inspektorat.papuatengah.go.id

# Verify auto-renewal
sudo certbot renew --dry-run

# Add auto-renewal to crontab
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -
```

### 2. SSL Configuration Check

```bash
#!/bin/bash
# ssl-check.sh

DOMAIN="inspektorat.papuatengah.go.id"
EXPIRY_DAYS=30

# Check SSL certificate expiry
EXPIRY_DATE=$(openssl s_client -servername $DOMAIN -connect $DOMAIN:443 2>/dev/null | openssl x509 -noout -dates | grep 'notAfter' | cut -d= -f2)
EXPIRY_TIMESTAMP=$(date -d "$EXPIRY_DATE" +%s)
CURRENT_TIMESTAMP=$(date +%s)
DAYS_UNTIL_EXPIRY=$(( ($EXPIRY_TIMESTAMP - $CURRENT_TIMESTAMP) / 86400 ))

if [ $DAYS_UNTIL_EXPIRY -lt $EXPIRY_DAYS ]; then
    echo "SSL certificate for $DOMAIN expires in $DAYS_UNTIL_EXPIRY days!" | mail -s "SSL Certificate Expiry Warning" admin@papuatengah.go.id
fi

echo "SSL certificate for $DOMAIN expires in $DAYS_UNTIL_EXPIRY days"
```

---

**© 2025 Inspektorat Provinsi Papua Tengah**  
*Dokumentasi deployment dan maintenance untuk Portal Inspektorat Papua Tengah*
