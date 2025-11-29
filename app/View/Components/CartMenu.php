<?php

namespace App\View\Components;

use App\Facades\Cart;
use Illuminate\View\Component;

class CartMenu extends Component
{
    // 
    // This Cart system is built using a clean and scalable design:
    //
    //  1. I defined an interface that describes the required methods, so any
    //      data provider must follow the same contract.      
    //  |||| look: 'App\Repositores\Cart\CartRepository'      
    //
    //  2. I created an Eloquent-based implementation that actually performs
    //      the operations and implements that interface.     
    //  |||| look: 'App\Repositores\Cart\CartModelRepository' 
    //
    //  3. The implementation is registered and bound to the interface inside
    //    the service container, allowing Laravel to automatically resolve
    //    the correct concrete class whenever the interface is requested. || look: 'App\Providers\CartServiceProvider' -> register it in
    //  |||| look: 'App\Providers\CartServiceProvider' -> binding them -> then register it in -> 'app.providers' array in 'config' Folder   
    //
    //  4. The Cart facade is mapped to that container binding, giving a
    //    convenient static-style API while still interacting with the real
    //    underlying instance from the container.
    //
    // This structure keeps the Cart module flexible, testable, loosely
    // coupled, and easy to use across the entire application.

    public $items;
    public $total;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 'get'    here actully from 'App\Repositores\Cart\CartModelRepository' @get()
        // 'total'  here actully from 'App\Repositores\Cart\CartModelRepository' @total()
        $this->items = Cart::get();
        $this->total = Cart::total();

        // OR

        //     public function __construct(CartRepoistory $cart)
        // {
        //     $this->items = $cart->get();
        //     $this->total = $cart->total();
        // }

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cart-menu');
    }
}
