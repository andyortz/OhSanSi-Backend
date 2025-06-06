<?php

namespace App\Http\Requests;

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
            '*.name' => 'required|string|max:50',
            '*.id_area' => 'required|exists:area,id_area',
            '*.grade_min' => 'required|integer|exists:grade,id_grado',
            '*.grade_max' => 'required|integer|exists:grade,id_grado',
        ];
    }

    public function messages(): array
    {
        return [
            '*.name.required' => 'El campo nombre es obligatorio.',
            '*.id_area.required' => 'El área es obligatoria.',
            '*.grade_min.required' => 'El grado mínimo es obligatorio.',
            '*.grade_max.required' => 'El grado máximo es obligatorio.',
            '*.grade_min.exists' => 'El grado mínimo no existe.',
            '*.grade_max.exists' => 'El grado máximo no existe.',
        ];
    }
}
