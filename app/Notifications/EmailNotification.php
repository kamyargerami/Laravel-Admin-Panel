<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $text, $subject, $button_text, $button_link;


    /**
     * EmailNotification constructor.
     * @param $subject
     * @param $text
     * @param null $button_text
     * @param null $button_link
     */
    public function __construct($text, $subject, $button_text = null, $button_link = null)
    {
        $this->text = $text;
        $this->subject = $subject;
        $this->button_text = $button_text;
        $this->button_link = $button_link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->line($this->text)
            ->subject($this->subject)
            ->action($this->button_text, $this->button_link);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'text' => $this->text
        ];
    }
}
