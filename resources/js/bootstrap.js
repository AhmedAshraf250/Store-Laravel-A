window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// To access values from the .env file and inject them into JavaScript files (resources directory) during compilation.
// Laravel Mix, which handles asset building (like JavaScript), exposes the 'process.env' object for reading .env values.
// Crucially, for a variable to be accessible in the client-side JavaScript, it must be prefixed with 'MIX_'.
// Therefore, any variable in the .env file that needs to be read by JavaScript must start with "MIX_".
// Example in .env: MIX_APP_NAME="My Store"
