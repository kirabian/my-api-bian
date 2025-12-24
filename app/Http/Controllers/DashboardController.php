<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user dari session jika ada
        $userId = session('user_id');
        $user = null;

        if ($userId) {
            $user = DB::table('api_developers')->where('id', $userId)->first();
        }

        // Cek apakah request datang dari halaman utama atau folder /dashboard
        // Jika URL adalah dashboard tapi belum login, lempar ke login
        if (request()->is('dashboard') && !$user) {
            return redirect('/v1/login-page');
        }

        // Jika URL adalah dashboard dan sudah login, arahkan ke view dashboard sesuai role
        if (request()->is('dashboard') && $user) {
            if ($user->role === 'admin') {
                $allUsers = DB::table('api_developers')->get();
                return view('dashboard.admin', compact('user', 'allUsers'));
            }
            return view('dashboard.user', compact('user'));
        }

        // Jika URL adalah halaman utama (welcome), tampilkan saja tanpa paksa login
        return view('welcome', compact('user'));
    }
}