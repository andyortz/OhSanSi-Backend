<?php

namespace App\Modules\Olympiads\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'area_name' => strtoupper($this->area_name),
        ]);
    }

    public function rules(): array
    {
        return [
            'area_name' => 'required|string|unique:area,name|max:50',
        ];
    }

    public function messages()
    {
        return [
            'area_name.required' => 'El nombre del área es obligatorio.',
            'area_name.unique' => 'Ya se ha registrado esta área.',
            'area_name.max' => 'El tamaño máximo para el nombre es de 50 caracteres.',
        ];
    }
}
