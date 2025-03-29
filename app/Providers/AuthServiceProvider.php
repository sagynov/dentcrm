<?php
namespace App\Providers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Policies\AppointmentPolicy;
use App\Policies\ClinicPolicy;
use App\Policies\DoctorPolicy;
use App\Policies\PatientPolicy;
use App\Policies\PatientRecordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Clinic::class => ClinicPolicy::class,
        Doctor::class => DoctorPolicy::class,
        Patient::class => PatientPolicy::class,
        PatientRecord::class => PatientRecordPolicy::class,
        Appointment::class => AppointmentPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}