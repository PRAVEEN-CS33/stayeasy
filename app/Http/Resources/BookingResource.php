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
            "id" => $this->id,
            "accommodation_id" => $this->accommodation_id,
            "accommodation_name" => $this->accommodationDetails->accommodation_name ?? null,
            "user_id" => $this->user_id,
            "user_name" => $this->user->name ?? null,
            "owner_id" => $this->owner_id,
            "sharing_rent_type_id"=> $this->sharing_rent_type_id,
            "no_of_slots" => $this->no_of_slots,
            "check_in" => $this->check_in,
            "check_out" => $this->check_out,
            "amount" => $this->amount,
            "booking_status" => $this->status,
            "booking_date" => $this->booking_date,
            "payment_status" => $this->payment->first()->payment_status ?? null
        ];
    }
}
