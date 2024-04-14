<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubmitIndexController extends Controller
{
    public function search(Request $request)
{
    $apiKey = env('GOOGLE_API_KEY');
    $searchEngineId = env('GOOGLE_SEARCH_ENGINE_ID');
    $query = 'site:' . env('VITE_YOUR_URL') . ' ' . $request->query('q');
    $start = $request->query('start', 1);
    $response = Http::get('https://www.googleapis.com/customsearch/v1', [
        'key' => $apiKey,
        'cx' => $searchEngineId,
        'q' => $query,
        'start' => $start,
    ]);

    if ($response->successful()) {
        $data = $response->json();
        $totalResults = $data['searchInformation']['totalResults'];
        return response()->json(['items' => $data['items'], 'totalResults' => $totalResults]);
    } else {
        return response()->json(['error' => 'Google Custom Search API からのレスポンスに失敗しました。'], 500);
    }
}

}
