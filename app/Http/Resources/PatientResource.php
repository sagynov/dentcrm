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
            'id' => $this->user_id,
            'iin' => $this->iin,
            'first_name' => $this->first_name,
            'last_name'=> $this->last_name,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date->format('d-m-Y'),
            'joined_at' => $this->pivot?->created_at->format('d-m-Y'),
        ];
    }
}
