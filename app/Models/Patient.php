<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;
    protected $fillable = [
        'iin',
        'first_name',
        'last_name',
        'birth_date'
    ];
    protected $primaryKey = 'user_id';
    protected $appends = array('full_name');
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }
    public $timestamps = false;

    public function getFullNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'user_id');
    }
    public function records()
    {
        return $this->hasMany(PatientRecord::class, 'patient_id');
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'patient_id');
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'patient_id');
    }
}
