<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return Inertia::render('home');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/searchquery', function () {
    return Inertia::render('SearchQuery');
});
Route::get('/pagequery', function () {
    return Inertia::render('PageQuery');
});
Route::get('/countryquery', function () {
    return Inertia::render('CountryQuery');
});
Route::get('/devicequery', function () {
    return Inertia::render('DeviceQuery');
});
Route::get('/datequery', function () {
    return Inertia::render('DateQuery');
});
Route::get('/pagespeed', function () {
    return Inertia::render('PageSpeed');
});
Route::get('/mobilepagespeed', function () {
    return Inertia::render('MobilePageSpeed');
});
Route::get('/twitteranalytics', function () {
    return Inertia::render('TwitterAnalytics');
});



require __DIR__.'/auth.php';
