<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\Whatsapp\AppointmentReminderNotification;
use Illuminate\Console\Command;

class AppointmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an appointment reminder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appointments = Appointment::whereBetween('visit_at', [now(), now()->addHour()])->where('reminder_sent', 0)->with(['clinic', 'doctor.user', 'patient.user'])->get();
        foreach ($appointments as $appointment) {
            $appointment->patient->user->notify(new AppointmentReminderNotification($appointment));
            $appointment->doctor->user->notify(new AppointmentReminderNotification($appointment));
        }
        Appointment::whereIn('id', $appointments->pluck('id'))->update(['reminder_sent' => 1]);
        $this->info('Appointment reminder sent!');
    }
}
