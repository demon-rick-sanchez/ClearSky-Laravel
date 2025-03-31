<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('admin')->group(function () {
    // Public routes
    Route::middleware(['web', 'guest:admin'])->group(function () {
        Route::get('/login', [AdminDashboardController::class, 'login'])->name('admin.login');
        Route::post('/authenticate', [AdminDashboardController::class, 'authenticate'])->name('admin.authenticate');
        Route::get('/register', [AdminDashboardController::class, 'showRegister'])->name('admin.register');
        Route::post('/register', [AdminDashboardController::class, 'register'])->name('admin.store');
    });

    // Protected routes - using full class name to avoid resolution issues
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');
    });

    Route::get('/check-admins', [AdminDashboardController::class, 'listAdmins'])
        ->name('admin.check');
});
