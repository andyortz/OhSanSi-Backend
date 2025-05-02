<?php

namespace App\Services\ImportHelpers;

use App\Models\Provincia;

class ProvinciaResolver
{
    public static function resolve(string $nombreProvincia, int $idDepartamento): ?int
    {
        // Try to find the province by its name and department ID
        $provincia = Provincia::where('nombre_provincia', 'ilike', trim($nombreProvincia))
            ->where('id_departamento', $idDepartamento)
            ->first();

        if ($provincia) {
            return $provincia->id_provincia;
        }

        // If no province found, set it to "Otro" for the same department
        return Provincia::where('nombre_provincia', 'ilike', 'Otro')
            ->where('id_departamento', $idDepartamento)
            ->value('id_provincia');
    }
}
