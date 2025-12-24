<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
    public function index() {
        // Ambil data user jika sudah login
        $user = null;
        if (session('user_id')) {
            $user = DB::table('api_developers')->where('id', session('user_id'))->first();
        }
        return view('welcome', compact('user'));
    }
}