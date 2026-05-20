<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KPIController;
use App\Http\Controllers\EmployeeKPIController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    // If there are no users yet, send the visitor to registration.
    // If users exist, send to the login page.
    if (User::count() === 0) {
        return redirect()->route('register');
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login-store', [LoginController::class, 'login'])
    ->name('loginStore');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Register Routes
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisterController::class, 'showRegisterForm'])
    ->name('register');

Route::post('/register-store', [RegisterController::class, 'register'])
    ->name('register.store');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('kpis', KPIController::class);

    Route::resource('employee-kpis', EmployeeKPIController::class);

    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
            ->name('admin.dashboard');
    });
});