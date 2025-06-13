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
            '*.nombre' => 'required|string|max:50',
            '*.id_area' => 'required|exists:areas_competencia,id_area',
            '*.grado_min' => 'required|integer|exists:grado,id_grado',
            '*.grado_max' => 'required|integer|exists:grado,id_grado',
        ];
    }

    public function messages(): array
    {
        return [
            '*.nombre.required' => 'El campo nombre es obligatorio.',
            '*.id_area.required' => 'El área es obligatoria.',
            '*.grado_min.required' => 'El grado mínimo es obligatorio.',
            '*.grado_max.required' => 'El grado máximo es obligatorio.',
            '*.grado_min.exists' => 'El grado mínimo no existe.',
            '*.grado_max.exists' => 'El grado máximo no existe.',
        ];
    }
}
