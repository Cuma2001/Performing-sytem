<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('login');
});
// Route::get('/welcome', function () {
//     return Redirect::to('login');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('registerStore',[RegisteredUserController::class, 'store'])->name('registerStore');
Route::get('registerUser', [RegisteredUserController::class, 'create'])->name('registerUser');
Route::post('registerUser', [RegisteredUserController::class, 'store']);

// Route::get('login',[LoginController::class, 'showLoginForm'])->name('login');
Route::post('loginStore',[LoginController::class, 'login'])->name('loginStore');
Route::post('logout',[LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{id}/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
