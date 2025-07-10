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
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PortalOpdController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/berita', [PublicController::class, 'berita'])->name('public.berita.index');
Route::get('/berita/{id}', [PublicController::class, 'show'])->name('public.berita.show');
Route::get('/wbs', [PublicController::class, 'wbs'])->name('public.wbs');
Route::get('/profil', [PublicController::class, 'profil'])->name('public.profil');
Route::get('/pelayanan', [PublicController::class, 'pelayanan'])->name('public.pelayanan.index');
Route::get('/pelayanan/{id}', [PublicController::class, 'pelayananShow'])->name('public.pelayanan.show');
Route::get('/dokumen', [PublicController::class, 'dokumen'])->name('public.dokumen.index');
Route::get('/dokumen/{id}/download', [PublicController::class, 'dokumenDownload'])->name('public.dokumen.download');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('public.galeri.index');
Route::get('/galeri/{id}', [PublicController::class, 'galeriShow'])->name('public.galeri.show');
Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('public.kontak');
Route::post('/kontak', [PublicController::class, 'kontakKirim'])->name('kontak.kirim');
Route::get('/pengaduan', [PublicController::class, 'pengaduan'])->name('public.pengaduan');

// Portal OPD Public Routes
Route::get('/portal-opd', [PortalOpdController::class, 'index'])->name('public.portal-opd.index');
Route::get('/portal-opd/{portalOpd}', [PortalOpdController::class, 'show'])->name('public.portal-opd.show');

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
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // WBS routes - protected by role middleware
        Route::middleware('role:admin_wbs,admin,super_admin')->group(function () {
            Route::get('wbs', [AdminWbsController::class, 'index'])->name('wbs.index');
            Route::get('wbs/create', [AdminWbsController::class, 'create'])->name('wbs.create');
            Route::post('wbs', [AdminWbsController::class, 'store'])->name('wbs.store');
            Route::get('wbs/{wbs}', [AdminWbsController::class, 'show'])->name('wbs.show');
            Route::get('wbs/{wbs}/edit', [AdminWbsController::class, 'edit'])->name('wbs.edit');
            Route::put('wbs/{wbs}', [AdminWbsController::class, 'update'])->name('wbs.update');
            Route::delete('wbs/{wbs}', [AdminWbsController::class, 'destroy'])->name('wbs.destroy');
        });
        
        // Portal Papua Tengah (News) routes - protected by role middleware
        Route::middleware('role:admin_berita,admin,super_admin')->group(function () {
            Route::get('portal-papua-tengah', [AdminPortalPapuaTengahController::class, 'index'])->name('portal-papua-tengah.index');
            Route::get('portal-papua-tengah/create', [AdminPortalPapuaTengahController::class, 'create'])->name('portal-papua-tengah.create');
            Route::post('portal-papua-tengah', [AdminPortalPapuaTengahController::class, 'store'])->name('portal-papua-tengah.store');
            Route::get('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'show'])->name('portal-papua-tengah.show');
            Route::get('portal-papua-tengah/{portalPapuaTengah}/edit', [AdminPortalPapuaTengahController::class, 'edit'])->name('portal-papua-tengah.edit');
            Route::put('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'update'])->name('portal-papua-tengah.update');
            Route::delete('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'destroy'])->name('portal-papua-tengah.destroy');
        });
        
        // Portal OPD routes - protected by role middleware
        Route::middleware('role:admin_portal_opd,admin,super_admin')->group(function () {
            Route::get('portal-opd', [AdminPortalOpdController::class, 'index'])->name('portal-opd.index');
            Route::get('portal-opd/create', [AdminPortalOpdController::class, 'create'])->name('portal-opd.create');
            Route::post('portal-opd', [AdminPortalOpdController::class, 'store'])->name('portal-opd.store');
            Route::get('portal-opd/{portalOpd}', [AdminPortalOpdController::class, 'show'])->name('portal-opd.show');
            Route::get('portal-opd/{portalOpd}/edit', [AdminPortalOpdController::class, 'edit'])->name('portal-opd.edit');
            Route::put('portal-opd/{portalOpd}', [AdminPortalOpdController::class, 'update'])->name('portal-opd.update');
            Route::delete('portal-opd/{portalOpd}', [AdminPortalOpdController::class, 'destroy'])->name('portal-opd.destroy');
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
        Route::middleware('role:admin_pelayanan,admin,super_admin')->group(function () {
            Route::get('pelayanan', [AdminPelayananController::class, 'index'])->name('pelayanan.index');
            Route::get('pelayanan/create', [AdminPelayananController::class, 'create'])->name('pelayanan.create');
            Route::post('pelayanan', [AdminPelayananController::class, 'store'])->name('pelayanan.store');
            Route::get('pelayanan/{pelayanan}', [AdminPelayananController::class, 'show'])->name('pelayanan.show');
            Route::get('pelayanan/{pelayanan}/edit', [AdminPelayananController::class, 'edit'])->name('pelayanan.edit');
            Route::put('pelayanan/{pelayanan}', [AdminPelayananController::class, 'update'])->name('pelayanan.update');
            Route::delete('pelayanan/{pelayanan}', [AdminPelayananController::class, 'destroy'])->name('pelayanan.destroy');
        });
        
        // Dokumen routes - protected by role middleware
        Route::middleware('role:admin_dokumen,admin,super_admin')->group(function () {
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
        Route::middleware('role:admin_galeri,admin,super_admin')->group(function () {
            Route::get('galeri', [AdminGaleriController::class, 'index'])->name('galeri.index');
            Route::get('galeri/create', [AdminGaleriController::class, 'create'])->name('galeri.create');
            Route::post('galeri', [AdminGaleriController::class, 'store'])->name('galeri.store');
            Route::get('galeri/{galeri}', [AdminGaleriController::class, 'show'])->name('galeri.show');
            Route::get('galeri/{galeri}/edit', [AdminGaleriController::class, 'edit'])->name('galeri.edit');
            Route::put('galeri/{galeri}', [AdminGaleriController::class, 'update'])->name('galeri.update');
            Route::delete('galeri/{galeri}', [AdminGaleriController::class, 'destroy'])->name('galeri.destroy');
            Route::post('galeri/bulk-upload', [AdminGaleriController::class, 'bulkUpload'])->name('galeri.bulk-upload');
        });
        
        // FAQ routes - protected by role middleware
        Route::middleware('role:admin_faq,admin,super_admin')->group(function () {
            Route::get('faq', [AdminFaqController::class, 'index'])->name('faq.index');
            Route::get('faq/create', [AdminFaqController::class, 'create'])->name('faq.create');
            Route::post('faq', [AdminFaqController::class, 'store'])->name('faq.store');
            Route::get('faq/{faq}', [AdminFaqController::class, 'show'])->name('faq.show');
            Route::get('faq/{faq}/edit', [AdminFaqController::class, 'edit'])->name('faq.edit');
            Route::put('faq/{faq}', [AdminFaqController::class, 'update'])->name('faq.update');
            Route::delete('faq/{faq}', [AdminFaqController::class, 'destroy'])->name('faq.destroy');
            Route::post('faq/reorder', [AdminFaqController::class, 'reorder'])->name('faq.reorder');
        });
        
        // User Management routes - only for super_admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
            Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });

        // Profil Organisasi routes - accessible by admin and super_admin
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('profil', [AdminProfilController::class, 'index'])->name('profil.index');
            Route::get('profil/edit', [AdminProfilController::class, 'edit'])->name('profil.edit');
            Route::put('profil', [AdminProfilController::class, 'update'])->name('profil.update');
        });

        // Pelayanan routes - accessible by admin_pelayanan, admin, super_admin
        Route::middleware('role:admin_pelayanan,admin,super_admin')->group(function () {
            Route::get('pelayanan', [AdminPelayananController::class, 'index'])->name('pelayanan.index');
            Route::get('pelayanan/create', [AdminPelayananController::class, 'create'])->name('pelayanan.create');
            Route::post('pelayanan', [AdminPelayananController::class, 'store'])->name('pelayanan.store');
            Route::get('pelayanan/{pelayanan}', [AdminPelayananController::class, 'show'])->name('pelayanan.show');
            Route::get('pelayanan/{pelayanan}/edit', [AdminPelayananController::class, 'edit'])->name('pelayanan.edit');
            Route::put('pelayanan/{pelayanan}', [AdminPelayananController::class, 'update'])->name('pelayanan.update');
            Route::delete('pelayanan/{pelayanan}', [AdminPelayananController::class, 'destroy'])->name('pelayanan.destroy');
        });

        // Dokumen routes - accessible by admin_dokumen, admin, super_admin
        Route::middleware('role:admin_dokumen,admin,super_admin')->group(function () {
            Route::get('dokumen', [AdminDokumenController::class, 'index'])->name('dokumen.index');
            Route::get('dokumen/create', [AdminDokumenController::class, 'create'])->name('dokumen.create');
            Route::post('dokumen', [AdminDokumenController::class, 'store'])->name('dokumen.store');
            Route::get('dokumen/{dokumen}', [AdminDokumenController::class, 'show'])->name('dokumen.show');
            Route::get('dokumen/{dokumen}/edit', [AdminDokumenController::class, 'edit'])->name('dokumen.edit');
            Route::put('dokumen/{dokumen}', [AdminDokumenController::class, 'update'])->name('dokumen.update');
            Route::delete('dokumen/{dokumen}', [AdminDokumenController::class, 'destroy'])->name('dokumen.destroy');
            Route::get('dokumen/{dokumen}/download', [AdminDokumenController::class, 'download'])->name('dokumen.download');
        });
        
        // Galeri routes - accessible by admin_galeri, admin, super_admin
        Route::middleware('role:admin_galeri,admin,super_admin')->group(function () {
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
        Route::middleware('role:admin_faq,admin,super_admin')->group(function () {
            Route::get('faq', [AdminFaqController::class, 'index'])->name('faq.index');
            Route::get('faq/create', [AdminFaqController::class, 'create'])->name('faq.create');
            Route::post('faq', [AdminFaqController::class, 'store'])->name('faq.store');
            Route::get('faq/{faq}', [AdminFaqController::class, 'show'])->name('faq.show');
            Route::get('faq/{faq}/edit', [AdminFaqController::class, 'edit'])->name('faq.edit');
            Route::put('faq/{faq}', [AdminFaqController::class, 'update'])->name('faq.update');
            Route::delete('faq/{faq}', [AdminFaqController::class, 'destroy'])->name('faq.destroy');
            Route::post('faq/reorder', [AdminFaqController::class, 'reorder'])->name('faq.reorder');
        });
    });
});

// Route::get('/pelayanan', [App\Http\Controllers\PelayananController::class, 'index'])->name('public.pelayanan');
