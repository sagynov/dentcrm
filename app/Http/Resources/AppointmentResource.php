<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'doctor' => $this->whenLoaded('doctor', function(){
                return $this->doctor->full_name;
            }),
            'service' => $this->whenLoaded('service', function(){
                return $this->service->name;
            }),
            'notes' => $this->notes,
            'visit_at' => $this->visit_at->translatedFormat('j F, H:i'),
            'status' => $this->status
        ];
    }
}
