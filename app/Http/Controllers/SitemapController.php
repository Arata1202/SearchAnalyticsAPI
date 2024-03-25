<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Webmasters;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        try {
            
            $client = new Google_Client();
            $client->setAuthConfig(storage_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
            $client->addScope(Google_Service_Webmasters::WEBMASTERS_READONLY);

            $service = new Google_Service_Webmasters($client);

            $siteUrl = env('VITE_YOUR_URL');

            $sitemapList = $service->sitemaps->listSitemaps($siteUrl);

            $sitemaps = [];
            foreach ($sitemapList->getSitemap() as $sitemap) {
                $sitemapDetails = $service->sitemaps->get(env('VITE_YOUR_URL'), $sitemap->path);
                $sitemaps[] = $sitemapDetails;
            }

            $sitemapURLs = [];
            foreach ($sitemaps as $sitemap) {
                $sitemapURLList = $service->sitemaps->get(env('VITE_YOUR_URL'), $sitemap->path);
                $sitemapURLs[$sitemap->path] = $sitemapURLList;
            }

            return response()->json([
                'sitemapList' => $sitemapList,
                'sitemaps' => $sitemaps,
                'sitemapURLs' => $sitemapURLs,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sitemap data: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching sitemap data.'], 500);
        }
    }
}
