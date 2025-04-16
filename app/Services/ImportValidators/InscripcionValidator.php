<?php
namespace App\Services\ImportValidators;

use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\DepartamentoResolver;
use App\Services\ImportHelpers\ProvinciaResolver;

class InscripcionValidator
{
    public static function validateRow(array $row, int $index, array $validAreas, int $olympiadId, int $maxAreas): ?array
    {
        // Validar el Grado
        $grado = trim($row[8]);  // El grado está en la columna 8
        $idGrado = GradoResolver::resolve($grado);  // Usamos el GradoResolver para resolver el grado
        if (!$idGrado) {
            logger()->error("Fila $index: Grado inválido '$grado'.");
            return null;
        }

        // Validar el Departamento
        $departamento = trim($row[5]);  // El departamento está en la columna 5
        $idDepartamento = DepartamentoResolver::resolve($departamento);  // Usamos DepartamentoResolver para resolver el departamento
        if (!$idDepartamento) {
            logger()->error("Fila $index: Departamento inválido '$departamento'.");
            return null;
        }

        // Validar la Provincia
        $provincia = trim($row[6]);  // La provincia está en la columna 6
        $idProvincia = ProvinciaResolver::resolve($provincia, $idDepartamento);  // Usamos ProvinciaResolver para resolver la provincia
        if (!$idProvincia) {
            logger()->error("Fila $index: Provincia inválida '$provincia' para el departamento '$departamento'.");
            return null;
        }

        // Aquí puedes devolver los datos validados para continuar con el procesamiento o guardarlos
        return [
            'olimpista' => [
                'nombres' => $row[0],
                'apellidos' => $row[1],
                'cedula_identidad' => $row[2],
                'fecha_nacimiento' => $row[3],
                'correo_electronico' => $row[4],
                'id_departamento' => $idDepartamento,
                'id_provincia' => $idProvincia,
                'unidad_educativa' => $row[7],
                'id_grado' => $idGrado,
            ],
            // Otros campos de los datos pueden ser añadidos si es necesario
        ];
    }
}
