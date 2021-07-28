<?php

namespace App\Notifications;

use App\Channels\SMSChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SMSNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $text, $template;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text, $template)
    {
        $this->afterCommit = true;
        $this->text = $text;
        $this->template = $template;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SMSChannel::class, 'database'];
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
            'text' => $this->text,
            'template' => $this->template
        ];
    }
}
