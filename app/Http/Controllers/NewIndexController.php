<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchConsoleController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->input('url');
        $apiKey = env('GOOGLE_API_KEY');
        $siteUrl = env('VITE_YOUR_URL');

        $endpoint = "https://www.googleapis.com/webmasters/v3/sites/" . urlencode($siteUrl) . "/urlInspection/index:inspect?key=" . $apiKey;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer YOUR_ACCESS_TOKEN',
            'Content-Type' => 'application/json'
        ])->post($endpoint, [
            'inspectionUrl' => $url,
            'siteUrl' => $siteUrl,
            'language' => 'en',
        ]);

        return $response->json();
    }
}
