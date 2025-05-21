<?php

namespace App\Http\Requests;

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
            'nombres'            => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'apellidos'          => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'cedula_identidad'   => 'required|integer',
            'fecha_nacimiento'   => 'required|date',
            'correo_electronico' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
            'unidad_educativa'   => 'required|integer',
            'id_grado'           => 'required|integer',
            'ci_tutor'           => 'required|integer|exists:persona,ci_persona',
        ];
    }

    public function messages(): array
    {
        return [
        'nombres.required' => 'El campo nombres es obligatorio.',
        'nombres.string' => 'El campo nombres debe ser un texto.',
        'nombres.max' => 'El campo nombres no debe superar los 100 caracteres.',
        'nombres.regex' => 'El campo nombres solo puede contener letras y espacios.',

        'apellidos.string' => 'El campo apellidos debe ser un texto.',
        'apellidos.max' => 'El campo apellidos no debe superar los 100 caracteres.',
        'apellidos.required' => 'El campo apellidos es obligatorio.',
        'apellidos.regex' => 'El campo apellidos solo puede contener letras y espacios.',

        'cedula_identidad.required' => 'La cédula de identidad es obligatoria.',
        'cedula_identidad.integer' => 'La cédula de identidad debe ser un número.',
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        'fecha_nacimiento.date' => 'La fecha de nacimiento no tiene un formato válido.',
        
        'correo_electronico.email' => 'El correo electrónico no tiene un formato válido.',
        'correo_electronico.required' => 'El correo electrónico es obligatorio.',
        'correo_electronico.max' => 'El correo electrónico no debe superar los 100 caracteres.',
        'correo_electronico.regex' => 'El correo electrónico contiene caracteres no válidos.',

        'unidad_educativa.string' => 'La unidad educativa debe ser un texto.',
        'unidad_educativa.required' => 'La unidad educativa es obligatoria.',
        'unidad_educativa.integer' => 'La unidad educativa debe ser un número.',
        
        'id_grado.required' => 'El grado es obligatorio.',
        'id_grado.integer' => 'El grado debe ser un número.',
        
        'ci_tutor.required' => 'La cédula del tutor es obligatoria.',
        'ci_tutor.integer' => 'La cédula del tutor debe ser un número.',
        'ci_tutor.exists' => 'El tutor no se ha registrado en el sistema.',
        ];
    }
}
