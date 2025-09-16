<?php

use Illuminate\Support\Facades\Route;

// Test route for logo component
Route::get('/test-logo', function () {
    return view('test-logo');
})->name('test.logo');