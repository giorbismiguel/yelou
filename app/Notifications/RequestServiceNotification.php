<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestServiceNotification extends Notification
{
    use Queueable;

    protected $distanceToTravel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($distanceToTravel)
    {
        $this->distanceToTravel = $distanceToTravel;
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
        $url = url('/servicios/aceptar');

        return (new MailMessage)
            ->subject(__('app.customer_request_transportation'))
            ->line(__('app.the_distance_travel',
                ['distance' => $this->distanceToTravel, 'time' => $this->distanceToTravel / 50]))
            ->action(__('app.want_accept_request'), $url)
            ->line(__('app.click_ok_provide_transportation_service_client'));
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
