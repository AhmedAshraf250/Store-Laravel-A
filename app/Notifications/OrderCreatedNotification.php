<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        /**
         * The via() method controls WHERE the notification is sent, Specify the notification delivery channels.
         *
         * Each channel (mail, database, broadcast, vonage/sms, slack, etc.) has its own configuration and requires a corresponding
         *  - toChannelName() method (e.g., toMail(), toDatabase()).
         * 
         * * $notifiable → The entity receiving the notification (typically a User).
         *               Automatically injected by Laravel when using:
         *               - $user->notify(...)
         *               - Notification::send($users, ...)
         *
         * Every channel returned by via() must have a matching method:
         *   'mail'       → toMail()
         *   'database'   → toDatabase()
         *   'vonage'     → toVonage()
         *   'broadcast'  → toBroadcast()
         */
        return ['mail', 'database', 'broadcast'];

        // Example: {"order_created": {"mail": true, "sms": false, "broadcast": true}} || json  [Multi Values]
        // User's preferences stored in JSON column: notification_preferences (JSON)
        $channels = ['database'];
        if ($notifiable->notification_preferences['order_created']['sms'] ?? false) {
            $channels[] = 'vonage';
        }
        if ($notifiable->notification_preferences['order_created']['mail'] ?? false) {
            $channels[] = 'mail';
        }
        if ($notifiable->notification_preferences['order_created']['broadcast'] ?? false) {
            $channels[] = 'broadcast';
        }
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /**
         * $notifiable → The entity (usually a User) that will receive the notification.
         *
         * Laravel automatically injects this parameter when sending the notification via:
         *
         *   1. Direct notify():
         *      $user->notify(new OrderCreatedNotification($order));
         *      → Here, $notifiable === $user
         *
         *   2. Bulk send using the Notification facade:
         *      $users = User::where('store_id', $order->store_id)->get();
         *      Notification::send($users, new OrderCreatedNotification($order));
         *      → Laravel loops through each user and calls via()/toX() with $notifiable = each user
         *
         * This allows us to access user-specific data (name, preferences, locale, etc.)
         * inside any toChannel() method.
         */
        $address = $this->order->billingAdresse;
        return (new MailMessage)
            ->subject('New Order #' . $this->order->number)
            // ->from('no-reply@ahmedIKA-store2.com', 'ahmedikaEdit') || override the default in config/mail.php
            ->greeting("Hi {$notifiable->name},")
            ->line("A new order #{$this->order->number} created by {$address->name} form {$address->country_name}.") // add like a paragraph inside message body
            ->action('View Order', url('/dashboard'))       // add a button inside message body
            ->line('Thank you for using our application!');
        /**
         * Render a fully custom Blade email template instead of the default Markdown layout.
         *
         * ->view('emails.order-created')
         *   → Bypasses Laravel's default notification mail template
         *   → Uses your own Blade file (resources/views/emails/order-created.blade.php)
         *   → Full control over HTML, CSS, branding, and layout
         *
         * Alternative customization methods:
         *
         * 1. Vendor Publish (Recommended for full redesign)
         *    php artisan vendor:publish --tag=laravel-notifications
         *    php artisan vendor:publish --tag=laravel-mail
         *    → Copies default templates to resources/views/vendor/
         *    → Edit them globally for all notifications
         *
         * 2. Use ->view() per notification (as shown here)
         *    → Best for notification-specific designs
         *
         * 3. Keep Markdown + customize theme
         *    → Create resources/views/vendor/notifications/email.blade.php
         */
    }

    public function toDatabase($notifiable)
    {

        /**
         * Define the data stored in the `notifications` table (database channel).
         *
         * This method returns an array that Laravel automatically:
         *   → JSON-encodes
         *   → Stores in the `data` column of the notifications table
         *
         * Why consistency matters:
         *   All notification classes using the database channel **must follow the same structure**.
         *   The frontend (notification dropdown, bell icon, inbox page) relies on fixed keys:
         *     - 'body'  → Message text
         *     - 'icon'  → FontAwesome / Heroicon class
         *     - 'url'   → Link when user clicks the notification
         *     - Any additional data (order_id, invoice_id, etc.)
         *
         * Keeping a unified format ensures:
         *   • Easy rendering in Blade/Livewire/Inertia/Vue
         *   • Reusable notification components
         *
         */

        $address = $this->order->billingAdresse;
        return [
            'body' => "A new order #{$this->order->number} created by {$address->name} form {$address->country_name}.",
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id, // additional data
        ];
    }

    public function toBroadcast($notifiable)
    {
        /**
         * -----------------------------------------------------------
         * 1. CONCEPT: REAL-TIME FLOW (WebSockets)
         * -----------------------------------------------------------
         * * Broadcasting is built on WebSocket technology:
         * A persistent connection between browser and server, Server pushes events instantly without HTTP polling.
         *
         * Server --(Broadcasts Message)--> [Channel] --(Listens)--> Client/Browser
         * * Listeners wait on specific "Ports/Sockets" to receive data.
         * 
         * -----------------------------------------------------------
         * 2. CHANNEL TYPES
         * -----------------------------------------------------------
         * A. Public  -> Open to everyone -> No Login required.
         * B. Private -> Secure -> Requires Login (Auth) -> Used here! ✅
         *
         * -----------------------------------------------------------
         * 3. LOGIC FOR THIS NOTIFICATION
         * -----------------------------------------------------------
         * Target: Specific User (Private Data).
         * Action: We use a 'Private Channel' unique to this user.
         *
         * -----------------------------------------------------------
         * 4. NAMING CONVENTION (routes/channels.php)
         * -----------------------------------------------------------
         * Format: 'App.Models.User.{id}'
         * Example:
         * - User ID 1 listens to ==> 'App.Models.User.1'
         * - User ID 2 listens to ==> 'App.Models.User.2'
         * 
         * * * * Authorization logic is defined in: 'routes/channels.php'
         */

        $address = $this->order->billingAdresse;
        return new BroadcastMessage([
            'body' => "A new order #{$this->order->number} created by {$address->name} form {$address->country_name}.",
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ]);

        // -----------------------------------------------------------
        // BROADCASTING HIERARCHY: CHANNELS vs. EVENTS
        // -----------------------------------------------------------
        // 1. THE CONCEPT:
        //    Think of a "Channel" as a Pipe, and "Events" as different colored balls flowing through it.
        //    Channel (Container) ----contains----> Multiple Events (Messages)
        //
        // 2. ONE CHANNEL, MANY EVENTS:
        //    A single channel can carry different types of events. We name them custom names.
        //    
        //    [ Channel: 'App.Models.User.1' ]
        //          |
        //          |----> Event A: "OrderCreated"  (Client handles order logic)
        //          |----> Event B: "NewMessage"    (Client shows popup)
        //          |----> Event C: "StatusUpdated" (Client refreshes status)
        //
        // 3. THE CLIENT'S JOB:
        //    The client connects to a Channel and "Listens" for specific Event names
        //    to handle them accordingly when they arrive.

        // -----------------------------------------------------------
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
