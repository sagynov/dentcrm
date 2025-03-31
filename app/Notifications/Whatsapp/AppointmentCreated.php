<?php

namespace App\Notifications\Whatsapp;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\Whatsapp\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCreated extends WhatsappNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Appointment $appointment)
    {
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsappChannel::class];
    }

    public function toWhatsapp(object $notifiable)
    {
        if($notifiable->role == 'patient') {
            return  __('Your appointment with Dr. :doctor_name is confirmed for :visit_date', [
                'doctor_name' => $this->appointment->doctor->full_name, 
                'visit_date' => $this->appointment->visit_at->translatedFormat('j F, H:i')
            ]);
        }else {
            return __( 'New appointment with :patient_name on :visit_date', [
                'patient_name' => $this->appointment->patient->full_name,
                'visit_date' => $this->appointment->visit_at->translatedFormat('j F, H:i')
            ]);
        }
    }
}
