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
    public static function extractOlimpistaData(array $row, $fila, array &$resultado): array
    {
        // Convertir la unidad educativa a string
        $unidadEducativa =$row[7];

        // Procesar la fecha de nacimiento
        $fechaNacimiento = self::normalizarFecha($row[3], $fila);

        return [
            'nombres' => $row[0],
            'apellidos' => $row[1],
            'cedula_identidad' => $row[2],
            'fecha_nacimiento' => $fechaNacimiento,
            'correo_electronico' => $row[4],
            'departamento' => $row[5],
            'provincia' => $row[6],
            'unidad_educativa' => $unidadEducativa,
            'id_grado' => $row[8],
            'ci_tutor' => $row[11],
            'fila' => $fila,
        ];
    }

    private static function normalizarFecha($valor, $fila): string
{
    // Si es numérico tipo Excel
    if (is_numeric($valor)) {
        return self::excelDateToDateString((int) $valor);
    }

    // Lista de formatos aceptados
    $formatos = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y'];

    foreach ($formatos as $formato) {
        $fecha = \DateTime::createFromFormat($formato, $valor);
        if ($fecha && $fecha->format($formato) === $valor) {
            return $fecha->format('Y-m-d');
        }
    }

    // Si nada funciona, registrar error
    $resultado['olimpistas_errores'][] = [
        'ci' => 'Desconocido',
        'message' => "Formato de fecha no válido",
        'fila' => $fila + 2
    ];

    return "0000-00-00"; // Valor por defecto para no romper la lógica
}


    private static function excelDateToDateString(int $excelDate): string
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2);
        return $carbonDate->format('Y-m-d');
    }
}
