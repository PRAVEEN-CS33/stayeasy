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
            'owner_id' => $this->owner_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name ?? null,
            'accommodation_id' => $this->accommodation_id,
            'accommodation_name' => $this->accommodationDetails->accommodation_name ?? null,
            'visit_date' => $this->visit_date,
            'status' => $this->status,
        ];
    }
}
