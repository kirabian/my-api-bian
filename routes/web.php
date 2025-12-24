<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// Halaman Utama / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::get('/v1/logout', [AuthController::class, 'logout']);

// UI Pages
Route::get('/v1/login-page', function () {
    if (session('user_id')) return redirect('/dashboard'); // Jika sudah login, jangan ke login lagi
    return view('auth.login');
})->name('login');

Route::get('/v1/register-page', function () {
    return view('auth.register');
});

// Dashboard Route (Simpel tanpa middleware closure)
Route::get('/dashboard', [DashboardController::class, 'index']);

// API Endpoints
Route::get('/v1/users', [UserController::class, 'index']);