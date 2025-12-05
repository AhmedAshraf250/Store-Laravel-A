<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification
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
    public function handle($event)
    {
        /**
         * $orders = $event->orders; // Collection of Order models created from the checkout
         *
         * Why multiple orders?
         * - If the cart contains products from multiple stores (e.g., Store A and Store B),
         *   Laravel splits the checkout into separate orders — one per store.
         * - This allows:
         *   - Independent shipping per store
         *   - Separate payments/invoices
         *   - Accurate store analytics and commissions
         *
         * Example Scenario:
         * - Cart: Product X (Store 1) + Product Y (Store 2)
         * - Result: 2 orders created → $orders has 2 Order models
         *
         * Next: Loop through each order to send notifications, update stock, etc.
         */
        $orders = $event->orders;

        foreach ($orders as $order) {

            /**
             * # Single recipient (one user):
             *      $user->notify(new OrderCreatedNotification($order));
             *
             *      → Laravel automatically passes $user as $notifiable to the notification class.
             *      Inside OrderCreatedNotification, $notifiable refers to this exact User instance.
             *
             *
             * # Multiple recipients (bulk notification):
             *      Use the Notification facade with send():
             *      Notification::send($users, new OrderCreatedNotification($order));
             *
             *      → Accepts an array or Collection of notifiable models (usually Users)
             *      → Laravel loops through each user and delivers the notification individually
             *      → Each user receives their own personalized version (name, preferences, locale, etc.)
             */


            // $user = User::where('store_id', $order->store_id)->first();
            // $user->notify(new OrderCreatedNotification($order));
            $users = User::where('store_id', $order->store_id)->get();
            Notification::send($users, new OrderCreatedNotification($order));
        }
    }
}
