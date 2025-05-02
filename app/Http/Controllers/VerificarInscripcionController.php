<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Olimpista;
use App\Models\Parentesco;
use App\Models\Olimpiada;
use App\Models\Inscripcion;
use App\Models\NivelAreaOlimpiada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerificarInscripcionController extends Controller
{
    public function verificar(Request $request)
    {
        try {
            $ciTutor = $request->input('ci_tutor');
            $ciOlimpista = $request->input('ci_olimpista');

            if (!$ciTutor || !$ciOlimpista) {
                return response()->json(['message' => 'Debe enviar CI del tutor y del olimpista.'], 400);
            }

            $tutor = Tutor::where('ci', $ciTutor)->first();
            $olimpista = Olimpista::where('cedula_identidad', $ciOlimpista)->first();

            if (!$tutor || !$olimpista) {
                return response()->json(['message' => 'Tutor u Olimpista no encontrados.'], 404);
            }

            Parentesco::firstOrCreate([
                'id_olimpista' => $olimpista->id_olimpista,
                'id_tutor' => $tutor->id_tutor
            ]);

            $fechaActual = Carbon::now()->toDateString();
            $olimpiada = Olimpiada::where('fecha_inicio', '<=', $fechaActual)
                ->where('fecha_fin', '>=', $fechaActual)
                ->first();

            if (!$olimpiada) {
                return response()->json(['message' => 'No hay una olimpiada activa.'], 404);
            }

            // Obtener inscripciones del olimpista
            $inscripciones = Inscripcion::where('id_olimpista', $olimpista->id_olimpista)->get();
            $nivelesInscritos = $inscripciones->pluck('id_nivel');

            if ($inscripciones->count() >= $olimpiada->max_categorias_olimpista) {
                return response()->json(['message' => 'El olimpista ya alcanzó el máximo de inscripciones permitidas.'], 403);
            }

            // Obtener todas las combinaciones nivel/área/olimpiada
            $relaciones = NivelAreaOlimpiada::with(['area', 'nivel'])
                ->where('id_olimpiada', $olimpiada->id_olimpiada)
                ->get();

            $contadorPorArea = [];

            // Contar inscripciones por área actual
            foreach ($relaciones as $rel) {
                if ($nivelesInscritos->contains($rel->id_nivel)) {
                    $contadorPorArea[$rel->id_area] = ($contadorPorArea[$rel->id_area] ?? 0) + 1;
                }
            }

            $disponibles = [];

            foreach ($relaciones as $rel) {
                $areaId = $rel->id_area;
                $nivelId = $rel->id_nivel;

                $yaInscritos = $contadorPorArea[$areaId] ?? 0;

                if ($yaInscritos < $rel->max_niveles && !$nivelesInscritos->contains($nivelId)) {
                    $disponibles[$rel->area->nombre][] = [
                        'id_nivel' => $nivelId,
                        'nombre_nivel' => $rel->nivel->nombre
                    ];
                }
            }

            return response()->json([
                'areas_disponibles' => $disponibles
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error interno',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    
    public function getTotalInscripciones($ci)
    {
        // Buscar al olimpista por CI
        $olimpista = Olimpista::where('cedula_identidad', $ci)->first();

        if (!$olimpista) {
            return response()->json([
                'success' => false,
                'message' => 'Olimpista no encontrado'
            ], 404);
        }

        // Contar inscripciones por id_olimpista
        $total = Inscripcion::where('id_olimpista', $olimpista->id_olimpista)->count();

        return response()->json([
            'success' => true,
            'ci_olimpista' => $ci,
            'total_inscripciones' => $total
        ]);
    }
    public function getInscripcionesPorCI($ci)
    {
        // 1. Buscar a la persona por su CI
        $persona = \App\Models\Persona::where('ci_persona', $ci)->first();

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada'
            ], 404);
        }

        // 2. Buscar el detalle_olimpista asociado a esa persona
        $detalle = \App\Models\DetalleOlimpista::where('ci_olimpista', $ci)->first();

        if (!$detalle) {
            return response()->json([
                'success' => false,
                'message' => 'No hay inscripciones registradas para esta persona'
            ], 404);
        }

        // 3. Buscar inscripciones por detalle_olimpista
        $inscripciones = \App\Models\Inscripcion::with('nivel') // agrega relaciones si tienes más
            ->where('id_detalle_olimpista', $detalle->id_detalle_olimpista)
            ->get();

        return response()->json([
            'success' => true,
            'ci_olimpista' => $ci,
            'nombre' => $persona->nombres . ' ' . $persona->apellidos,
            'inscripciones' => $inscripciones
        ], 200);
    }
}
