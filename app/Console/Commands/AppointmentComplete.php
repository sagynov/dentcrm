<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class AppointmentComplete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete scheduled appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Appointment::where([['visit_at', '<', now()], ['status', '=', 'scheduled']])->update(['status' => 'completed']);
        $this->info('Appointments completed!');
    }
}
