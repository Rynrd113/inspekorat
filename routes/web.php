<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WbsController as AdminWbsController;
use App\Http\Controllers\Admin\PortalPapuaTengahController as AdminPortalPapuaTengahController;
use App\Http\Controllers\Admin\PortalOpdController as AdminPortalOpdController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\PelayananController as AdminPelayananController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\SystemConfigurationController as AdminSystemConfigurationController;
use App\Http\Controllers\Admin\BrandingController as AdminBrandingController;
use App\Http\Controllers\Admin\ContentApprovalController as AdminContentApprovalController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\WebPortalController as AdminWebPortalController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PortalOpdController;

// Test route untuk Tailwind CSS
Route::get('/test-tailwind', function () {
    return view('test-tailwind');
});
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes with admin logout middleware
Route::middleware('admin.logout.public')->group(function () {
    Route::get('/', [PublicController::class, 'index'])->name('public.index');
    Route::get('/berita', [PublicController::class, 'berita'])->name('public.berita.index');
    Route::get('/berita/{id}', [PublicController::class, 'show'])->name('public.berita.show');
    Route::get('/wbs', [PublicController::class, 'wbs'])->name('public.wbs');
    Route::post('/wbs', [PublicController::class, 'storeWbs'])->name('public.wbs.store');
    Route::get('/profil', [PublicController::class, 'profil'])->name('public.profil');
    Route::get('/pelayanan', [PublicController::class, 'pelayanan'])->name('public.pelayanan.index');
    Route::get('/pelayanan/{id}', [PublicController::class, 'pelayananShow'])->name('public.pelayanan.show');
    Route::get('/dokumen', [PublicController::class, 'dokumen'])->name('public.dokumen.index');
    Route::get('/dokumen/{id}/download', [PublicController::class, 'dokumenDownload'])->name('public.dokumen.download');
    Route::get('/dokumen/{id}/preview', [PublicController::class, 'dokumenPreview'])->name('public.dokumen.preview');
    Route::get('/galeri', [PublicController::class, 'galeri'])->name('public.galeri.index');
    Route::get('/galeri/{id}', [PublicController::class, 'galeriShow'])->name('public.galeri.show');
    Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');
    Route::get('/kontak', [PublicController::class, 'kontak'])->name('public.kontak');
    Route::post('/kontak', [PublicController::class, 'kontakKirim'])->name('kontak.kirim');
    Route::get('/pengaduan', [PublicController::class, 'pengaduan'])->name('public.pengaduan');
    Route::post('/pengaduan', [PublicController::class, 'storePengaduan'])->name('public.pengaduan.store');
    
    // Web Portal Public Routes
    Route::get('/web-portal', [PublicController::class, 'webPortal'])->name('public.web-portal.index');
    
    // Portal OPD Public Routes
    Route::get('/portal-opd', [PortalOpdController::class, 'index'])->name('public.portal-opd.index');
    Route::get('/portal-opd/{portalOpd}', [PortalOpdController::class, 'show'])->name('public.portal-opd.show');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });
    
    // Authenticated admin routes
    Route::middleware('auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::post('extend-session', [AdminAuthController::class, 'extendSession'])->name('extend-session');
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // WBS routes - protected by role middleware
        Route::middleware('role:admin_wbs,wbs_manager,admin,super_admin')->group(function () {
            Route::get('wbs', [AdminWbsController::class, 'index'])->name('wbs.index');
            Route::get('wbs/create', [AdminWbsController::class, 'create'])->name('wbs.create');
            Route::post('wbs', [AdminWbsController::class, 'store'])->name('wbs.store');
            Route::get('wbs/{wbs}', [AdminWbsController::class, 'show'])->name('wbs.show');
            Route::get('wbs/{wbs}/edit', [AdminWbsController::class, 'edit'])->name('wbs.edit');
            Route::put('wbs/{wbs}', [AdminWbsController::class, 'update'])->name('wbs.update');
            Route::delete('wbs/{wbs}', [AdminWbsController::class, 'destroy'])->name('wbs.destroy');
        });
        
        // Portal Papua Tengah (News) routes - protected by role middleware
        Route::middleware('role:admin_berita,content_manager,admin,super_admin')->group(function () {
            Route::get('portal-papua-tengah', [AdminPortalPapuaTengahController::class, 'index'])->name('portal-papua-tengah.index');
            Route::get('portal-papua-tengah/create', [AdminPortalPapuaTengahController::class, 'create'])->name('portal-papua-tengah.create');
            Route::post('portal-papua-tengah', [AdminPortalPapuaTengahController::class, 'store'])->name('portal-papua-tengah.store');
            Route::get('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'show'])->name('portal-papua-tengah.show');
            Route::get('portal-papua-tengah/{portalPapuaTengah}/edit', [AdminPortalPapuaTengahController::class, 'edit'])->name('portal-papua-tengah.edit');
            Route::put('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'update'])->name('portal-papua-tengah.update');
            Route::delete('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'destroy'])->name('portal-papua-tengah.destroy');
        });
        
        // Portal OPD routes - protected by role middleware
        Route::middleware('role:admin_portal_opd,opd_manager,admin,super_admin')->group(function () {
            Route::get('portal-opd', [AdminPortalOpdController::class, 'index'])->name('portal-opd.index');
            Route::post('portal-opd/sync', [AdminPortalOpdController::class, 'sync'])->name('portal-opd.sync');
            Route::get('portal-opd/create', [AdminPortalOpdController::class, 'create'])->name('portal-opd.create');
            Route::post('portal-opd', [AdminPortalOpdController::class, 'store'])->name('portal-opd.store');
            Route::get('portal-opd/{portalOpd}', [AdminPortalOpdController::class, 'show'])->name('portal-opd.show');
            Route::get('portal-opd/{portalOpd}/edit', [AdminPortalOpdController::class, 'edit'])->name('portal-opd.edit');
            Route::put('portal-opd/{portalOpd}', [AdminPortalOpdController::class, 'update'])->name('portal-opd.update');
            Route::delete('portal-opd/{portalOpd}', [AdminPortalOpdController::class, 'destroy'])->name('portal-opd.destroy');
        });
        
        // Info Kantor routes - protected by role middleware (missing routes)
        Route::middleware('role:admin_info_kantor,admin,super_admin')->group(function () {
            Route::get('info-kantor', [\App\Http\Controllers\Admin\InfoKantorController::class, 'index'])->name('info-kantor.index');
            Route::get('info-kantor/create', [\App\Http\Controllers\Admin\InfoKantorController::class, 'create'])->name('info-kantor.create');
            Route::post('info-kantor', [\App\Http\Controllers\Admin\InfoKantorController::class, 'store'])->name('info-kantor.store');
            Route::get('info-kantor/{infoKantor}', [\App\Http\Controllers\Admin\InfoKantorController::class, 'show'])->name('info-kantor.show');
            Route::get('info-kantor/{infoKantor}/edit', [\App\Http\Controllers\Admin\InfoKantorController::class, 'edit'])->name('info-kantor.edit');
            Route::put('info-kantor/{infoKantor}', [\App\Http\Controllers\Admin\InfoKantorController::class, 'update'])->name('info-kantor.update');
            Route::delete('info-kantor/{infoKantor}', [\App\Http\Controllers\Admin\InfoKantorController::class, 'destroy'])->name('info-kantor.destroy');
        });
        
        // Profil routes - protected by role middleware
        Route::middleware('role:admin_profil,admin,super_admin')->group(function () {
            Route::get('profil', [AdminProfilController::class, 'index'])->name('profil.index');
            Route::get('profil/create', [AdminProfilController::class, 'create'])->name('profil.create');
            Route::post('profil', [AdminProfilController::class, 'store'])->name('profil.store');
            Route::get('profil/{profil}', [AdminProfilController::class, 'show'])->name('profil.show');
            Route::get('profil/{profil}/edit', [AdminProfilController::class, 'edit'])->name('profil.edit');
            Route::put('profil/{profil}', [AdminProfilController::class, 'update'])->name('profil.update');
            Route::delete('profil/{profil}', [AdminProfilController::class, 'destroy'])->name('profil.destroy');
        });
        
        // Pelayanan routes - protected by role middleware
        Route::middleware('role:admin_pelayanan,service_manager,admin,super_admin')->group(function () {
            Route::get('pelayanan', [AdminPelayananController::class, 'index'])->name('pelayanan.index');
            Route::get('pelayanan/create', [AdminPelayananController::class, 'create'])->name('pelayanan.create');
            Route::post('pelayanan', [AdminPelayananController::class, 'store'])->name('pelayanan.store');
            Route::get('pelayanan/{pelayanan}', [AdminPelayananController::class, 'show'])->name('pelayanan.show');
            Route::get('pelayanan/{pelayanan}/edit', [AdminPelayananController::class, 'edit'])->name('pelayanan.edit');
            Route::put('pelayanan/{pelayanan}', [AdminPelayananController::class, 'update'])->name('pelayanan.update');
            Route::delete('pelayanan/{pelayanan}', [AdminPelayananController::class, 'destroy'])->name('pelayanan.destroy');
        });
        
        // Dokumen routes - protected by role middleware
        Route::middleware('role:admin_dokumen,service_manager,admin,super_admin')->group(function () {
            Route::get('dokumen', [AdminDokumenController::class, 'index'])->name('dokumen.index');
            Route::get('dokumen/create', [AdminDokumenController::class, 'create'])->name('dokumen.create');
            Route::post('dokumen', [AdminDokumenController::class, 'store'])->name('dokumen.store');
            Route::get('dokumen/{dokumen}', [AdminDokumenController::class, 'show'])->name('dokumen.show');
            Route::get('dokumen/{dokumen}/edit', [AdminDokumenController::class, 'edit'])->name('dokumen.edit');
            Route::put('dokumen/{dokumen}', [AdminDokumenController::class, 'update'])->name('dokumen.update');
            Route::delete('dokumen/{dokumen}', [AdminDokumenController::class, 'destroy'])->name('dokumen.destroy');
            Route::get('dokumen/{dokumen}/download', [AdminDokumenController::class, 'download'])->name('dokumen.download');
        });
        
        // Galeri routes - protected by role middleware
        Route::middleware('role:admin_galeri,content_manager,admin,super_admin')->group(function () {
            Route::get('galeri', [AdminGaleriController::class, 'index'])->name('galeri.index');
            Route::get('galeri/create', [AdminGaleriController::class, 'create'])->name('galeri.create');
            Route::post('galeri', [AdminGaleriController::class, 'store'])->name('galeri.store');
            Route::get('galeri/{galeri}', [AdminGaleriController::class, 'show'])->name('galeri.show');
            Route::get('galeri/{galeri}/edit', [AdminGaleriController::class, 'edit'])->name('galeri.edit');
            Route::put('galeri/{galeri}', [AdminGaleriController::class, 'update'])->name('galeri.update');
            Route::delete('galeri/{galeri}', [AdminGaleriController::class, 'destroy'])->name('galeri.destroy');
            Route::post('galeri/bulk-upload', [AdminGaleriController::class, 'bulkUpload'])->name('galeri.bulk-upload');
        });
        
        // FAQ routes - accessible by admin_faq, admin, super_admin
        Route::middleware('role:admin_faq,content_manager,admin,super_admin')->group(function () {
            Route::get('faq', [AdminFaqController::class, 'index'])->name('faq.index');
            Route::get('faq/create', [AdminFaqController::class, 'create'])->name('faq.create');
            Route::post('faq', [AdminFaqController::class, 'store'])->name('faq.store');
            Route::get('faq/{faq}', [AdminFaqController::class, 'show'])->name('faq.show');
            Route::get('faq/{faq}/edit', [AdminFaqController::class, 'edit'])->name('faq.edit');
            Route::put('faq/{faq}', [AdminFaqController::class, 'update'])->name('faq.update');
            Route::delete('faq/{faq}', [AdminFaqController::class, 'destroy'])->name('faq.destroy');
            Route::patch('faq/{faq}/toggle-status', [AdminFaqController::class, 'toggleStatus'])->name('faq.toggle-status');
            Route::patch('faq/{faq}/move-up', [AdminFaqController::class, 'moveUp'])->name('faq.move-up');
            Route::patch('faq/{faq}/move-down', [AdminFaqController::class, 'moveDown'])->name('faq.move-down');
            Route::post('faq/reorder', [AdminFaqController::class, 'reorder'])->name('faq.reorder');
        });
        
        // Content Approval routes - accessible by content_manager, admin, super_admin
        Route::middleware('role:content_manager,admin,super_admin')->group(function () {
            Route::get('approvals', [AdminContentApprovalController::class, 'index'])->name('approvals.index');
            Route::get('approvals/{approval}', [AdminContentApprovalController::class, 'show'])->name('approvals.show');
            Route::post('approvals/{approval}/approve', [AdminContentApprovalController::class, 'approve'])->name('approvals.approve');
            Route::post('approvals/{approval}/reject', [AdminContentApprovalController::class, 'reject'])->name('approvals.reject');
            Route::post('approvals/bulk-action', [AdminContentApprovalController::class, 'bulkAction'])->name('approvals.bulk-action');
            Route::get('approvals/stats', [AdminContentApprovalController::class, 'stats'])->name('approvals.stats');
        });
        
        // System Configuration routes - only for super_admin
        Route::middleware('role:super_admin')->group(function () {
            // User Management routes
            Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
            Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
            
            // System Configuration routes
            Route::get('configurations', [AdminSystemConfigurationController::class, 'index'])->name('configurations.index');
            Route::post('configurations', [AdminSystemConfigurationController::class, 'update'])->name('configurations.update');
            Route::post('configurations/store', [AdminSystemConfigurationController::class, 'store'])->name('configurations.store');
            Route::delete('configurations/{configuration}', [AdminSystemConfigurationController::class, 'destroy'])->name('configurations.destroy');
            Route::post('configurations/initialize', [AdminSystemConfigurationController::class, 'initialize'])->name('configurations.initialize');
            Route::get('configurations/export', [AdminSystemConfigurationController::class, 'export'])->name('configurations.export');
            Route::post('configurations/import', [AdminSystemConfigurationController::class, 'import'])->name('configurations.import');
            
            // Branding Configuration routes
            Route::prefix('branding')->name('branding.')->group(function () {
                Route::get('/', [AdminBrandingController::class, 'index'])->name('index');
                Route::post('update-branding', [AdminBrandingController::class, 'updateBranding'])->name('update-branding');
                Route::post('update-social', [AdminBrandingController::class, 'updateSocial'])->name('update-social');
                Route::post('upload-image', [AdminBrandingController::class, 'uploadImage'])->name('upload-image');
                Route::get('preview', [AdminBrandingController::class, 'preview'])->name('preview');
                Route::post('generate-css', [AdminBrandingController::class, 'generateCss'])->name('generate-css');
                Route::post('reset', [AdminBrandingController::class, 'reset'])->name('reset');
                Route::get('test', function () {
                    return view('admin.branding.test');
                })->name('test');
            });
        });
        
        // Audit Log routes - only for super_admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('audit-logs/{auditLog}', [AdminAuditLogController::class, 'show'])->name('audit-logs.show');
            Route::get('audit-logs/stats', [AdminAuditLogController::class, 'stats'])->name('audit-logs.stats');
            Route::get('audit-logs/export', [AdminAuditLogController::class, 'export'])->name('audit-logs.export');
        });

        // Pengaduan routes - accessible by admin, super_admin
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
            Route::get('pengaduan/create', [AdminPengaduanController::class, 'create'])->name('pengaduan.create');
            Route::post('pengaduan', [AdminPengaduanController::class, 'store'])->name('pengaduan.store');
            Route::get('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
            Route::get('pengaduan/{pengaduan}/edit', [AdminPengaduanController::class, 'edit'])->name('pengaduan.edit');
            Route::put('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'update'])->name('pengaduan.update');
            Route::delete('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');
        });

        // Web Portal routes - accessible by admin, super_admin
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('web-portal', [AdminWebPortalController::class, 'index'])->name('web-portal.index');
            Route::get('web-portal/create', [AdminWebPortalController::class, 'create'])->name('web-portal.create');
            Route::post('web-portal', [AdminWebPortalController::class, 'store'])->name('web-portal.store');
            Route::get('web-portal/{webPortal}', [AdminWebPortalController::class, 'show'])->name('web-portal.show');
            Route::get('web-portal/{webPortal}/edit', [AdminWebPortalController::class, 'edit'])->name('web-portal.edit');
            Route::put('web-portal/{webPortal}', [AdminWebPortalController::class, 'update'])->name('web-portal.update');
            Route::delete('web-portal/{webPortal}', [AdminWebPortalController::class, 'destroy'])->name('web-portal.destroy');
        });
    });
});

// Route::get('/pelayanan', [App\Http\Controllers\PelayananController::class, 'index'])->name('public.pelayanan');
