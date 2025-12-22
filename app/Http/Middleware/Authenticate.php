<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         return route('login');
    //     }
    // }

    protected function redirectTo($request)
    {
        // if ($request->expectsJson()) {
        //     return null;
        // }

        if (! $request->expectsJson()) {
            $guard = Auth::getDefaultDriver(); // almost always 'web'

            if ($request->route()) {
                $middleware = $request->route()->middleware(); //  ['web', 'auth:admin' , 'auth:web', 'auth:sanctum' ....]
                $guard = collect($middleware)
                    ->filter(fn($m) => str_starts_with($m, 'auth:'))
                    ->map(fn($m) => str_replace('auth:', '', $m))
                    ->first() ?? $guard;
            }

            return match ($guard) {
                'admin' => route('admin.login'),
                'web'   => route('login'),
                // 'api'   => null,
                default => route('login'),
            };
        }
    }
}
