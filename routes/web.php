<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GoogleLoginController;

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
    return redirect()->route('searchquery');
});

Route::get('/dashboard', function () {
    return redirect()->intended('/searchquery');
});


Route::get('/login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);


Route::get('/profile', function () {
    return redirect()->intended('/searchquery');
});


Route::get('/searchquery', function () {
    return Inertia::render('SearchQuery');
})->middleware(['auth', 'verified'])
->name('searchquery');;

Route::get('/pagequery', function () {
    return Inertia::render('PageQuery');
})->middleware(['auth', 'verified']);

Route::get('/countryquery', function () {
    return Inertia::render('CountryQuery');
})->middleware(['auth', 'verified']);

Route::get('/devicequery', function () {
    return Inertia::render('DeviceQuery');
})->middleware(['auth', 'verified']);

Route::get('/datequery', function () {
    return Inertia::render('DateQuery');
})->middleware(['auth', 'verified']);

Route::get('/pagespeed', function () {
    return Inertia::render('PageSpeed');
})->middleware(['auth', 'verified']);

Route::get('/mobilepagespeed', function () {
    return Inertia::render('MobilePageSpeed');
})->middleware(['auth', 'verified']);

Route::get('/sitemap', function () {
    return Inertia::render('Sitemap');
})->middleware(['auth', 'verified']);

Route::get('/newsitemap', function () {
    return Inertia::render('NewSitemap');
})->middleware(['auth', 'verified']);

Route::get('/submitindex', function () {
    return Inertia::render('SubmitIndex');
})->middleware(['auth', 'verified']);

// Route::get('/contactmail', function () {
//     return Inertia::render('ContactMail');
// })->middleware(['auth', 'verified']);




require __DIR__.'/auth.php';
