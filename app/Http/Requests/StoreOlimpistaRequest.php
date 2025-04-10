<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOlimpistaRequest extends FormRequest
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
            'cedula_identidad' => [
                'required',
                'numeric',
                Rule::unique('olimpistas')->ignore($this->student)
            ],
            'correo_electronico' => 'required|email',
            'fecha_nacimiento' => 'required|date',
            'unidad_educativa' => 'required|string',
            'id_grado' => 'required|integer',
        ];
    }
    public function messages(): array
    {
        return [
            'cedula_identidad.unique' => 'Ya existe un olimpista con ese numero de carnet',
            'nombres.required' => 'El nombre es obligatorio',
            'apellidos.required' => 'El apllido es obligatorio',
            'cedula_identidad.required' => 'La cedula de identidad es obligatoria',
            'correo_electronico.required' => 'El correo electronico es obligatorio',
            'unidad_educativa.required' => 'La unidad educativa es obligatoria',
            'id_grado.required' => 'El grado es obligatorio',
        ];
    }
}
