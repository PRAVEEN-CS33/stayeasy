<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class 
StoreAccommodation_detailsRequest extends FormRequest
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
            'accommodation_name' => 'required|string|max:255',
            'accommodation_types' => 'required|string',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|string|max:10',
            'gender_types' => 'required|string',
            'preferred_by' => 'required|string',
            'image' => 'nullable|string',
        ];
    }
}
