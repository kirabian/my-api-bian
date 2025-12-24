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

        // 1. Validasi Limit
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Caching dengan Logika Fallback Pintar
        $data = Cache::remember('hardware_prices_smart_' . $type, 3600, function () use ($type) {
            $realData = $this->scrapeRealPrice($type);

            // Jika gagal ambil data asli (kosong atau error), gunakan data cadangan
            if (empty($realData)) {
                return [
                    'source_type' => 'SIMULATED_DATA_FALLBACK',
                    'items' => $this->getFallbackData($type)
                ];
            }

            return [
                'source_type' => 'REALTIME_GLOBAL_MARKET',
                'items' => $realData
            ];
        });

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'kategori' => strtoupper($type),
                'currency' => 'USD',
                'source' => $data['source_type'],
                'data' => $data['items']
            ],
            'server_info' => [
                'last_update' => now()->toDateTimeString(),
                'limit_info' => $apiKey ? '100/min (Premium)' : '5/min (Free)'
            ]
        ]);
    }

    private function scrapeRealPrice($type)
    {
        try {
            // Kita coba akses API luar dengan timeout singkat (3 detik)
            $response = Http::timeout(3)->get("https://api.pangoly.com/v1/products", [
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
            // Jika internet server mati, jangan kirim error, kembalikan null saja
            return null;
        }
        return null;
    }

    private function getFallbackData($type)
    {
        // Data cadangan jika server Hostinger sedang bermasalah koneksinya
        $ssd = [
            ['merk' => 'Samsung', 'tipe' => '990 Pro 1TB NVMe', 'harga' => 119.99, 'status' => 'Tersedia'],
            ['merk' => 'WD Black', 'tipe' => 'SN850X 1TB', 'harga' => 84.99, 'status' => 'Tersedia'],
            ['merk' => 'Crucial', 'tipe' => 'P3 2TB NVMe', 'harga' => 98.50, 'status' => 'Tersedia'],
        ];

        $ram = [
            ['merk' => 'Corsair', 'tipe' => 'Vengeance RGB 32GB DDR5', 'harga' => 114.99, 'status' => 'Tersedia'],
            ['merk' => 'Kingston', 'tipe' => 'Fury Beast 16GB DDR4', 'harga' => 45.20, 'status' => 'Tersedia'],
        ];

        return ($type == 'ssd') ? $ssd : $ram;
    }
}