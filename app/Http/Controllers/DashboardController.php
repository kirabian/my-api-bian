<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cek session. Jika tidak ada, lempar ke halaman login
        if (!session('user_id')) {
            return redirect('/v1/login-page');
        }

        // 2. Ambil data user
        $user = DB::table('api_developers')->where('id', session('user_id'))->first();
        
        // Jaga-jaga jika session ada tapi data di DB dihapus
        if (!$user) {
            session()->flush();
            return redirect('/v1/login-page');
        }

        // 3. Tampilkan halaman berdasarkan role
        if ($user->role === 'admin') {
            $allUsers = DB::table('api_developers')->get();
            return view('dashboard.admin', compact('user', 'allUsers'));
        }

        return view('dashboard.user', compact('user'));
    }
}