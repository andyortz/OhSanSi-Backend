<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'nombre' => strtoupper($this->nombre),
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|unique:area_competencia,nombre|max:50',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del área es obligatorio.',
            'nombre.unique' => 'Ya se ha registrado esta área.',
            'nombre.max' => 'El tamaño máximo para el nombre es de 50 caracteres.',
        ];
    }
}
