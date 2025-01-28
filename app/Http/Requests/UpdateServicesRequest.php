<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicesRequest extends FormRequest
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
            'meals' => 'sometimes|required|boolean',
            'power_backup' => 'sometimes|required|boolean',
            'workout_zone' => 'sometimes|required|boolean',
            'housekeeping' => 'sometimes|required|boolean',
            'refrigerator' => 'sometimes|required|boolean',
            'washing_machine' => 'sometimes|required|boolean',
            'hot_water' => 'sometimes|required|boolean',
            'water_purifier' => 'sometimes|required|boolean',
            'television' => 'sometimes|required|boolean',
            'biometric_entry' => 'sometimes|required|boolean',
        ];
    }
}
