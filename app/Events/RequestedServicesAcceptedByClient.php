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
        $line = 'El cliente: '.$this->requestedService->client->name.' ha aceptado su servicio, la ruta es: ';
        $line .= $this->requestedService->service->name_start.' hasta '.$this->requestedService->service->name_end;
        $line .= ' y el telefono de contacto es: '.$this->requestedService->client->phone;

        return [
            'driver_id' => $this->driverId,
            'message'   => $line
        ];
    }
}
