<?php

namespace App\Modules\Persons\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonRequest extends FormRequest
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
            'names' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'person_ci' => 'required|integer|unique:person,person_ci',
            'phone' => 'nullable|integer',
            'email' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'names.required' => 'El campo "nombres" es obligatorio.',
            'names.string' => 'El campo "nombres" debe ser un texto.',
            'names.max' => 'El campo "nombres" no puede exceder los 100 caracteres.',
            'names.regex' => 'El campo "nombres" solo puede contener letras y espacios.',
            
            'surnames.required' => 'El campo "apellidos" es obligatorio.',
            'surnames.string' => 'El campo "apellidos" debe ser un texto.',
            'surnames.max' => 'El campo "apellidos" no puede exceder los 100 caracteres.',
            'surnames.regex' => 'El campo "apellidos" solo puede contener letras y espacios.',
            
            'ci.required' => 'El campo "CI" es obligatorio.',
            'ci.integer' => 'El campo "CI" debe ser un número entero.',
            'ci.unique' => 'El "CI" ya está registrado en la base de datos.',
            
            'phone.integer' => 'El campo "celular" debe ser un número entero.',
            
            'email.required' => 'El campo "correo electrónico" es obligatorio.',
            'email.regex' => 'El campo "correo electrónico" no tiene un formato válido.',
            'email.max' => 'El campo "correo electrónico" no puede exceder los 100 caracteres.',
            'email.email' => 'El campo "correo electrónico" debe ser una dirección de correo válida.',
        ];
    }
}
