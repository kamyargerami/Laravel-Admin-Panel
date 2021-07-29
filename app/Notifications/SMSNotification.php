<?php

namespace App\Notifications;

use App\Channels\SMSChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SMSNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $text, $template, $receiver;

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
        $this->receiver = $notifiable->routes['mobile'] ?? $notifiable->mobile;

        if (!$this->receiver)
            return [];

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
        $data['text'] = $this->text;

        if ($this->template) $data['template'] = $this->template;

        return $data;
    }
}
