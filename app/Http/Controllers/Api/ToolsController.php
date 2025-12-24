<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ToolsController extends Controller
{
    // 1. URL Shortener (Generate Link)
    public function shortenUrl(Request $request)
    {
        $url = $request->input('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'URL wajib diisi'], 400);

        try {
            $response = Http::get("https://tinyurl.com/api-create.php?url=" . urlencode($url));
            $short = $response->body();
            
            // Mengambil kode unik di ujung link (misal: mbq3m)
            $code = str_replace('https://tinyurl.com/', '', $short);
            
            // Link baru menggunakan domain Bian API
            $customShort = url("/go/{$code}");

            return response()->json([
                'status' => 200,
                'creator' => 'BIAN DEVELOPER STUDIO',
                'result' => [
                    'original' => $url,
                    'short' => $customShort
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Gagal memproses'], 500);
        }
    }

    // 2. Fungsi Eksekusi Redirect (Agar link /go/ bisa dibuka)
    public function handleRedirect($code)
    {
        // Mengembalikan link ke TinyURL asli di latar belakang agar user dipindahkan
        return redirect()->away("https://tinyurl.com/{$code}");
    }

    // 3. Website Screenshot JSON
    public function screenshotWeb(Request $request)
    {
        $url = $request->input('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'URL wajib diisi'], 400);

        $encodedUrl = base64_encode($url);
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

    // 4. Proxy Gambar Screenshot
    public function getScreenshotImage($encodedUrl)
    {
        try {
            $targetUrl = base64_decode($encodedUrl);
            $externalUrl = "https://api.s-shot.ru/1024x768/JPEG/1024/Z100/?" . $targetUrl;
            
            $imageResponse = Http::timeout(50)->get($externalUrl);

            if($imageResponse->successful()){
                return response($imageResponse->body())->header('Content-Type', 'image/jpeg');
            }
            
            return response()->json(['message' => 'Engine sibuk'], 503);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }
    }
}