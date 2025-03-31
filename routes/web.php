<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/login', [AdminDashboardController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminDashboardController::class, 'authenticate'])->name('admin.authenticate');
    Route::get('/register', [AdminDashboardController::class, 'showRegister'])->name('admin.register');
    Route::post('/register', [AdminDashboardController::class, 'register'])->name('admin.store');
});
