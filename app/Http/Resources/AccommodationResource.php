<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccommodationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'accommodation_id' => $this->accommodation_id,
            'accommodation_name' => $this->accommodation_name,
            'accommodation_types' => $this->accommodation_types,
            'owner_id' => $this->owner_id,
            'description' => $this->description,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'gender_types' => $this->gender_types,
            'preferred_by' => $this->preferred_by,
            'services' => new ServiceResource($this->services),
            'reviews' => ReviewResource::collection($this->reviews),
            'sharingRents' => SharingRentResource::collection($this->sharingRents),
        ];
    }
}
