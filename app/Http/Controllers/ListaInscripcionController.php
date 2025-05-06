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
                $allSameDetalle = $inscripciones->count() > 0 
                    && $inscripciones->every(function ($insc) use ($inscripciones) {
                        return $insc->id_detalle_olimpista === $inscripciones->first()->id_detalle_olimpista;
                    });

                if ($allSameDetalle) {
                    // Caso "pseudo-individual" (mismo olimpista en múltiples inscripciones)
                    $olimpista = $inscripciones->first()->detalleOlimpista;
                    
                    $item['detalle'] = [
                        'tipo' => 'individual',
                        'cantidad_inscripciones' => $inscripciones->count(),
                        'olimpista' => [
                            'ci' => $olimpista->ci_olimpista ?? null,
                            'nombre' => $olimpista->olimipista->nombre ?? null,
                            'colegio' => $olimpista->colegio->nombre_colegio ?? null
                        ],
                        'niveles' => $inscripciones->pluck('id_nivel')->unique()->values() // Niveles distintos
                    ];
                } else {
                    // Caso grupal real (diferentes olimpistas)
                    $item['detalle'] = [
                        'tipo' => 'grupal',
                        'cantidad_participantes' => $inscripciones->count(),
                        'inscripciones' => $inscripciones->map(function ($insc) {
                            return [
                                'id' => $insc->id,
                                'olimpista' => [
                                    'ci' => $insc->detalleOlimpista->ci_olimpista ?? null,
                                    'nombre' => $insc->detalleOlimpista->persona->nombre ?? null,
                                    'colegio' => $insc->detalleOlimpista->colegio->nombre ?? null
                                ],
                                'id_nivel' => $insc->id_nivel
                            ];
                        })
                    ];
                }
            }
            $resultado[] = $item;
        }
        return response()->json(['data' => $resultado], 200);
    }

    public function individual($id){
        try {
            // Cargar todas las relaciones necesarias en una sola consulta
            $lista = ListaInscripcion::with([
                'inscripciones.nivel.asociaciones.area',
                'inscripciones.detalleOlimpista.olimpista',
                'responsable', // Asumiendo que tienes esta relación
                'olimpiada', // Para obtener el precio
                'inscripciones.detalleOlimpista.colegio'
            ])->findOrFail($id);
    
            // Obtener datos del responsable
            $responsable = [
                'nombre' => $lista->responsable->nombres ?? 'No especificado',
                'apellido' => $lista->responsable->apellidos ?? 'No especificado',
                'ci' => $lista->ci_responsable_inscripcion
            ];
    
            // Obtener datos del olimpista (primera inscripción)
            $olimpista = $lista->inscripciones->first()->detalleOlimpista->olimpista;
    
            // Procesar niveles y áreas
            $niveles = $lista->inscripciones->map(function ($inscripcion) {
                return [
                    'nivel_id' => $inscripcion->nivel->id_nivel,
                    'nivel_nombre' => $inscripcion->nivel->nombre,
                    'area_id' => $inscripcion->nivel->asociaciones->area->id_area ?? null,
                    'area_nombre' => $inscripcion->nivel->asociaciones->area->nombre ?? 'Sin área'
                ];
            });

            $precioUnitario = $lista->olimpiada->costo;
            $montoTotal = $precioUnitario * $lista->inscripciones->count();

            $pago = Pago::create([
                'comprobante' => 'PAGO-DUMMY-' . uniqid(),
                'fecha_pago' => now(),
                'id_lista' => $id,
                'monto_total' => $montoTotal,
                'verificado' => false,
                'verificado_en' => null,
                'verificado_por' => null
            ]);

            return response()->json([
                'id_pago' => $pago->id_pago,
                'fecha_pago' => $pago->fecha_pago,
                'responsable' => $responsable,
                'olimpista' => [
                    'nombres' => $olimpista->nombres,
                    'apellidos' => $olimpista->apellidos,
                    'ci' => $olimpista->ci_persona
                ],
                'cantidad_niveles' => $lista->inscripciones->count(),
                'niveles_inscritos' => $niveles,
                'precio_unitario' => $lista->olimpiada->costo,
                'precio_total' => $montoTotal
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar boleta',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function grupal($id){
        try {
            $lista = ListaInscripcion::with([
                'inscripciones.detalleOlimpista.colegio',
                'responsable',
                'olimpiada'
            ])->findOrFail($id);
    
            // Verificar si todas las inscripciones son del mismo colegio
            $colegiosUnicos = $lista->inscripciones
                ->pluck('detalleOlimpista.colegio.id')
                ->unique()
                ->count();
    
            $mismoColegio = $colegiosUnicos === 1;
            $colegioNombre = $mismoColegio 
                ? $lista->inscripciones->first()->detalleOlimpista->colegio->nombre_colegio
                : 'Varios colegios';
    
            $precioUnitario = $lista->olimpiada->costo;
            $cantidad = $lista->inscripciones->count();
            $montoTotal = $precioUnitario * $cantidad;

            $pago = Pago::create([
                'comprobante' => 'PAGO-DUMMY-' . uniqid(),
                'fecha_pago' => now(),
                'id_lista' => $id,
                'monto_total' => $montoTotal,
                'verificado' => false,
                'verificado_en' => null,
                'verificado_por' => null
            ]);
            return response()->json([
                'id_pago' => $pago->id_pago,
                'fecha_pago' => $pago->fecha_pago,
                'responsable' => [
                    'ci' => $lista->ci_responsable_inscripcion,
                    'nombres' => $lista->responsable->nombres ?? 'No especificado',
                    'apellidos' => $lista->responsable->apellidos ?? 'No especificado',
                ],
                'nombre' => $colegioNombre,
                'todos_iguales' => $mismoColegio,
                'precio_unitario' => $precioUnitario,
                'cantidad_inscripciones' => $cantidad,
                'monto_total' => $montoTotal,
                

            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar boleta grupal',
                'message' => $e->getMessage()
            ], 500);
        }
    
    }
}
