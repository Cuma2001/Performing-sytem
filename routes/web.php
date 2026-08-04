<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreTargetUploadController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\KPIController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegionController;
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
| Stores Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('stores', StoreController::class);
});

/*
|--------------------------------------------------------------------------
| Region Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('region', RegionController::class);
    Route::resource('users', UserController::class);
});


/*
|--------------------------------------------------------------------------
| KPI Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('kpis', KPIController::class);
    Route::get('kpi-distribution', [KPIController::class, 'distribution'])->name('kpi.distribution');
    Route::get('kpi-upload', [KPIController::class, 'upload'])->name('kpi.upload');
});

/*
|--------------------------------------------------------------------------
| User Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});

/*
|--------------------------------------------------------------------------
| Region Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('regions', \App\Http\Controllers\RegionController::class);
});

/*
|--------------------------------------------------------------------------
| Employee Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
});

/*
|--------------------------------------------------------------------------
| Utility Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::prefix('utilities')->group(function () {
        Route::get('/', [StoreTargetUploadController::class, 'utilities'])->name('utilities.index');
        Route::get('/master-upload', [StoreTargetUploadController::class, 'index'])->name('utility.master-upload');
        
        // AJAX routes
        Route::post('/master-process', [StoreTargetUploadController::class, 'process'])->name('utility.master-process');
        Route::get('/master-history', [StoreTargetUploadController::class, 'getHistory'])->name('utility.master-history');
        
        // History routes
        Route::get('/history', [StoreTargetUploadController::class, 'history'])->name('utility.history');
        Route::get('/history/{id}', [StoreTargetUploadController::class, 'show'])->name('utility.history.show');
        
        // Template download
        Route::get('/template', [StoreTargetUploadController::class, 'downloadTemplate'])->name('utility.template');
        
        // File management
        Route::post('/retry/{id}', [StoreTargetUploadController::class, 'retry'])->name('utility.retry');
        Route::delete('/delete/{id}', [StoreTargetUploadController::class, 'delete'])->name('utility.delete');
        
        // Stats
        Route::get('/stats', [StoreTargetUploadController::class, 'stats'])->name('utility.stats');
    });
});

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
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password');
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
| Reports Module (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('reports')->group(function () {
    Route::get('/', function () {
        return view('reports.index');
    })->name('reports.index');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});


Route::middleware(['auth'])->group(function () {
    // User Management Routes
    Route::resource('users', UserController::class);
    
    // Additional User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{user}/lock', [UserController::class, 'lock'])->name('lock');
        Route::patch('/{user}/unlock', [UserController::class, 'unlock'])->name('unlock');
        Route::patch('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
        
        // ADD THIS LINE FOR EXPORT
        Route::get('/export', [UserController::class, 'export'])->name('export');
    });
});


