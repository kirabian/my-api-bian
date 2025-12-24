<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * PROSES DAFTAR (REGISTER)
     * Menangani pendaftaran user baru dan pembuatan API Key pertama.
     */
    public function register(Request $request) {
        $request->validate([
            'username' => 'required|unique:api_developers',
            'password' => 'required'
        ]);

        $apiKey = Str::random(32);
        
        // Cek apakah ini user pertama (akan dijadikan admin)
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

        return response()->json([
            'status' => 'success', 
            'message' => 'Berhasil daftar!',
            'api_key' => $apiKey
        ]);
    }

    /**
     * PROSES MASUK (LOGIN)
     * Membuat session untuk akses Dashboard Web.
     */
    public function login(Request $request) {
        $dev = DB::table('api_developers')->where('username', $request->username)->first();
        
        if ($dev && Hash::check($request->password, $dev->password)) {
            // Simpan data ke session
            session([
                'user_id' => $dev->id, 
                'role' => $dev->role, 
                'username' => $dev->username
            ]);
            
            // Mengirimkan instruksi redirect ke JavaScript
            return response()->json([
                'status' => 'success', 
                'redirect' => '/dashboard'
            ]);
        }
        
        // Mengirimkan error jika data tidak cocok
        return response()->json([
            'status' => 'error', 
            'message' => 'Username atau Password salah!'
        ], 401);
    }

    /**
     * FUNGSI KEAMANAN: Verify Action
     * Digunakan untuk Show, Copy, atau Revoke API Key.
     * Mewajibkan konfirmasi password ulang.
     */
    public function verifyAction(Request $request) {
        // Validasi input
        $request->validate([
            'password' => 'required',
            'action' => 'required'
        ]);

        $dev = DB::table('api_developers')->where('id', session('user_id'))->first();

        // Verifikasi apakah user sah dan password benar
        if ($dev && Hash::check($request->password, $dev->password)) {
            
            // Jika user memilih untuk mengganti key (Revoke)
            if ($request->action == 'revoke') {
                $newKey = Str::random(32);
                
                // Update key baru di database (limit request_count tetap lanjut)
                DB::table('api_developers')
                    ->where('id', session('user_id'))
                    ->update(['api_key' => $newKey]);
                
                return response()->json([
                    'status' => 'success', 
                    'key' => $newKey
                ]);
            }
            
            // Jika user hanya ingin melihat (Show) atau menyalin (Copy)
            return response()->json([
                'status' => 'success', 
                'key' => $dev->api_key
            ]);
        }
        
        // Jika password salah
        return response()->json([
            'status' => 'error', 
            'message' => 'Password salah!'
        ], 403);
    }

    /**
     * PROSES KELUAR (LOGOUT)
     * Menghapus semua session user.
     */
    public function logout() {
        session()->flush();
        return redirect('/v1/login-page');
    }
}