<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'iin' => $this->patient->iin,
            'first_name' => $this->patient->first_name,
            'last_name'=> $this->patient->last_name,
            'full_name' => $this->patient->full_name,
            'birth_date' => $this->patient->birth_date->format('d-m-Y'),
            'joined_at' => $this->pivot?->created_at->format('d-m-Y')
        ];
    }
}
