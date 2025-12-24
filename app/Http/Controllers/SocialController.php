<?php

namespace App\Http\Controllers;

use App\Services\SocialProviderService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();
    //     if ($user->socialAccounts()->where('provider', 'google')->exists()) {
    //         $socialAccount = $user->socialAccounts()->where('provider', 'google')->first();
    //     } else {
    //         $socialAccount = null;
    //     }

    //     $provider_user = Socialite::driver($socialAccount->provider)->userFromToken($socialAccount->token);
    //     dd($provider_user);
    // }


    public function show(string $provider, SocialProviderService $service)
    {
        $account = auth()->user()
            ->socialAccounts()
            ->where('provider', $provider)
            ->firstOrFail();

        $providerUser = $service->fetchUser($account);

        // return view('social.show', compact('account', 'providerUser'));
        dd($providerUser);
    }
}
