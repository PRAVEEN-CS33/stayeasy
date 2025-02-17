<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingsRequest extends FormRequest
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
        // dd('ok');
        return [
            'accommodation_id' => 'required|exists:accommodation_details,accommodation_id',
            'owner_id' => 'required|exists:owners,id',
            'sharing_rent_type_id'=> 'required|numeric|exists:sharing_rents,id',
            'no_of_slots'=> 'required|numeric',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'amount' => 'required|numeric|min:0',
            'status' => 'string',
        ];
    }
}
