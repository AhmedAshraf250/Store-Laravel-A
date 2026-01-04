<?php

/*******************************************************************************
 * THE CORE LOGIC: HOW DATA FLOWS FROM CODE TO SOCKET
 *******************************************************************************/

// 1. THE TRIGGER (Notification Class)
// Location: app/Notifications/OrderCreatedNotification.php
class OrderCreatedNotification extends Notification {
    public function via($notifiable) { return ['broadcast']; }

    // If this method is missing, Laravel defaults to: "App.Models.User.{id}"
    public function broadcastOn() {
        return new PrivateChannel('App.Models.User.' . $this->user->id);
    }
}


// 2. THE SECURITY GATE (Authorization)
// Location: routes/channels.php
/**
 * Understanding: Broadcast::channel('App.Models.User.{id}', function ($user, $id))
 * * @param $user : The "Authenticated User" (The person trying to connect). 
 * Laravel injects this automatically.
 * @param $id   : The "Route Parameter". 
 * It is extracted from the {id} in the string.
 * * Logic: "Only let the user join the channel if their ID matches the channel name."
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id; 
});


// 3. THE FRONTEND (Listening)
// Location: resources/js/app.js
/*
Echo.private(`App.Models.User.${userId}`)
    .notification((data) => {
        console.log("Notification received via the secure pipe!");
    });
*/

/*******************************************************************************
 * QUICK SUMMARY:
 * 1. The Notification defines the ADDRESS (e.g., App.Models.User.5).
 * 2. The Browser tries to connect to that ADDRESS.
 * 3. Laravel intercepts the connection and runs the 'channels.php' function.
 * 4. In the function: $user is "Who is asking", $id is "Which door are they at".
 * 5. If they match (Return True), the "Pipe" is opened.
 *******************************************************************************/

/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/

/*******************************************************************************
 * SECTION 1: BROADCAST CHANNEL AUTHORIZATION
 * Location: routes/channels.php
 * * This is the SECURITY LAYER. It verifies if the user is allowed to "listen" 
 * to the stream. We use a custom naming convention instead of the default.
 *******************************************************************************/

Broadcast::channel('shipment-tracking.{orderId}.{secureCode}', function ($user, $orderId, $secureCode) {
    // Fetch the order from the database
    $order = \App\Models\Order::find($orderId);

    // LOGIC: User must own the order AND provide the correct security token
    return $order && (int) $user->id === (int) $order->user_id && $order->secure_token === $secureCode;
});

/*******************************************************************************
 * SECTION 2: THE NOTIFICATION CLASS (The Data Wrapper)
 * Location: app/Notifications/ShipmentUpdate.php
 * * This is the DATA LAYER. It prepares the message and defines the "Destination".
 *******************************************************************************/

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class ShipmentUpdate extends Notification
{
    public $order;

    public function __construct($order) {
        $this->order = $order;
    }

    // Define delivery channels (Broadcast only)
    public function via($notifiable) {
        return ['broadcast'];
    }

    // EXPLICIT CHANNEL: Overriding the default channel naming
    public function broadcastOn() {
        return new PrivateChannel("shipment-tracking.{$this->order->id}.{$this->order->secure_token}");
    }

    // PAYLOAD: The actual data that will travel through the "Pipe" (Socket)
    public function toBroadcast($notifiable) {
        return new BroadcastMessage([
            'order_id'         => $this->order->id,
            'current_location' => $this->order->last_location,
            'status'           => 'In Transit',
        ]);
    }
}

/*******************************************************************************
 * SECTION 3: THE TRIGGER (The Event Starter)
 * Location: app/Http/Controllers/OrderController.php
 * * This is the ACTION LAYER. Where PHP tells the system: "Hey, something happened!"
 *******************************************************************************/

// Inside your Controller method:
public function shipOrder($id) 
{
    $order = Order::findOrFail($id);
    
    // Step 1: Logic (Update DB)
    $order->update(['last_location' => 'Warehouse A']);

    // Step 2: Trigger the Notification
    // This sends the data to the Broadcaster (Pusher/Reverb) via API call
    $order->user->notify(new ShipmentUpdate($order));
}

/*******************************************************************************
 * SECTION 4: THE CLIENT-SIDE LISTENER (The Receiver)
 * Location: resources/js/app.js (or any Blade file)
 * * This is the UI LAYER. It keeps the "Socket" open to catch incoming data.
 *******************************************************************************/

/**
 * [JAVASCRIPT CODE]
 * 1. Connect to the custom private channel.
 * 2. Listen for the notification event.
 */
/*
Echo.private(`shipment-tracking.${orderId}.${token}`)
    .notification((data) => {
        console.log("Real-time update received:", data.status);
        // DOM Manipulation to update the map or status text
        document.getElementById('status').innerText = data.current_location;
    });
*/

/*******************************************************************************
 * SUMMARY OF DATA FLOW:
 * 1. Controller triggers Notify.
 * 2. Notification Class builds JSON + PrivateChannel name.
 * 3. Laravel sends JSON to the Broadcaster (Pusher/Reverb).
 * 4. Broadcaster pushes JSON to all users connected to that specific channel string.
 * 5. Echo (JS) receives the JSON and updates the screen instantly.
 *******************************************************************************/

/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/

/*******************************************************************************
 * BROADCASTING METHODS REFERENCE (Events & Notifications)
 * * This list covers how to control "Where, What, and When" you broadcast.
 *******************************************************************************/

// --- FOR EVENTS (using ShouldBroadcast) ---

/**
 * 1. broadcastOn()   -> (The Address)
 * DESTINATION: Defines which channel(s) the event should be sent to.
 * Returns: A Channel, PrivateChannel, or PresenceChannel (or an array of them).
 */
public function broadcastOn() { return new PrivateChannel('room.1'); }

/**
 * 2. broadcastWith()   -> (The Content)
 * PAYLOAD: Defines the specific data sent to JavaScript. 
 * DEFAULT BEHAVIOR: If you don't use this, Laravel sends all public properties of the event.
 */
public function broadcastWith() { return ['id' => $this->user->id]; }

/**
 * 3. broadcastAs()   -> (The Name/Alias)
 * Purpose: Customizes the event name that JavaScript (Echo) listens for.
 * DEFAULT BEHAVIOR: Laravel uses the full class name (e.g., App\Events\OrderCreated).
 */
public function broadcastAs() { return 'order.placed'; }
// In JS: .listen('.order.new', (e) => { ... }) // Note the dot prefix in Echo

/**
 * 4. broadcastWhen()   -> (The Condition)
 * CONDITION: Only broadcasts if this returns true.
 */
public function broadcastWhen() { return $this->order->total > 100; }

/**
 * 5. broadcastQueue()   -> (The Queue Customization)
 * PERFORMANCE: Specifies which queue name the broadcast job should be pushed to.
 */
public function broadcastQueue() { return 'broadcast-queue'; }


// --- FOR NOTIFICATIONS (using Broadcast Channel) ---

/**
 * 6. toBroadcast()
 * PAYLOAD: Equivalent to broadcastWith() but for Notifications.
 */
public function toBroadcast($notifiable) { 
    return new BroadcastMessage(['msg' => 'Hello']); 
}

/**
 * 7. broadcastType()
 * ALIAS: Customizes the 'type' field in the JSON sent to the browser.
 * In JS, this appears as 'type' inside the notification object.
 */
public function broadcastType() { return 'system.alert'; }

/*******************************************************************************/
/*******************************************************************************/
/*******************************************************************************/
