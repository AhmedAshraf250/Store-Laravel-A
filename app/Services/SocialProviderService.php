<?php

namespace App\Services;

use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;

class SocialProviderService
{
    public function fetchUser(SocialAccount $account)
    {
        try {
            return Socialite::driver($account->provider)->userFromToken($account->token);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
