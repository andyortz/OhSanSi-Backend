<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProfesorRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la solicitud.
     */
    public function rules(): array
    {
        return [
            'nombres' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'apellidos' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci' => 'required|integer',
            'celular' => 'nullable|integer',
            'correo_electronico' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'nombres.required' => 'El campo "nombres del profesor" es obligatorio.',
            'nombres.string' => 'El campo "nombres del profesor" debe ser un texto.',
            'nombres.max' => 'El campo "nombres del profesor" no puede exceder los 100 caracteres.',
            'nombres.regex' => 'El campo "nombres del profesor" solo puede contener letras y espacios.',
            
            'apellidos.required' => 'El campo "apellidos del profesor" es obligatorio.',
            'apellidos.string' => 'El campo "apellidos del profesor" debe ser un texto.',
            'apellidos.max' => 'El campo "apellidos del profesor" no puede exceder los 100 caracteres.',
            'apellidos.regex' => 'El campo "apellidos del profesor" solo puede contener letras y espacios.',
            
            'ci.required' => 'El campo "CI del profesor" es obligatorio.',
            'ci.integer' => 'El campo "CI del profesor" debe ser un número entero.',
            
            'celular.integer' => 'El campo "celular del profesor" debe ser un número entero.',
            
            'correo_electronico.required' => 'El campo "correo electrónico del profesor" es obligatorio.',
            'correo_electronico.regex' => 'El campo "correo electrónico del profesor" no tiene un formato válido.',
            'correo_electronico.max' => 'El campo "correo electrónico del profesor" no puede exceder los 100 caracteres.',
            'correo_electronico.email' => 'El campo "correo electrónico del profesor" debe ser una dirección de correo válida.',
        ];
    }
}
