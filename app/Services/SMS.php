<?php


namespace App\Services;


use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Notification;

class SMS
{
    public static function to($receiver, $text, $delay = null)
    {
        Notification::route('mobile', $receiver)->notify((new SMSNotification($text))->delay($delay));
    }

    public static function send($users, $text, $delay = null)
    {
        Notification::send($users, (new SMSNotification($text))->delay($delay));
    }
}
