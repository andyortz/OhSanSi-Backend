<?php

namespace App\Modules\Persons\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTutorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'names' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci' => 'required|integer',
            'phone' => 'nullable|integer',
            'email' => 'required|email:rfc,dns|max:100',
        ];
    }
    public function messages()
    {
        return [
            'names.required' => 'El campo nombres de tutor es obligatorio.',
            'names.string' => 'El campo nombres de tutor debe ser un texto.',
            'names.max' => 'El campo nombres de tutor no puede exceder los 100 caracteres.',
            'names.regex' => 'El campo nombres de tutor solo puede contener letras y espacios.',
            
            'surnames.required' => 'El campo apellidos de tutor es obligatorio.',
            'surnames.string' => 'El campo apellidos de tutor debe ser un texto.',
            'surnames.max' => 'El campo apellidos de tutor no puede exceder los 100 caracteres.',
            'surnames.regex' => 'El campo apellidos de tutor solo pued contener letras y espacios.',
            
            'ci.required' => 'El campo CI de tutor es obligatorio.',
            'ci.integer' => 'El campo CI de tutor debe ser un número entero.',
            // 'ci.unique' => 'El CI de tutor ya está registrado en la base de datos.',
            
            'phone.string' => 'El campo celular de tutor debe ser un texto.',
            
            'email.required' => 'El campo correo electrónico de tutor es obligatorio.',
            'email.max' => 'El campo correo electrónico de tutor no puede exceder los 100 caracteres.',
            'email.regex' => 'El campo correo electrónico de tutor no tiene un formato válido.',
            'email.email' => 'El campo correo electrónico de tutor debe ser una dirección de correo válida.',
        ];
    }
}
