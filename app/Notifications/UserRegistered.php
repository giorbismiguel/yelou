<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    protected $newActivationCode;

    /**
     * Create a new notification instance.
     *
     * UserRegistered constructor.
     * @param      $code
     * @param bool $newActivationCode
     * @return void
     */
    public function __construct($code, $newActivationCode = false)
    {
        $this->code = $code;
        $this->newActivationCode = $newActivationCode;
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
        $url = url('/entrar');

        return (new MailMessage)
            ->subject(__('app.message_code_activation_subject'))
            ->line(__('app.message_code_activation', ['code' => $this->code]))
            ->action(__(
                !$this->newActivationCode ? 'app.message_code_activation_action' : 'message_code_activation_new'
            ), $url)
            ->line(__('app.message_code_activation_line2'));
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
