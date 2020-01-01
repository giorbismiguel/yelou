<?php

namespace App\Notifications;

use App\RequestServices;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestServiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var $driver User */
    protected $driver;

    /** @var $requestServices RequestServices */
    protected $requestServices;

    /** @var integer */
    protected $distanceToTravel;

    /**
     * RequestServiceNotification constructor.
     * @param $driver
     * @param $requestServices
     * @param $distanceToTravel
     */
    public function __construct($driver, $requestServices, $distanceToTravel)
    {
        $this->driver = $driver;
        $this->requestServices = $requestServices;
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
        $url = url('/servicios/aceptar/'.$this->requestServices->id.'/'.$this->driver->id);

        return (new MailMessage)
            ->subject(__('app.customer_request_transportation'))
            ->line(__('app.the_distance_travel', [
                'distance'     => $this->distanceToTravel,
                'time'         => calculate_time($this->distanceToTravel, 50), // 50 Km,
                'init_address' => $this->requestServices->name_start,
                'end_address'  => $this->requestServices->name_end,
                'velocity'     => '50'.'Km/h',
                'tariff'       => get_tariff($this->distanceToTravel),
            ]))
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
