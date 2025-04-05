<?php

namespace App\Http\Controllers;

use App\Models\NivelCategoria;
use App\Models\NivelGrado;
use App\Models\Grado;
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
                // Validaciones básicas
                $faltantes = [];
                foreach (['id_nivel', 'id_area', 'id_olimpiada'] as $campo) {
                    if (empty($registro[$campo])) {
                        $faltantes[] = $campo;
                    }
                }

                if (!empty($faltantes)) {
                    $errores[] = "Item #" . ($index + 1) . ": Faltan los campos " . implode(', ', $faltantes);
                    continue;
                }

                // Validar que la relación no exista
                $existe = NivelAreaOlimpiada::where('id_nivel', $registro['id_nivel'])
                    ->where('id_area', $registro['id_area'])
                    ->where('id_olimpiada', $registro['id_olimpiada'])
                    ->exists();

                if ($existe) {
                    $errores[] = "Item #" . ($index + 1) . ": Ya existe esta relación.";
                    continue;
                }

                // Crear la relación
                $registroNuevo = NivelAreaOlimpiada::create([
                    'id_nivel' => $registro['id_nivel'],
                    'id_area' => $registro['id_area'],
                    'id_olimpiada' => $registro['id_olimpiada'],
                ]);

                $registrosGuardados[] = $registroNuevo;
            }

            DB::commit();

            if (!empty($errores)) {
                return response()->json([
                    'message' => 'Algunas relaciones fueron registradas, pero otras fallaron.',
                    'guardados' => $registrosGuardados,
                    'errores' => $errores
                ], 207); // 207: Multi-Status
            }

            return response()->json([
                'message' => 'Todas las relaciones registradas exitosamente.',
                'guardados' => $registrosGuardados
            ], 201);

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
        $niveles = NivelCategoria::where('id_area', $id_area)->with('grados')->get();

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
