<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Services\ImportHelpers\AreaResolver;
use App\Services\ImportValidators\InscripcionValidator;
use App\Services\OlimpiadaService;

class InscripcionesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows->shift();//Headers
        $rows->shift();//example

        $olimpiada = OlimpiadaService::getOlimpiadaAbierta();
        if (!$olimpiada) {
            logger()->error("No se puede continuar sin olimpiada activa.");
            return;
        }

        $idOlimpiada = $olimpiada->id_olimpiada;
        $maxAreas = $olimpiada->max_categorias_olimpista;

        $validAreas = AreaResolver::getValidAreas($idOlimpiada);

        foreach ($rows as $index => $row) {
            if ($row->filter()->isEmpty()) continue;

            $datos = InscripcionValidator::validarFila(
                $row->toArray(),
                $index,
                $validAreas,
                $idOlimpiada,
                $maxAreas
            );

            if (!$datos) continue;

            logger()->info("Fila $index procesada correctamente.", $datos);

        }
    }
}
