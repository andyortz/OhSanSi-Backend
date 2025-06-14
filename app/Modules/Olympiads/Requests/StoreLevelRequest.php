<?php

namespace App\Modules\Olympiads\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLevelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.level_name' => 'required|string|max:50',
            '*.area_id' => 'required|exists:area,area_id',
            '*.grade_min' => 'required|integer|exists:grade,grade_id',
            '*.grade_max' => 'required|integer|exists:grade,grade_id',
        ];
    }

    public function messages(): array
    {
        return [
            '*.level_name.required' => 'El campo nombre es obligatorio.',
            '*.area_id.required' => 'El área es obligatoria.',
            '*.grade_min.required' => 'El grado mínimo es obligatorio.',
            '*.grade_max.required' => 'El grado máximo es obligatorio.',
            '*.grade_min.exists' => 'El grado mínimo no existe.',
            '*.grade_max.exists' => 'El grado máximo no existe.',
        ];
    }
}
