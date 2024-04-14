<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Webmasters;

class SitemapController extends Controller
{
    public function submitSitemap(Request $request)
    {
        try {
            $client = new Google_Client();
            $client->setAuthConfig(storage_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
            $client->addScope(Google_Service_Webmasters::WEBMASTERS_READONLY);

            $service = new Google_Service_Webmasters($client);

            $siteUrl = env('VITE_YOUR_URL');
            $sitemapPath = $request->sitemapPath;

            $service->sitemaps->submit($siteUrl, $sitemapPath);

            return response()->json(['success' => 'Sitemap submitted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error submitting sitemap: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while submitting sitemap.'], 500);
        }
    }
}
