<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_olimpista' => 'required|integer|exists:olimpistas,id_olimpista',
            'id_olimpiada' => 'required|integer|exists:olimpiadas,id_olimpiada',
            'id_pago' => 'nullable|integer|exists:pagos,id_pago',
            'estado' => 'required|string|max:50',
            'niveles' => 'required|array|min:1',
            'niveles.*' => 'integer|exists:niveles_categoria,id_nivel',
        ];
    }

    public function messages(): array
    {
        return [
            'niveles.min' => 'Debes seleccionar al menos una categoría.',
        ];
    }
}
