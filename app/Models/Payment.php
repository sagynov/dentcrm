<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'plan_slug',
        'amount',
        'payment_method',
        'status',
        'paid_at'
    ];
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime'
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_slug', 'slug');
    }
}
