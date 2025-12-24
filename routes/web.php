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
use App\Http\Controllers\Api\PrayerController;

/*
|--------------------------------------------------------------------------
| Rate Limiter Configuration
|--------------------------------------------------------------------------
| Diperbarui untuk mendukung pengecekan API Key lewat Header (X-BIAN-KEY)
| Jika ada API Key valid: 100 Req/Min
| Jika tidak ada atau tidak valid: 5 Req/Min
*/

RateLimiter::for('api-limiter', function (Request $request) {
    // 1. Cek apakah ada API Key di Header (X-BIAN-KEY)
    $apiKey = $request->header('X-BIAN-KEY');
    
    if ($apiKey) {
        $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
        if ($user) {
            // User Terdaftar & Valid: Beri 100 limit
            return Limit::perMinute(100)->by($user->id);
        }
    }

    // 2. Jika tidak ada Header, cek Session (untuk akses Dashboard/Web)
    $userId = session('user_id');
    if ($userId) {
        return Limit::perMinute(100)->by($userId);
    }

    // 3. User Publik (Tanpa Key & Tanpa Login): 5 limit per IP
    return Limit::perMinute(5)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama / Landing Page
Route::get('/', [DashboardController::class, 'index']);

// Halaman Dokumentasi
Route::get('/docs/v1', function () {
    $user = session('user_id') ? DB::table('api_developers')->where('id', session('user_id'))->first() : null;
    return view('docs.v1', compact('user'));
});

// ROUTE AUTH
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);
Route::post('/v1/verify-action', [AuthController::class, 'verifyAction']);

// UI Pages dengan Proteksi Redirect Terpusat
Route::get('/v1/login-page', function () {
    if (session('user_id')) return redirect('/dashboard');
    return view('auth.login');
})->name('login');

Route::get('/v1/register-page', function () {
    if (session('user_id')) return redirect('/dashboard');
    return view('auth.register');
});

// Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Endpoints (Dengan Rate Limiting Hybrid)
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    // API User List
    Route::get('/v1/users', [UserController::class, 'index']);
    
    // API Jadwal Sholat (Global)
    Route::get('/v1/prayer-times', [PrayerController::class, 'getTimes']);
});