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
            //
            'nombres' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'ci' => 'required|integer',
            'celular' => 'required|digits:8',
            'correo_electronico' => 'required|email|unique:tutores',
            'rol_parentesco' => 'required|string'
        ];
    }
    public function messages()
    {
        return [
            'nombres' => 'Error al ingresar el nombre',
            'apellidos' => 'Error al ingresar el apellido',
            'ci' => 'Solo se acepta caracteres numéricos',
            'celular' => 'el número debe ser de 8 dígitos',
            'correo_electronico' => 'Correo electrónico no válido',
            'rol_parentesco' => 'Seleccione un rol válido'
        ];
    }
}
