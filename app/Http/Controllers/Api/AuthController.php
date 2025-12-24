<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // PROSES REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:api_developers',
            'password' => 'required',
        ]);

        $apiKey = Str::random(32); // Generate API Key otomatis

        // User pertama jadi admin, selanjutnya user biasa
        $count = DB::table('api_developers')->count();
        $role = ($count == 0) ? 'admin' : 'user';

        DB::table('api_developers')->insert([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'api_key' => $apiKey,
            'role' => $role,
            'daily_limit' => 100, // Limit default untuk member
            'request_count' => 0,
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil daftar!',
            'api_key' => $apiKey,
            'role' => $role,
        ]);
    }

    // PROSES LOGIN
    public function login(Request $request)
    {
        $dev = DB::table('api_developers')->where('username', $request->username)->first();

        if ($dev && Hash::check($request->password, $dev->password)) {
            // Simpan data ke session untuk akses web
            session([
                'user_id' => $dev->id,
                'role' => $dev->role,
                'username' => $dev->username,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'role' => $dev->role,
                'redirect' => '/dashboard',
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Username atau Password salah!'], 401);
    }

    // PROSES REVOKE KEY (GANTI KEY BARU)
    public function revokeKey()
    {
        if (! session('user_id')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $newKey = Str::random(32);
        DB::table('api_developers')
            ->where('id', session('user_id'))
            ->update(['api_key' => $newKey]);

        return response()->json([
            'status' => 'success',
            'new_key' => $newKey,
        ]);
    }

    // PROSES LOGOUT
    public function logout()
    {
        session()->flush(); // Hapus semua session

        return redirect('/v1/login-page');
    }
}
