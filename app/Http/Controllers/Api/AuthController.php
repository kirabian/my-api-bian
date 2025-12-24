<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // API & WEB REGISTER
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:api_developers',
            'password' => 'required'
        ]);

        $apiKey = Str::random(32);

        // User pertama yang daftar otomatis jadi admin, sisanya user biasa
        $count = DB::table('api_developers')->count();
        $role = ($count == 0) ? 'admin' : 'user';

        DB::table('api_developers')->insert([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'api_key' => $apiKey,
            'role' => $role,
            'daily_limit' => 100, // Limit default
            'created_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil daftar!',
            'api_key' => $apiKey,
            'role' => $role
        ]);
    }

    // API & WEB LOGIN
    public function login(Request $request) {
        $dev = DB::table('api_developers')->where('username', $request->username)->first();

        if ($dev && Hash::check($request->password, $dev->password)) {
            // SIMPAN SESSION UNTUK LOGIN WEB
            session([
                'user_id' => $dev->id,
                'role'    => $dev->role,
                'username'=> $dev->username
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'role' => $dev->role,
                'redirect' => '/dashboard' // Arahkan ke dashboard
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Username atau Password salah!'], 401);
    }

    // LOGOUT WEB
    public function logout() {
        session()->flush();
        return redirect('/v1/login-page');
    }
}