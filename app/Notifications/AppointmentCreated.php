<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\Channels\WhatsappChannel;
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

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if($notifiable->role == 'patient') {
            return [
                'doctor_name' => $this->appointment->doctor->full_name,
                'visit_date' => $this->appointment->visit_at->format('d-m-Y H:i')
            ];
        }else {
            return [
                'patient_name' => $this->appointment->patient->full_name,
                'visit_date' => $this->appointment->visit_at->format('d-m-Y H:i')
            ];
        }
    }

    public function toWhatsapp(object $notifiable)
    {
        if($notifiable->role == 'patient') {
            return  __('Your appointment with Dr. :doctor_name is confirmed for :visit_date', [
                'doctor_name' => $this->appointment->doctor->full_name, 
                'visit_date' => $this->appointment->visit_at->format('d-m-Y H:i')
            ]);
        }else {
            return __( 'New appointment with :patient_name on :visit_date', [
                'patient_name' => $this->appointment->patient->full_name,
                'visit_date' => $this->appointment->visit_at->format('d-m-Y H:i')
            ]);
        }
    }
}
