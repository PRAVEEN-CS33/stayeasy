<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicesResource extends JsonResource
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
            'meals' => $this->meals,
            'power_backup' => $this->power_backup,
            'workout_zone' => $this->workout_zone,
            'housekeeping' => $this->housekeeping,
            'refrigerator' => $this->refrigerator,
            'washing_machine' => $this->washing_machine,
            'hot_water' => $this->hot_water,
            'water_purifier' => $this->water_purifier,
            'television' => $this->television,
            'biometric_entry' => $this->biometric_entry,
        ];
    }
}
