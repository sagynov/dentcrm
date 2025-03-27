<?php

namespace App\Models;

use App\Observers\AppointmentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([AppointmentObserver::class])]
class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'notes',
        'visit_at',
        'status',
    ];
    protected $appends = array('visit_hour');
    protected function casts(): array
    {
        return [
            'visit_at' => 'datetime',
        ];
    }
    public function getVisitHourAttribute()
    {
        return $this->visit_at->format('G');
    }
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'patient_id');
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'doctor_id');
    }
}
