# ARCHITECTURE.md — Portal Inspektorat Papua Tengah

> Panduan konteks AI: arsitektur, peta modul, alur data, entry point, dan blast radius proyek ini.

---

## 1. Ringkasan Proyek

| Aspek | Detail |
|---|---|
| **Tujuan** | Portal publik + CMS admin untuk Inspektorat Provinsi Papua Tengah |
| **Bahasa** | PHP 8.2+ (backend), JavaScript ES Modules (frontend) |
| **Framework** | Laravel 12.x |
| **Frontend** | Bootstrap 5.3 + Tailwind CSS 3.4 + Vite 6.2 |
| **Database** | MySQL (production), SQLite (testing) |
| **Auth** | Laravel Sanctum (API) + Session (web) + RBAC custom |
| **Deployment** | Hostinger (`inspektorat.papuatengahprov.cloud`) |
| **Local Dev** | Laravel Herd |

---

## 2. Arsitektur & Pattern

### 2.1 Pola Desain: Clean Architecture (Layered)

Proyek ini mengadopsi **Clean Architecture** dengan pemisahan yang ketat antara lapisan:

```
┌─────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                     │
│  Routes → Controllers → Requests → Views (Blade)         │
│  API Controllers → Resources → ApiResponse                │
├─────────────────────────────────────────────────────────┤
│                    APPLICATION LAYER                      │
│  Services (Contracts → Implementation)                    │
│  Actions (Single-Responsibility Command Classes)          │
│  Form Requests (Validation)                               │
│  Jobs (Queued Notifications)                              │
├─────────────────────────────────────────────────────────┤
│                    DOMAIN LAYER                           │
│  Eloquent Models (20 models)                              │
│  Scopes, Traits (HasAuditLog, HasSearch, HasPagination)  │
│  Events & Listeners                                       │
│  Observers                                                │
├─────────────────────────────────────────────────────────┤
│                    INFRASTRUCTURE LAYER                   │
│  Repositories (Contracts → Implementation)                │
│  Service Providers (DI binding)                           │
│  Middleware (14 custom)                                   │
│  Config, Database Migrations, Seeders                     │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Pattern yang Digunakan

| Pattern | Implementasi | Lokasi |
|---|---|---|
| **Repository Pattern** | Interface + Implementation, di-bind via `RepositoryServiceProvider` | `app/Repositories/` |
| **Service Layer** | Interface + Implementation, di-bind via `RepositoryServiceProvider` | `app/Services/` |
| **Action Classes** | Single-responsibility command objects untuk create/update | `app/Actions/` |
| **Observer Pattern** | Model observers untuk audit logging & cache clearing | `app/Observers/` |
| **Event-Driven** | Events + Listeners untuk pelayanan & pengaduan | `app/Events/`, `app/Listeners/` |
| **Form Request** | 14 request validation classes | `app/Http/Requests/` |
| **API Resource** | 8 resource transformers untuk JSON output | `app/Http/Resources/` |
| **Middleware Pipeline** | 14 custom middleware (role, security, performance) | `app/Http/Middleware/` |
| **Trait Composition** | Reusable behaviors (AuditLog, Search, Pagination, FileUpload) | `app/Traits/` |

### 2.3 Struktur Folder Utama

```
app/
├── Actions/           # Command classes (Create/Update per domain)
│   ├── Pelayanan/     # CreatePelayananAction, UpdatePelayananAction
│   ├── PortalOpd/     # CreatePortalOpdAction, UpdatePortalOpdAction
│   ├── PortalPapuaTengah/
│   ├── User/          # CRUD + Search + Stats
│   └── Wbs/           # CreateWbsAction, UpdateWbsAction
├── Console/Commands/  # DebugPortalOpdData, PerformanceCleanup/Report, SyncPortalOpdData
├── Events/            # PelayananCreated/Updated/Deleted, PengaduanCreated
├── Http/
│   ├── Controllers/
│   │   ├── PublicController.php       # Semua halaman publik (1 controller)
│   │   ├── PortalOpdController.php    # Portal OPD publik
│   │   ├── SitemapController.php
│   │   ├── Admin/                     # 21 admin controllers
│   │   └── Api/                       # 8 REST API controllers
│   ├── Middleware/     # 14 custom middleware
│   ├── Requests/      # 14 form request validation
│   └── Resources/     # 8 API resource transformers
├── Jobs/              # ProcessPelayananNotification, SendPelayananNotification
├── Listeners/         # Log/Notify for Pelayanan & Pengaduan events
├── Models/            # 20 Eloquent models
├── Observers/         # HeroSlider, Pelayanan, Pengaduan observers
├── Providers/         # 6 service providers
├── Repositories/      # 5 contracts + 5 implementations
├── Services/          # 5 contracts + 5 implementations + 2 utility services
├── Traits/            # 7 reusable traits
└── View/Components/   # 6 Blade components
```

---

## 3. Data Flow

### 3.1 Request Lifecycle (Web — Admin CRUD)

```
HTTP Request
    │
    ▼
public/index.php (Front Controller)
    │
    ▼
bootstrap/app.php (Middleware stack registration)
    │
    ▼
routes/web.php (Route matching)
    │
    ▼
[Middleware Pipeline]
    ├── SecurityHeadersMiddleware (global)
    ├── AssetOptimizationMiddleware (global)
    ├── DatabaseQueryOptimization (web group)
    ├── auth (admin routes)
    └── role:super_admin|admin|content_admin (RoleMiddleware)
    │
    ▼
Admin Controller (e.g. PelayananController)
    │  - Receives FormRequest (validated data)
    ▼
Service Layer (e.g. PelayananService via PelayananServiceInterface)
    │  - Business logic, file upload handling, DB::transaction
    ├── Dispatches Events (PelayananCreated/Updated/Deleted)
    ▼
Repository Layer (e.g. PelayananRepository via PelayananRepositoryInterface)
    │  - Query building, filtering, pagination
    │  - Cache management (Cache::remember / Cache::forget)
    ▼
Eloquent Model (e.g. Pelayanan)
    │  - Observer fires (PelayananObserver)
    │  - HasAuditLog trait auto-logs to AuditLog
    ▼
MySQL Database
    │
    ▼
Response → Blade View → HTML
```

### 3.2 Request Lifecycle (Public Pages)

```
HTTP Request (e.g. GET /berita)
    │
    ▼
routes/web.php → PublicController@berita
    │
    ▼
[Middleware: AdminLogoutOnPublic — auto-logout admin di halaman publik]
    │
    ▼
PublicController
    │  - trackVisitor() → system_configurations.total_visitors
    │  - Cache::remember() for query caching (600s default)
    │  - Eloquent query (PortalPapuaTengah::published())
    ▼
Blade View (resources/views/public/*.blade.php)
    │  - Compiled by Vite (Tailwind + Bootstrap)
    ▼
HTML Response
```

### 3.3 Request Lifecycle (API — Sanctum Auth)

```
HTTP Request (e.g. POST /api/v1/wbs)
    │
    ▼
routes/api.php → API Controller
    │
    ▼
[Middleware Pipeline]
    ├── Sanctum::authenticate (API token)
    ├── api.format (ApiResponse formatting)
    ├── api.errors (error handling)
    └── api.rate.limit (120/min)
    │
    ▼
Api Controller → Service → Repository → Model → DB
    │
    ▼
ApiResponse (JSON) → ApiResource transformation
```

### 3.4 Event-Driven Flow (Pelayanan & Pengaduan)

```
Pelayanan CRUD
    │
    ▼
PelayananObserver (created/updated/deleted)
    ├── AuditLog::log() — writes to audit_logs table
    └── Cache::forget() — clears related cache keys

Pengaduan Submission
    │
    ▼
PengaduanObserver → dispatches PengaduanCreated event
    │
    ├── LogPengaduanActivity (Listener)
    │   └── AuditLog::create()
    │
    └── NotifyPengaduanCreated (Listener)
        └── ProcessPelayananNotification (Job, queued)
            ├── logAuditActivity()
            ├── sendNotificationToAdmins() → Mail::queue()
            ├── updateCache()
            └── generateReports() → Cache::put()
```

### 3.5 File Upload Flow

```
Form Request (file input)
    │
    ▼
FormRequest validation (HasFileUpload trait rules)
    │
    ▼
Controller/Service/Action
    │  - $file->store('path', 'public')
    │  - Storage::disk('public')->delete(old_file)
    ▼
storage/app/public/{path} → symlinked to public/storage
```

---

## 4. Core Domain Logic

### 4.1 Domain Models (20 total)

| Model | Table | Purpose | Traits | Key Relationships |
|---|---|---|---|---|
| **User** | `users` | Authentication & RBAC | HasApiTokens, HasAuditLog, EagerLoadingOptimized | — |
| **PortalOpd** | `portal_opds` | Data OPD (unit kerja) | SoftDeletes, HasAuditLog, HasSearch, HasPagination | creator, updater |
| **PortalPapuaTengah** | `portal_papua_tengahs` | Berita/konten portal | HasAuditLog | author (User) |
| **Pelayanan** | `pelayanans` | Layanan publik | HasAuditLog, HasSearch, HasPagination | creator, updater |
| **Wbs** | `wbs` | Whistleblower reports | HasAuditLog | — |
| **Pengaduan** | `pengaduans` | Komplain publik | HasAuditLog | — |
| **Dokumen** | `dokumens` | Repository dokumen | HasAuditLog | creator, updater |
| **Galeri** | `galeris` | Galeri foto/video | HasAuditLog | album (Album) |
| **Album** | `albums` | Album galeri (hierarchical) | SoftDeletes | parent, children, photos (Galeri) |
| **Faq** | `faqs` | FAQ | — | — |
| **HeroSlider** | `hero_sliders` | Slider beranda | — | — |
| **InfoKantor** | `info_kantors` | Data kantor | — | — |
| **SystemConfiguration** | `system_configurations` | Key-value config | — | — |
| **AuditLog** | `audit_logs` | Audit trail | — | user |
| **PesanKontak** | `pesan_kontaks` | Pesan kontak | — | — |
| **ReviewOpd** | `review_opds` | Review OPD | — | portal_opd |
| **WebPortal** | `web_portals` | Portal eksternal | — | — |
| **ContentApproval** | `content_approvals` | Approval workflow | — | — |

### 4.2 Repository Contracts & Implementations (5 domain)

| Domain | Contract | Implementation | Binds To |
|---|---|---|---|
| PortalOpd | `PortalOpdRepositoryInterface` | `PortalOpdRepository` | DI Container |
| Pelayanan | `PelayananRepositoryInterface` | `PelayananRepository` | DI Container |
| Wbs | `WbsRepositoryInterface` | `WbsRepository` | DI Container |
| Dokumen | `DokumenRepositoryInterface` | `DokumenRepository` | DI Container |
| PortalPapuaTengah | `PortalPapuaTengahRepositoryInterface` | `PortalPapuaTengahRepository` | DI Container |

### 4.3 Service Contracts & Implementations (5 domain)

| Domain | Contract | Implementation | Binds To |
|---|---|---|---|
| PortalOpd | `PortalOpdServiceInterface` | `PortalOpdService` | DI Container |
| Pelayanan | `PelayananServiceInterface` | `PelayananService` | DI Container |
| Wbs | `WbsServiceInterface` | `WbsService` | DI Container |
| Dokumen | `DokumenServiceInterface` | `DokumenService` | DI Container |
| PortalPapuaTengah | `PortalPapuaTengahServiceInterface` | `PortalPapuaTengahService` | DI Container |

### 4.4 Action Classes (Domain Commands)

| Action | Purpose | Model |
|---|---|---|
| `CreatePelayananAction` | Create pelayanan + file upload + DB::transaction | Pelayanan |
| `UpdatePelayananAction` | Update pelayanan + delete old file + DB::transaction | Pelayanan |
| `CreatePortalOpdAction` | Create portal OPD + logo/banner upload + AuditLog | PortalOpd |
| `UpdatePortalOpdAction` | Update portal OPD + file replacement + AuditLog | PortalOpd |
| `CreatePortalPapuaTengahAction` | Create berita/konten | PortalPapuaTengah |
| `UpdatePortalPapuaTengahAction` | Update berita/konten | PortalPapuaTengah |
| `CreateWbsAction` | Create WBS report + multi-file upload + AuditLog | Wbs |
| `UpdateWbsAction` | Update WBS report + file replacement + AuditLog | Wbs |
| `CreateUserAction`, `UpdateUserAction`, `DeleteUserAction` | User CRUD + stats | User |

### 4.5 RBAC Role Hierarchy

```
super_admin (level 100)  → Akses penuh ke semua modul + manajemen user
    │
admin (level 90)        → Semua modul operasional + approval
    │
content_admin (level 70) → Berita, Galeri, FAQ, Dokumen
    │
content_manager          → Approval workflow
    │
wbs_manager              → Manajemen WBS
    │
user (level 0)           → Akses publik saja
```

---

## 5. Entry Points Penting

### 5.1 HTTP Entry Points

| Entry Point | File | Purpose |
|---|---|---|
| **Web Front Controller** | `public/index.php` | Semua HTTP request masuk di sini |
| **Alternative Root** | `index.php` | Fallback untuk Herd/shared hosting |
| **Web Routes** | `routes/web.php` | 287 baris — public + admin routes |
| **API Routes** | `routes/api.php` | 121 baris — Sanctum-authenticated REST API |
| **Console Routes** | `routes/console.php` | Artisan scheduled commands |

### 5.2 Key Route Groups

| Prefix | Controller | Auth | Role Required |
|---|---|---|---|
| `/` (root) | `PublicController` | None | — |
| `/admin/login` | `AuthController` | Guest | — |
| `/admin/dashboard` | `DashboardController` | Auth | Any admin |
| `/admin/users/*` | `UserController` | Auth | `super_admin` |
| `/admin/pelayanan/*` | `PelayananController` | Auth | `admin, super_admin` |
| `/admin/wbs/*` | `WbsController` | Auth | `admin, super_admin` |
| `/admin/pengaduan/*` | `PengaduanController` | Auth | `admin, super_admin` |
| `/admin/portal-opd/*` | `PortalOpdController` | Auth | `content_admin, admin, super_admin` |
| `/admin/dokumen/*` | `DokumenController` | Auth | `content_admin, admin, super_admin` |
| `/admin/galeri/*` | `GaleriController` | Auth | `content_admin, admin, super_admin` |
| `/admin/faq/*` | `FaqController` | Auth | `content_admin, admin, super_admin` |
| `/admin/audit-logs/*` | `AuditLogController` | Auth | `super_admin` |
| `/api/v1/*` | Various API Controllers | Sanctum | Token |
| `/api/v1/wbs/public` | `Api\WbsController` | None | — |
| `/api/v1/pengaduan/public` | `Api\PengaduanController` | None | — |

### 5.3 CLI Entry Points

| Command | File | Purpose |
|---|---|---|
| `artisan` | `artisan` | Laravel CLI |
| `DebugPortalOpdData` | `app/Console/Commands/` | Debug OPD data issues |
| `PerformanceCleanup` | `app/Console/Commands/` | Clean old logs/cache |
| `PerformanceReport` | `app/Console/Commands/` | Generate performance report |
| `SyncPortalOpdData` | `app/Console/Commands/` | Sync OPD data |

### 5.4 Frontend Entry Points (Vite)

| Entry | File | Purpose |
|---|---|---|
| **App JS** | `resources/js/app.js` | Main application JavaScript |
| **Admin JS** | `resources/js/admin.js` | Admin panel JavaScript |
| **Public JS** | `resources/js/public.js` | Public pages (hero slider, search, FAQ) |
| **App CSS** | `resources/css/app.css` | Main styles (imports Tailwind) |
| **Admin CSS** | `resources/css/admin.css` | Admin panel styles |

### 5.5 Service Providers (Critical for DI)

| Provider | File | Responsibility |
|---|---|---|
| `AppServiceProvider` | `app/Providers/AppServiceProvider.php` | Model observers, general bindings |
| `RepositoryServiceProvider` | `app/Providers/RepositoryServiceProvider.php` | **Repository + Service DI bindings** |
| `EventServiceProvider` | `app/Providers/EventServiceProvider.php` | Event-Listener mappings |
| `AssetServiceProvider` | `app/Providers/AssetServiceProvider.php` | Asset management |
| `StorageDirectoryProvider` | `app/Providers/StorageDirectoryProvider.php` | Storage directory setup |

---

## 6. Call Hierarchy & Blast Radius

### 5 Modul Paling Berisiko Jika Diubah

#### 1. `User` Model — Blast Radius: **32+ callers**

```
User (app/Models/User.php)
├── Dikontrol oleh:
│   ├── RoleMiddleware (setiap request admin)
│   ├── AdminLogoutOnPublic (setiap halaman publik)
│   ├── AdminRedirectMiddleware
│   └── Auth system (Sanctum + session)
├── Digunakan oleh:
│   ├── 21 Admin Controllers (semua admin routes)
│   ├── 8 API Controllers
│   ├── PublicController (visitor tracking)
│   ├── AuditLog (user_id foreign key)
│   ├── Semua Models dengan created_by/updated_by
│   └── ProcessPelayananNotification (query admin emails)
└── Risiko: Mengubah role hierarchy atau auth logic
    mempengaruhi SELURUH admin panel dan API.
    ⚠️ Tidak ada covering tests untuk User model.
```

#### 2. `PublicController` — Blast Radius: **27+ methods, 15+ routes**

```
PublicController (app/Http/Controllers/PublicController.php)
├── Dikontrol oleh:
│   └── routes/web.php (semua public routes)
├── Memanggil:
│   ├── PortalOpd, PortalPapuaTengah, Pelayanan, Wbs
│   ├── Dokumen, Galeri, Faq, HeroSlider, Album
│   ├── SystemConfiguration, AuditLog
│   ├── Cache::remember() dengan 10+ cache keys
│   ├── trackVisitor() → system_configurations table
│   └── Storage::download/response untuk file serving
├── View: 15+ public blade templates
└── Risiko: Controller ini melayani SEMUA halaman publik.
    Perubahan method mana pun mengubah halaman publik.
    ⚠️ Tidak ada covering tests.
```

#### 3. `PortalOpd` Model — Blast Radius: **32 callers**

```
PortalOpd (app/Models/PortalOpd.php)
├── Dikontrol oleh:
│   ├── PortalOpdController (admin CRUD)
│   ├── PortalOpdController (public portal)
│   ├── CreatePortalOpdAction, UpdatePortalOpdAction
│   ├── PortalOpdRepository (query + cache)
│   ├── PortalOpdService (business logic)
│   ├── DashboardController (stats)
│   ├── SitemapController (sitemap generation)
│   └── SyncPortalOpdData (CLI sync)
├── Cache keys: portal_opds.all, portal_opds.active, portal_opds.{id}
├── Relationships: creator, updater (User)
├── Traits: SoftDeletes, HasAuditLog, HasSearch, HasPagination
└── Risiko: Model ini adalah INTI dari portal.
    Digunakan di publik, admin, API, CLI, dan sitemap.
    Mengubah schema/attributes mempengaruhi 32+ callers.
    ⚠️ Tidak ada covering tests.
```

#### 4. `PelayananService` + `PelayananRepository` — Blast Radius: **22+ callers**

```
PelayananService (app/Services/Implementation/PelayananService.php)
├── Dependencies:
│   ├── PelayananRepositoryInterface → PelayananRepository
│   ├── StorePelayananRequest, UpdatePelayananRequest
│   └── Storage (file upload/delete)
├── Dipanggil oleh:
│   ├── PelayananController (admin CRUD) — 6 methods
│   ├── PublicController (public listing) — direct model query
│   └── API controllers
├── Events dispatched: PelayananCreated/Updated/Deleted
├── Observers triggered: PelayananObserver (audit + cache)
└── Risiko: Service layer ini mengkoordinasikan
    Repository, Events, Observers, Cache, dan File Storage.
    Perubahan mempengaruhi seluruh pelayanan flow.

PelayananRepository (app/Repositories/Implementation/PelayananRepository.php)
├── Cache: pelayanans.all, pelayanans.active, pelayanans.{id}
├── Query: search, filter, pagination
└── Risiko: Cache key names hard-coded di banyak tempat.
    Mengubah cache strategy mempengaruhi semua consumer.
```

#### 5. `RepositoryServiceProvider` — Blast Radius: **semua DI bindings**

```
RepositoryServiceProvider (app/Providers/RepositoryServiceProvider.php)
├── Binds:
│   ├── PortalOpdRepositoryInterface → PortalOpdRepository
│   ├── PortalOpdServiceInterface → PortalOpdService
│   ├── PelayananRepositoryInterface → PelayananRepository
│   ├── PelayananServiceInterface → PelayananService
│   ├── WbsRepositoryInterface → WbsRepository
│   ├── WbsServiceInterface → WbsService
│   ├── DokumenRepositoryInterface → DokumenRepository
│   ├── DokumenServiceInterface → DokumenService
│   ├── PortalPapuaTengahRepositoryInterface → PortalPapuaTengahRepository
│   └── PortalPapuaTengahServiceInterface → PortalPapuaTengahService
├── Dipanggil oleh: Semua Controllers (via constructor injection)
└── Risiko: Provider ini adalah SINGLE POINT OF FAILURE
    untuk dependency injection. Salah binding = crash semua.
```

---

## 7. Cache Strategy

| Cache Key | TTL | Invalidate Trigger |
|---|---|---|
| `portal_opds.all` | 600s | PortalOpdRepository (create/update/delete) |
| `portal_opds.active` | 600s | PortalOpdRepository (create/update/delete) |
| `portal_opds.{id}` | 600s | PortalOpdRepository (update/delete) |
| `public_portal_papua_tengah` | 600s | Manual (no observer) |
| `public_latest_gallery` | 600s | Manual |
| `public_pelayanans` | 600s | PelayananObserver (clearCache) |
| `public_dokumens_{hash}` | 600s | Manual |
| `public_faqs_{hash}` | 600s | Manual |
| `hero_sliders_homepage` | 3600s | HeroSliderObserver |
| `pelayanans.all/active` | — | PelayananObserver, LogPelayananActivity |
| `pelayanan_monthly_stats` | 86400s | ProcessPelayananNotification |
| `total_visitors` | — | PublicController::trackVisitor() (DB counter) |

---

## 8. Middleware Stack

### Global Middleware
- `SecurityHeadersMiddleware` — Security headers (X-Frame, CSP, etc.)
- `AssetOptimizationMiddleware` — Asset optimization headers

### Web Group
- Standard Laravel stack + `DatabaseQueryOptimization` + `AssetOptimization`

### API Group
- `Sanctum::stateful` + `api.format` + `api.errors` + `api.rate.limit` (120/min) + `db.optimize`

### Admin Routes
- `auth` — Authentication check
- `role:super_admin` / `role:admin,content_admin` — RBAC via `RoleMiddleware`

### Special Middleware
- `AdminLogoutOnPublic` — Auto-logout admin saat akses halaman publik (kecuali pengaduan/wbs/api)
- `AdminRedirectMiddleware` — Redirect admin ke dashboard saat akses halaman publik

---

## 9. Testing

| Suite | Location | Coverage |
|---|---|---|
| **Unit** | `tests/Unit/Repositories/`, `tests/Unit/Services/` | PelayananRepositoryTest, PelayananServiceTest |
| **Feature** | `tests/Feature/Http/Controllers/Admin/` | Admin controller tests (Album, Galeri, etc.) |
| **Config** | `phpunit.xml` | SQLite in-memory, array cache/mail, sync queue |

> **Catatan Penting:** Banyak modul inti (User, PublicController, PortalOpd, Wbs, Pengaduan) **belum memiliki test coverage**. Ini adalah risiko signifikan.

---

## 10. Konfigurasi Penting

| Config | File | Purpose |
|---|---|---|
| `config/app.php` | Application config | App name, locale (id), timezone |
| `config/auth.php` | Auth guards | Web + Sanctum guards |
| `config/contact.php` | Custom | Alamat, email, telepon kantor |
| `config/performance.php` | Custom | Performance monitoring thresholds |
| `config/database.php` | DB connections | MySQL (prod), SQLite (test) |
| `config/sanctum.php` | API tokens | Stateful domains, token expiry |
| `tailwind.config.js` | CSS theme | Custom colors, fonts, shadows |
| `vite.config.js` | Build config | 5 entry points, manual chunks, Terser |
| `.env.production` | Prod env | URL, SMTP, session domain |

---

## 11. Deployment

| Aspek | Detail |
|---|---|
| **Host** | Hostinger shared hosting |
| **URL** | `https://inspektorat.papuatengahprov.cloud` |
| **Web Server** | Apache (`.htaccess` rewrite rules) |
| **Deploy Script** | `HOSTINGER_DEPLOY.sh` |
| **Storage Sync** | `sync-storage.sh` |
| **Build** | `npm run build` → `public/build/` |
| **Migrate** | `php artisan migrate --force` |

---

*Terakhir diperbarui: Agustus 2026. Dokumen ini dihasilkan dari analisis CodeGraph.*
