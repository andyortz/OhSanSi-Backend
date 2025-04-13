<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InscripcionesImport implements ToCollection
{
    private $grados = [
        '1ro de Primaria' => 1,
        '2do de Primaria' => 2,
        '3ro de Primaria' => 3,
        '4to de Primaria' => 4,
        '5to de Primaria' => 5,
        '6to de Primaria' => 6,
        '1ro de Secundaria' => 7,
        '2do de Secundaria' => 8,
        '3ro de Secundaria' => 9,
        '4to de Secundaria' => 10,
        '5to de Secundaria' => 11,
        '6to de Secundaria' => 12,
    ];

    public function collection(Collection $rows)
    {
        $rows->shift();

        foreach ($rows as $index => $row) {
            // Ignorar filas completamente vacías
            if ($row->filter()->isEmpty()) continue;

            if (!$row[0] || !$row[1] || !$row[2] || !$row[8]) {
                logger()->warning("Fila $index: Faltan campos obligatorios (nombre, apellido, CI, grado)");
                continue;
            }

            $gradoTexto = trim($row[8]);
            $idGrado = $this->grados[$gradoTexto] ?? null;
            if (!$idGrado) {
                logger()->error("Fila $index: Grado inválido: $gradoTexto");
                continue;
            }

            $olimpista = [
                'nombres' => $row[0],
                'apellidos' => $row[1],
                'cedula_identidad' => $row[2],
                'fecha_nacimiento' => $row[3],
                'correo_electronico' => $row[4],
                'departamento' => $row[5],
                'provincia' => $row[6],
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
                'nivel_categoria' => $row[16]
            ];

            logger()->info("Fila $index procesada correctamente.", compact('olimpista', 'tutor', 'inscripcion'));
        }
    }
}
