<?php


namespace App\Services;


use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Notification;

class SMS
{
    public static function send($receiver, $text)
    {
        dd($receiver, $text);
        // Todo send sms
    }

    public static function notify($users, $text, $delay = null)
    {
        Notification::send($users, (new SMSNotification($text))->delay($delay));
    }
}
