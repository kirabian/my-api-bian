<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GempaController extends Controller
{
    public function getGempa(Request $request)
    {
        $apiKey = $request->header('X-BIAN-KEY');

        // 1. Hitung Limit jika menggunakan API Key
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Cache data selama 5 menit agar tidak terus-menerus hit BMKG
        $data = Cache::remember('info_gempa_bian', 300, function () {
            return $this->fetchBmkgData();
        });

        if (!$data) {
            return response()->json(['status' => 500, 'message' => 'Gagal mengambil data BMKG'], 500);
        }

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'endpoint' => 'v1/info/gempa',
            'result' => $data,
            'server_info' => [
                'last_update' => now()->toDateTimeString(),
                'limit_info' => $apiKey ? '100/min' : '10/min'
            ]
        ]);
    }

    private function fetchBmkgData()
    {
        try {
            // Ambil data XML dari BMKG (Gratis & Terpercaya)
            $response = Http::get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $gempa = $xml->gempa;

                // Transformasi ke format BIAN API (Masking)
                return [
                    'waktu_kejadian' => (string) $gempa->Tanggal . ' - ' . (string) $gempa->Jam,
                    'skala_magnitudo' => (string) $gempa->Magnitude . ' SR',
                    'kedalaman_gempa' => (string) $gempa->Kedalaman,
                    'pusat_koordinat' => (string) $gempa->Coordinates,
                    'titik_lokasi' => (string) $gempa->Wilayah,
                    'peringatan' => (string) $gempa->Potensi,
                    'dirasakan_di' => (string) $gempa->Dirasakan,
                    'peta_visual' => "https://data.bmkg.go.id/DataMKG/TEWS/" . (string) $gempa->Shakemap
                ];
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}