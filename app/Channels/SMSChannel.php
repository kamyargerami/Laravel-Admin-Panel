<?php


namespace App\Channels;


use App\Services\HttpRequestService;
use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $receiver = isset($notifiable->routes['mobile']) ? $notifiable->routes['mobile'] : $notifiable->mobile;

        if (!$notification->text or !$receiver) {
            return false;
        }

        $this->kavenegar($receiver, $notification->text);
    }

    public function kavenegar($receptor, $message, $template = null)
    {
        if (!$receptor or is_null($receptor) or !$message)
            return false;

        if ($template) {
            if (!is_array($message)) {
                return false;
            }

            $smsData = [
                'receptor' => $receptor,
                'template' => $template,
                'sender' => config('services.sms.sender')
            ];

            if (isset($message[0]))
                $smsData['token'] = $message[0];
            if (isset($message[1]))
                $smsData['token2'] = $message[1];
            if (isset($message[2]))
                $smsData['token3'] = $message[2];

            return HttpRequestService::send('POST', 'https://api.kavenegar.com/v1/' . config('services.sms.api') . '/verify/lookup.json', [
                'form_params' => $smsData
            ]);
        }

        return HttpRequestService::send('POST', 'https://api.kavenegar.com/v1/' . config('services.sms.api') . '/sms/send.json', [
            'form_params' => [
                'receptor' => $receptor,
                'message' => $message,
                'sender' => config('services.sms.sender')
            ]
        ]);
    }
}
