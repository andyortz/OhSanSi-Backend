<?php

namespace App\Modules\Olympiads\Controllers;

use App\Modules\Olympiads\Models\Olimpiada;
use Illuminate\Http\Request;

class OlimpiadaAreaController
{
    public function maxCategorias($id)
    {
        $olimpiada = Olimpiada::find($id);

        if (!$olimpiada) {
            return response()->json([
                'message' => 'Olimpiada no encontrada',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'Máximo de categorías obtenido correctamente',
            'max_categorias' => $olimpiada->max_categorias_olimpista
        ], 200);
    }

}
