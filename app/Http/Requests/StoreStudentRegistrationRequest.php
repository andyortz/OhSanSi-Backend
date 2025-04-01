<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'cedula_identidad' => 'required|numeric',
            'numero_celular' => 'required|numeric',
            'correo_electronico' => 'required|email',
            'fecha_nacimiento' => 'required|date',
            'unidad_educativa' => 'required|string',
            'id_grado' => 'required|integer',
            'id_provincia' => 'required|integer',
            'id_tutor' => 'required|integer'
        ];
    }
    public function messages(): array
    {
        return [
            'nombres.required' => 'El nombre es obligatorio',
            'apellidos.required' => 'El apllido es obligatorio',
            'cedula_identidad.required' => 'La cedula de identidad es obligatoria',
            'numero_celular.required' => 'El numero de celular es obligatorio',
            'correo_electronico.required' => 'El correo electronico es obligatorio',
            'unidad_educativa.required' => 'La unidad educativa es obligatoria',
            'id_grado.required' => 'El grado es obligatorio',
            'id_provincia.required' => 'La provincia es obligatoria',
            'id_tutor.required' => 'El tutor es obligatorio',
            //'fecha_nacimiento.before_or_equal' => 'El estudiante debe tener al menos 13 años.',
            // 'cedula_identidad.unique' => 'La cédula ya está registrada.',
            // 'correo_electronico.unique' => 'El correo electrónico ya está en uso.',
            // 'id_grado.exists' => 'El grado seleccionado no existe.',
            // 'id_provincia.exists' => 'La provincia seleccionada no existe.',
            // 'id_tutor.exists' => 'El tutor seleccionado no existe.'
        ];
    }
}
