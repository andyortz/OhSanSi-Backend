<?php

namespace App\Services\Registers;

use Illuminate\Support\Facades\DB;

class OlimpiadaService
{
    public function getAreasByResponsible(string $responsibleCi)
    {
        $cleanCi = preg_replace('/\D/', '', $responsibleCi);

        return DB::table('lista_inscripcion as li')
            ->join('inscripcion          as i',  'i.id_lista',              '=', 'li.id_lista')
            ->join('detalle_olimpista    as do', 'do.id_detalle_olimpista', '=', 'i.id_detalle_olimpista')
            ->join('nivel_area_olimpiada as nao', function ($join) {
                $join->on('nao.id_nivel',     '=', 'i.id_nivel')
                     ->on('nao.id_olimpiada', '=', 'do.id_olimpiada');
            })
            ->join('area_competencia     as ac', 'ac.id_area',              '=', 'nao.id_area')
            ->where('li.ci_responsable_inscripcion', (int) $cleanCi)
            ->distinct()
            ->pluck('ac.nombre');
    }

    public function getTotalRegistrationsByResponsible(string $responsibleCi): int
    {
        $cleanCi = preg_replace('/\D/', '', $responsibleCi);

        return DB::table('lista_inscripcion as li')
            ->join('inscripcion as i', 'i.id_lista', '=', 'li.id_lista')
            ->where('li.ci_responsable_inscripcion', (int) $cleanCi)
            ->count('i.id_inscripcion');
    }
}
