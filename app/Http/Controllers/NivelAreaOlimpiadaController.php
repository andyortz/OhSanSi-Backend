<?php

namespace App\Http\Controllers;

use App\Models\NivelAreaOlimpiada;
use App\Http\Requests\StoreNivelAreaOlimpiadaRequest;

class NivelAreaOlimpiadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNivelAreaOlimpiadaRequest $request): JsonResponse
    {
        try {
            $nivelArea = NivelAreaOlimpiada::create($request->validated());

            return response()->json([
                'success' => true,
                'data' => $nivelArea,
                'message' => 'Relación creada exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la relación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
