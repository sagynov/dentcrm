<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'features'
    ];
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'features' => 'array'
        ];
    } 
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
