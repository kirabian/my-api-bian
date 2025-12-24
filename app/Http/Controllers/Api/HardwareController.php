<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HardwareController extends Controller
{
    public function getPrices(Request $request)
    {
        // Default ke 'ssd' jika tidak ada input
        $type = $request->query('type', 'ssd');
        $apiKey = $request->header('X-BIAN-KEY');

        // 1. Validasi Limit
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Caching Data (Bersihkan cache dulu di terminal sebelum tes)
        $data = Cache::remember('hardware_live_fix_' . $type, 3600, function () use ($type) {
            return $this->fetchFromEbay($type);
        });

        if (!$data) {
            return response()->json([
                'status' => 503,
                'message' => 'Gagal sinkronisasi data global. Cek kuota RapidAPI atau koneksi server.'
            ], 503);
        }

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'kategori' => strtoupper($type),
                'currency' => 'USD',
                'items' => $data
            ],
            'server_info' => [
                'source' => 'RapidAPI Gateway (Live)',
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    private function fetchFromEbay($type)
    {
        try {
            // PERBAIKAN: URL sekarang menggunakan path '/search/{keyword}' sesuai data cURL kamu
            $url = "https://ebay-search-result.p.rapidapi.com/search/" . urlencode($type);

            $response = Http::withHeaders([
                'x-rapidapi-host' => 'ebay-search-result.p.rapidapi.com',
                'x-rapidapi-key'  => '6a58292612mshe506626c4ddd9c8p131e33jsn96ed4840e98b' 
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                $raw = $response->json();
                
                // Berdasarkan cURL, data biasanya ada di dalam key 'results'
                $items = $raw['results'] ?? $raw;
                
                if (!is_array($items)) return null;

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
        } catch (\Exception $e) {
            \Log::error("Ebay API Error: " . $e->getMessage());
            return null;
        }
        return null;
    }
}