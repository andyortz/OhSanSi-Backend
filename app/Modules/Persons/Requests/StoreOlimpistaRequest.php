<?php

namespace App\Modules\Persons\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOlimpistaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir a todos los usuarios hacer esta solicitud
    }

    public function rules(): array
    {
        return [
            'names'              => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames'           => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci'                 => 'required|integer', //cedula_identidad
            'birthdate'   => 'required|date',
            'email' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
            'school'   => 'required|integer',
            'grade_id'           => 'required|integer',
            'tutor_ci'           => 'required|integer|exists:persona,ci_persona',
        ];
    }

    public function messages(): array
    {
        return [
        'names.required' => 'El campo nombres del olimpista es obligatorio.',
        'names.string' => 'El campo nombres del olimpista debe ser un texto.',
        'names.max' => 'El campo nombres del olimpista no debe superar los 100 caracteres.',
        'names.regex' => 'El campo nombres del olimpista solo puede contener letras y espacios.',

        'surnames.string' => 'El campo apellidos del olimpista debe ser un texto.',
        'surnames.max' => 'El campo apellidos del olimpista no debe superar los 100 caracteres.',
        'surnames.required' => 'El campo apellidos del olimpista es obligatorio.',
        'surnames.regex' => 'El campo apellidos del olimpista solo puede contener letras y espacios.',

        'ci.required' => 'La cédula de identidad del olimpista es obligatoria.', //cedula_identidad
        'ci.integer' => 'La cédula de identidad del olimpista debe ser un número.', //cedula_identidad
        'birthdate.required' => 'La fecha de nacimiento del olimpista es obligatoria.',
        'birthdate.date' => 'La fecha de nacimiento del olimpista no tiene un formato válido.',
        
        'email.email' => 'El correo electrónico del olimpista no tiene un formato válido.',
        'email.required' => 'El correo electrónico del olimpista es obligatorio.',
        'email.max' => 'El correo electrónico del olimpista no debe superar los 100 caracteres.',
        'email.regex' => 'El correo electrónico del olimpista contiene caracteres no válidos.',

        'school.required' => 'La unidad educativa es obligatoria.',
        'school.integer' => 'La unidad educativa debe ser un número.',
        
        'grade_id.required' => 'El grado es obligatorio.',
        'grade_id.integer' => 'El grado debe ser un número.',
        
        'tutor_ci.required' => 'La cédula del tutor es obligatoria.',
        'tutor_ci.integer' => 'La cédula del tutor debe ser un número.',
        'tutor_ci.exists' => 'El tutor no se ha registrado en el sistema.',
        ];
    }
}
