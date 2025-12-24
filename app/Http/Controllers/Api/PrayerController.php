<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PrayerController extends Controller
{
    public function getTimes(Request $request)
    {
        $city = $request->query('city', 'Jakarta');
        $country = $request->query('country', 'Indonesia');
        $apiKey = $request->header('X-BIAN-KEY');

        // Logic Hitung Penggunaan jika pakai API Key
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // Ambil data dari API Aladhan (Global)
        $response = Http::get("http://api.aladhan.com/v1/timingsByCity", [
            'city' => $city,
            'country' => $country,
            'method' => 2 // Kemenag / Muslim World League
        ]);

        if ($response->successful()) {
            $data = $response->json()['data'];
            
            return response()->json([
                'status' => 'success',
                'author' => 'BIAN API',
                'location' => [
                    'city' => $city,
                    'country' => $country,
                    'timezone' => $data['meta']['timezone']
                ],
                'timings' => $data['timings'],
                'date' => $data['date']['readable'],
                'limit_info' => $apiKey ? '100/min (Premium)' : '5/min (Free)'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Kota tidak ditemukan'], 404);
    }
}