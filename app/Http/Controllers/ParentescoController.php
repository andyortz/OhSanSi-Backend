<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Parentesco;

class ParentescoController extends Controller
{
    public function asociarTutor(Request $request): JsonResponse
    {
        $request->validate([
            'id_olimpista' => 'required|exists:olimpistas,ci',
            'id_tutor' => 'required|exists:tutores,id'
        ]);

        try {
            // Verificar si ya existe la asociación
            $existe = DB::table('olimpista_tutor')
                      ->where('ci_olimpista', $request->id_olimpista)
                      ->where('id_tutor', $request->id_tutor)
                      ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta asociación ya existe'
                ], 409);
            }

            // Crear la asociación
            DB::table('olimpista_tutor')->insert([
                'ci_olimpista' => $request->id_olimpista,
                'id_tutor' => $request->id_tutor,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asociación creada exitosamente',
                'data' => [
                    'id_olimpista' => $request->id_olimpista,
                    'id_tutor' => $request->id_tutor
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la asociación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}