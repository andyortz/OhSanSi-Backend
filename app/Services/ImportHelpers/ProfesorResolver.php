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
    public static function extractProfesorData(array $row): array
    {
        return [
            'nombresProfesor' => $row[17],  
            'apellidosProfesor' => $row[18],  
            'cisProfesor' => $row[19],  
            'celularsProfesor' => $row[20], 
            'correo_electronicosProfesor' => $row[21], 
        ];
    }
}
