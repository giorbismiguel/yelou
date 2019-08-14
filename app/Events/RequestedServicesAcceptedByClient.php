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

class RequestedServicesAcceptedByClient implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $requestedService;
    private $driverId;

    /**
     * Create a new event instance.
     *
     * RequestedServicesAcceptedByClient constructor.
     * @param RequestedService $requestedService
     * @param int              $driverId
     */
    public function __construct(RequestedService $requestedService, int $driverId)
    {
        $this->requestedService = $requestedService;
        $this->driverId = $driverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('serviceAcceptedByClient.'.$this->driverId);
    }

    public function broadcastWith()
    {
        $distance = get_distance(
            $this->requestedService->service->lat_start,
            $this->requestedService->service->lng_start,
            $this->requestedService->service->lat_end,
            $this->requestedService->service->lng_end
        );
        $name = $this->requestedService->client->first_name.' '.$this->requestedService->client->last_name;

        return [
            'driver_id' => $this->driverId,
            'name'      => $name,
            'origin'    => $this->requestedService->service->name_start,
            'destiny'   => $this->requestedService->service->name_end,
            'phone'     => $this->requestedService->client->phone,
            'distance'  => $distance,
            'tariff'    => $tariff = format_money($distance),
        ];
    }
}
