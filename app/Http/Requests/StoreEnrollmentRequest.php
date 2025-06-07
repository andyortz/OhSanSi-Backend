<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
//este controlador no se está utilizando, se eliminará en la siguiente versión
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
            'id_olympic_detail' => 'required|integer|exists:olympiad,id_olympiad',
            'id_payment' => 'nullable|integer|exists:pagos,id_pago',
            'status' => 'required|string|max:50',
            'levels' => 'required|array|min:1',
            'levels.*' => 'integer|exists:category_level,id_level',
        ];
    }

    public function messages(): array
    {
        return [
            'niveles.min' => 'Debes seleccionar al menos una categoría.',
        ];
    }
}
