<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\Whatsapp\SendResetLink;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'phone',
        'phone_verified_at',
        'email',
        'password',
    ];
    protected $appends = array('is_owner', 'is_doctor', 'is_patient', 'is_receptionist', 'active_clinic');

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_owner' => 'boolean',
            'is_doctor' => 'boolean',
            'is_patient' => 'boolean',
        ];
    }
    public function username()
    {
        return 'phone';
    }
    protected function locale(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? Session::get('locale', config('app.locale')),
        );
    }
    
    public function preferredLocale(): string
    {
        return $this->locale;
    }

    public function getIsOwnerAttribute()
    {
        return $this->role == 'owner';
    }
    public function getIsDoctorAttribute()
    {
        return $this->role == 'doctor';
    }
    public function getIsPatientAttribute()
    {
        return $this->role == 'patient';
    }
    public function getIsReceptionistAttribute()
    {
        return $this->role == 'receptionist';
    }
    public function getActiveClinicAttribute()
    {
        if(Session::get('active_clinic')) {
            return Session::get('active_clinic');
        }
        elseif($this->clinics()->count() > 0) {
            $clinic = $this->clinics()->first();
            Session::put('active_clinic', $clinic);
            return $clinic;
        }
        return;
    }
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_user', 'user_id', 'clinic_id')
            ->withTimestamps();
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'id');
    }
    public function sendResetLink()
    {
        $recent_code = DB::table('password_reset_codes')->where([['phone', '=', $this->phone], ['valid_until', '>', now()]])->first();
        if(!$recent_code){
            $token = Str::random(60);
            DB::table('password_reset_codes')->where('phone', $this->phone)->delete();
            DB::table('password_reset_codes')->insert([
                'phone'=> $this->phone,
                'code' => rand(1000, 9999),
                'token' => $token,
                'valid_until' => now()->addHour()
            ]);
            $this->notify(new SendResetLink($token));
        }
    }
}
