<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchQueryController;
use App\Http\Controllers\PageQueryController;
use App\Http\Controllers\CountryQueryController;
use App\Http\Controllers\DeviceQueryController;
use App\Http\Controllers\DateQueryController;
use App\Http\Controllers\PageSpeedController;
use App\Http\Controllers\MobilePageSpeedController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubmitIndexController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/page-query', [PageQueryController::class, 'index']);
Route::post('/search-query', [SearchQueryController::class, 'index']);
Route::post('/country-query', [CountryQueryController::class, 'index']);
Route::post('/device-query', [DeviceQueryController::class, 'index']);
Route::post('/date-query', [DateQueryController::class, 'index']);
Route::get('/pagespeed', [PageSpeedController::class, 'fetchPageSpeedData']);
Route::get('/mobilepagespeed', [MobilePageSpeedController::class, 'fetchPageSpeedData']);
Route::get('/sitemaps', [SitemapController::class, 'index']);
Route::post('/submit-sitemap', [SitemapController::class, 'submitSitemap']);
Route::get('/search', [SubmitIndexController::class, 'search']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



