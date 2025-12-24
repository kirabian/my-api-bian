<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // API REGISTER
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:api_developers',
            'password' => 'required'
        ]);

        $apiKey = Str::random(32); // Generate API Key otomatis

        DB::table('api_developers')->insert([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Enkripsi password
            'api_key' => $apiKey,
            'created_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil daftar!',
            'api_key' => $apiKey
        ]);
    }

    // API LOGIN
    public function login(Request $request) {
        $dev = DB::table('api_developers')->where('username', $request->username)->first();

        if ($dev && Hash::check($request->password, $dev->password)) {
            return response()->json([
                'status' => 'success',
                'username' => $dev->username,
                'api_key' => $dev->api_key,
                'limit' => $dev->daily_limit
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Login gagal!'], 401);
    }
}