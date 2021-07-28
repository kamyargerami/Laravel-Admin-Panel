<?php


namespace App\Services;


use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Notification;

class SMS
{
    public static function to($receiver, $text, $template = null, $delay = null)
    {
        Notification::route('mobile', $receiver)->notify((new SMSNotification($text, $template))->delay($delay));
    }

    public static function send($users, $text, $template = null, $delay = null)
    {
        Notification::send($users, (new SMSNotification($text, $template))->delay($delay));
    }
}
