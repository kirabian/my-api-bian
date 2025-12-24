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
use App\Http\Controllers\Api\GempaController;
use App\Http\Controllers\Api\ToolsController;

/*
|--------------------------------------------------------------------------
| Rate Limiter Configuration
|--------------------------------------------------------------------------
*/

RateLimiter::for('api-limiter', function (Request $request) {
    // 1. Cek Header API Key
    $apiKey = $request->header('X-BIAN-KEY');
    
    if ($apiKey) {
        $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
        if ($user) {
            return Limit::perMinute(100)->by($user->id);
        }
    }

    // 2. Cek Session User
    $userId = session('user_id');
    if ($userId) {
        return Limit::perMinute(100)->by($userId);
    }

    // 3. Default Public Limit
    return Limit::perMinute(10)->by($request->ip());
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

// Dashboard Protected
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Endpoints (Dengan Rate Limiting & Proxy)
|--------------------------------------------------------------------------
*/

Route::middleware(['throttle:api-limiter'])->group(function () {
    Route::get('/v1/users', [UserController::class, 'index']);
    Route::get('/v1/prayer-times', [PrayerController::class, 'getTimes']);
    Route::get('/v1/info/gempa', [GempaController::class, 'getGempa']);
    Route::get('/v1/info/gempa/map.jpg', [GempaController::class, 'getGempaMap']);

    // Endpoints Tools
    Route::get('/v1/tools/shorten', [ToolsController::class, 'shortenUrl']);
    Route::get('/v1/tools/ssweb', [ToolsController::class, 'screenshotWeb']);
    
    // Proxy Gambar Baru - Menggunakan Path Parameter agar tidak tertukar
    Route::get('/v1/tools/ssweb/view/{encodedUrl}/image.jpg', [ToolsController::class, 'getScreenshotImage']);
});