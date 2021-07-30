<?php


namespace App\Services;


use App\Notifications\SMSNotification;
use Illuminate\Support\Facades\Notification;

class SMS
{
    /**
     * @param $users // user or users collection
     * this method stores notification in database
     * @param $text
     * @param null $template
     * @param null $delay
     */
    public static function notify($users, $text, $template = null, $delay = null)
    {
        Notification::send($users, (new SMSNotification($text, $template))->delay($delay));
    }

    /**
     * @param $users // user or users collection
     * this method stores notification in database
     * @param $text
     * @param null $template
     */
    public static function notifyNow($users, $text, $template = null)
    {
        Notification::sendNow($users, new SMSNotification($text, $template));
    }

    /**
     * @param $receiver // mobile number
     * this method not store notification in database
     * @param $text
     * @param null $template
     * @param null $delay
     */
    public static function send($receiver, $text, $template = null, $delay = null)
    {
        Notification::route('mobile', $receiver)->notify((new SMSNotification($text, $template))->delay($delay));
    }

    /**
     * @param $receiver // mobile number
     * this method not store notification in database
     * @param $text
     * @param null $template
     */
    public static function sendNow($receiver, $text, $template = null)
    {
        Notification::route('mobile', $receiver)->notifyNow((new SMSNotification($text, $template)));
    }
}
