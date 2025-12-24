<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ToolsController extends Controller
{
    // 1. URL Shortener (DATABASE MANDIRI)
    public function shortenUrl(Request $request)
    {
        $url = $request->input('url');
        if (!$url) return response()->json(['status' => 400, 'message' => 'URL wajib diisi'], 400);

        // Cek apakah URL ini sudah pernah di-short sebelumnya agar tidak duplikat
        $exists = DB::table('short_links')->where('original_url', $url)->first();
        
        if ($exists) {
            $code = $exists->code;
        } else {
            // Buat kode unik acak (misal: aB12c)
            $code = Str::random(6);
            DB::table('short_links')->insert([
                'original_url' => $url,
                'code' => $code,
                'created_at' => now()
            ]);
        }

        return response()->json([
            'status' => 200,
            'creator' => 'BIAN DEVELOPER STUDIO',
            'result' => [
                'original' => $url,
                'short' => url("/go/{$code}") // Hasil murni link web kamu
            ]
        ]);
    }

    // 2. Fungsi Eksekusi Redirect (Pindah ke link asli)
    public function handleRedirect($code)
    {
        $data = DB::table('short_links')->where('code', $code)->first();

        if ($data) {
            return redirect()->away($data->original_url);
        }

        return response()->view('errors.404', [], 404);
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