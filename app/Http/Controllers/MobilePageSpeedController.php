<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MobilePageSpeedController extends Controller
{
    public function fetchPageSpeedData(Request $request)
    {
        $url = $request->input('url');
        if (!$url) {
            return response()->json(['error' => 'URL parameter is required.'], 400);
        }

        $client = new Client();
        $strategies = ['mobile', 'desktop'];
        $results = [];

        foreach ($strategies as $strategy) {
            try {
                $response = $client->request('GET', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                    'query' => [
                        'url' => $url,
                        'strategy' => $strategy,
                        'locale'=>'ja',
                        'key' => env('GOOGLE_API_KEY')
                    ]
                ]);

                $data = json_decode($response->getBody(), true);
                $results[$strategy] = [
                    'performanceScore' => $data['lighthouseResult']['categories']['performance']['score'] ?? null,
                    'diagnostics' => $data['lighthouseResult']['audits']['diagnostics']['details']['items'][0] ?? [],
                ];
            } catch (\Exception $e) {
                $results[$strategy] = ['error' => $e->getMessage()];
            }
        }

        return response()->json($results);
    }
}
