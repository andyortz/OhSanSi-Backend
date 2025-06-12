<?php

namespace App\Modules\Persons\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Parentesco;

class ParentescoController
{
    public function asociarTutor(Request $request): JsonResponse
    {
        $request->validate([
            'id_olimpista' => 'required|exists:olimpistas,id_olimpista',
            'id_tutor' => 'required|exists:tutores,id_tutor',
            'rol_parentesco' => 'required|in:Tutor Legal,Tutor Academico'
        ]);

        try {
            // Verificar si ya existe la asociación
            $existe = DB::table('parentescos')
                ->where('id_olimpista', $request->id_olimpista)
                ->where('id_tutor', $request->id_tutor)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta asociación ya existe'
                ], 409);
            }

            // Crear la asociación
            DB::table('parentescos')->insert([
                'id_olimpista' => $request->id_olimpista,
                'id_tutor' => $request->id_tutor,
                'rol_parentesco' => $request->rol_parentesco
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asociación creada exitosamente',
                'data' => [
                    'id_olimpista' => $request->id_olimpista,
                    'id_tutor' => $request->id_tutor,
                    'rol_parentesco' => $request->rol_parentesco
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