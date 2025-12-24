<?php

namespace App\Services;

use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthService
{
    public function handle(string $provider): User
    {
        $providerUser = Socialite::driver($provider)
            // ->stateless()
            ->user();

        // 1- is the social account exists
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $providerUser->id)
            ->first();

        if ($account) {
            return $account->user;
        }

        // 2- is there a user with the same email
        $user = null;

        if ($providerUser->email) {
            $user = User::where('email', $providerUser->email)->first();
        }

        // 3- if not exists yet, create a new user
        if (!$user) {
            $user = User::create([
                'name' => $providerUser->name ?? $providerUser->nickname ?? 'User',
                'email' => $providerUser->email,
                'password' => Hash::make('password'),
            ]);
        }

        // 4- link user with social account
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_user_id' => $providerUser->id,
            'token' => $providerUser->token,
            'refresh_token' => $providerUser->refreshToken ?? null,
            'expires_at' => now()->addSeconds($providerUser->expiresIn ?? 0),
        ]);


        // ==== [TODO]: link social account inside the user =============
        // $user->socialAccounts()->where('provider', 'github')->exists();
        // ==============================================================


        return $user;
    }
}
