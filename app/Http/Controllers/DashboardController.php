<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user = null;

        if ($userId) {
            $user = DB::table('api_developers')->where('id', $userId)->first();
        }

        // Cek URL saat ini
        $path = request()->path();

        // JIKA MENGAKSES /dashboard
        if ($path === 'dashboard') {
            if (!$user) {
                return redirect('/v1/login-page');
            }

            if ($user->role === 'admin') {
                $allUsers = DB::table('api_developers')->get();
                return view('dashboard.admin', compact('user', 'allUsers'));
            }
            return view('dashboard.user', compact('user'));
        }

        // JIKA MENGAKSES / (Welcome Page)
        return view('welcome', compact('user'));
    }
}