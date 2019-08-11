<?php

namespace App\Events;

use App\RequestedService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequestedServicesAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var RequestedService $requestedService */
    private $requestedService;

    /**
     * Create a new event instance.
     *
     * RequestedServicesAccepted constructor.
     * @param RequestedService $requestedService
     */
    public function __construct(RequestedService $requestedService)
    {
        $this->requestedService = $requestedService;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('servicesAccepted.'.$this->requestedService->client_id);
    }
}
