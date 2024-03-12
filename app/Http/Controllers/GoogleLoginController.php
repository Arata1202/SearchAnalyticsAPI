<?php
namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->email,
        ], [
            'name' => $googleUser->name,
            'google_id' => $googleUser->id,
        ]);

        Auth::login($user, true);

        return redirect('/home');
    }
}
