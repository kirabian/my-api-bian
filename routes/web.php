<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;

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
    $userId = session('user_id');
    // Jika login dapat 100 req/min, jika publik hanya 5 req/min
    return $userId 
        ? Limit::perMinute(100)->by($userId)
        : Limit::perMinute(5)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama / Landing Page (Bisa diakses tanpa login)
Route::get('/', [DashboardController::class, 'index']);

// Halaman Dokumentasi API (Bisa diakses tanpa login)
Route::get('/docs/v1', function () {
    $user = session('user_id') ? DB::table('api_developers')->where('id', session('user_id'))->first() : null;
    return view('docs.v1', compact('user'));
});

// ROUTE AUTH (Proses di balik layar)
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);

Route::post('/v1/verify-action', [AuthController::class, 'verifyAction']);

// Fitur Revoke Key (Ganti API Key baru)
Route::post('/v1/revoke-key', [AuthController::class, 'revokeKey']);

// HALAMAN UI (Tampilan Form Login & Register)
Route::get('/v1/login-page', function () {
    if (session('user_id')) return redirect('/dashboard');
    return view('auth.login');
})->name('login');

Route::get('/v1/register-page', function () {
    return view('auth.register');
});

// ROUTE DASHBOARD (Proteksi login diproses di DashboardController)
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Endpoints (Dengan Rate Limiting)
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    // API untuk mengambil daftar user
    Route::get('/v1/users', [UserController::class, 'index']);
});