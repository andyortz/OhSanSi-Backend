<?php

namespace App\Http\Controllers;
//REVISAR!!!
use App\Models\Olimpista;
use App\Models\Area;
use Illuminate\Http\Request;

class AreasFiltroController extends Controller
{
    /**
     * Devuelve las áreas en las que el olimpista puede inscribirse
     */
    public function obtenerAreasDisponibles($id_olimpista, $id_olimpiada)
    {
        $olimpista = Olimpista::find($id_olimpista);

        if (!$olimpista) {
            return response()->json([
                'message' => 'Olimpista no encontrado.'
            ], 404);
        }

        $grado_id = $olimpista->id_grado;

        $areas = Area::where('id_olimpiada', $id_olimpiada)
            ->whereHas('niveles.grados', function ($query) use ($grado_id) {
                $query->where('grados.id_grado', $grado_id);
            })
            ->with(['niveles' => function ($query) use ($grado_id) {
                $query->whereHas('grados', function ($q) use ($grado_id) {
                    $q->where('grados.id_grado', $grado_id);
                });
            }])
            ->get();

        return response()->json([
            'message' => 'Áreas disponibles encontradas.',
            'areas' => $areas
        ], 200);
    }
}
