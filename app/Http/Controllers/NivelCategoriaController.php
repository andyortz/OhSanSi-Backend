<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Models\Grado;
use App\Models\NivelGrado;
use App\Models\NivelAreaOlimpiada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NivelCategoriaController extends Controller
{
    public function store(Request $request)
    {
        $datos = $request->all();

        if (!is_array($datos) || empty($datos)) {
            return response()->json([
                'message' => 'Debes enviar un array de relaciones a registrar.'
            ], 400);
        }

        $errores = [];
        $registrosGuardados = [];

        DB::beginTransaction();
        try {
            foreach ($datos as $index => $registro) {
                // Validar campos requeridos
                $faltantes = [];
                foreach (['id_nivel', 'id_area', 'id_olimpiada', 'grados'] as $campo) {
                    if (!isset($registro[$campo]) || empty($registro[$campo])) {
                        $faltantes[] = $campo;
                    }
                }

                if (!empty($faltantes)) {
                    $errores[] = "Item #" . ($index + 1) . ": Faltan los campos " . implode(', ', $faltantes);
                    continue;
                }

                // Crear relación en niveles_areas_olimpiadas si no existe
                $existeRelacion = DB::table('niveles_areas_olimpiadas')->where([
                    'id_nivel' => $registro['id_nivel'],
                    'id_area' => $registro['id_area'],
                    'id_olimpiada' => $registro['id_olimpiada']
                ])->exists();

                if (!$existeRelacion) {
                    DB::table('niveles_areas_olimpiadas')->insert([
                        'id_nivel' => $registro['id_nivel'],
                        'id_area' => $registro['id_area'],
                        'id_olimpiada' => $registro['id_olimpiada']
                    ]);
                }

                // Registrar grados asociados al nivel
                foreach ($registro['grados'] as $id_grado) {
                    DB::table('grados_niveles')->updateOrInsert([
                        'id_nivel' => $registro['id_nivel'],
                        'id_grado' => $id_grado
                    ]);
                }

                $registrosGuardados[] = $registro;
            }

            DB::commit();

            return response()->json([
                'message' => empty($errores) 
                    ? 'Relaciones registradas exitosamente.' 
                    : 'Algunas relaciones fueron registradas, pero otras fallaron.',
                'guardados' => $registrosGuardados,
                'errores' => $errores
            ], empty($errores) ? 201 : 207);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar las relaciones.',
                'error' => $e->getMessage()
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

    public function index()
    {
        $niveles = NivelCategoria::all();

        return response()->json([
            'message' => 'Lista de niveles cargada correctamente.',
            'niveles' => $niveles
        ], 200);
    }
}
