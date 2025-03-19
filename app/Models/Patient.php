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
    protected function casts(): array
    {
        return [
            'birth_date' => 'date:d-m-Y',
        ];
    }
    public $timestamps = false;
}
