<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah sudah login
        if (!session('user_id')) {
            return redirect('/v1/login-page');
        }

        $user = DB::table('api_developers')->where('id', session('user_id'))->first();
        
        // Jika admin, tampilkan view admin, jika user tampilkan view user
        if ($user->role === 'admin') {
            $allUsers = DB::table('api_developers')->get();
            return view('dashboard.admin', compact('user', 'allUsers'));
        }

        return view('dashboard.user', compact('user'));
    }
}