<?php

namespace App\Services\ImportValidators;

use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\DepartamentoResolver;
use App\Services\ImportHelpers\ProvinciaResolver;
use App\Services\ImportValidators\AreaValidator;

class InscripcionValidator
{
    public static function validarFila(array $row, int $index, array $areasValidas, int $idOlimpiada, int $maxAreas): ?array
    {
        if (!isset($row[0], $row[1], $row[2], $row[8])) {
            logger()->warning("Row $index: Missing required fields (first name, last name, ID, grade).");
            return null;
        }

        $idGrado = GradoResolver::resolve($row[8]);
        if (!$idGrado) {
            logger()->error("Row $index: Invalid grade: '{$row[8]}'.");
            return null;
        }

        $idDepartamento = DepartamentoResolver::resolve($row[5]);
        if (!$idDepartamento) {
            logger()->error("Row $index: Invalid department: '{$row[5]}'.");
            return null;
        }

        $idProvincia = ProvinciaResolver::resolve($row[6], $idDepartamento);
        if (!$idProvincia) {
            logger()->error("Row $index: Province '{$row[6]}' not found and fallback to 'Other' failed.");
            return null;
        }

        $areaValidada = AreaValidator::validarAreas(
            $row[15],         // area_1
            $row[16] ?? null, // area_2
            $areasValidas,
            $maxAreas,
            $index,
            $idOlimpiada
        );

        if (!$areaValidada) return null;

        $categoria = isset($row[17]) && trim($row[17]) !== '' ? $row[17] : null;

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
            'tutor' => [
                'nombres' => $row[9],
                'apellidos' => $row[10],
                'ci' => $row[11],
                'celular' => $row[12],
                'correo_electronico' => $row[13],
                'rol_parentesco' => $row[14],
            ],
            'inscripcion' => [
                'area_1' => $areaValidada['area_1'],
                'area_2' => $areaValidada['area_2'],
                'nivel_categoria' => $categoria, // can be null
            ]
        ];
    }
}
