<?php

namespace App\Services\ImportHelpers;

class TutorResolver
{
    /**
     * Extraer los datos del tutor desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    public static function extractTutorData(array $row, $fila): array
    {
        return [
            'nombres' => $row[9],  // Columna 10 (Nombre del tutor)
            'apellidos' => $row[10],  // Columna 11 (Apellido del tutor)
            'ci' => $row[11],  // Columna 12 (CI del tutor)
            'celular' => strval($row[12]), // Columna 13 (Celular del tutor)
            'correo_electronico' => $row[13],  // Columna 14 (Correo electrónico del tutor)
            'rol_parentesco' => 'Madre',
            'fila' => $fila,
        ];
    }
}
