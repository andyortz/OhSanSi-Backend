<?php

namespace App\Services\ImportHelpers;

use App\Models\Colegio;

class ColegioResolver
{
    public static function resolve(int $idDepartamento, int $idProvincia): int
    {
        $colegio = Colegio::where('id_provincia', $idProvincia)->first();

        // Si no se encuentra, devolver el id "Otro"
        return $colegio ? $colegio->id_colegio : 1;  // 1 es el ID de "Otros"
    }
}
