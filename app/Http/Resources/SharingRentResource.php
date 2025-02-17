<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SharingRentResource extends JsonResource
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
            'accommodation_id' => $this->accommodation_id,
            'sharing_type' => $this->sharing_type,
            'rent_amount' => $this->rent_amount,
            'available_slots'=> $this->available_slots
        ];
    }
}
