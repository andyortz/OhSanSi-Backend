<?php

namespace App\Services\Registers;

use App\Models\Persona;
use Illuminate\Support\Facades\DB;

class PersonaService
{
    public static function register(array $data): Persona
    {
        return DB::transaction(function () use ($data) {
            $persona = new Persona();
            $persona->ci_persona = $data['ci'];
            $persona->nombres = $data['nombres'];
            $persona->apellidos = $data['apellidos'];
            $persona->correo_electronico = $data['correo_electronico'];
            $persona->celular = $data['celular'] ?? null;
            $persona->save();

            return $persona;
        });
    }
}
