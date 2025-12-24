<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HardwareController extends Controller
{
    public function getPrices(Request $request)
    {
        $type = $request->query('type', 'ssd');
        $apiKey = $request->header('X-BIAN-KEY');

        // 1. Validasi & Catat Penggunaan API Key
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Caching Data Selama 1 Jam (Gunakan cache:clear untuk tes ulang)
        $data = Cache::remember('hardware_live_final_' . $type, 3600, function () use ($type) {
            return $this->fetchFromEbay($type);
        });

        if (!$data) {
            return response()->json([
                'status' => 503,
                'message' => 'Gagal sinkronisasi data global. Cek kuota RapidAPI atau koneksi server.',
                'debug_info' => 'Periksa storage/logs/laravel.log untuk detail error.'
            ], 503);
        }

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'kategori' => strtoupper($type),
                'currency' => 'USD',
                'data' => $data
            ],
            'server_info' => [
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    private function fetchFromEbay($type)
    {
        try {
            // URL Path dinamis berdasarkan input user
            $url = "https://ebay-search-result.p.rapidapi.com/search/" . urlencode($type);

            $response = Http::withHeaders([
                'x-rapidapi-host' => 'ebay-search-result.p.rapidapi.com',
                'x-rapidapi-key'  => '6a58292612mshe506626c4ddd9c8p131e33jsn96ed4840e98b' 
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                $raw = $response->json();
                
                // Menangani perbedaan struktur JSON (results vs array langsung)
                $items = $raw['results'] ?? $raw;
                
                if (!is_array($items) || empty($items)) {
                    Log::warning("API Response empty or not an array for type: $type");
                    return null;
                }

                $maskedData = [];
                foreach (array_slice($items, 0, 10) as $item) {
                    $maskedData[] = [
                        'nama_produk' => $item['title'] ?? 'Hardware Item',
                        'harga'       => $item['price'] ?? '0.00',
                        'pengiriman'  => $item['shipping'] ?? 'N/A',
                        'lokasi'      => $item['location'] ?? 'Global',
                        'gambar'      => $item['image'] ?? null
                    ];
                }
                return $maskedData;
            }

            // Catat error jika response tidak sukses
            Log::error("RapidAPI Failure: " . $response->status() . " - " . $response->body());
            
        } catch (\Exception $e) {
            Log::error("Ebay API Exception: " . $e->getMessage());
            return null;
        }
        return null;
    }
}