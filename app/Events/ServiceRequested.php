<?php

namespace App\Events;

use App\RequestServices;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ServiceRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $requestServices;

    /**
     * ServiceRequested constructor.
     * @param RequestServices $requestServices
     */
    public function __construct(RequestServices $requestServices)
    {
        $this->requestServices = $requestServices;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('requestServices');
    }

    public function broadcastWith()
    {
        return [
            'title' => $this->requestServices->id,
        ];
    }
}
