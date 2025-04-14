<?php

namespace App\Services\ImportHelpers;

use App\Models\Provincia;

class ProvinciaResolver
{
    public static function resolve(string $nombre, int $idDepartamento): ?int
    {
        $id = Provincia::where('nombre_provincia', 'ilike', trim($nombre))
            ->where('id_departamento', $idDepartamento)
            ->value('id_provincia');

        if (!$id) {
            $id = Provincia::where('nombre_provincia', 'ilike', 'Otro')
                ->where('id_departamento', $idDepartamento)
                ->value('id_provincia');
        }

        return $id;
    }
}
