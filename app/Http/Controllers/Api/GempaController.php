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

        // 1. Hitung Limit Penggunaan
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Ambil data (Cache dikurangi ke 60 detik agar lebih akurat dengan BMKG)
        $data = Cache::remember('info_gempa_bian_v2', 60, function () {
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
                'cache_status' => 'Hit/Live'
            ]
        ]);
    }

    // Fungsi baru untuk Masking URL Gambar ke domain sendiri
    public function getGempaMap()
    {
        try {
            $response = Http::get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $mapFileName = (string) $xml->gempa->Shakemap;
                $mapUrl = "https://data.bmkg.go.id/DataMKG/TEWS/" . $mapFileName;

                $imageContent = Http::get($mapUrl)->body();
                return response($imageContent)->header('Content-Type', 'image/jpeg');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Peta tidak tersedia'], 404);
        }
    }

    private function fetchBmkgData()
    {
        try {
            $response = Http::withoutVerifying()->get("https://data.bmkg.go.id/DataMKG/TEWS/autogempa.xml");

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $gempa = $xml->gempa;

                return [
                    'waktu_kejadian' => (string) $gempa->Tanggal . ' - ' . (string) $gempa->Jam,
                    'skala_magnitudo' => (string) $gempa->Magnitude . ' SR',
                    'kedalaman_gempa' => (string) $gempa->Kedalaman,
                    'pusat_koordinat' => (string) $gempa->Coordinates,
                    'titik_lokasi' => (string) $gempa->Wilayah,
                    'peringatan' => (string) $gempa->Potensi,
                    'dirasakan_di' => (string) $gempa->Dirasakan,
                    // URL dialihkan ke domain sendiri
                    'peta_visual' => url('/v1/info/gempa/map.jpg')
                ];
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}