<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ToolsController extends Controller
{
    // 1. URL Shortener
    public function shortenUrl(Request $request)
    {
        $url = $request->input('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        try {
            $response = Http::get("https://tinyurl.com/api-create.php?url=" . urlencode($url));
            $short = $response->body();
            $customShort = str_replace('tinyurl.com', 'my-api-bian.absenps.com/go', $short);

            return response()->json([
                'status' => 200,
                'creator' => 'BIAN DEVELOPER STUDIO',
                'result' => ['original' => $url, 'short' => $customShort]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Gagal memproses'], 500);
        }
    }

    // 2. Website Screenshot JSON
    public function screenshotWeb(Request $request)
    {
        $url = $request->input('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        // Link gambar diarahkan ke proxy domain Anda sendiri
        $maskedImageUrl = url('/v1/tools/ssweb/image.jpg') . "?url=" . urlencode($url);

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'target' => $url,
                'image_url' => $maskedImageUrl
            ]
        ]);
    }

    // 3. Proxy Gambar Screenshot - FIX TOTAL
    public function getScreenshotImage(Request $request)
    {
        // Menangkap parameter 'url' dari query string
        $targetUrl = $request->query('url');

        if (!$targetUrl) {
            return response()->json(['message' => 'URL target tidak ditemukan dalam request'], 400);
        }

        try {
            // Engine s-shot.ru (Gratis & No Key)
            $externalUrl = "https://api.s-shot.ru/1024x768/JPEG/1024/Z100/?" . urlencode($targetUrl);
            
            $imageResponse = Http::timeout(45)->get($externalUrl);

            if($imageResponse->successful()){
                return response($imageResponse->body())->header('Content-Type', 'image/jpeg');
            }
            
            return response()->json(['message' => 'Gagal mengambil gambar dari engine server'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }
}