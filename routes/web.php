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
| Mengatur limit: 100/menit untuk user terdaftar, 5/menit untuk publik.
*/

RateLimiter::for('api-limiter', function (Request $request) {
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

// Halaman Utama / Landing Page (Akses Tanpa Login)
Route::get('/', [DashboardController::class, 'index']);

// Halaman Dokumentasi API (Akses Tanpa Login)
Route::get('/docs/v1', function () {
    $user = session('user_id') ? DB::table('api_developers')->where('id', session('user_id'))->first() : null;
    return view('docs.v1', compact('user'));
});

// ROUTE AUTH (Proses Backend)
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);

// Keamanan Satu Pintu: Digunakan untuk Show, Copy, dan Revoke API Key
Route::post('/v1/verify-action', [AuthController::class, 'verifyAction']);

// HALAMAN UI (Tampilan Form Login & Register)
Route::get('/v1/login-page', function () {
    // Jika sudah login, jangan tampilkan form login, langsung ke dashboard
    if (session('user_id')) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('login');

Route::get('/v1/register-page', function () {
    // Jika sudah login, langsung ke dashboard
    if (session('user_id')) {
        return redirect('/dashboard');
    }
    return view('auth.register');
});

// ROUTE DASHBOARD (Proteksi login diproses di dalam DashboardController)
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Endpoints (Dengan Rate Limiting)
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    // Endpoint API untuk mengambil daftar user
    Route::get('/v1/users', [UserController::class, 'index']);
});