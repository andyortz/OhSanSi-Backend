<?php

namespace App\Http\Requests;

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
            'nombre'     => 'required|string|max:50',
            'id_area'    => 'required|integer|exists:areas_competencia,id_area',
            'grado_min'  => 'required|integer|lte:grado_max|exists:grados,id_grado',
            'grado_max'  => 'required|integer|gte:grado_min|exists:grados,id_grado',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'id_area.required' => 'El área es obligatoria.',
            'id_area.exists'   => 'El área seleccionada no es válida.',
        ];
    }
}
