<?php

namespace App\Modules\Olympiads\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNivelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:50',
            '*.area_id' => 'required|exists:areas,area_id',
            '*.grade_min' => 'required|integer|exists:grade,grade_id',
            '*.grade_max' => 'required|integer|exists:grade,grade_id',
        ];
    }

    public function messages(): array
    {
        return [
            '*.name.required' => 'El campo name es obligatorio.',
            '*.area_id.required' => 'El área es obligatoria.',
            '*.grado_min.required' => 'El grado mínimo es obligatorio.',
            '*.grado_max.required' => 'El grado máximo es obligatorio.',
            '*.grado_min.exists' => 'El grado mínimo no existe.',
            '*.grado_max.exists' => 'El grado máximo no existe.',
        ];
    }
}
