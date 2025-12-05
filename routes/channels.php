<?php

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

// 'App.Models.User.{id}' -> name of the channel
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
