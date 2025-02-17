<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSharingRentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('owner')->check();
    }

    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.*.accommodation_id' => 'required|exists:accommodation_details,accommodation_id',
            'data.*.sharing_type' => 'required|string',
            'data.*.rent_amount' => 'required|numeric',
            'data.*.available_slots' => 'required|numeric',
        ];
    }
}
