<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorDisabledResponse as TwoFactorDisabledResponseContract;

class TwoFactorDisabledResponse implements TwoFactorDisabledResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return redirect()->route('user.2fa')
            ->with('status', 'two-factor-disabled');
    }
}
