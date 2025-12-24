<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ToolsController extends Controller
{
    // 1. URL Shortener (Masking Bridge)
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

    // 2. Website Screenshot (Engine Baru - No API Key Required)
    public function screenshotWeb(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        // Menggunakan engine s-shot.ru yang lebih stabil tanpa key
        $ssUrl = "https://api.s-shot.ru/1024x768/JPEG/1024/Z100/?" . urlencode($url);

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'target' => $url,
                'image_url' => $ssUrl,
                'format' => 'JPEG',
                'dimensi' => '1024x768'
            ]
        ]);
    }
}