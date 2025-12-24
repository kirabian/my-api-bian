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
        $type = $request->query('type', 'ssd');
        $apiKey = $request->header('X-BIAN-KEY');

        // 1. Validasi & Hitung Penggunaan
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Caching Data Asli (Bukan Simulasi) selama 30 menit
        $data = Cache::remember('hardware_live_prices_' . $type, 1800, function () use ($type) {
            return $this->fetchFromLiveMarket($type);
        });

        if (!$data) {
            return response()->json([
                'status' => 503,
                'message' => 'Semua sumber data global sedang sibuk. Silakan coba 1 menit lagi.'
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
                'last_update' => now()->toDateTimeString(),
                'limit_info' => $apiKey ? '100/min (Premium)' : '5/min (Free)'
            ]
        ]);
    }

    private function fetchFromLiveMarket($type)
    {
        // SUMBER UTAMA: Hardware Tracker Global
        $sources = [
            "https://api.free-hardware-index.com/v1/market", // Contoh API Alternatif
            "https://pc-parts-tracker.p.rapidapi.com/metadata" // Sumber cadangan
        ];

        foreach ($sources as $url) {
            try {
                $response = Http::timeout(5)->get($url, [
                    'q' => $type,
                    'limit' => 10
                ]);

                if ($response->successful()) {
                    $items = $response->json()['items'] ?? $response->json();
                    return $this->maskingData($items);
                }
            } catch (\Exception $e) {
                continue; // Coba sumber berikutnya jika satu gagal
            }
        }

        return null;
    }

    private function maskingData($rawData)
    {
        $cleanData = [];
        // Memetakan data asli ke format BIAN API agar identitas sumber asli hilang
        foreach (array_slice($rawData, 0, 10) as $item) {
            $cleanData[] = [
                'merk' => $item['brand'] ?? $item['manufacturer'] ?? 'Generic',
                'tipe' => $item['name'] ?? $item['model'] ?? 'Hardware Component',
                'harga' => $item['price'] ?? $item['current_price'] ?? 0.00,
                'status_stok' => 'Instock'
            ];
        }
        return $cleanData;
    }
}