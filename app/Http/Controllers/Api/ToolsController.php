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
        if (!$url) return response()->json(['status' => 400, 'message' => 'URL wajib diisi'], 400);

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

    // 2. Website Screenshot JSON (Versi FIX Base64)
    public function screenshotWeb(Request $request)
    {
        $url = $request->input('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'URL wajib diisi'], 400);

        // Ubah URL target menjadi Base64 agar aman dibaca server
        $encodedUrl = base64_encode($url);
        
        // Link gambar sekarang menggunakan path parameter, bukan query string lagi
        $maskedImageUrl = url("/v1/tools/ssweb/view/{$encodedUrl}/image.jpg");

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'target' => $url,
                'image_url' => $maskedImageUrl
            ]
        ]);
    }

    // 3. Proxy Gambar Screenshot - FIX TOTAL 100%
    public function getScreenshotImage($encodedUrl)
    {
        try {
            // Dekode kembali URL aslinya
            $targetUrl = base64_decode($encodedUrl);

            if (!filter_var($targetUrl, FILTER_VALIDATE_URL)) {
                return response()->json(['message' => 'Format URL tidak valid setelah dekode'], 400);
            }

            // Gunakan engine s-shot.ru
            $externalUrl = "https://api.s-shot.ru/1024x768/JPEG/1024/Z100/?" . $targetUrl;
            
            $imageResponse = Http::timeout(50)->get($externalUrl);

            if($imageResponse->successful()){
                return response($imageResponse->body())->header('Content-Type', 'image/jpeg');
            }
            
            return response()->json(['message' => 'Engine sibuk, coba lagi nanti'], 503);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}