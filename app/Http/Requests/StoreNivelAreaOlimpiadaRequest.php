<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNivelAreaOlimpiadaRequest extends FormRequest
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
            'id_olimpiada' => [
                'required',
                'integer',
                Rule::exists('olimpiadas', 'id_olimpiada'),
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\NivelAreaOlimpiada::where([
                        'id_olimpiada' => $this->id_olimpiada,
                        'id_area' => $this->id_area,
                        'id_nivel' => $this->id_nivel
                    ])->exists();

                    if ($exists) {
                        $fail('Esta combinación de olimpiada, área y nivel ya existe');
                    }
                }
            ],
            'id_area' => [
                'required',
                'integer',
                Rule::exists('areas_competencia', 'id_area')
            ],
            'id_nivel' => [
                'required',
                'integer',
                Rule::exists('niveles_categoria', 'id_nivel')
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'id_olimpiada.required' => 'El ID de la olimpiada es obligatorio',
            'id_olimpiada.integer' => 'El ID de la olimpiada debe ser un número entero',
            'id_olimpiada.exists' => 'La olimpiada especificada no existe',
            
            'id_area.required' => 'El ID del área es obligatorio',
            'id_area.integer' => 'El ID del área debe ser un número entero',
            'id_area.exists' => 'El área especificada no existe',
            
            'id_nivel.required' => 'El ID del nivel es obligatorio',
            'id_nivel.integer' => 'El ID del nivel debe ser un número entero',
            'id_nivel.exists' => 'El nivel especificado no existe',
        ];
    }
}
