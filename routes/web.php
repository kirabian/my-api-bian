<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

// Import Controller
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Rate Limiter Configuration
|--------------------------------------------------------------------------
*/

RateLimiter::for('api-limiter', function (Request $request) {
    // Gunakan null coalescing untuk menghindari error jika session kosong
    $userId = session('user_id');
    return $userId 
        ? Limit::perMinute(100)->by($userId)
        : Limit::perMinute(5)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama / Landing Page Dokumentasi
Route::get('/', [DashboardController::class, 'index']);

// ROUTE AUTH
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);

// HALAMAN LOGIN & REGISTER (Tampilan UI Web)
Route::get('/v1/login-page', function () {
    return view('auth.login');
})->name('login');

Route::get('/v1/register-page', function () {
    return view('auth.register');
});

// ROUTE DASHBOARD
// Kita hapus Closure Middleware dan pindahkan logika proteksi ke DashboardController
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Endpoints (Dengan Rate Limiting)
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    Route::get('/v1/users', [UserController::class, 'index']);
});