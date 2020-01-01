<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestedDriverRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requestedService;

    /**
     * RequestedDriverRejected constructor.
     * @param $requestedService
     */
    public function __construct($requestedService)
    {
        $this->requestedService = $requestedService;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Servicio de traslado rechazado')
            ->line('El cliente: ' . $this->requestedService->client->name . ' ha rechazado su servicio')
            ->line('Muchas gracias por utilizar nuestro servicio');
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
