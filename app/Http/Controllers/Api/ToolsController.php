<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ToolsController extends Controller
{
    // 1. URL Shortener (Masking Bridge ke nama BIAN)
    public function shortenUrl(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        try {
            $response = Http::get("https://tinyurl.com/api-create.php?url=" . urlencode($url));
            $short = $response->body();
            
            // Masking agar seolah-olah domain Bian API
            $customShort = str_replace('tinyurl.com', 'my-api-bian.absenps.com/go', $short);

            return response()->json([
                'status' => 200,
                'creator' => 'BIAN DEVELOPER STUDIO',
                'result' => [
                    'original' => $url,
                    'short' => $customShort
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Gagal memperpendek URL'], 500);
        }
    }

    // 2. Website Screenshot JSON Response
    public function screenshotWeb(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        // Link hasil sekarang mengarah ke domain Anda sendiri
        $maskedImageUrl = url('/v1/tools/ssweb/image.jpg') . "?url=" . urlencode($url);

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'target' => $url,
                'image_url' => $maskedImageUrl,
                'format' => 'JPEG',
                'dimensi' => '1024x768',
                'info' => 'Gambar diproses melalui server Bian API'
            ]
        ]);
    }

    // 3. Proxy/Masking Gambar Screenshot agar pakai domain sendiri
    public function getScreenshotImage(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['message' => 'URL target tidak ada'], 400);

        try {
            // Server Bian mengambil gambar dari engine s-shot.ru
            $externalUrl = "https://api.s-shot.ru/1024x768/JPEG/1024/Z100/?" . urlencode($url);
            $imageContent = Http::timeout(30)->get($externalUrl)->body();

            // Mengirimkan kembali sebagai gambar asli milik domain Anda
            return response($imageContent)->header('Content-Type', 'image/jpeg');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memproses gambar'], 500);
        }
    }
}