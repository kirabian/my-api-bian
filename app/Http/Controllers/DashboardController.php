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

    // Jika sedang mencoba akses folder /dashboard tapi belum login
    if (request()->is('dashboard') && !$user) {
        return redirect('/v1/login-page');
    }

    // Jika sudah login dan akses dashboard
    if (request()->is('dashboard') && $user) {
        if ($user->role === 'admin') {
            $allUsers = DB::table('api_developers')->get();
            return view('dashboard.admin', compact('user', 'allUsers'));
        }
        return view('dashboard.user', compact('user'));
    }

    // Tampilkan Landing Page Welcome
    return view('welcome', compact('user'));
}
}