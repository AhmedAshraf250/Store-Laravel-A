<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SocialAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider, SocialAuthService $service)
    {

        //  #################################### DEPRICATED ################################################
        // try {
        //     $providerUser = Socialite::driver($provider)
        //         // ->stateless()
        //         ->user();

        //     // 1- search User by provider
        //     $user = User::where('provider', $provider)
        //         ->where('provider_id', $providerUser->id)
        //         ->first();

        //     // 2- if not found, search by email
        //     if (!$user && $providerUser->email) {
        //         $user = User::where('email', $providerUser->email)->first();

        //         if ($user) {
        //             // link old user with new provider
        //             $user->update([
        //                 'provider' => $provider,
        //                 'provider_id' => $providerUser->id,
        //                 'provider_token' => $providerUser->token,
        //             ]);
        //         }
        //     }

        //     // 3- if not exists yet, create a new user
        //     if (!$user) {
        //         $user = User::create([
        //             'name' => $providerUser->name ?? $providerUser->nickname ?? 'User',
        //             'email' => $providerUser->email,
        //             'password' => null, // social only
        //             'provider' => $provider,
        //             'provider_id' => $providerUser->id,
        //             'provider_token' => $providerUser->token,
        //         ]);
        //     }

        //     Auth::login($user, true);

        //     return redirect()->intended(route('home'));
        // } catch (\Throwable $e) {

        //     report($e); // logging

        //     return redirect()->route('login')->withErrors([
        //         'social' => 'Login failed, please try again.',
        //     ]);
        // }
        //  #################################### DEPRICATED ####################################

        try {
            $user = $service->handle($provider);

            Auth::login($user, true);

            return redirect()->intended(route('home'));
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('login')->withErrors([
                'email' => 'Social login failed.',
                'social' => 'Social login failed.',
            ]);
        }
    }
}
