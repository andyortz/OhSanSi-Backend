<?php

namespace App\Modules\Persons\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonaRequest extends FormRequest
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
            'ci' => 'required|integer|unique:persona,ci_persona',
            'celular' => 'nullable|integer',
            'correo_electronico' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'nombres.required' => 'El campo "nombres" es obligatorio.',
            'nombres.string' => 'El campo "nombres" debe ser un texto.',
            'nombres.max' => 'El campo "nombres" no puede exceder los 100 caracteres.',
            'nombres.regex' => 'El campo "nombres" solo puede contener letras y espacios.',
            
            'apellidos.required' => 'El campo "apellidos" es obligatorio.',
            'apellidos.string' => 'El campo "apellidos" debe ser un texto.',
            'apellidos.max' => 'El campo "apellidos" no puede exceder los 100 caracteres.',
            'apellidos.regex' => 'El campo "apellidos" solo puede contener letras y espacios.',
            
            'ci.required' => 'El campo "CI" es obligatorio.',
            'ci.integer' => 'El campo "CI" debe ser un número entero.',
            'ci.unique' => 'El "CI" ya está registrado en la base de datos.',
            
            'celular.integer' => 'El campo "celular" debe ser un número entero.',
            
            'correo_electronico.required' => 'El campo "correo electrónico" es obligatorio.',
            'correo_electronico.regex' => 'El campo "correo electrónico" no tiene un formato válido.',
            'correo_electronico.max' => 'El campo "correo electrónico" no puede exceder los 100 caracteres.',
            'correo_electronico.email' => 'El campo "correo electrónico" debe ser una dirección de correo válida.',
        ];
    }
}
