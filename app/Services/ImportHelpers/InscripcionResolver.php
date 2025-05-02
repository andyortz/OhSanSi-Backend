<?php

namespace App\Services\ImportHelpers;

use App\Services\ImportHelpers\NivelResolver;

class InscripcionResolver
{
    public static function extract(array $row): array
    {
        return [
            'ci' => $row[2], // CI Olimpista (columna 3)
            'nivel' => NivelResolver::resolve($row[15]), // ID del nivel ya resuelto
            'id_pago' => 1, // Puedes poner un dummy temporal si no tienes pago
            'estado' => 'pendiente',
            'ci_tutor_academico' => $row[18] 
        ];
    }
}
