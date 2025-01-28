<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('owner')->check();
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
            'meals' => 'required|boolean',
            'power_backup' => 'required|boolean',
            'workout_zone' => 'required|boolean',
            'housekeeping' => 'required|boolean',
            'refrigerator' => 'required|boolean',
            'washing_machine' => 'required|boolean',
            'hot_water' => 'required|boolean',
            'water_purifier' => 'required|boolean',
            'television' => 'required|boolean',
            'biometric_entry' => 'required|boolean',
        ];
    }
}
