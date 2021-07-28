<?php


namespace App\Services;


use App\Notifications\Email;
use Illuminate\Support\Facades\Notification;

class MailService
{
    public static function send($users, $subject, $text, $button_text, $button_link, $delay = null)
    {
        Notification::send($users, (new Email($subject, $text, $button_text, $button_link))->delay($delay));
    }
}
