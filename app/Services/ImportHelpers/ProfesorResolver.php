<?php

namespace App\Services\ImportHelpers;

class ProfesorResolver
{
    /**
     * Extraer los datos del profesor desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    public static function extractProfesorData(array $row, $fila): array
    {
        return [
            'nombres' => $row[16],  
            'apellidos' => $row[17],  
            'ci' => $row[18],  
            'celular' => strval($row[19]),
            'correo_electronico' => $row[20], 
            'rol_parentesco' => 'Madre',
            'fila' => $fila,  // Asumiendo que la fila es la primera columna
        ];
    }
}
