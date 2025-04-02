<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicService extends Model
{
    /** @use HasFactory<\Database\Factories\ClinicServiceFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'base_price',
        'description',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
