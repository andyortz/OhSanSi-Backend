<?php

namespace App\Modules\Olympiads\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOlympiadAreaLevelRequest extends FormRequest
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
            'olympiad_id' => [
                'required',
                'integer',
                Rule::exists('olympiad', 'olympiad_id'),
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\OlympiadAreaLevel::where([
                        'olympiad_id' => $this->olympiad_id,
                        'area_id' => $this->area_id,
                        'level_id' => $this->level_id
                    ])->exists();

                    if ($exists) {
                        $fail('Esta combinación de olimpiada, área y nivel ya existe');
                    }
                }
            ],
            'area_id' => [
                'required',
                'integer',
                Rule::exists('area', 'area_id')
            ],
            'level_id' => [
                'required',
                'integer',
                Rule::exists('category_level', 'level_id')
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'olympiad_id.required' => 'El ID de la olimpiada es obligatorio',
            'olympiad_id.integer' => 'El ID de la olimpiada debe ser un número entero',
            'olympiad_id.exists' => 'La olimpiada especificada no existe',
            
            'area_id.required' => 'El ID del área es obligatorio',
            'area_id.integer' => 'El ID del área debe ser un número entero',
            'area_id.exists' => 'El área especificada no existe',
            
            'level_id.required' => 'El ID del nivel es obligatorio',
            'level_id.integer' => 'El ID del nivel debe ser un número entero',
            'level_id.exists' => 'El nivel especificado no existe',
        ];
    }
}
