<?php


namespace App\Channels;


use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receiver = isset($notifiable->routes['mobile']) ? $notifiable->routes['mobile'] : $notifiable->mobile;

        if (!$notification->text or !$receiver) {
            return false;
        }

        // TODO send sms
    }
}
