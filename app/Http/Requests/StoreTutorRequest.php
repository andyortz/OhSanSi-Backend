<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'nombres' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'apellidos' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci' => 'required|integer|unique:persona,ci_persona',
            'celular' => 'nullable|string|max:20',
            'correo_electronico' => 'required|email:rfc,dns|max:100',
        ];
    }
    public function messages()
    {
        return [
            'nombres.required' => 'El campo nombres de tutor es obligatorio.',
            'nombres.string' => 'El campo nombres de tutor debe ser un texto.',
            'nombres.max' => 'El campo nombres de tutor no puede exceder los 100 caracteres.',
            'nombres.regex' => 'El campo nombres de tutor solo puede contener letras y espacios.',
            
            'apellidos.required' => 'El campo apellidos de tutor es obligatorio.',
            'apellidos.string' => 'El campo apellidos de tutor debe ser un texto.',
            'apellidos.max' => 'El campo apellidos de tutor no puede exceder los 100 caracteres.',
            'apellidos.regex' => 'El campo apellidos de tutor solo pued contener letras y espacios.',
            
            'ci.required' => 'El campo CI de tutor es obligatorio.',
            'ci.integer' => 'El campo CI de tutor debe ser un número entero.',
            'ci.unique' => 'El CI de tutor ya está registrado en la base de datos.',
            
            'celular.string' => 'El campo celular de tutor debe ser un texto.',
            'celular.max' => 'El campo celular de tutor no puede exceder los 20 caracteres.',
            
            'correo_electronico.required' => 'El campo correo electrónico de tutor es obligatorio.',
            'correo_electronico.email' => 'El campo correo electrónico de tutor debe ser una dirección de correo válida.',
            'correo_electronico.max' => 'El campo correo electrónico de tutor no puede exceder los 100 caracteres.',
        ];
    }
}
