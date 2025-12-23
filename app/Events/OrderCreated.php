<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderCreated
{

    /*

                [ User Action / System Trigger ]
                        |
                        v
                    Event Fired
                (OrderCreatedEvent)
                        |
                        v
                Listener Catches Event
                (SendOrderNotification)
                        |
                        v
                Notification Instantiated
            (OrderCreatedNotification($order))
                        |
                        v
                    Queueable Used?
                        |
        +---------------+---------------+
        |                               |
        v                               v
    sync Queue                      database/redis
    (execute immediately)     (insert Job into jobs table)
        |                               |
        |                               |
        v                               v
    toMail(), toDatabase(),         toBroadcast() 
    (executed inline)           (executed by worker)
        |                               |
        +---------------+---------------+
                        v
                Notification Sent

    */



    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orders;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $orders, $user = null)
    {
        $this->orders = $orders;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
