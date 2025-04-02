<?php

namespace App\Models;

use App\Models\Scopes\AppointmentScope;
use App\Observers\AppointmentObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([AppointmentScope::class])]
#[ObservedBy([AppointmentObserver::class])]
class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'patient_id',
        'service_id',
        'notes',
        'visit_at',
        'status',
        'reminder_sent'
    ];
    protected $appends = array('visit_hour');
    protected function casts(): array
    {
        return [
            'visit_at' => 'datetime',
            'reminder_sent' => 'boolean'
        ];
    }
    public function getVisitHourAttribute()
    {
        return $this->visit_at->format('G');
    }
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'user_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
