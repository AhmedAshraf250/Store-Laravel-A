<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Facades\Cart;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmptyCart
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    // public function handle($order, $user = null)
    // {
    //     // any code here will be executed when the event is triggered
    //     // lisetener code always be here

    //     Cart::empty();
    // }

    public function handle($event)
    {
        Cart::empty();
    }
}
