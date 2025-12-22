<?php

namespace App\Providers;

use App\Support\AbilityRegistry;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Product' => 'App\Policies\ProductPolicy',

    ];

    public function register()
    {
        parent::register();

        $this->app->singleton('abilities', function () {
            // return include base_path('data/abilities.php');
            return \App\Support\AbilityRegistry::load();
        });
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // [Gate] is a way to define authorization rules in Laravel. It is a global object that is used to check if a user has permission to perform an action.

        // Gate::define('admin-only', function ($user) {
        //     return $user->role === 'admin';
        // });

        // ------- IGNORED ------- //
        // foreach (config('abilities') as $ability => $label) {
        //     Gate::define($ability, function ($user) use ($ability) {
        //         return $user->hasAbility($ability);
        //         // return true; // --- TEMPORARY FOR TESTING ---
        //     });
        // }

        // ------- IGNORED ------- //
        // foreach (app('abilities') as $ability) {
        //     Gate::define($ability, fn($user) => $user->hasAbility($ability));
        // }

        // THIS WILL OVERWRITE ALL ABILITIES EVEN ON THE ALL POLICIES //
        Gate::before(function ($user, $ability) {
            if ($user->super_admin) {
                return true;
            }
        });
        // THIS WILL OVERWRITE ALL ABILITIES EVEN ON THE ALL POLICIES //

        foreach (
            /* \App\Support\AbilityRegistry::load() */
            app('abilities') as $code => $label
        ) {
            // Gate::define($code, fn($user) => true);
            Gate::define($code, fn($user) => $user->hasAbility($code));
        }
    }
}
