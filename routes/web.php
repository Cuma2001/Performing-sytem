<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\StoreTargetUploadController;
use App\Mail\OTPVerification;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);

// utilities storeUploads
// Store Target Upload Routes
    Route::get('utilities', [StoreTargetUploadController::class, 'utilities'])->name('utility');

Route::prefix('utility')->middleware(['auth'])->group(function () {
    Route::get('master-upload', [StoreTargetUploadController::class, 'index'])->name('utility.master-upload');
    Route::post('master-preview', [StoreTargetUploadController::class, 'preview'])->name('utility.master-preview');
    Route::post('master-validate', [StoreTargetUploadController::class, 'validate'])->name('utility.master-validate');
    Route::post('master-process', [StoreTargetUploadController::class, 'process'])->name('utility.master-process');
    Route::get('master-history', [StoreTargetUploadController::class, 'history'])->name('utility.master-history');
    Route::get('master-upload/{id}', [StoreTargetUploadController::class, 'show'])->name('utility.master-upload.show');
    Route::get('master-template', [StoreTargetUploadController::class, 'downloadTemplate'])->name('utility.master-template');
});

/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
*/

Route::get('register', [RegisterController::class, 'showRegisterForm'])
    ->name('register');

Route::post('register', [RegisterController::class, 'register'])
    ->name('register.store');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('loginStore', [LoginController::class, 'login'])
    ->name('loginStore');

Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Password Reset
|--------------------------------------------------------------------------
*/

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('password/reset/{id}/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset.form');

Route::post('password/reset', [ForgotPasswordController::class, 'reset'])
    ->name('password.update');


Route::get('/otp/verify', [OTPController::class, 'showVerifyForm'])
    ->name('otp.verify');

Route::post('/otp/verify', [OTPController::class, 'verify'])
    ->name('otp.verify.submit');
/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});
