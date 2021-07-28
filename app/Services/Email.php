<?php


namespace App\Services;


use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Notification;

class Email
{
    public static function notify($users, $subject, $text, $button_text = null, $button_link = null, $delay = null)
    {
        Notification::send($users, (new EmailNotification($subject, $text, $button_text, $button_link))->delay($delay));
    }
}
