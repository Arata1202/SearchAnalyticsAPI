<?php
namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Webmasters;
use Illuminate\Http\Request;

class DeviceQueryController extends Controller
{
    public function index(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/myblogproject-415210-3a60a1c6fb1c.json'));
        $client->addScope(Google_Service_Webmasters::WEBMASTERS_READONLY);

        $service = new Google_Service_Webmasters($client);

        $siteUrl = 'https://mochaccinoblog.com'; 
        $startDate = $request->input('startDate', '7daysAgo');
        $endDate = $request->input('endDate', 'today');

        $searchAnalyticsQueryRequest = new \Google_Service_Webmasters_SearchAnalyticsQueryRequest();
        $searchAnalyticsQueryRequest->setStartDate($startDate);
        $searchAnalyticsQueryRequest->setEndDate($endDate);
        $searchAnalyticsQueryRequest->setDimensions(['device']);

        $response = $service->searchanalytics->query($siteUrl, $searchAnalyticsQueryRequest);
        return response()->json($response->getRows());
    }
}
