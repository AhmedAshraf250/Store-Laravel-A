<?php

namespace App\Events;

use App\Models\Delivery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Delivery $delivery)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('deliveries');

        // return new privateChannel('deliveries' . $this->delivery->order_id); // look at channels.php
    }

    public function broadcastWith()
    {
        return [
            // 'id' => $this->delivery->id,
            'longitude' => $this->delivery->location['longitude'],
            'latitude' => $this->delivery->location['latitude'],
        ];
    }

    public function broadcastAs() // custom event name
    {
        return 'delivery.location.updated';
    }
}
