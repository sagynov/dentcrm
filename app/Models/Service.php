<?php

namespace App\Models;

use App\Models\Scopes\ServiceScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([ServiceScope::class])]
class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'clinic_service_id',
        'name',
        'price',
        'description',
        'status'
    ];
    protected function casts(): array
    {
        return [
            'price' => 'integer',
        ];
    }
    public function getPaidAttribute(): int
    {
        return $this->deposits->sum('amount');
    }
    public function getDebtAttribute(): int
    {
        return $this->price - $this->paid;
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
