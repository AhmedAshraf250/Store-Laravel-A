<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    // These Service Providers are classes in Laravel that:
    // - Run code on every request
    // - Initialize and set up important parts of the app

    /**
     * Register any application services.
     * Register services (runs on every request)
     * Here we bind things into the service container (like repositories, helpers, etc.).
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     * This method is called on EVERY request (after all providers are registered).
     * Perfect for:
     * - Registering view composers
     * - Setting up event listeners
     * - Publishing config/files
     * - Extending validators, etc.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * registering a [Custom Validation Rule] using the Validator Facade to [the Validation System] using Validator::extend()
         * 
         * 1- Use the Validator Facade
         * 2- Call the extend method to define a new rule
         *      - The extend method takes 3 arguments:
         *          1. The name of the rule ('filter' in this case)
         *          2. A Closure that contains the validation logic
         *          3. An optional error message to be used if validation fails
         * 3- Inside the Closure:
         *      - We receive three parameters:        || <input name="name" value=""> ||
         *          1. $attribute:  Field name ('phone' , 'name', etc.) 
         *          2. $value:      The value of the field which sent in the request
         *          3. $parameters: An array of additional parameters passed to the rule
         *  - The Closure should return true if the validation passes, or false if it fails
         * 4- The error message will be displayed if the validation fails
         */
        Validator::extend('filter', function ($attribute, $value, $parameters) {
            return !in_array(strtolower($value), $parameters);
        }, 'القيمة التى قمت بإدخالها محظورة');

        Paginator::useBootstrapFour(); //pagination system will use Bootstrap 4 styles by default
        /**
         * Paginator::defaultView('pagination.custom');
         *  - This sets the default view for pagination links to a custom view located at 'resources\views\pagination\custom.blade.php'.
         */

        JsonResource::withoutWrapping(); // Removes the default "data" wrapper from single JSON resources. so the response returns the object directly.
    }
}
