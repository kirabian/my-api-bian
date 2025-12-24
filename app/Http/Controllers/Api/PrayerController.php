<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PrayerController extends Controller
{
    /**
     * Mengambil jadwal sholat dengan output yang telah disamarkan (Masked).
     */
    public function getTimes(Request $request)
    {
        $city = $request->query('city', 'Jakarta');
        $country = $request->query('country', 'Indonesia');
        $apiKey = $request->header('X-BIAN-KEY');

        // 1. Validasi & Hitung Penggunaan Limit
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Ambil data dari sumber (Backend Aladhan)
        $response = Http::get("http://api.aladhan.com/v1/timingsByCity", [
            'city' => $city,
            'country' => $country,
            'method' => 2 
        ]);

        if ($response->successful()) {
            $raw = $response->json()['data'];
            
            // 3. Transformasi Data: Ubah nama key agar tidak bisa ditebak
            return response()->json([
                'status' => 200,
                'creator' => 'BIAN DEVELOPER STUDIO',
                'endpoint_v' => '1.0-stable',
                'result' => [
                    'info_lokasi' => [
                        'nama_kota' => strtoupper($city),
                        'wilayah' => $country,
                        'zona_waktu' => $raw['meta']['timezone']
                    ],
                    'jadwal' => [
                        'subuh'   => $raw['timings']['Fajr'],
                        'terbit'  => $raw['timings']['Sunrise'],
                        'dzuhur'  => $raw['timings']['Dhuhr'],
                        'ashar'   => $raw['timings']['Asr'],
                        'maghrib' => $raw['timings']['Maghrib'],
                        'isya'    => $raw['timings']['Isha'],
                    ],
                    'kalender' => [
                        'masehi'  => $raw['date']['readable'],
                        'hijriah' => $raw['date']['hijri']['day'] . ' ' . $raw['date']['hijri']['month']['en'] . ' ' . $raw['date']['hijri']['year']
                    ]
                ],
                'server_info' => [
                    'limit_type' => $apiKey ? 'PREMIUM_MEMBER' : 'FREE_PUBLIC',
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'Data lokasi tidak ditemukan dalam database kami.'
        ], 404);
    }
}