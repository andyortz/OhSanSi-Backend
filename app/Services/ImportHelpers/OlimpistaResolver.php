<?php

namespace App\Services\ImportHelpers;

use Carbon\Carbon;

class OlimpistaResolver
{
    /**
     * Extraer los datos del olimpista desde la fila del Excel.
     * 
     * @param array $row
     * @param int $fila
     * @return array
     */
    public static function extractOlimpistaData(array $row, $fila): array
    {
        // Convertir la unidad educativa a string
        $unidadEducativa = (string) $row[7];

        // Procesar la fecha de nacimiento
        $fechaNacimiento = self::normalizarFecha($row[3]);

        return [
            'nombres' => $row[0],
            'apellidos' => $row[1],
            'cedula_identidad' => $row[2],
            'fecha_nacimiento' => $fechaNacimiento,
            'correo_electronico' => $row[4],
            'unidad_educativa' => $unidadEducativa,
            'id_grado' => $row[8],
            'ci_tutor' => $row[11],
            'fila' => $fila,
        ];
    }

    private static function normalizarFecha($valor): string
    {
        if (is_numeric($valor)) {
            return self::excelDateToDateString((int) $valor);
        }

        // Si es string y parece formato válido, devolverlo tal cual
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
            return $valor;
        }

        // Si falla, lanzar error
        throw new \Exception("Formato de fecha no reconocido: '$valor'");
    }


    private static function excelDateToDateString(int $excelDate): string
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2);
        return $carbonDate->format('Y-m-d');
    }
}
