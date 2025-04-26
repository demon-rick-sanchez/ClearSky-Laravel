<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SimulationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Add API routes for sensor data
Route::get('/api/sensors', [DashboardController::class, 'getSensors']);
Route::get('/api/sensors/{sensor}/readings', [DashboardController::class, 'getSensorReadings']);
Route::get('/api/alerts', [DashboardController::class, 'getAlerts']);

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
        Route::get('/simulation', [SimulationController::class, 'index'])->name('admin.simulation');
        Route::post('/simulation/generate', [SimulationController::class, 'generateData'])->name('admin.simulation.generate');
        Route::get('/alerts', [AdminDashboardController::class, 'alerts'])->name('admin.alerts');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
        Route::post('/logout', [AdminDashboardController::class, 'logout'])->name('admin.logout');
        Route::delete('/admins/{admin}', [AdminDashboardController::class, 'destroy'])->name('admin.destroy');

        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/profile/password', [AdminDashboardController::class, 'updatePassword'])->name('admin.profile.password');

        Route::prefix('sensors')->group(function () {
            Route::get('/{sensor}/edit', [AdminDashboardController::class, 'editSensor'])->name('admin.sensors.edit');
            Route::put('/{sensor}', [AdminDashboardController::class, 'updateSensor'])->name('admin.sensors.update');
            Route::put('/{sensor}/status', [AdminDashboardController::class, 'updateSensorStatus'])->name('admin.sensors.status');
            Route::delete('/{sensor}', [AdminDashboardController::class, 'deleteSensor'])->name('admin.sensors.delete');
        });

        Route::prefix('simulation')->group(function () {
            Route::post('/{sensor}/settings', [SimulationController::class, 'updateSettings']);
            Route::post('/{sensor}/toggle', [SimulationController::class, 'toggleSimulation']);
            Route::get('/{sensor}/logs', [SimulationController::class, 'getSimulationLogs']);
        });
    });

    Route::get('/check-admins', [AdminDashboardController::class, 'listAdmins'])
        ->name('admin.check');
});
