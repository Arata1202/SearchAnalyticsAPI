<?php
namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Webmasters;
use Illuminate\Http\Request;

class CountryQueryController extends Controller
{
    public function index(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
        $client->addScope(Google_Service_Webmasters::WEBMASTERS_READONLY);

        $service = new Google_Service_Webmasters($client);

        $siteUrl = env('VITE_YOUR_URL');
        $startDate = $request->input('startDate', '7daysAgo');
        $endDate = $request->input('endDate', 'today');

        $searchAnalyticsQueryRequest = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $searchAnalyticsQueryRequest->setStartDate($startDate);
        $searchAnalyticsQueryRequest->setEndDate($endDate);
        $searchAnalyticsQueryRequest->setDimensions(['country']);

        $response = $service->searchanalytics->query($siteUrl, $searchAnalyticsQueryRequest);
        return response()->json($response->getRows());
    }
}
