<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\DepartamentoResolver;
use App\Services\ImportHelpers\ProvinciaResolver;

class InscripcionesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows->shift(); //heads
        $rows->shift(); //example

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            if (!$row[0] || !$row[1] || !$row[2] || !$row[8]) {
                logger()->warning("Fila $index: Faltan campos obligatorios (nombre, apellido, CI, grado)");
                continue;
            }

            $idGrado = GradoResolver::resolve($row[8]);
            if (!$idGrado) {
                logger()->error("Fila $index: Grado inválido: {$row[8]}");
                continue;
            }

            $idDepartamento = DepartamentoResolver::resolve($row[5]);
            if (!$idDepartamento) {
                logger()->error("Fila $index: Departamento inválido: {$row[5]}");
                continue;
            }

            $idProvincia = ProvinciaResolver::resolve($row[6], $idDepartamento);
            if (!$idProvincia) {
                logger()->error("Fila $index: No se pudo encontrar la provincia ni asignar 'Otro'");
                continue;
            }

            $olimpista = [
                'nombres' => $row[0],
                'apellidos' => $row[1],
                'cedula_identidad' => $row[2],
                'fecha_nacimiento' => $row[3],
                'correo_electronico' => $row[4],
                'id_departamento' => $idDepartamento,
                'id_provincia' => $idProvincia,
                'unidad_educativa' => $row[7],
                'id_grado' => $idGrado,
            ];

            $tutor = [
                'nombres' => $row[9],
                'apellidos' => $row[10],
                'ci' => $row[11],
                'celular' => $row[12],
                'correo_electronico' => $row[13],
                'rol_parentesco' => $row[14],
            ];

            $inscripcion = [
                'area' => $row[15],
                'nivel_categoria' => $row[16],
            ];

            logger()->info("Fila $index procesada correctamente.", compact('olimpista', 'tutor', 'inscripcion'));
        }
    }
}
