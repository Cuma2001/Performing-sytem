<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreTargetUploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Main Dashboard (routes to role-specific view)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-specific direct access routes (optional)
    Route::get('/dashboard/ceo-hr', [DashboardController::class, 'ceoHrDashboard'])->name('dashboard.ceo-hr');
    Route::get('/dashboard/supervisor', [DashboardController::class, 'supervisorDashboard'])->name('dashboard.supervisor');
    Route::get('/dashboard/salesperson', [DashboardController::class, 'salespersonDashboard'])->name('dashboard.salesperson');
});

/*
|--------------------------------------------------------------------------
| Utility Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('utility')->group(function () {
    
    Route::get('/', [StoreTargetUploadController::class, 'utilities'])->name('utility');
    Route::get('master-upload', [StoreTargetUploadController::class, 'index'])->name('utility.master-upload');
    Route::post('master-upload', [StoreTargetUploadController::class, 'store'])
    ->name('utility.master-upload.store');
    Route::post('master-preview', [StoreTargetUploadController::class, 'preview'])->name('utility.master-preview');
    Route::post('master-validate', [StoreTargetUploadController::class, 'validate'])->name('utility.master-validate');
    Route::post('master-process', [StoreTargetUploadController::class, 'process'])->name('utility.master-process');
    Route::get('master-history', [StoreTargetUploadController::class, 'history'])->name('utility.master-history');
    Route::get('master-upload/{id}', [StoreTargetUploadController::class, 'show'])->name('utility.master-upload.show');
    Route::get('master-template', [StoreTargetUploadController::class, 'downloadTemplate'])->name('utility.master-template');
});

// Add to your utility routes group
Route::get('utility/history-data', [StoreTargetUploadController::class, 'getHistoryData'])->name('utility.history-data');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Registration
Route::middleware(['guest'])->group(function () {
    Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.store');
    
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.store');
});

// Logout
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});