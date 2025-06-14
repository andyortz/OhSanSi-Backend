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
            'name' => strtoupper($this->name),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:area,name|max:50',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El name del área es obligatorio.',
            'name.unique' => 'Ya se ha registrado esta área.',
            'name.max' => 'El tamaño máximo para el name es de 50 caracteres.',
        ];
    }
}
