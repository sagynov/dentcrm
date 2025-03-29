<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientRecordResource extends JsonResource
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
            'doctor'=> $this->doctor->full_name,
            'clinic' => $this->clinic->name,
            'comment' => $this->comment,
            'attachments' => $this->attachments,
            'created_at' => $this->created_at->translatedFormat('j F Y')
        ];
    }
}
