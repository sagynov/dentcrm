<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'speciality'
    ];
    protected $primaryKey = 'user_id';
    protected $appends = array('full_name');
    public $timestamps = false;
    
    public function getFullNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'doctor_id');
    }
}
