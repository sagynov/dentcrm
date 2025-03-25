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
            'id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name'=> $this->last_name,
            'full_name' => $this->full_name,
            'speciality' => $this->speciality,
            'appointments' => ScheduleResource::collection($this->whenLoaded('appointments')),
            'joined_at' => $this->pivot?->created_at->translatedFormat('j F Y'),
        ];
    }
}
