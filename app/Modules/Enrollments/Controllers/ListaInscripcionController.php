<?php

namespace App\Modules\Enrollments\Controllers;

use App\Models\ListaInscripcion;
use App\Models\NivelAreaOlimpiada;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\Inscripcion;

use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;

class ListaInscripcionController
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

    private function formatoIndividual($inscripciones)
    {
        $olimpista = $inscripciones->first()->detalleOlimpista->olimpista;
        
        return [
            'tipo' => 'individual',
            'cantidad_inscripciones' => $inscripciones->count(),
            'olimpista' => [
                'ci' => $olimpista->ci_persona,
                'nombres' => $olimpista->nombres,
                'apellidos' => $olimpista->apellidos
            ],
            'niveles' => $inscripciones->map(function ($insc) {
                return [
                    'id' => $insc->nivel->id_nivel,
                    'nombre' => $insc->nivel->nombre,
                    'area' => $insc->nivel->asociaciones->first()->area->nombre ?? 'Sin área'
                ];
            })->unique('id')->values()->toArray()
        ];
    }

    private function formatoGrupal($inscripciones)
    {
        return [
            'tipo' => 'grupal',
            'cantidad_estudiantes' => $inscripciones->groupBy('id_detalle_olimpista')->count(),
            'cantidad_inscripciones' => $inscripciones->count()
        ];
    }
    public function obtenerPorResponsable($ci, $estado)
    {
        $responsable = Persona::where('ci_persona', $ci)
            ->first(['nombres', 'apellidos', 'ci_persona']);
        if ($estado !== 'TODOS') {
            $listas = ListaInscripcion::with([
                'inscripciones.detalleOlimpista.olimpista:nombres,apellidos,ci_persona',
                'inscripciones.nivel.asociaciones.area:nombre,id_area'
            ])->where('ci_responsable_inscripcion', $ci)->where('estado', $estado)->get(['id_lista', 'estado', 'ci_responsable_inscripcion']);
        } else {
            $listas = ListaInscripcion::with([
                'inscripciones.detalleOlimpista.olimpista:nombres,apellidos,ci_persona',
                'inscripciones.nivel.asociaciones.area:nombre,id_area'
            ])->where('ci_responsable_inscripcion', $ci)->get(['id_lista', 'estado', 'ci_responsable_inscripcion']);
        }

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
            $inscripciones = $lista->inscripciones;

            $allSameDetalle = $inscripciones->count() > 0 && 
            $inscripciones->every(function ($insc) use ($inscripciones) {
                return $insc->id_detalle_olimpista === $inscripciones->first()->id_detalle_olimpista;
            });
            $item = [
                'id_lista' => $lista->id_lista,
                'estado' => $lista->estado,
                'detalle' => $allSameDetalle ? $this->formatoIndividual($inscripciones) : $this->formatoGrupal($inscripciones)
            ];
            $resultado[] = $item;
        }
        return response()->json([
        'responsable' => [
            'ci' => $responsable->ci_persona,
            'nombres' => $responsable->nombres,
            'apellidos' => $responsable->apellidos
        ],
        'listas' => $resultado
       ], 200);
    }

    public function listasPagoPendiente($ci)
    {
        $responsable = Persona::where('ci_persona', $ci)
            ->first(['nombres', 'apellidos', 'ci_persona']);

        if (!$responsable) {
            return response()->json([
                'message' => 'No existe ninguna persona con ese CI.',
                'ci_buscado' => $ci
            ], 404);
        }

        // Obtén los id_lista que tengan pago pendiente (verificado = false)
        $listasConPagoPendiente = \DB::table('lista_inscripcion')
            ->join('pago', 'lista_inscripcion.id_lista', '=', 'pago.id_lista')
            ->where('lista_inscripcion.ci_responsable_inscripcion', $ci)
            ->where('pago.verificado', false);

        $idsListas = $listasConPagoPendiente
            ->pluck('lista_inscripcion.id_lista')
            ->unique()
            ->toArray();

        if (empty($idsListas)) {
            return response()->json([
                'message' => 'No existen listas con pagos pendientes para este responsable.',
                'ci_buscado' => $ci
            ], 404);
        }

        // Trae solo las listas filtradas por esos IDs
        $listas = ListaInscripcion::with([
            'inscripciones.detalleOlimpista.olimpista:nombres,apellidos,ci_persona',
            'inscripciones.nivel.asociaciones.area:nombre,id_area'
        ])
        ->whereIn('id_lista', $idsListas)
        ->get(['id_lista', 'estado', 'ci_responsable_inscripcion']);

        $resultado = [];
        foreach ($listas as $lista) {
            $inscripciones = $lista->inscripciones;
            $allSameDetalle = $inscripciones->count() > 0 && 
                $inscripciones->every(function ($insc) use ($inscripciones) {
                    return $insc->id_detalle_olimpista === $inscripciones->first()->id_detalle_olimpista;
                });

            $item = [
                'id_lista' => $lista->id_lista,
                'estado' => $lista->estado,
                'detalle' => $allSameDetalle ? $this->formatoIndividual($inscripciones) : $this->formatoGrupal($inscripciones)
            ];
            $resultado[] = $item;
        }

        return response()->json([
            'responsable' => [
                'ci' => $responsable->ci_persona,
                'nombres' => $responsable->nombres,
                'apellidos' => $responsable->apellidos
            ],
            'listas' => $resultado
        ], 200);
    }




    public function individual($id){
        try {
            $lista = ListaInscripcion::with([
                'olimpiada:costo,id_olimpiada',
                'inscripciones.detalleOlimpista.olimpista',
                'inscripciones.nivel.asociaciones.area',
            ])->findOrFail($id);
            
            $responsable = Persona::where('ci_persona', $lista->ci_responsable_inscripcion)
            ->first(['nombres', 'apellidos', 'ci_persona']);

            // Verificar que sea individual
            if ($lista->inscripciones->groupBy('id_detalle_olimpista')->count() > 1) {
                throw new \Exception('Esta función es solo para listas individuales');
            }
    
            $precioUnitario = (float)$lista->olimpiada->costo;
            $montoTotal = round((float)$precioUnitario * $lista->inscripciones->count(), 2);
            $cantidad = $lista->inscripciones->count();

            $pago = Pago::firstOrCreate(
                ['id_lista' => $id],
                [
                    'comprobante' => 'PAGO-' . uniqid(),
                    'fecha_pago' => now(),
                    'monto_total' => $montoTotal,
                    'estado' => 'PENDIENTE'
                ]
            );
    
            // Procesar niveles
            $niveles = $lista->inscripciones->map(function ($inscripcion) {
                return [
                    'nivel_id' => $inscripcion->nivel->id_nivel,
                    'nombre_nivel' => $inscripcion->nivel->nombre,
                    'area' => optional($inscripcion->nivel->asociaciones->first())->area->nombre ?? 'Sin área'
                ];
            });
            return response()->json([
                'responsable' => [
                    'ci' => $responsable->ci_persona,
                    'nombres' => $responsable->nombres,
                    'apellidos' => $responsable->apellidos
                ],
                'pago' => [
                    'id' => $pago->id_pago,
                    'referencia' => $pago->comprobante,
                    'monto_unitario' => $precioUnitario,
                    'total_inscripciones' => $cantidad,
                    'total_a_pagar' => $montoTotal,
                    'estado' => $pago->estado,
                    'fecha_pago' => now()
                ],
                'olimpista' => [
                    'ci' => $lista->inscripciones->first()->detalleOlimpista->olimpista->ci_persona,
                    'nombres' => $lista->inscripciones->first()->detalleOlimpista->olimpista->nombres,
                    'apellidos' => $lista->inscripciones->first()->detalleOlimpista->olimpista->apellidos
                ],
                'niveles' => $niveles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function grupal($id){
        try {
            $lista = ListaInscripcion::with([
                'inscripciones',
                'responsable',
                'olimpiada'
            ])->findOrFail($id);
            $responsable = Persona::where('ci_persona', $lista->ci_responsable_inscripcion)
            ->first(['nombres', 'apellidos', 'ci_persona']);

            // Cálculos básicos
            $precioUnitario = (float)$lista->olimpiada->costo;
            $cantidad = $lista->inscripciones->count();
            
            $montoTotal = round((float)$lista->olimpiada->costo * $lista->inscripciones->count(), 2);

            // Verificar/crear pago
            $pago = Pago::firstOrCreate(
                ['id_lista' => $id],
                [
                    'comprobante' => 'PAGO-' . uniqid(),
                    'fecha_pago' => now(),
                    'monto_total' => $montoTotal,
                    'estado' => 'PENDIENTE'
                ]
            );
    
            return response()->json([
                'responsable' => [
                    'ci' => $lista->ci_responsable_inscripcion,
                    'nombres' => $responsable->nombres,
                    'apellidos' => $responsable->apellidos
                ],
                'pago' => [
                    'id' => $pago->id_pago,
                    'referencia' => $pago->comprobante,
                    'monto_unitario' => $precioUnitario,
                    'total_inscripciones' => $cantidad,
                    'total_a_pagar' => $montoTotal,
                    'estado' => $pago->estado,
                    'fecha_pago' => now()
                ],
                'detalle_grupo' => [
                    'participantes_unicos' => $lista->inscripciones->groupBy('id_detalle_olimpista')->count()
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    
    }
    public function getById($id){
        $listas = ListaInscripcion::with(
            'inscripciones.detalleOlimpista.olimpista',
            'inscripciones.detalleOlimpista.grado',
            'inscripciones.detalleOlimpista.colegio.provincia.departamento',
            'inscripciones.nivel.asociaciones.area')
                ->where('estado', 'PAGADO')
                ->where('id_olimpiada', $id)
                ->get();
        $data = [];
        foreach ($listas as $lista) {
            // Acceder al olimpista relacionado (asumiendo que hay una relación definida en el modelo)
            $inscripciones = $lista->inscripciones;
            foreach ($inscripciones as $inscripcion) {
                $olimpista = $inscripcion -> detalleOlimpista;
                $persona = $olimpista -> olimpista;
                $grado = $olimpista -> grado;
                $colegio = $olimpista-> colegio;
                $provincia = $colegio -> provincia;
                $departamento = $provincia -> departamento ?? null;
                $nivel = $inscripcion -> nivel;
                $area = $nivel -> asociaciones -> first() -> area;
    
                $data[] = [
                    'apellidos' => $persona->apellidos,
                    'nombres' => $persona->nombres,
                    'ci' => $persona -> ci_persona,
                    'colegio' => $colegio->nombre_colegio,
                    'grado' => $grado -> nombre_grado,
                    'departamento' => $departamento->nombre_departamento,
                    'provincia' => $provincia-> nombre_provincia,
                    'area' => $area->nombre,
                    'nivel' => $nivel->nombre,
                ];
            }
        }

        // 3. Devolver la respuesta en JSON
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);

    }
}
