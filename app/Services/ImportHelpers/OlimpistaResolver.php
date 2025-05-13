<?php

namespace App\Services\ImportHelpers;

class OlimpistaResolver
{
    /**
     * Extraer los datos del olimpista desde la fila del Excel.
     * 
     * @param array $row
     * @return array
     */
    public static function extractOlimpistaData(array $row, $fila): array
    {
        // Convertir la unidad educativa a string
        $unidadEducativa = (string) $row[7];  // Columna 8 (Unidad Educativa)
        
        // Normalizar fecha de nacimiento a formato YYYY-MM-DD
        $fechaNacimiento = null;
        if (!empty($row[3])) {
            $fechaNacimiento = \Carbon\Carbon::parse($row[3])->format('Y-m-d');
        }

        return [
            'nombres' => $row[0],  // Columna 1 (Nombre del olimpista)
            'apellidos' => $row[1],  // Columna 2 (Apellido del olimpista)
            'cedula_identidad' => $row[2],  // Columna 3 (CI del olimpista)
            'fecha_nacimiento' => $fechaNacimiento,  // Fecha convertida
            'correo_electronico' => $row[4],  // Columna 5 (Correo electrónico del olimpista)
            'unidad_educativa' => $unidadEducativa,  // Columna 8 (Unidad Educativa como string)
            'id_grado' => $row[8],  // Columna 9 (Grado)
            'ci_tutor' => $row[11],  // Columna 12 (CI del tutor)
            'fila'=> $fila,
        ];
    }

}
