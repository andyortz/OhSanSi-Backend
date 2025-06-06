<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAreaLevelOlympiadRequest extends FormRequest
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
            'id_olympiad' => [
                'required',
                'integer',
                Rule::exists('olympiad', 'id_olympiad'),
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\AreaLevelOlympiad::where([
                        'id_olympiad' => $this->id_olympiad,
                        'id_area' => $this->id_area,
                        'id_level' => $this->id_level
                    ])->exists();

                    if ($exists) {
                        $fail('Esta combinación de olimpiada, área y nivel ya existe');
                    }
                }
            ],
            'id_area' => [
                'required',
                'integer',
                Rule::exists('area', 'id_area')
            ],
            'id_level' => [
                'required',
                'integer',
                Rule::exists('category_level', 'id_nivel')
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'id_olympiad.required' => 'El ID de la olimpiada es obligatorio',
            'id_olympiad.integer' => 'El ID de la olimpiada debe ser un número entero',
            'id_olympiad.exists' => 'La olimpiada especificada no existe',
            
            'id_area.required' => 'El ID del área es obligatorio',
            'id_area.integer' => 'El ID del área debe ser un número entero',
            'id_area.exists' => 'El área especificada no existe',
            
            'id_level.required' => 'El ID del nivel es obligatorio',
            'id_level.integer' => 'El ID del nivel debe ser un número entero',
            'id_level.exists' => 'El nivel especificado no existe',
        ];
    }
}
