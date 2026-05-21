<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KPIController;
use App\Http\Controllers\EmployeeKPIController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
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
    ->name('registerStore');

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
});
