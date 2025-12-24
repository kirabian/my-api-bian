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

        // Validasi Limit User
        if ($apiKey) {
            $user = DB::table('api_developers')->where('api_key', $apiKey)->first();
            if ($user) {
                DB::table('api_developers')->where('id', $user->id)->increment('request_count');
            }
        }

        // Caching 1 jam agar server tidak kena blokir sumber data
        $results = Cache::remember('real_hardware_prices_' . $type, 3600, function () use ($type) {
            return $this->scrapeRealPrice($type);
        });

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'kategori' => strtoupper($type),
                'currency' => 'USD',
                'data' => $results
            ],
            'server_info' => [
                'source' => 'Global Hardware Index',
                'last_update' => now()->toDateTimeString()
            ]
        ]);
    }

    private function scrapeRealPrice($type)
    {
        // Mencari data melalui endpoint publik perbandingan harga atau scraping
        // Sebagai contoh, kita menembak API publik hardware sebagai backend kita
        $response = Http::get("https://api.pangoly.com/v1/products", [
            'category' => ($type == 'ssd') ? 'ssd' : 'ram',
            'limit' => 10
        ]);

        if ($response->successful()) {
            $products = $response->json()['data'];
            $maskedData = [];

            foreach ($products as $item) {
                // Proses Masking: Ubah struktur asli menjadi struktur BIAN API
                $maskedData[] = [
                    'merk' => $item['brand'] ?? 'Unknown',
                    'tipe' => $item['name'] ?? 'Component',
                    'harga' => $item['price'] ?? 0.00,
                    'status_stok' => ($item['in_stock'] ?? true) ? 'Tersedia' : 'Habis',
                    'link_cek' => $item['url'] ?? '#'
                ];
            }
            return $maskedData;
        }

        return [['message' => 'Gagal mengambil data real-time, mencoba sinkronisasi ulang...']];
    }
}