<?php


namespace App\Services;


use App\Notifications\EmailNotification;
use Illuminate\Support\Facades\Notification;

class Email
{
    public static function to($email, $text, $subject, $button_text, $button_link, $delay = null)
    {
        Notification::route('mail', $email)->notify((new EmailNotification($text, $subject, $button_text, $button_link))->delay($delay));
    }

    public static function send($users, $text, $subject, $button_text = null, $button_link = null, $delay = null)
    {
        Notification::send($users, (new EmailNotification($text, $subject, $button_text, $button_link))->delay($delay));
    }
}
