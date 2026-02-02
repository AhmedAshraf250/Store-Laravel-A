<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession as BaseStartSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StartSession extends BaseStartSession
{
    public function handle($request, Closure $next)
    {
        // before starting the session , configure the session cookie name and path
        if ($request->is('admin') || $request->is('admin/*')) {

            // Log::info('Admin route detected', [
            //     'url' => $request->url(),
            //     'cookie_before' => config('session.cookie'),
            // ]);

            if ($this->isAdminRoute($request)) {
                $this->configureAdminSession();
            }

            // Log::info('Config changed', [
            //     'cookie_after' => config('session.cookie'),
            //     'path' => config('session.path'),
            // ]);
        }

        return parent::handle($request, $next);
    }

    protected function configureAdminSession(): void
    {
        config([
            'session.cookie' => Str::slug(config('app.name'), '_') . '_admin_session',
            'session.path' => '/admin',
        ]);
    }

    /**
     * Determine if the request is for an admin route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isAdminRoute($request): bool
    {
        return $request->is('admin') || $request->is('admin/*');
    }
}
