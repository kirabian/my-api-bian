<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // PROSES DAFTAR
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:api_developers',
            'password' => 'required'
        ]);

        $apiKey = Str::random(32);
        $count = DB::table('api_developers')->count();
        $role = ($count == 0) ? 'admin' : 'user';

        DB::table('api_developers')->insert([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'api_key' => $apiKey,
            'role' => $role,
            'daily_limit' => 100,
            'request_count' => 0,
            'created_at' => now()
        ]);

        return response()->json(['status' => 'success', 'api_key' => $apiKey]);
    }

    // PROSES MASUK
    public function login(Request $request) {
        $dev = DB::table('api_developers')->where('username', $request->username)->first();
        if ($dev && Hash::check($request->password, $dev->password)) {
            session([
                'user_id' => $dev->id, 
                'role' => $dev->role, 
                'username' => $dev->username
            ]);
            return response()->json(['status' => 'success', 'redirect' => '/dashboard']);
        }
        return response()->json(['status' => 'error', 'message' => 'Login gagal!'], 401);
    }

    // FUNGSI KEAMANAN: Show, Copy, & Revoke API Key
    // Pastikan ini ada di dalam class AuthController { ... }
public function verifyAction(Request $request) {
    $request->validate(['password' => 'required', 'action' => 'required']);
    $dev = DB::table('api_developers')->where('id', session('user_id'))->first();

    if ($dev && Hash::check($request->password, $dev->password)) {
        if ($request->action == 'revoke') {
            $newKey = Str::random(32);
            DB::table('api_developers')->where('id', session('user_id'))->update(['api_key' => $newKey]);
            return response()->json(['status' => 'success', 'key' => $newKey]);
        }
        return response()->json(['status' => 'success', 'key' => $dev->api_key]);
    }
    return response()->json(['status' => 'error', 'message' => 'Password salah!'], 403);
}

    // KELUAR SISTEM
    public function logout() {
        session()->flush();
        return redirect('/v1/login-page');
    }
}