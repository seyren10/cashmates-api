<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'google_id' => $googleUser->getId()
        ], [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => Str::password(12),
            'email_verified_at' => now(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(
            config('app.frontend_url') . '/?verified=1'
        );
    }
}
