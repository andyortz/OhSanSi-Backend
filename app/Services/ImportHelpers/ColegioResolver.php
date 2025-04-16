<?php

namespace App\Services\ImportHelpers;

use App\Models\Colegio;

class ColegioResolver
{
    public static function resolve(int $idDepartamento, int $idProvincia): int
    {
        // Buscar el colegio por el id_provincia, ya que solo tenemos relación con provincias en el modelo Colegio
        $colegio = Colegio::where('provincia', $idProvincia)
            ->first();

        // Si no se encuentra, devolver el id "Otro"
        return $colegio ? $colegio->id_colegio : 1;  // 1 es el ID de "Otros"
    }
}
