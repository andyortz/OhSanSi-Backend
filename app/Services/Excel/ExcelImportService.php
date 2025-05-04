<?php

namespace App\Services\Excel;

use App\Models\Departamento;
use App\Services\ImportHelpers\ProvinciaResolver;
use App\Services\ImportHelpers\ColegioResolver;
use App\Services\ImportHelpers\GradoResolver;
use App\Services\ImportHelpers\NivelResolver;
use App\Services\ImportHelpers\TutorResolver;
use App\Services\ImportHelpers\OlimpistaResolver;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportHelpers\ProfesorResolver;
use App\Services\ImportHelpers\InscripcionResolver;

class ExcelImportService
{
    public function sanitize(array $datos): array
    {
        $sanitizedData = [];
        $tutorsData = [];
        $olimpistasData = [];
        $areasData = [];
        $profesorData = [];
        $inscripcionesData = [];

        foreach ($datos as $index => $row) {
            if (empty(array_filter($row))) break;

            // Validaciones y resoluciones
            $departamento = Departamento::where('nombre_departamento', $row[5])->first();
            if (!$departamento) throw new \Exception("Departamento invalido en fila $index");
            $row[5] = $departamento->id_departamento;

            $provincia = ProvinciaResolver::resolve($row[6], $row[5]);
            if (!$provincia) throw new \Exception("Provincia invalida en fila $index");
            $row[6] = $provincia;

            $colegio = ColegioResolver::resolve($row[5], $row[6]);
            if (!$colegio) throw new \Exception("Unidad educativa invalida en fila $index");
            $row[7] = $colegio;

            $grado = GradoResolver::resolve($row[8]);
            if (!$grado) throw new \Exception("Grado invalido en fila $index");
            $row[8] = $grado;

            $nivel = NivelResolver::resolve($row[15]);
            if (!$nivel) throw new \Exception("Nivel invalido en fila $index");
            $row[15] = $nivel;

            $tutor = TutorResolver::extractTutorData($row);
            $tutorsData[$tutor['ci']] = $tutor;

            $olimpista = OlimpistaResolver::extractOlimpistaData($row);
            $olimpistasData[$olimpista['cedula_identidad']] = $olimpista;

            $areasData[] = AreaResolver::extractAreaData($row);
            $profesorData[] = ProfesorResolver::extractProfesorData($row);
            $inscripcionesData[] = InscripcionResolver::extract($row);
            $sanitizedData[] = $row;
        }

        return [
            'sanitized' => $sanitizedData,
            'tutores' => array_values($tutorsData),
            'olimpistas' => array_values($olimpistasData),
            'profesores' => array_values($profesorData),
            'areas' => $areasData,
            'inscripciones' => $inscripcionesData,
        ];
    }
}
