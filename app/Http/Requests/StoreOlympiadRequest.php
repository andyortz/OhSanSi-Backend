<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            //'id_olimpiada' => 'required|integer|unique:olympiads',
            'gestion' => 'required|integer|min:2000|max:2200',
            'costo' => 'required|numeric|min:0|max:999999.99',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'max_categorias_olimpista' => 'required|integer|min:1',
            // 'creado_en' se asignará automáticamente (timestamp)
            //
        ];
    }
    public function messages(): array
    {
        return [
            'gestion.required' => 'La gestión es obligatoria.',
            'costo.required' => 'El costo es obligatorio.',
            'fecha_inicio.required' => 'La fecha inicio es obligatoria.',
            'fecha_fin.required'   => 'La fecha fin es obligatoria.',
            'max_categorias_olimpista' => 'La cantidad maxima de categorias por olimpista es obligatoria'
        ];
    }
}
