<?php

use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/*
|--------------------------------------------------------------------------
| Notification Broadcasting Flow (Laravel)
|--------------------------------------------------------------------------
|
|  Notification::send()
|        |
|        v
|  via() method returns: ['broadcast']
|        |
|        v
|  toBroadcast()
|        |   (Prepare broadcast payload)
|        |
|        v
|  Automatic Channel Resolution                   (App.Models.{Model}.{id}) ===>> DEAFULT channel
|        |   private: App.Models.User.{id}
|        |
|        v
|  Channel Authorization (routes/channels.php)
|        |   (Verify the user can listen on this channel)
|        |
|        v
|  Broadcast Server (Pusher, Reverb, Soketi, etc.)
|        |
|        v
|  Frontend Listener
|        |   Echo.private('App.Models.User.{id}')
|        |        .notification(...)
|        v
|  Notification received in real-time 
|
*/

/*
 * The broadcast channel can be explicitly defined inside the notification/event.
 *
 * If no custom channel is defined, Laravel automatically resolves
 * a default broadcast channel for the notifiable model.
 *
 * The default channel name follows this pattern:
 * App.Models.{ModelName}.{id}
 *
 * Example:
 * App.Models.User.1
 */

/*
|--------------------------------------------------------------------------
| Custom Broadcast Channel Flow
|--------------------------------------------------------------------------
|
| 1) Create an Event with a custom broadcast channel
|    - The event must implement ShouldBroadcast
|    - The channel is defined explicitly
|
|    class OrderCreated implements ShouldBroadcast
|    {
|        public function broadcastOn()
|        {
|            return new PrivateChannel('orders.' . $order->id);
|        }
|    }
|
| 2) Define the channel authorization (routes/channels.php)
|    - This is used ONLY for authorization
|    - No data is broadcasted here
|
|    Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
|        return $user->isAdmin();
|    });
|
| 3) Listen to the event on the frontend (JavaScript)
|
|    Echo.private('orders')
|        .listen('OrderCreated', (e) => {
|            console.log(e);
|        });
|
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
| - Event/Notification defines WHERE to broadcast
| - channels.php defines WHO can listen
| - Echo listens and handles the event in real-time
|
*/

//إحنا هنا مش بنعرف الإتشانلز - هنا بنعرف الأوثنتيكاشن الخاص بالإتشانل - يعنى هل اليوزر مسموح له يعمل ليسيننج على هذا الإتشانل ام لا
//  وبالتالى كل برايفت إتشانل إحنا بنضيفها فى الأبليكاشن لازم اضيف فى ملف الإتشانل هنا الأوثنتيكاشن تبعها - ترجعلى ترو او فالس بناء على معيار انا بحدده
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
    // always will pass to Closure an instance of the authenticated user ($user) || {id} is a parameter will pass from the route to the closure
    // ture -> That user can listen to the channel
    // false -> That user not allow to listen to the channel

    // [THE OUTPUT]: 
    // EVENT: [API message] => 
    //      Details: { 
    //          Channel: private-App.Models.User.1, 
    //          Event: Illuminate\Notifications\Events\BroadcastNotificationCreated 
    //      }

    // Explanation:
    // so this message sent to the channel named 'private-App.Models.User.1'
    // 'private-' -> this is a prefix means that is a private channel 
    // 'App.Models.User.1' -> but this is the already defined channel name || the user who id is 1 can listen to this channel

});

Broadcast::channel('deliveries.{order_id}', function ($user, $order_id) {
    return (int) $user->id === (int) Order::findOrFail($order_id)->user_id;
});
