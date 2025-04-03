<?php

namespace App\Models;

use App\Models\Scopes\DepositScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;

#[ScopedBy([DepositScope::class])]
class Deposit extends Model
{
    /** @use HasFactory<\Database\Factories\DepositFactory> */
    use HasFactory;
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'service_id',
        'amount',
        'payment_method'
    ];
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
