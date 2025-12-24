<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menangani tampilan Landing Page (/) dan Dashboard (/dashboard).
     */
    public function index()
    {
        // 1. Identifikasi User dari Session
        $userId = session('user_id');
        $user = null;

        if ($userId) {
            $user = DB::table('api_developers')->where('id', $userId)->first();
        }

        // 2. Logika Proteksi untuk URL '/dashboard'
        if (request()->is('dashboard')) {
            // Jika mencoba akses dashboard tapi belum login, lempar ke login-page
            if (!$user) {
                return redirect('/v1/login-page');
            }

            // Jika session ada tapi data di database hilang (kasus khusus)
            if (!$user) {
                session()->flush();
                return redirect('/v1/login-page');
            }

            // Tampilkan Dashboard sesuai Role (Admin atau User Biasa)
            if ($user->role === 'admin') {
                $allUsers = DB::table('api_developers')->get();
                return view('dashboard.admin', compact('user', 'allUsers'));
            }
            
            return view('dashboard.user', compact('user'));
        }

        // 3. Logika untuk URL Landing Page (/)
        // Pengunjung bisa melihat welcome page tanpa harus login
        return view('welcome', compact('user'));
    }
}