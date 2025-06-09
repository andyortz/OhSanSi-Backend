<?php

namespace App\Modules\Olympist\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOlympiadParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir a todos los usuarios hacer esta solicitud
    }

    public function rules(): array
    {
        return [
            'names'             => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames'          => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci'                => 'required|integer',
            'birthdate'         => 'required|date',
            'email'             => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
            'school'            => 'required|integer',
            'id_grade'          => 'required|integer',
            'ci_tutor'          => 'required|integer|exists:person,ci_person',
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

        'ci_person.required' => 'La cédula de identidad del olimpista es obligatoria.',
        'ci_person.integer' => 'La cédula de identidad del olimpista debe ser un número.',
        'birthdate.required' => 'La fecha de nacimiento del olimpista es obligatoria.',
        'birthdate.date' => 'La fecha de nacimiento del olimpista no tiene un formato válido.',
        
        'email.email' => 'El correo electrónico del olimpista no tiene un formato válido.',
        'email.required' => 'El correo electrónico del olimpista es obligatorio.',
        'email.max' => 'El correo electrónico del olimpista no debe superar los 100 caracteres.',
        'email.regex' => 'El correo electrónico del olimpista contiene caracteres no válidos.',

        'school.required' => 'La unidad educativa es obligatoria.',
        'school.integer' => 'La unidad educativa debe ser un número.',
        
        'id_grade.required' => 'El grado es obligatorio.',
        'id_grade.integer' => 'El grado debe ser un número.',
        
        'ci_tutor.required' => 'La cédula del tutor es obligatoria.',
        'ci_tutor.integer' => 'La cédula del tutor debe ser un número.',
        'ci_tutor.exists' => 'El tutor no se ha registrado en el sistema.',
        ];
    }
}
