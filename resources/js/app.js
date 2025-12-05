import Echo from "laravel-echo";
import "./bootstrap";

import Alpine, { $data } from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// private() connects to the Pusher server and subscribes to this private channel,
// even before the backend sends any notifications.
// Once subscribed, any event broadcast to this channel will be received immediately.

// هذا السطر يقوم بالاشتراك في قناة خاصة وآمنة (private channel) عبر Pusher/Echo.
// **الوظيفة:** يفتح اتصالاً بالخادم لجعل هذا المستخدم مستعداً لاستقبال أي إشعارات أو رسائل (events)

var channel = window.Echo.private(`App.Models.User.${userID}`);

channel.notification(function (data) {
    console.log(data);
    alert(data.body);
});

// var channel = window.Echo.channel("Define the channel name you want Echo to listen to");

/*        Each channel can listen for specific events.
        The listen() method takes the event name that you want to subscribe to.
        Laravel Echo automatically maps event class names to their broadcast names, so 'my-event' => 'app.events.my-event'  || '.my-event' => 'my-event'
    */
// channel.listen(".my-event", function (data) {
//     alert(JSON.stringify(data));
// });
