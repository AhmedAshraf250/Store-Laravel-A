<?php

namespace App\Providers;

use App\Repositores\Cart\CartModelRepository;
use App\Repositores\Cart\CartRepoistory;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('cart', function () {
        //     return new CartModelRepository();
        // });
        // when we want to retrieve the CartModelRepository instance -> App::make('cart')

        $this->app->bind(CartRepoistory::class, CartModelRepository::class);

        /**
         * Now that it's registered in the service container,
         * Laravel can automatically inject it wherever needed.
         * 
         * Example usage:
         * 
         * public function index(CartRepository $cart)
         * {
         *     // $cart = new CartModelRepository();
         *     $items = $cart->get();
         *     return view('front.cart', compact('items'));
         * }
         */
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
