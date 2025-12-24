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

        // 1. Validasi & Catat Limit
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // 2. Cache Data selama 1 Jam agar tidak boros kuota RapidAPI
        $data = Cache::remember('hardware_live_prices_' . $type, 3600, function () use ($type) {
            return $this->fetchFromEbay($type);
        });

        if (!$data) {
            return response()->json([
                'status' => 503,
                'message' => 'Gagal sinkronisasi dengan Global Market. Coba lagi nanti.'
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
                'source' => 'Ebay Global Market (Live)',
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    private function fetchFromEbay($type)
    {
        try {
            // Menggunakan Endpoint & Key dari gambar Anda
            $response = Http::withHeaders([
                'x-rapidapi-host' => 'ebay-search-result.p.rapidapi.com',
                'x-rapidapi-key'  => '6a58292612mshe506626c4ddd9c8p131e33jsn96ed4840e98b' 
            ])->timeout(15)->get('https://ebay-search-result.p.rapidapi.com/search', [
                'keyword' => $type . ' nvme ssd brand new', // Pencarian lebih spesifik
            ]);

            if ($response->successful()) {
                $raw = $response->json()['results'] ?? [];
                $maskedData = [];

                // Ambil 10 item pertama dan lakukan Masking
                foreach (array_slice($raw, 0, 10) as $item) {
                    $maskedData[] = [
                        'nama_produk' => $item['title'] ?? 'Hardware Item',
                        'harga_raw'   => $item['price'] ?? '0.00',
                        'pengiriman'  => $item['shipping'] ?? 'N/A',
                        'lokasi'      => $item['location'] ?? 'Global',
                        'gambar'      => $item['image'] ?? null
                    ];
                }
                return $maskedData;
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}