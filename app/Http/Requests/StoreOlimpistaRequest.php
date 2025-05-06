<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOlimpistaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir a todos los usuarios hacer esta solicitud
    }

    public function rules(): array
    {
        return [
            'nombres'            => 'required|string|max:100',
            'apellidos'          => 'required|string|max:100',
            'cedula_identidad'   => 'required|integer',
            'fecha_nacimiento'   => 'required|date',
            'correo_electronico' => 'required|email|max:100',
            'unidad_educativa'   => 'required|integer',
            'id_grado'           => 'required|integer',
            'ci_tutor'           => 'required|integer|exists:persona,ci_persona',
        ];
    }
}
