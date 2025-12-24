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
| Di sini kita mengatur batas penggunaan API.
| Jika user login (ada session), mereka dapat 100 request/menit.
| Jika tidak login, hanya dapat 5 request/menit berdasarkan IP.
*/

RateLimiter::for('api-limiter', function (Request $request) {
    if (session('user_id')) {
        return Limit::perMinute(100)->by(session('user_id'));
    }
    return Limit::perMinute(5)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Halaman Utama / Landing Page Dokumentasi
Route::get('/', [DashboardController::class, 'index']);

// ROUTE AUTH (Tanpa CSRF karena sudah didaftarkan di VerifyCsrfToken)
// Digunakan untuk proses pendaftaran dan masuk sistem
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

// ROUTE DASHBOARD (Hanya bisa dibuka jika sudah login)
// Middleware manual untuk mengecek session user_id
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(function ($request, $next) {
    if (!session('user_id')) {
        return redirect('/v1/login-page');
    }
    return $next($request);
});

/*
|--------------------------------------------------------------------------
| API Endpoints (Dengan Rate Limiting)
|--------------------------------------------------------------------------
| Semua route di dalam grup ini akan dibatasi oleh 'api-limiter'
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    
    // API untuk mengambil daftar user
    Route::get('/v1/users', [UserController::class, 'index']);
    
    // Anda bisa menambah endpoint API lainnya di bawah ini
    // Route::get('/v1/data', [DataController::class, 'index']);

});