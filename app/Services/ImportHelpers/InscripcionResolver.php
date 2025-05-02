<?php

namespace App\Services\ImportHelpers;

use App\Services\ImportHelpers\NivelResolver;

class InscripcionResolver
{
    public static function extract(array $row): array
    {
        if (!isset($row[2], $row[15])) {
            throw new \Exception("Fila incompleta: faltan columnas clave (CI o Nivel)");
        }

        return [
            'cedula_identidad' => $row[2],
            'nivel' => NivelResolver::resolve($row[15]),
            'id_pago' => 1,
            'estado' => 'pendiente',
            'ci_tutor_academico' => $row[18] ?? null
        ];
    }
}
