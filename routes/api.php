<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InfoKantorController;
use App\Http\Controllers\Api\WbsController;
use App\Http\Controllers\Api\PortalPapuaTengahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Admin Panel API Routes (no prefix for admin convenience)
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Auth
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Dashboard
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/wbs-chart', [DashboardController::class, 'wbsChart']);
    
    // Admin CRUD routes
    Route::apiResource('wbs', WbsController::class)->names([
        'index' => 'api.admin.wbs.index',
        'store' => 'api.admin.wbs.store',
        'show' => 'api.admin.wbs.show',
        'update' => 'api.admin.wbs.update',
        'destroy' => 'api.admin.wbs.destroy'
    ]);
    Route::apiResource('info-kantor', InfoKantorController::class);
    Route::apiResource('portal-papua-tengah', PortalPapuaTengahController::class);
});

// Public API Routes (accessible without authentication)
Route::get('portal-papua-tengah/public', [PortalPapuaTengahController::class, 'publicIndex']);
Route::get('info-kantor/public', [InfoKantorController::class, 'publicIndex']);
Route::post('wbs/public', [WbsController::class, 'publicStore']);

// Public berita endpoint for homepage
Route::get('berita', [PortalPapuaTengahController::class, 'publicBerita']);

// Auth routes
Route::post('auth/login', [AuthController::class, 'login']);

// Public API Routes with versioning (v1 prefix)
Route::prefix('v1')->group(function () {
    // Public routes - no authentication required
    Route::get('portal-papua-tengah', [PortalPapuaTengahController::class, 'index']);
    Route::get('info-kantor', [InfoKantorController::class, 'index']);
    Route::post('wbs', [WbsController::class, 'store']);
    
    // Auth routes
    Route::post('login', [AuthController::class, 'login']);
    
    // Protected routes - require authentication
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
        
        // Dashboard
        Route::get('stats', [DashboardController::class, 'stats']);
        Route::get('wbs-chart', [DashboardController::class, 'wbsChart']);
        
        // Resource routes for admin with v1 prefix
        Route::apiResource('wbs', WbsController::class)->names([
            'index' => 'v1.wbs.index',
            'store' => 'v1.wbs.store',
            'show' => 'v1.wbs.show',
            'update' => 'v1.wbs.update',
            'destroy' => 'v1.wbs.destroy'
        ])->except('store');
        Route::apiResource('info-kantor', InfoKantorController::class)->names([
            'index' => 'v1.info-kantor.index',
            'store' => 'v1.info-kantor.store',
            'show' => 'v1.info-kantor.show',
            'update' => 'v1.info-kantor.update',
            'destroy' => 'v1.info-kantor.destroy'
        ])->except('index');
        Route::apiResource('portal-papua-tengah', PortalPapuaTengahController::class)->names([
            'index' => 'v1.portal-papua-tengah.index',
            'store' => 'v1.portal-papua-tengah.store',
            'show' => 'v1.portal-papua-tengah.show',
            'update' => 'v1.portal-papua-tengah.update',
            'destroy' => 'v1.portal-papua-tengah.destroy'
        ])->except('index');
    });
});
