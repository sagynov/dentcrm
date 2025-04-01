<?php

namespace App\Notifications\Whatsapp;

use App\Notifications\Whatsapp\WhatsappNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class WhatsappChannel extends Notification
{
    /**
     * Отправить переданное уведомление.
     */
    public function send(object $notifiable, WhatsappNotification $notification): void
    {
        if (!$notifiable->phone) {
            return;
        }
        $message = $notification->toWhatsapp($notifiable);
        $this->sendMessage($notifiable->phone, $message);
    }
    public function sendMessage($phone, $message)
    {
        $waapi_id = env('WAAPI_ID');
        $token = env('WAAPI_TOKEN');
        $data = [
            'chatId' => $phone.'@c.us',
            'message' => $message,
        ];
        Http::timeout(60)->withToken($token)
            ->post('https://waapi.app/api/v1/instances/'.$waapi_id.'/client/action/send-message',
            $data
        );
    }
}