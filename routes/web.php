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
        Route::get('/sensors', [AdminDashboardController::class, 'sensors'])->name('admin.sensors');
        Route::post('/sensors/generate-id', [AdminDashboardController::class, 'generateSensorId'])->name('admin.generate-sensor-id');
        Route::post('/sensors/store', [AdminDashboardController::class, 'storeSensor'])->name('admin.sensors.store');
        Route::get('/simulation', [AdminDashboardController::class, 'simulation'])->name('admin.simulation');
        Route::get('/alerts', [AdminDashboardController::class, 'alerts'])->name('admin.alerts');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
        Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');
        Route::delete('/admins/{admin}', [AdminDashboardController::class, 'destroy'])->name('admin.destroy');
    });

    Route::get('/check-admins', [AdminDashboardController::class, 'listAdmins'])
        ->name('admin.check');
});
