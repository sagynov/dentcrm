<?php

namespace App\Notifications\Whatsapp;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends WhatsappNotification implements ShouldQueue
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
            return  __("🔔 Appointment Reminder\nDear :patient_name, Your appointment with :doctor_name is in 1 hour.\n📍 Clinic Address: :address\n⏰ Appointment Time: :visit_time\n📞 Contact Number: :phone\nPlease arrive on time. If you need to reschedule, please contact us.", [
                'patient_name' => $this->appointment->patient->full_name,
                'doctor_name' => $this->appointment->doctor->full_name, 
                'address' => $this->appointment->clinic->address, 
                'phone' => $this->appointment->clinic->phone,
                'visit_time' => $this->appointment->visit_at->translatedFormat('H:i'),
            ]);
        }else {
            return  __("🔔 Appointment Reminder\nDear :doctor_name, you have an appointment in 1 hour!\n👤 Patient: :patient_name\n📞 Contact Number: :phone\n⏰ Time: :visit_time", [
                'doctor_name' => $this->appointment->doctor->full_name, 
                'patient_name' => $this->appointment->patient->full_name,
                'phone' => $this->appointment->patient->user->phone,
                'visit_time' => $this->appointment->visit_at->translatedFormat('H:i'),
            ]);
        }
    }
}
