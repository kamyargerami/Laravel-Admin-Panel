<?php


namespace App\Channels;


use App\Services\HttpRequestService;
use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receiver = isset($notifiable->routes['mobile']) ? $notifiable->routes['mobile'] : $notifiable->mobile;
        if (!$receiver) return false;

        switch (config('sms.provider')) {
            case 'kavenegar':
                $this->kavenegar($receiver, $notification->text, $notification->template);
                break;
            case 'armaghan':
                $this->armaghan($receiver, $notification->text);
                break;
        }
    }

    public function kavenegar($receptor, $text, $template = null)
    {
        $data = [
            'receptor' => $receptor,
            'sender' => config('sms.kavenegar.sender')
        ];

        if ($template) {
            if (!is_array($text)) return false;

            $data['template'] = $template;

            if (isset($text[0])) $data['token'] = $text[0];
            if (isset($text[1])) $data['token2'] = $text[1];
            if (isset($text[2])) $data['token3'] = $text[2];

            return HttpRequestService::send('POST', 'https://api.kavenegar.com/v1/' . config('sms.kavenegar.api') . '/verify/lookup.json', [
                'form_params' => $data
            ]);
        }

        $data['message'] = $text;

        return HttpRequestService::send('POST', 'https://api.kavenegar.com/v1/' . config('sms.kavenegar.api') . '/sms/send.json', [
            'form_params' => $data
        ]);
    }

    public function armaghan($receiver, $text)
    {
        return HttpRequestService::send('GET', 'https://negar.armaghan.net/sms/url_send.html', [
            'query' => [
                'username' => config('sms.armaghan.username'),
                'password' => config('sms.armaghan.password'),
                'originator' => config('sms.armaghan.sender'),
                'destination' => $receiver,
                'content' => $text
            ]
        ]);
    }
}
