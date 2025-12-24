<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ToolsController extends Controller
{
    // 1. URL Shortener
    public function shortenUrl(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        // Logika sederhana: Gunakan layanan tinyurl bridge atau simpan ke DB sendiri
        $response = Http::get("https://tinyurl.com/api-create.php?url=" . $url);
        
        $short = $response->body();
        // Masking agar seolah-olah milik Bian API
        $customShort = str_replace('tinyurl.com', 'my-api-bian.absenps.com/go', $short);

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'original' => $url,
                'short' => $customShort
            ]
        ]);
    }

    // 2. Website Screenshot
    public function screenshotWeb(Request $request)
    {
        $url = $request->query('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'Query url wajib diisi'], 400);

        // Menggunakan jembatan API Screenshot gratis
        $ssUrl = "https://api.screenshotmachine.com/?key=FREE&url=" . urlencode($url) . "&dimension=1024x768";

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'target' => $url,
                'image_url' => $ssUrl
            ]
        ]);
    }

    // 3. PDF to Text
    public function pdfToText(Request $request)
    {
        $pdfUrl = $request->query('url');
        if (!$pdfUrl) return response()->json(['status' => 400, 'message' => 'Query url file PDF wajib diisi'], 400);

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'info' => 'Fitur ini memerlukan library PDFParser di server.',
                'note' => 'Gunakan library smalot/pdfparser di Laravel kamu.'
            ]
        ]);
    }
}