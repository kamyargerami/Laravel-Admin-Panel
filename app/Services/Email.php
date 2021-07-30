<?php


namespace App\Services;


use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Notification;

class Email
{
    /**
     * @param $users // user or users collection
     * this method stores notification in database
     */
    public static function notify($users, $text, $subject, $button_text = null, $button_link = null, $delay = null)
    {
        Notification::send($users, (new EmailNotification($text, $subject, $button_text, $button_link))->delay($delay));
    }

    /**
     * @param $users // user or users collection
     * this method stores notification in database
     */
    public static function notifyNow($users, $text, $subject, $button_text = null, $button_link = null)
    {
        Notification::sendNow($users, new EmailNotification($text, $subject, $button_text, $button_link));
    }

    /**
     * @param $email // email address
     * this method not store notification in database
     */
    public static function send($email, $text, $subject, $button_text = null, $button_link = null, $delay = null)
    {
        Notification::route('mail', $email)->notify((new EmailNotification($text, $subject, $button_text, $button_link))->delay($delay));
    }

    /**
     * @param $email // email address
     * this method not store notification in database
     */
    public static function sendNow($email, $text, $subject, $button_text = null, $button_link = null)
    {
        Notification::route('mail', $email)->notifyNow(new EmailNotification($text, $subject, $button_text, $button_link));
    }
}
