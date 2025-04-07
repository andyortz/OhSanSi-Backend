<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\Olimpista;
use App\Models\Parentesco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VincularController  extends Controller
{
    public function registrarConParentesco(Request $request)
    {
        $ciTutor = $request->input('ci_tutor');
        $ciOlimpista = $request->input('ci_olimpista');
        $parentesco = $request->input('parentesco');

        if (!$ciTutor || !$ciOlimpista || !$parentesco) {
            return response()->json([
                'message' => 'Debe proporcionar ci_tutor, ci_olimpista y parentesco.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $tutor = Tutor::where('ci', $ciTutor)->first();
            $olimpista = Olimpista::where('cedula_identidad', $ciOlimpista)->first();

            if (!$tutor || !$olimpista) {
                return response()->json([
                    'message' => 'Tutor u olimpista no encontrados en la base de datos.'
                ], 404);
            }

            $vinculo = Parentesco::firstOrCreate([
                'id_tutor' => $tutor->id_tutor,
                'id_olimpista' => $olimpista->id_olimpista
            ], [
                'parentesco' => strtoupper($parentesco)
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Parentesco registrado correctamente.',
                'vinculo' => $vinculo
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el parentesco.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
