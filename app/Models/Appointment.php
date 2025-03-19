<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'notes',
        'visit_at',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'visit_at' => 'datetime:d-m-Y H:i',
        ];
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
