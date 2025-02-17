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
            'meals' => 'boolean',
            'power_backup' => 'boolean',
            'workout_zone' => 'boolean',
            'housekeeping' => 'boolean',
            'refrigerator' => 'boolean',
            'washing_machine' => 'boolean',
            'hot_water' => 'boolean',
            'water_purifier' => 'boolean',
            'television' => 'boolean',
            'biometric_entry' => 'boolean',
        ];
    }
}
