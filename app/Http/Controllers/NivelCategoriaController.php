<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Models\Grado;
use App\Models\NivelGrado;
use App\Models\NivelAreaOlimpiada;
use App\Models\Olimpiada;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NivelCategoriaController extends Controller
{
    public function asociarNivelesPorArea(Request $request)
    {
        $data = $request->validate([
            'id_area' => 'required|integer|exists:areas_competencia,id_area',
            'id_categorias' => 'required|array|min:1',
            'id_categorias.*' => 'required|integer|exists:niveles_categoria,id_nivel',
        ]);

        // Obtener olimpiada activa según la fecha actual
        $fechaHoy = Carbon::now()->toDateString();
        $olimpiada = Olimpiada::where('fecha_inicio', '<=', $fechaHoy)
            ->where('fecha_fin', '>=', $fechaHoy)
            ->first();

        if (!$olimpiada) {
            return response()->json([
                'message' => 'No hay ninguna olimpiada activa en este momento.'
            ], 404);
        }

        $insertadas = [];

        DB::beginTransaction();
        try {
            foreach ($data['id_categorias'] as $idNivel) {
                // Validar si ya existe para evitar duplicados
                $yaExiste = NivelAreaOlimpiada::where([
                    'id_olimpiada' => $olimpiada->id_olimpiada,
                    'id_area' => $data['id_area'],
                    'id_nivel' => $idNivel
                ])->exists();

                if (!$yaExiste) {
                    NivelAreaOlimpiada::create([
                        'id_olimpiada' => $olimpiada->id_olimpiada,
                        'id_area' => $data['id_area'],
                        'id_nivel' => $idNivel,
                        'max_niveles' => 1 // Obtener el valor máximo de niveles por área
                    ]);

                    $insertadas[] = $idNivel;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Niveles asociados exitosamente a la olimpiada actual.',
                'id_olimpiada' => $olimpiada->id_olimpiada,
                'niveles_asociados' => $insertadas
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al asociar niveles al área.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:50',
            'id_area' => 'required|integer|exists:areas_competencia,id_area',
            'grado_min' => 'required|integer|exists:grados,id_grado',
            'grado_max' => 'required|integer|exists:grados,id_grado',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buscar el id_nivel basado en el nombre
            $nivel = NivelCategoria::where('nombre', $data['nombre'])->first();

            if (!$nivel) {
                return response()->json([
                    'message' => 'Nivel no encontrado en el catálogo.',
                    'nombre' => $data['nombre']
                ], 404);
            }

            // 2. Asociarlo al área de competencia
            NivelAreaOlimpiada::create([
                'id_olimpiada' => 1, // Ajustar si manejas varias olimpiadas
                'id_area' => $data['id_area'],
                'id_nivel' => $nivel->id_nivel,
                'max_niveles' => 1, // O ajustar si quieres
            ]);

            // 3. Asociarlo a los grados
            for ($grado = $data['grado_min']; $grado <= $data['grado_max']; $grado++) {
                NivelGrado::create([
                    'id_nivel' => $nivel->id_nivel,
                    'id_grado' => $grado,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Nivel asociado correctamente a área y grados.',
                'nivel' => $nivel
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error interno al asociar el nivel.',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function nivelesPorArea($id_area)
    {
        $niveles = DB::table('niveles_areas_olimpiadas')
            ->join('niveles_categoria', 'niveles_areas_olimpiadas.id_nivel', '=', 'niveles_categoria.id_nivel')
            ->where('niveles_areas_olimpiadas.id_area', $id_area)
            ->select('niveles_categoria.id_nivel', 'niveles_categoria.nombre')
            ->groupBy('niveles_categoria.id_nivel', 'niveles_categoria.nombre')
            ->get();

        if ($niveles->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron niveles para esta área.',
                'niveles' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Niveles encontrados correctamente.',
            'niveles' => $niveles
        ], 200);
    }
    public function asociarGrados(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'id_nivel' => 'required|integer|exists:niveles_categoria,id_nivel',
            'id_grado_min' => 'required|integer|exists:grados,id_grado',
            'id_grado_max' => 'required|integer|exists:grados,id_grado',
        ]);

        try {
            // Verificar que el nivel existe
            $nivel = NivelCategoria::findOrFail($request->id_nivel);
            
            // Obtener todos los grados entre el mínimo y el máximo
            $grados = Grado::whereBetween('id_grado', [$request->id_grado_min, $request->id_grado_max])
                          ->orderBy('id_grado')
                          ->get();

            // Crear las asociaciones
            foreach ($grados as $grado) {
                NivelGrado::firstOrCreate([
                    'id_nivel' => $request->id_nivel,
                    'id_grado' => $grado->id_grado
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Asociaciones creadas correctamente',
                'asociaciones_creadas' => $grados->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear las asociaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $niveles = NivelCategoria::all();

        return response()->json([
            'message' => 'Lista de niveles cargada correctamente.',
            'niveles' => $niveles
        ], 200);
    }
}
