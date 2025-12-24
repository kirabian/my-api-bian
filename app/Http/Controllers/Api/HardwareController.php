<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\ConnectionException; // Import ini penting

class HardwareController extends Controller
{
    public function getPrices(Request $request)
    {
        $type = $request->query('type', 'ssd');
        $apiKey = $request->header('X-BIAN-KEY');

        // 1. Validasi Limit
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Caching dengan Error Handling
        try {
            $results = Cache::remember('real_hardware_prices_' . $type, 3600, function () use ($type) {
                return $this->scrapeRealPrice($type);
            });
        } catch (\Exception $e) {
            // Jika koneksi internet server bermasalah, kirim pesan error yang rapi
            return response()->json([
                'status' => 500,
                'message' => 'Server kami gagal terhubung ke sumber data global. Silakan coba lagi nanti.',
                'error_detail' => 'Connection issue on server side.'
            ], 500);
        }

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'kategori' => strtoupper($type),
                'currency' => 'USD',
                'data' => $results
            ],
            'server_info' => [
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    private function scrapeRealPrice($type)
    {
        try {
            // Menembak API Hardware (Pastikan domain ini bisa diakses dari server Anda)
            $response = Http::timeout(5)->get("https://api.pangoly.com/v1/products", [
                'category' => ($type == 'ssd') ? 'ssd' : 'ram',
                'limit' => 10
            ]);

            if ($response->successful()) {
                $products = $response->json()['data'] ?? [];
                $maskedData = [];

                foreach ($products as $item) {
                    $maskedData[] = [
                        'merk' => $item['brand'] ?? 'Unknown',
                        'tipe' => $item['name'] ?? 'Component',
                        'harga' => $item['price'] ?? 0.00,
                        'status' => 'Tersedia'
                    ];
                }
                return $maskedData;
            }
        } catch (\Exception $e) {
            // Jika gagal scrape, lempar error agar ditangkap oleh getPrices
            throw new \Exception("External API Unreachable");
        }

        return [];
    }
}