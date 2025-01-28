<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduledVisitsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'visit_id' => $this->id,
            'accommodation_id' => $this->accommodation_id,
            'owner_id' => $this->owner_id,
            'user_id' => $this->user_id,
            'visit_date' => $this->visit_date->format('Y-m-d'),
            'status' => $this->status,
            'accommodation_name' => $this->accommodation->accommodation_name ?? null,
        ];
    }
}
