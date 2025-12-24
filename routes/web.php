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
use App\Http\Controllers\Api\HardwareController;

/*
|--------------------------------------------------------------------------
| Rate Limiter Configuration
|--------------------------------------------------------------------------
*/

RateLimiter::for('api-limiter', function (Request $request) {
    // 1. Cek Header API Key (Prioritas Utama)
    $apiKey = $request->header('X-BIAN-KEY');
    
    if ($apiKey) {
        $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
        if ($user) {
            // Member Premium: 100 Req / Min
            return Limit::perMinute(100)->by($user->id);
        }
    }

    // 2. Cek Session User (Jika login via Web)
    $userId = session('user_id');
    if ($userId) {
        return Limit::perMinute(100)->by($userId);
    }

    // 3. Default Public Limit: 5 Req / Min
    return Limit::perMinute(5)->by($request->ip());
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Utama & Docs
Route::get('/', [DashboardController::class, 'index']);
Route::get('/docs/v1', function () {
    $user = session('user_id') ? DB::table('api_developers')->where('id', session('user_id'))->first() : null;
    return view('docs.v1', compact('user'));
});

// Auth Routes
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);
Route::post('/v1/verify-action', [AuthController::class, 'verifyAction']);

// UI Pages
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
| API Endpoints (Dengan Rate Limiting & Custom Error 429)
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    Route::get('/v1/users', [UserController::class, 'index']);
    Route::get('/v1/prayer-times', [PrayerController::class, 'getTimes']);
    Route::get('/v1/hardware/prices', [HardwareController::class, 'getPrices']);
});