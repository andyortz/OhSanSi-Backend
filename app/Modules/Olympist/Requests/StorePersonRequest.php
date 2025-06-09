<?php

namespace App\Modules\Olympist\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'names' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci_person' => 'required|integer|unique:person,ci_person',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
            'birthdate' => 'nullable|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'names.required' => 'El campo "nombres" es obligatorio.',
            'names.string' => 'El campo "nombres" debe ser un texto.',
            'names.max' => 'El campo "nombres" no puede tener más de 100 caracteres.',
            'names.regex' => 'El campo "nombres" solo puede contener letras y espacios.',

            'surnames.required' => 'El campo "apellidos" es obligatorio.',
            'surnames.string' => 'El campo "apellidos" debe ser un texto.',
            'surnames.max' => 'El campo "apellidos" no puede tener más de 100 caracteres.',
            'surnames.regex' => 'El campo "apellidos" solo puede contener letras y espacios.',

            'ci_person.required' => 'El campo "número de CI" es obligatorio.',
            'ci_person.integer' => 'El campo "número de CI" debe ser un número entero.',
            'ci_person.unique' => 'Este número de CI ya está registrado en la base de datos.',

            'phone.string' => 'El campo "teléfono" debe ser un texto.',
            'phone.max' => 'El campo "teléfono" no puede tener más de 20 caracteres.',

            'email.required' => 'El campo "correo electrónico" es obligatorio.',
            'email.regex' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El campo "correo electrónico" no puede tener más de 100 caracteres.',

            'birthdate.required' => 'El campo "fecha de nacimiento" es obligatorio.',
            'birthdate.date' => 'La "fecha de nacimiento" debe ser una fecha válida.',
            'birthdate.before_or_equal' => 'La "fecha de nacimiento" no puede ser una fecha futura.',
        ];
    }
}