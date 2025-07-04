<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\WbsController as AdminWbsController;
use App\Http\Controllers\Admin\PortalPapuaTengahController as AdminPortalPapuaTengahController;
use App\Http\Controllers\PublicController;
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
        
        // Admin CRUD routes
        Route::get('wbs', [AdminWbsController::class, 'index'])->name('wbs.index');
        Route::get('wbs/create', [AdminWbsController::class, 'create'])->name('wbs.create');
        Route::post('wbs', [AdminWbsController::class, 'store'])->name('wbs.store');
        Route::get('wbs/{wbs}', [AdminWbsController::class, 'show'])->name('wbs.show');
        Route::get('wbs/{wbs}/edit', [AdminWbsController::class, 'edit'])->name('wbs.edit');
        Route::put('wbs/{wbs}', [AdminWbsController::class, 'update'])->name('wbs.update');
        Route::delete('wbs/{wbs}', [AdminWbsController::class, 'destroy'])->name('wbs.destroy');
        
        // Portal Papua Tengah (News) CRUD routes
        Route::get('portal-papua-tengah', [AdminPortalPapuaTengahController::class, 'index'])->name('portal-papua-tengah.index');
        Route::get('portal-papua-tengah/create', [AdminPortalPapuaTengahController::class, 'create'])->name('portal-papua-tengah.create');
        Route::post('portal-papua-tengah', [AdminPortalPapuaTengahController::class, 'store'])->name('portal-papua-tengah.store');
        Route::get('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'show'])->name('portal-papua-tengah.show');
        Route::get('portal-papua-tengah/{portalPapuaTengah}/edit', [AdminPortalPapuaTengahController::class, 'edit'])->name('portal-papua-tengah.edit');
        Route::put('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'update'])->name('portal-papua-tengah.update');
        Route::delete('portal-papua-tengah/{portalPapuaTengah}', [AdminPortalPapuaTengahController::class, 'destroy'])->name('portal-papua-tengah.destroy');
    });
});
