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
}
