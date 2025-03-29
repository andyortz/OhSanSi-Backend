<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNivelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ← permite ejecutar la request (importante si no usas Gates o Policies)
    }

    public function rules(): array
    {
        return [
            'nombre'   => 'required|string|max:50',
            'codigo'   => 'required|string|max:10',
            'id_area'  => 'required|integer|exists:areas_competencia,id_area',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'codigo.required' => 'El código es obligatorio.',
            'id_area.required' => 'El área es obligatoria.',
            'id_area.exists'   => 'El área seleccionada no es válida.',
        ];
    }
}
