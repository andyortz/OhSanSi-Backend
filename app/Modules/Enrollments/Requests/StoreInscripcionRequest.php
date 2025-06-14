<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
//OJITO
class StoreInscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'olympist_id' => 'required|integer|exists:olimpistas,id_olimpista',
            'olympiad_id' => 'required|integer|exists:olimpiadas,id_olimpiada',
            'payment_id' => 'nullable|integer|exists:pagos,id_pago',
            'status' => 'required|string|max:50',
            'levels' => 'required|array|min:1',
            'levels.*' => 'integer|exists:niveles_categoria,id_nivel',
        ];
    }

    public function messages(): array
    {
        return [
            'levels.min' => 'Debes seleccionar al menos una categoría.',
        ];
    }
}
