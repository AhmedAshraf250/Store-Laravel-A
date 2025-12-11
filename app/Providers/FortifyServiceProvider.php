<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*
        * Instead of redefining the same routes for Admin
        * or for every additional guard, we can dynamically
        * change the authentication configuration at runtime.
        *
        * This allows us to:
        * - Reuse the same routes
        * - Reuse the same views
        * - Support multiple guards without extra code
        */

        $request = request();

        /*
        * If the incoming request starts with "admin/",
        * we switch Fortify to use the admin guard.
        * Otherwise, the default "web" guard is used.
        */
        if ($request->is('admin/*')) {
            Config::set('fortify.guard', 'admin');
            Config::set('fortify.passwords', 'admins');

            /*
            * IMPORTANT:
            * This ensures that all Fortify routes are automatically
            * This prefix is added to ALL Fortify routes
            * (login, logout, password reset, etc.)
            */
            Config::set('fortify.prefix', 'admin');

            // Redirect path after successful authentication
            // Config::set('fortify.home', 'admin/dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Override Fortify Default Login Behavior
        |--------------------------------------------------------------------------
        |
        | By binding a new implementation of the LoginResponse interface
        | into the service container, we override Fortify’s default
        | post-login behavior.
        |
        | This allows us to fully control the login redirect logic
        | for different guards (e.g. admin vs web) while still using
        | Fortify’s authentication flow.
        |
        */
        $this->app->instance(
            LoginResponse::class,
            new class implements LoginResponse {
                public function toResponse($request)
                {
                    if ($request->user('admin')) {
                        /*
                        * intended():
                        * - Redirects the user back to the originally requested page
                        *   before authentication, if it exists.
                        * - Falls back to the admin dashboard if no intended URL
                        *   is stored in the session.
                        */
                        return redirect()->intended('admin/dashboard');
                    }

                    // Default behavior for non-admin users
                    return redirect()->intended('/');
                }
            }
        );

        $this->app->instance(
            LogoutResponse::class,
            new class implements LogoutResponse {
                public function toResponse($request)
                {
                    return redirect()->intended('/');
                }
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {



        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });



        // Fortify::loginView('auth.login');
        // // Fortify::twoFactorChallengeView('auth.two-factor-challenge');
        // Fortify::registerView(function () {
        //     return view('auth.register');
        // });
        // Fortify::requestPasswordResetLinkView(function () {
        //     return view('auth.forgot-password');
        // });
        // Fortify::resetPasswordView(function ($request) {
        //     return view('auth.reset-password', ['request' => $request]);
        // });
        // Fortify::verifyEmailView(function () {
        //     return view('auth.verify-email');
        // });
        // Fortify::confirmPasswordView(function () {
        //     return view('auth.confirm-password');
        // });



        /**
         * When passing a class method:
         * - Instance method → pass an object
         * - Static method   → pass the class name only
         *
         * Example (static):
         * Fortify::authenticateUsing([AuthenticateUser::class, 'authenticate']);
         *
         * Example (instance):
         * Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']);
         *      - By placing this inside the boot method, Fortify will use this authentication logic for ALL guards (web, admin, or any other guard).
         */
        if (Config::get('fortify.guard') == 'admin') {
            Fortify::authenticateUsing([new AuthenticateUser, 'authenticate']);
            Fortify::viewPrefix('auth.');
        } else {
            Fortify::viewPrefix('front.auth.');
        }
    }
}
