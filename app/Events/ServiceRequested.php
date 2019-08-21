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

    /** @var RequestServices RequestServices */
    private $requestServices;

    /** @var float */
    private $distanceToTravel;

    /**
     * ServiceRequested constructor.
     * @param RequestServices $requestServices
     * @param float           $distanceToTravel
     */
    public function __construct(RequestServices $requestServices, float $distanceToTravel)
    {
        $this->requestServices = $requestServices;
        $this->distanceToTravel = $distanceToTravel;
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
        $name = $this->requestServices->user->first_name.' '.$this->requestServices->user->last_name;

        return [
            'id'                => $this->requestServices->id,
            'name'              => $name,
            'origin'            => $this->requestServices->name_start,
            'destiny'           => $this->requestServices->name_end,
            'payment'           => $this->requestServices->paymentMethod->name,
            'date'              => $this->requestServices->start_date,
            'time'              => $this->requestServices->start_time,
            'distance'          => $this->distanceToTravel,
            'services_accepted' => false,
        ];
    }
}
