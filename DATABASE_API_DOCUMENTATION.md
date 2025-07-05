# 🗄️ Database & API Documentation

**Portal Inspektorat Papua Tengah**  
**Database Schema & API Reference**

---

## 📋 Daftar Isi

1. [Database Schema](#database-schema)
2. [Model Relationships](#model-relationships)
3. [Migration Guide](#migration-guide)
4. [Seeder Documentation](#seeder-documentation)
5. [API Endpoints](#api-endpoints)
6. [Query Optimization](#query-optimization)
7. [Backup & Recovery](#backup--recovery)

---

## 🗄️ Database Schema

### 1. Users Table

```sql
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','super_admin') DEFAULT 'admin',
  `is_admin` tinyint(1) DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_is_admin_index` (`is_admin`)
);
```

### 2. Portal Papua Tengah Table (News/Articles)

```sql
CREATE TABLE `portal_papua_tengahs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` text,
  `featured_image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT 'news',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `views` bigint unsigned DEFAULT '0',
  `author_id` bigint unsigned NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `tags` json DEFAULT NULL,
  `reading_time` int DEFAULT NULL,
  `featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portal_papua_tengahs_slug_unique` (`slug`),
  KEY `portal_papua_tengahs_author_id_foreign` (`author_id`),
  KEY `portal_papua_tengahs_status_published_at_index` (`status`,`published_at`),
  KEY `portal_papua_tengahs_category_index` (`category`),
  KEY `portal_papua_tengahs_featured_index` (`featured`),
  FULLTEXT KEY `portal_papua_tengahs_title_content_fulltext` (`title`,`content`),
  CONSTRAINT `portal_papua_tengahs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

### 3. WBS (Whistleblower System) Table

```sql
CREATE TABLE `wbs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_number` varchar(50) NOT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `reporter_email` varchar(255) DEFAULT NULL,
  `reporter_phone` varchar(20) DEFAULT NULL,
  `reporter_address` text,
  `is_anonymous` tinyint(1) DEFAULT '0',
  `violation_type` enum('corruption','nepotism','abuse_of_power','misconduct','other') NOT NULL,
  `violation_category` varchar(100) DEFAULT NULL,
  `incident_date` date DEFAULT NULL,
  `incident_location` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `accused_person` varchar(255) DEFAULT NULL,
  `accused_position` varchar(255) DEFAULT NULL,
  `description` longtext NOT NULL,
  `evidence_files` json DEFAULT NULL,
  `status` enum('pending','reviewing','investigating','resolved','rejected') DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `assigned_to` bigint unsigned DEFAULT NULL,
  `investigation_notes` longtext,
  `resolution` longtext,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `follow_up_required` tinyint(1) DEFAULT '0',
  `confidentiality_level` enum('public','restricted','confidential','secret') DEFAULT 'confidential',
  `estimated_loss` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wbs_report_number_unique` (`report_number`),
  KEY `wbs_assigned_to_foreign` (`assigned_to`),
  KEY `wbs_status_index` (`status`),
  KEY `wbs_violation_type_index` (`violation_type`),
  KEY `wbs_priority_index` (`priority`),
  KEY `wbs_incident_date_index` (`incident_date`),
  KEY `wbs_created_at_index` (`created_at`),
  FULLTEXT KEY `wbs_description_fulltext` (`description`),
  CONSTRAINT `wbs_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
);
```

### 4. Info Kantor Table

```sql
CREATE TABLE `info_kantors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `office_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `office_hours` json DEFAULT NULL,
  `head_office` varchar(255) DEFAULT NULL,
  `head_position` varchar(255) DEFAULT NULL,
  `head_photo` varchar(255) DEFAULT NULL,
  `office_photo` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` longtext,
  `vision` text,
  `mission` text,
  `organizational_structure` varchar(255) DEFAULT NULL,
  `services` json DEFAULT NULL,
  `achievements` json DEFAULT NULL,
  `social_media` json DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `info_kantors_is_active_index` (`is_active`)
);
```

### 5. Web Portals Table (Additional Content)

```sql
CREATE TABLE `web_portals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `type` enum('page','post','announcement','regulation') DEFAULT 'page',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `author_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `menu_order` int DEFAULT '0',
  `template` varchar(100) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` text,
  `featured_image` varchar(255) DEFAULT NULL,
  `excerpt` text,
  `views` bigint unsigned DEFAULT '0',
  `allow_comments` tinyint(1) DEFAULT '1',
  `password_protected` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `web_portals_slug_unique` (`slug`),
  KEY `web_portals_author_id_foreign` (`author_id`),
  KEY `web_portals_parent_id_foreign` (`parent_id`),
  KEY `web_portals_type_index` (`type`),
  KEY `web_portals_status_published_at_index` (`status`,`published_at`),
  FULLTEXT KEY `web_portals_title_content_fulltext` (`title`,`content`),
  CONSTRAINT `web_portals_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `web_portals_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `web_portals` (`id`) ON DELETE CASCADE
);
```

### 6. File Uploads Table

```sql
CREATE TABLE `file_uploads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint unsigned NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_extension` varchar(10) NOT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `uploadable_type` varchar(255) DEFAULT NULL,
  `uploadable_id` bigint unsigned DEFAULT NULL,
  `file_category` enum('image','document','video','audio','other') DEFAULT 'other',
  `is_public` tinyint(1) DEFAULT '1',
  `download_count` bigint unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `file_uploads_uploaded_by_foreign` (`uploaded_by`),
  KEY `file_uploads_uploadable_type_uploadable_id_index` (`uploadable_type`,`uploadable_id`),
  KEY `file_uploads_file_category_index` (`file_category`),
  KEY `file_uploads_is_public_index` (`is_public`),
  CONSTRAINT `file_uploads_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
);
```

### 7. Activity Logs Table

```sql
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_log_name_index` (`log_name`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_causer_type_causer_id_index` (`causer_type`,`causer_id`),
  KEY `activity_logs_created_at_index` (`created_at`)
);
```

---

## 🔗 Model Relationships

### 1. User Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
        'avatar',
        'phone',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    // Relationships
    public function portalPapuaTengahs()
    {
        return $this->hasMany(PortalPapuaTengah::class, 'author_id');
    }

    public function webPortals()
    {
        return $this->hasMany(WebPortal::class, 'author_id');
    }

    public function assignedWbsReports()
    {
        return $this->hasMany(Wbs::class, 'assigned_to');
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'uploaded_by');
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // Mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
```

### 2. PortalPapuaTengah Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PortalPapuaTengah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'status',
        'published_at',
        'views',
        'author_id',
        'meta_title',
        'meta_description',
        'tags',
        'reading_time',
        'featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
        'tags' => 'array',
        'reading_time' => 'integer',
        'featured' => 'boolean'
    ];

    protected $dates = ['deleted_at'];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function files()
    {
        return $this->morphMany(FileUpload::class, 'uploadable');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    // Accessors
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }
        
        return asset('images/default-news.jpg');
    }

    public function getReadingTimeAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // Assuming 200 words per minute
    }

    public function getTagsListAttribute()
    {
        return $this->tags ? implode(', ', $this->tags) : '';
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setTagsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['tags'] = json_encode(array_map('trim', explode(',', $value)));
        } else {
            $this->attributes['tags'] = json_encode($value);
        }
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }
}
```

### 3. WBS Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wbs extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wbs';

    protected $fillable = [
        'report_number',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'reporter_address',
        'is_anonymous',
        'violation_type',
        'violation_category',
        'incident_date',
        'incident_location',
        'department',
        'accused_person',
        'accused_position',
        'description',
        'evidence_files',
        'status',
        'priority',
        'assigned_to',
        'investigation_notes',
        'resolution',
        'resolved_at',
        'follow_up_required',
        'confidentiality_level',
        'estimated_loss'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'resolved_at' => 'datetime',
        'is_anonymous' => 'boolean',
        'follow_up_required' => 'boolean',
        'evidence_files' => 'array',
        'estimated_loss' => 'decimal:2'
    ];

    protected $dates = ['deleted_at'];

    // Relationships
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->morphMany(FileUpload::class, 'uploadable');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Accessors
    public function getReporterDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Anonymous' : $this->reporter_name;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'reviewing' => 'Sedang Ditinjau',
            'investigating' => 'Sedang Diselidiki',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak'
        ];

        return $labels[$this->priority] ?? $this->priority;
    }

    public function getViolationTypeLabelAttribute()
    {
        $labels = [
            'corruption' => 'Korupsi',
            'nepotism' => 'Nepotisme',
            'abuse_of_power' => 'Penyalahgunaan Kekuasaan',
            'misconduct' => 'Pelanggaran Kode Etik',
            'other' => 'Lainnya'
        ];

        return $labels[$this->violation_type] ?? $this->violation_type;
    }

    // Mutators
    public function setReportNumberAttribute($value)
    {
        if (!$value) {
            $this->attributes['report_number'] = $this->generateReportNumber();
        } else {
            $this->attributes['report_number'] = $value;
        }
    }

    // Methods
    public function generateReportNumber()
    {
        $year = date('Y');
        $month = date('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return "WBS-{$year}{$month}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function assignTo($userId)
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => 'reviewing'
        ]);
    }

    public function resolve($resolution)
    {
        $this->update([
            'status' => 'resolved',
            'resolution' => $resolution,
            'resolved_at' => now()
        ]);
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }
}
```

---

## 🗂️ Migration Guide

### 1. Creating New Migration

```bash
# Create migration
php artisan make:migration create_new_table_name --create=table_name

# Create migration for existing table
php artisan make:migration add_column_to_table_name --table=table_name

# Create model with migration
php artisan make:model ModelName -m

# Create complete resource (Model, Migration, Controller, Factory, Seeder)
php artisan make:model ModelName -a
```

### 2. Migration Best Practices

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('example_table', function (Blueprint $table) {
            $table->id();
            
            // String columns
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            
            // Text columns
            $table->text('description')->nullable();
            $table->longText('content');
            
            // Numeric columns
            $table->integer('views')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->bigInteger('file_size')->nullable();
            
            // Boolean columns
            $table->boolean('is_active')->default(true);
            $table->boolean('featured')->default(false);
            
            // Date/Time columns
            $table->timestamp('published_at')->nullable();
            $table->date('birth_date')->nullable();
            
            // Enum columns
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            
            // JSON columns
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            
            // Foreign keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Standard timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index(['status', 'published_at']);
            $table->fullText(['title', 'content']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('example_table');
    }
};
```

### 3. Data Migration Example

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Migrate data from old table structure to new
        DB::table('old_articles')->orderBy('id')->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                DB::table('portal_papua_tengahs')->insert([
                    'title' => $article->title,
                    'slug' => Str::slug($article->title),
                    'content' => $article->body,
                    'excerpt' => Str::limit(strip_tags($article->body), 150),
                    'status' => $article->published ? 'published' : 'draft',
                    'published_at' => $article->published_date,
                    'author_id' => $article->user_id,
                    'created_at' => $article->created_at,
                    'updated_at' => $article->updated_at,
                ]);
            }
        });
    }

    public function down()
    {
        // Reverse migration if needed
        DB::table('portal_papua_tengahs')->truncate();
    }
};
```

---

## 🌱 Seeder Documentation

### 1. User Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@papuatengah.go.id',
            'password' => 'password',
            'role' => 'super_admin',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Regular Admin
        User::create([
            'name' => 'Admin Inspektorat',
            'email' => 'admin.inspektorat@papuatengah.go.id',
            'password' => 'password',
            'role' => 'admin',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create additional users using factory
        User::factory(10)->create();
    }
}
```

### 2. PortalPapuaTengah Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\PortalPapuaTengah;
use App\Models\User;
use Illuminate\Database\Seeder;

class PortalPapuaTengahSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('email', 'admin@papuatengah.go.id')->first();
        
        $articles = [
            [
                'title' => 'Inspektorat Papua Tengah Luncurkan Portal Informasi Publik',
                'content' => $this->getSampleContent('portal-launch'),
                'category' => 'pengumuman',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'featured' => true,
                'tags' => ['portal', 'informasi publik', 'transparansi'],
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Sosialisasi Whistleblower System (WBS) untuk Masyarakat',
                'content' => $this->getSampleContent('wbs-socialization'),
                'category' => 'kegiatan',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'featured' => false,
                'tags' => ['WBS', 'sosialisasi', 'masyarakat'],
                'author_id' => $admin->id,
            ],
            [
                'title' => 'Laporan Kinerja Inspektorat Triwulan I 2025',
                'content' => $this->getSampleContent('quarterly-report'),
                'category' => 'laporan',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'featured' => false,
                'tags' => ['laporan', 'kinerja', 'triwulan'],
                'author_id' => $admin->id,
            ],
        ];
        
        foreach ($articles as $article) {
            PortalPapuaTengah::create($article);
        }

        // Create additional articles using factory
        PortalPapuaTengah::factory(20)->create([
            'author_id' => $admin->id,
            'status' => 'published',
            'published_at' => now()->subDays(rand(1, 30))
        ]);
    }

    private function getSampleContent($type)
    {
        $contents = [
            'portal-launch' => '<p>Inspektorat Provinsi Papua Tengah dengan bangga mengumumkan peluncuran portal informasi publik yang telah dirancang khusus untuk meningkatkan transparansi dan akuntabilitas pemerintahan.</p><p>Portal ini menyediakan berbagai fitur unggulan termasuk sistem pelaporan Whistleblower System (WBS) yang memungkinkan masyarakat untuk melaporkan dugaan pelanggaran dengan aman dan terpercaya.</p><p>Melalui portal ini, masyarakat dapat mengakses:</p><ul><li>Informasi terkini tentang kegiatan Inspektorat</li><li>Berita dan pengumuman resmi</li><li>Sistem pelaporan WBS yang aman</li><li>Kontak dan informasi layanan</li></ul>',
            
            'wbs-socialization' => '<p>Dalam rangka meningkatkan pemahaman masyarakat tentang Whistleblower System (WBS), Inspektorat Provinsi Papua Tengah mengadakan kegiatan sosialisasi kepada berbagai lapisan masyarakat.</p><p>Kegiatan ini bertujuan untuk:</p><ul><li>Memberikan pemahaman tentang WBS dan manfaatnya</li><li>Menjelaskan cara melaporkan dugaan pelanggaran</li><li>Menjamin kerahasiaan identitas pelapor</li><li>Meningkatkan partisipasi masyarakat dalam pengawasan</li></ul><p>Masyarakat diharapkan dapat berpartisipasi aktif dalam menciptakan pemerintahan yang bersih dan transparan.</p>',
            
            'quarterly-report' => '<p>Inspektorat Provinsi Papua Tengah menyampaikan laporan kinerja triwulan pertama tahun 2025 yang menunjukkan pencapaian positif dalam berbagai bidang pengawasan.</p><p>Highlights kinerja triwulan I:</p><ul><li>150 kegiatan pengawasan telah dilaksanakan</li><li>95% tingkat penyelesaian temuan audit</li><li>25 laporan WBS telah ditindaklanjuti</li><li>100% kerahasiaan pelapor terjaga</li></ul><p>Pencapaian ini menunjukkan komitmen Inspektorat dalam melaksanakan fungsi pengawasan yang efektif dan efisien.</p>'
        ];
        
        return $contents[$type] ?? '<p>Konten artikel sample.</p>';
    }
}
```

### 3. Info Kantor Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\InfoKantor;
use Illuminate\Database\Seeder;

class InfoKantorSeeder extends Seeder
{
    public function run()
    {
        InfoKantor::create([
            'office_name' => 'Inspektorat Provinsi Papua Tengah',
            'address' => 'Jl. Trikora No. 1, Nabire, Papua Tengah 98816',
            'postal_code' => '98816',
            'phone' => '(0984) 21234',
            'fax' => '(0984) 21235',
            'email' => 'inspektorat@papuatengah.go.id',
            'website' => 'https://inspektorat.papuatengah.go.id',
            'office_hours' => [
                'senin' => '08:00 - 16:00',
                'selasa' => '08:00 - 16:00',
                'rabu' => '08:00 - 16:00',
                'kamis' => '08:00 - 16:00',
                'jumat' => '08:00 - 16:00',
                'sabtu' => 'Tutup',
                'minggu' => 'Tutup'
            ],
            'head_office' => 'Dr. John Doe, M.Si',
            'head_position' => 'Inspektur Provinsi Papua Tengah',
            'latitude' => -3.3667,
            'longitude' => 135.4833,
            'description' => 'Inspektorat Provinsi Papua Tengah merupakan unsur pengawas penyelenggaraan pemerintahan yang bertugas menyelenggarakan pengawasan internal di lingkungan Pemerintah Provinsi Papua Tengah.',
            'vision' => 'Terwujudnya pengawasan yang profesional dan berintegritas untuk mendukung tata kelola pemerintahan yang baik di Papua Tengah.',
            'mission' => 'Melaksanakan pengawasan internal yang efektif dan efisien untuk mendukung pencapaian tujuan organisasi dan peningkatan kinerja pemerintahan.',
            'services' => [
                'Audit Kinerja',
                'Audit Keuangan', 
                'Audit Kepatuhan',
                'Reviu Laporan Keuangan',
                'Evaluasi Program',
                'Investigasi',
                'Whistleblower System'
            ],
            'achievements' => [
                [
                    'year' => 2024,
                    'title' => 'Penghargaan Inspektorat Terbaik Wilayah Papua',
                    'description' => 'Meraih penghargaan sebagai Inspektorat terbaik di wilayah Papua'
                ],
                [
                    'year' => 2023,
                    'title' => 'Implementasi WBS Terbaik',
                    'description' => 'Berhasil mengimplementasikan sistem WBS dengan efektif'
                ]
            ],
            'social_media' => [
                'facebook' => 'https://facebook.com/inspektorat.papuatengah',
                'instagram' => 'https://instagram.com/inspektorat_papuatengah',
                'twitter' => 'https://twitter.com/inspektorat_pt',
                'youtube' => 'https://youtube.com/inspektoratpapuatengah'
            ],
            'is_active' => true
        ]);
    }
}
```

---

## 🚀 API Endpoints

### 1. Public API Routes

```php
// routes/api.php
<?php

use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\WbsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public endpoints
    Route::get('/', [PublicController::class, 'index']);
    Route::get('/info', [PublicController::class, 'info']);
    Route::get('/stats', [PublicController::class, 'stats']);
    
    // News endpoints
    Route::prefix('news')->group(function () {
        Route::get('/', [NewsController::class, 'index']);
        Route::get('/featured', [NewsController::class, 'featured']);
        Route::get('/categories', [NewsController::class, 'categories']);
        Route::get('/search', [NewsController::class, 'search']);
        Route::get('/{id}', [NewsController::class, 'show']);
    });
    
    // WBS endpoints
    Route::prefix('wbs')->group(function () {
        Route::post('/report', [WbsController::class, 'store']);
        Route::get('/types', [WbsController::class, 'violationTypes']);
        Route::get('/status/{reportNumber}', [WbsController::class, 'checkStatus']);
    });
});
```

### 2. API Controllers

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PortalPapuaTengah;
use App\Http\Resources\NewsResource;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $category = $request->get('category');
        
        $query = PortalPapuaTengah::published()
                                 ->with('author')
                                 ->orderBy('published_at', 'desc');
        
        if ($category) {
            $query->byCategory($category);
        }
        
        $news = $query->paginate($perPage);
        
        return NewsResource::collection($news);
    }
    
    public function show($id)
    {
        $news = PortalPapuaTengah::published()
                                ->with('author', 'files')
                                ->findOrFail($id);
        
        // Increment views
        $news->incrementViews();
        
        return new NewsResource($news);
    }
    
    public function featured()
    {
        $news = PortalPapuaTengah::published()
                                ->featured()
                                ->with('author')
                                ->orderBy('published_at', 'desc')
                                ->limit(5)
                                ->get();
        
        return NewsResource::collection($news);
    }
    
    public function categories()
    {
        $categories = PortalPapuaTengah::published()
                                     ->select('category')
                                     ->distinct()
                                     ->orderBy('category')
                                     ->pluck('category');
        
        return response()->json(['data' => $categories]);
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q');
        $perPage = $request->get('per_page', 15);
        
        if (!$query) {
            return response()->json(['message' => 'Query parameter required'], 400);
        }
        
        $news = PortalPapuaTengah::published()
                                ->with('author')
                                ->where(function ($q) use ($query) {
                                    $q->where('title', 'LIKE', "%{$query}%")
                                      ->orWhere('content', 'LIKE', "%{$query}%")
                                      ->orWhere('excerpt', 'LIKE', "%{$query}%");
                                })
                                ->orderBy('published_at', 'desc')
                                ->paginate($perPage);
        
        return NewsResource::collection($news);
    }
}
```

### 3. API Resources

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->when($request->routeIs('api.news.show'), $this->content),
            'featured_image' => $this->featured_image_url,
            'category' => $this->category,
            'status' => $this->status,
            'published_at' => $this->published_at?->toISOString(),
            'views' => $this->views,
            'reading_time' => $this->reading_time,
            'featured' => $this->featured,
            'tags' => $this->tags,
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
            ],
            'author' => new UserResource($this->whenLoaded('author')),
            'files' => FileUploadResource::collection($this->whenLoaded('files')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
```

### 4. WBS API Controller

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wbs;
use App\Http\Requests\StoreWbsRequest;
use Illuminate\Http\Request;

class WbsController extends Controller
{
    public function store(StoreWbsRequest $request)
    {
        $wbs = Wbs::create($request->validated());
        
        // Send notification to admin
        // event(new WbsReportSubmitted($wbs));
        
        return response()->json([
            'message' => 'Laporan berhasil dikirim',
            'data' => [
                'report_number' => $wbs->report_number,
                'status' => $wbs->status
            ]
        ], 201);
    }
    
    public function checkStatus($reportNumber)
    {
        $wbs = Wbs::where('report_number', $reportNumber)->first();
        
        if (!$wbs) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }
        
        return response()->json([
            'data' => [
                'report_number' => $wbs->report_number,
                'status' => $wbs->status,
                'status_label' => $wbs->status_label,
                'submitted_at' => $wbs->created_at->toISOString(),
                'last_updated' => $wbs->updated_at->toISOString(),
            ]
        ]);
    }
    
    public function violationTypes()
    {
        $types = [
            'corruption' => 'Korupsi',
            'nepotism' => 'Nepotisme', 
            'abuse_of_power' => 'Penyalahgunaan Kekuasaan',
            'misconduct' => 'Pelanggaran Kode Etik',
            'other' => 'Lainnya'
        ];
        
        return response()->json(['data' => $types]);
    }
}
```

---

## ⚡ Query Optimization

### 1. Eager Loading

```php
// Bad - N+1 Problem
$articles = PortalPapuaTengah::all();
foreach ($articles as $article) {
    echo $article->author->name; // This will execute N queries
}

// Good - Eager Loading
$articles = PortalPapuaTengah::with('author')->get();
foreach ($articles as $article) {
    echo $article->author->name; // Single query
}

// Advanced Eager Loading
$articles = PortalPapuaTengah::with([
    'author:id,name,email',
    'files' => function ($query) {
        $query->where('file_category', 'image');
    }
])->get();
```

### 2. Database Indexes

```php
// Migration with proper indexes
Schema::create('portal_papua_tengahs', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->longText('content');
    $table->string('category', 50);
    $table->enum('status', ['draft', 'published', 'archived']);
    $table->timestamp('published_at')->nullable();
    $table->foreignId('author_id')->constrained();
    $table->timestamps();
    
    // Composite indexes for common queries
    $table->index(['status', 'published_at']);
    $table->index(['category', 'status']);
    $table->index(['author_id', 'status']);
    
    // Full-text search
    $table->fullText(['title', 'content']);
});
```

### 3. Query Scopes for Reusability

```php
// Model scopes
class PortalPapuaTengah extends Model
{
    public function scopePublishedWithAuthor($query)
    {
        return $query->published()->with('author:id,name,email');
    }
    
    public function scopeLatestWithStats($query, $limit = 10)
    {
        return $query->select(['id', 'title', 'slug', 'published_at', 'views'])
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->limit($limit);
    }
}

// Usage
$articles = PortalPapuaTengah::publishedWithAuthor()->get();
$latest = PortalPapuaTengah::latestWithStats(5)->get();
```

### 4. Database Queries Monitoring

```php
// Enable query logging (development only)
DB::enableQueryLog();

// Your queries here
$articles = PortalPapuaTengah::with('author')->get();

// Get executed queries
$queries = DB::getQueryLog();
dd($queries);

// Using Laravel Telescope for query monitoring
// Install: composer require laravel/telescope --dev
// Monitor slow queries and N+1 problems
```

---

## 💾 Backup & Recovery

### 1. Database Backup Script

```bash
#!/bin/bash
# backup-database.sh

# Configuration
DB_HOST="localhost"
DB_PORT="3306"
DB_NAME="portal_inspektorat"
DB_USER="your_username"
DB_PASS="your_password"
BACKUP_DIR="/var/backups/database"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Create backup directory
mkdir -p $BACKUP_DIR

# Create backup
mysqldump \
  --host=$DB_HOST \
  --port=$DB_PORT \
  --user=$DB_USER \
  --password=$DB_PASS \
  --single-transaction \
  --routines \
  --triggers \
  --events \
  --add-drop-database \
  --databases $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Remove old backups
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +$RETENTION_DAYS -delete

echo "Database backup completed: $BACKUP_DIR/db_backup_$DATE.sql.gz"
```

### 2. Laravel Artisan Commands for Backup

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {--compress}';
    protected $description = 'Create database backup';

    public function handle()
    {
        $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        // Ensure backup directory exists
        Storage::makeDirectory('backups');
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.port'),
            config('database.connections.mysql.database'),
            $path
        );
        
        $this->info('Creating database backup...');
        
        $result = shell_exec($command);
        
        if ($this->option('compress')) {
            shell_exec("gzip {$path}");
            $filename .= '.gz';
            $this->info('Backup compressed.');
        }
        
        $this->info("Database backup created: {$filename}");
        
        return 0;
    }
}
```

### 3. Automated Backup Schedule

```php
// app/Console/Kernel.php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Daily database backup at 2 AM
        $schedule->command('db:backup --compress')
                 ->dailyAt('02:00')
                 ->emailOutputOnFailure('admin@papuatengah.go.id');
        
        // Weekly full backup (including files)
        $schedule->exec('bash /path/to/full-backup.sh')
                 ->weekly()
                 ->sundays()
                 ->at('03:00');
        
        // Monthly cleanup of old backups
        $schedule->exec('find /var/backups -type f -mtime +90 -delete')
                 ->monthly();
    }
}
```

---

Dokumentasi ini memberikan panduan lengkap untuk mengelola database dan API pada Portal Inspektorat Papua Tengah. Developer dapat menggunakan dokumentasi ini sebagai referensi untuk pengembangan dan maintenance aplikasi.
