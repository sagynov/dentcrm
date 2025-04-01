<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'patient'=> $this->patient->full_name,
            'visit_at' => $this->visit_at->translatedFormat('j F, H:i'),
            'visit_hour' => $this->visit_hour,
            'status' => $this->status,
        ];
    }
}
