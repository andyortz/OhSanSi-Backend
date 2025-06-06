<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOlympiadRequest extends FormRequest
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
            'year' => [
                'required',
                'integer',
                'min:2000',
                'max:2200',
                Rule::unique('olympiad')->ignore($this->olympiad) // Ignora el registro actual al editar
                
            ],
            'cost' => 'required|numeric|min:0|max:999999.99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_olympic_categories' => 'required|integer|min:1',
            // 'creado_en' se asignará automáticamente (timestamp)
            //
        ];
    }
    public function messages(): array
    {
        return [
            'year.required' => 'La gestión es obligatoria.',
            'year.unique' => 'Ya existe una olimpiada registrada para esa gestión',
            'cost.required' => 'El costo es obligatorio.',
            'start_date.required' => 'La fecha inicio es obligatoria.',
            'end_date.required'   => 'La fecha fin es obligatoria.',
            'max_olympic_categories' => 'La cantidad maxima de categorias por olimpista es obligatoria'
        ];
    }
}
