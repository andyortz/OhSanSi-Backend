<?php

namespace App\Modules\Persons\Requests;

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
            'names' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci' => 'required|integer',
            'phone' => 'nullable|integer',
            'email' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'names.required' => 'El campo "nombres del profesor" es obligatorio.',
            'names.string' => 'El campo "nombres del profesor" debe ser un texto.',
            'names.max' => 'El campo "nombres del profesor" no puede exceder los 100 caracteres.',
            'names.regex' => 'El campo "nombres del profesor" solo puede contener letras y espacios.',
            
            'surnames.required' => 'El campo "apellidos del profesor" es obligatorio.',
            'surnames.string' => 'El campo "apellidos del profesor" debe ser un texto.',
            'surnames.max' => 'El campo "apellidos del profesor" no puede exceder los 100 caracteres.',
            'surnames.regex' => 'El campo "apellidos del profesor" solo puede contener letras y espacios.',
            
            'ci.required' => 'El campo "CI del profesor" es obligatorio.',
            'ci.integer' => 'El campo "CI del profesor" debe ser un número entero.',
            
            'phone.integer' => 'El campo "celular del profesor" debe ser un número entero.',
            
            'email.required' => 'El campo "correo electrónico del profesor" es obligatorio.',
            'email.regex' => 'El campo "correo electrónico del profesor" no tiene un formato válido.',
            'email.max' => 'El campo "correo electrónico del profesor" no puede exceder los 100 caracteres.',
            'email.email' => 'El campo "correo electrónico del profesor" debe ser una dirección de correo válida.',
        ];
    }
}
