<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorEnabledResponse as TwoFactorEnabledResponseContract;

class TwoFactorEnabledResponse implements TwoFactorEnabledResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Redirect to 2FA page (State 2: needs confirmation)
        return redirect()->route('user.2fa');
    }
}
