<?php

namespace App\Notifications\Channels;

use App\Notifications\WhatsappNotification;
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
        Http::withToken($token)
            ->post('https://waapi.app/api/v1/instances/'.$waapi_id.'/client/action/send-message',
            $data
        );
    }
}