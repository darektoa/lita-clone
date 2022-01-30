<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    use Queueable;

    protected $data;


    public function __construct($data)
    {
        $this->data = (object) $data;
    }


    public function via($notifiable)
    {
        $this->pushNotification($notifiable);

        return ['database'];
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    
    public function toArray($notifiable)
    {
        return [
            'title' => $this->data->title,
            'body'  => $this->data->body,
        ];
    }


    protected function getDeviceIds($notifiable) {
        return $notifiable
            ->deviceIds()
            ->get()
            ->pluck('device_id');
    }


    protected function pushNotification($notifiable) {
        $data       = $this->data;
        $recipients = $this->getDeviceIds($notifiable);
        $payloads   = [
            'title' => $data->title,
            'body'  => $data->body,
        ];

        fcm()->to($recipients)
            ->timeToLive($data->timeToLive ?? 86400) // 1 day
            ->data($payloads)
            ->notification($payloads)
            ->send();
    }
}
