<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

// Halaman Utama (Welcome)
Route::get('/', function () {
    return view('welcome');
});

// ROUTE AUTH (Tanpa CSRF karena sudah didaftarkan di VerifyCsrfToken)
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);

// HALAMAN LOGIN & REGISTER (Tampilan Web)
Route::get('/v1/login-page', function () {
    return view('auth.login'); // Nanti kita buat file view-nya
});
Route::get('/v1/register-page', function () {
    return view('auth.register'); 
});

// ROUTE DASHBOARD (Hanya bisa dibuka jika sudah login)
Route::get('/dashboard', [DashboardController::class, 'index']);