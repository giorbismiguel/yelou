<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestedDriverAccepted extends Notification
{
    use Queueable;

    protected $requestedService;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($requestedService)
    {
        $this->requestedService = $requestedService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $line = 'El cliente: ' . $this->requestedService->client->name . ' ha aceptado su servicio, la ruta es: ';
        $line .= $this->requestedService->service->route->name_start . ' hasta ' . $this->requestedService->service->route->name_start;
        $line .= ' y el telefono de contacto es: ' . $this->requestedService->client->phone;

        return (new MailMessage)
            ->line($line)
            ->line('Muchas gracias por utilizar nuestro servicio');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
