<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "user_id" => $this->user_id,
            "accommodation_id"> $this->accommodation_id,
            "owner_id"=>$this->owner_id,
            "check_in"=> $this->check_in,
            "check_out"=> $this->check_out,
            "amount"=> $this->amount,
            "status"=> $this->status,
            "booking_date"=> $this->booking_date
        ];
    }
}
