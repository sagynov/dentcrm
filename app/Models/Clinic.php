<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    /** @use HasFactory<\Database\Factories\ClinicFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'specialization',
        'address',
        'phone',
        'email',
        'website',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'clinic_user', 'clinic_id', 'user_id')
            ->withTimestamps();
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_id');
    }
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'clinic_user', 'clinic_id', 'user_id')->withTimestamps();
    }
    public function patients()
    {
        return $this->belongsToMany(Patient::class,'clinic_user', 'clinic_id', 'user_id')->withTimestamps();
    }
    public function clinic_services()
    {
        return $this->hasMany(ClinicService::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
