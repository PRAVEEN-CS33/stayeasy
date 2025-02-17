<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'accommodation_id' => 'required|exists:accommodation_details,accommodation_id',
            'owner_id' => 'required|exists:owners,id',
            'sharing_rent_type_id'=> 'sometimes|numeric|exists:sharing_rents,id',
            'no_of_slots'=> 'sometimes|numeric',
            'check_in' => 'sometimes|date|after_or_equal:today',
            'check_out' => 'sometimes|date|after:check_in',
            'status' => 'sometimes|string'
        ];
    }
}
