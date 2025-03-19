<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'first_name' => $this->doctor->first_name,
            'last_name'=> $this->doctor->last_name,
            'full_name' => $this->doctor->full_name,
            'speciality' => $this->doctor->speciality,
            'joined_at' => $this->pivot?->created_at->format('d-m-Y')
        ];
    }
}
