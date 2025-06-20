<?php

namespace App\Services;

use App\Modules\Olympiads\Models\Olympiad;
use Illuminate\Support\Carbon;

class OlimpiadaService
{
    public static function getOlimpiadaAbierta(): ?Olimpiada
    {
        $hoy = Carbon::now();

        return Olimpiada::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();
    }
}