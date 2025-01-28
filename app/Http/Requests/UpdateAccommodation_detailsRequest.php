<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccommodation_detailsRequest extends FormRequest
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
            'accommodation_name' => 'sometimes|string|max:255',
            'accommodation_types' => 'sometimes|string',
            'description' => 'sometimes|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'state' => 'sometimes|string',
            'pincode' => 'sometimes|string|max:10',
            'gender_types' => 'sometimes|string',
            'preferred_by' => 'sometimes|string',
            'image' => 'sometimes|string',
        ];
    }
}
