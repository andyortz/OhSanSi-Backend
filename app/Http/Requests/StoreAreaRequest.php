<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
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
            'id_olimpiada' => 'required|integer|exists:olimpiadas,id_olimpiada',
            'nombre' => 'required|string|unique|max:50',
            'imagen' => 'required|image|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'id_olimpiada.required' => 'La olimpiada es obligatoria',
            'id_olimpiada.exists' => 'La olimpiada seleccionada no existe',
            'nombre.required' => 'El nombre del área es obligatorio',
            'nombre.unique' => 'Ya se ha registrado esta area',
            'nombre.max' => 'El tamaño máximo para el nombre es de 50 carácteres',
            'imagen.required' => 'La imagen es obligatoria',
            'imagen.image' => 'El archivo debe ser una imagen válida',
            'imagen.max' => 'La imagen no debe superar los 2MB'
        ];
    }
}
