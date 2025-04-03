<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class DepositResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'patient' => $this->whenLoaded('patient', function(){
                return $this->patient->full_name;
            }),
            'service' => $this->whenLoaded('service', function(){
                return $this->service->name;
            }),
            'amount' => $this->amount,
            'amount_format' => Number::format($this->amount).' ₸',
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at->translatedFormat('j F, H:i'),
        ];
    }
}
