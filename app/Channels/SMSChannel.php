<?php


namespace App\Channels;


use App\Services\SMS;
use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$notification->text or !$notifiable->mobile) {
            return false;
        }

        SMS::send($notifiable->mobile, $notification->text);
    }
}
