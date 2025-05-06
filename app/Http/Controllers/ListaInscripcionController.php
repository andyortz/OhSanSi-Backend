<?php

namespace App\Http\Controllers;

use App\Models\ListaInscripcion;
use App\Models\NivelAreaOlimpiada;
use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;

class ListaInscripcionController extends Controller
{
    /**
     * Obtener todas las áreas
     */
    public function index()
    {
        $resultado = [];
        foreach ($listas as $lista) {
            // Primero: Todos los campos de la lista
            $item = $lista->toArray(); 
        
            // Luego: Añadir el detalle específico
            if ($lista->count() == 1) {
                $inscripcion = $lista->inscripciones->first();

                if ($inscripcion) {
                    // Cargar todas las relaciones necesarias en una sola consulta
                    $inscripcion->load([
                        'nivel.asociaciones.area',  // Relación a área a través de nivel
                        'detalleOlimpista.olimpista',   // Nombre del olimpista
                        'detalleOlimpista.colegio'    // Nombre del colegio
                    ]);

                    $item['detalle'] = [
                        'tipo' => 'individual',
                        'nivel' => [
                            'id' => $inscripcion->id_nivel,
                            'nombre' => $inscripcion->nivel->nombre ?? null,
                            'area' => $inscripcion->nivel->asociaciones->area->nombre ?? null
                        ],
                        'olimpista' => [
                            'ci' => $inscripcion->detalleOlimpista->ci_olimpista ?? null,
                            'nombre' => $inscripcion->detalleOlimpista->olimpista->nombre ?? null
                        ],
                        'colegio' => [
                            'id' => $inscripcion->detalleOlimpista->unidad_educativa ?? null,
                            'nombre' => $inscripcion->detalleOlimpista->colegio->nombre_colegio ?? null
                        ]
                    ];
                } else {
                    $item['detalle'] = [
                        'tipo' => 'individual',
                        'error' => 'Inscripción no encontrada'
                    ];
                }
            } else {
                $inscripciones = $lista->inscripciones;
                $item['detalle'] = [
                    'tipo' => 'grupal',
                    'cantidad_participantes' => $inscripciones->count(),
                    'inscripciones' => $lista->inscripciones->toArray()
                ];
            }
        
            $resultado[] = $item;
        }
        return response()->json(['data' => $resultado], 200);
    }

    /**
     * Obtener áreas por ID de olimpiada
     */
    public function obtenerPorResponsable($ci)
    {
        $listas = ListaInscripcion::where('ci_responsable_inscripcion', $ci)->get();
        if ($listas->isEmpty()) {
            return response()->json(
                [
                    'message' => 'No existe ninguna lista asociada a este CI.',
                    'ci_buscado' => $ci
                ],
                404 // Not Found
            );
        }
        //Desgloce
        $resultado = [];
        foreach ($listas as $lista) {
            // Primero: Todos los campos de la lista
            $item = $lista->toArray(); 
        
            // Luego: Añadir el detalle específico
            if ($lista->cantidad == 1) {
                $inscripcion = $lista->inscripciones->first();
                $item['detalle'] = [
                    'tipo' => 'individual',
                    'inscripcion' => $inscripcion ? $inscripcion->toArray() : null
                ];
            } else {
                $item['detalle'] = [
                    'tipo' => 'grupal',
                    'cantidad_participantes' => $lista->cantidad,
                    'inscripciones' => $lista->inscripciones->toArray()
                ];
            }
        
            $resultado[] = $item;
        }
        return response()->json(['data' => $resultado], 200);
    }
}
