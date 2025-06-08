<?php

namespace App\Http\Controllers;

use App\Modules\Olympist\Models\EnrollmentList;
use App\Modules\Olympiad\Models\AreaLevelOlympiad;
use App\Modules\Olympist\Models\Payment;
use App\Modules\Olympist\Models\Person;
use App\Modules\Olympist\Models\Enrollment;

use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentListController extends Controller
{
    /**
     * Obtener todas las áreas
     */
    public function index()
    {
        $answer = [];
        foreach ($lists as $list) {
            // Primero: Todos los campos de la lista
            $item = $list->toArray(); 
        
            // Luego: Añadir el detalle específico
            if ($list->count() == 1) {
                $enrollment = $list->enrollments->first();

                if ($enrollment) {
                    // Cargar todas las relaciones necesarias en una sola consulta
                    $enrollment->load([
                        'level.area_level_olympiad.area',  // Relación a área a través de nivel
                        'olympicDetail.olympist',   // Nombre del olimpista
                        'olympicDetail.school'    // Nombre del colegio
                    ]);

                    $item['detail'] = [
                        'kind' => 'individual',
                        'level' => [
                            'id' => $enrollment->id_level,
                            'nome' => $enrollment->level->name ?? null,
                            'area' => $enrollment->level->area_level_olympiad->area->name ?? null
                        ],
                        'olympist' => [
                            'ci' => $enrollment->olympistDetail->ci_olympic ?? null,
                            'name' => $enrollment->olympistDetail->olympist->name ?? null
                        ],
                        'school' => [
                            'id' => $enrollment->olympistDetail->school ?? null,
                            'name' => $enrollment->olympistDetail->school->school_name ?? null
                        ]
                    ];
                } else {
                    $item['detail'] = [
                        'kind' => 'individual',
                        'error' => 'Inscripción no encontrada'
                    ];
                }
            } else {
                $enrollments = $list->enrollments;
                $item['details'] = [
                    'kind' => 'grupal',
                    'participant_amount' => $enrollments->count(),
                    'inscripciones' => $list->enrollments->toArray()
                ];
            }
        
            $answer[] = $item;
        }
        return response()->json(['data' => $answer], 200);
    }
    private function individualFormat($enrollments)
    {
    $olympist = $enrollments->first()->olympistDetail->olympist;

    return [
        'type' => 'individual',
        'enrollment_count' => $enrollments->count(),
        'olympist' => [
            'id' => $olympist->ci_person,
            'names' => $olympist->names,
            'surnames' => $olympist->surnames
        ],
        'levels' => $enrollments->map(function ($enrollment) {
            return [
                'id' => $enrollment->category_level->id_level,
                'name' => $enrollment->category_level->name,
                'area' => $enrollment->category_level->area_level_olympiad->first()->area->name ?? 'No area'
            ];
        })->unique('id')->values()->toArray()
    ];
    }

    private function groupFormat($enrollments)
    {
    return [
        'type' => 'group',
        'student_count' => $enrollments->groupBy('id_olympist_detail')->count(),
        'enrollment_count' => $enrollments->count()
    ];
    }
    public function getByResponsible($ci, $status)
    {
        $responsible = Person::where('ci_person', $ci)
            ->first(['names', 'surnames', 'ci_person']);
        if ($status !== 'TODOS') {
            $lists = EnrollmentList::with([
                'enrollments.olympicDetail..olympist:names,surnames,ci_person',
                'enrollments.nivel.enrollments.area:name,id_area'
            ])->where('ci_enrollment_responsible', $ci)->where('status', $status)->get(['id_list', 'status', 'ci_enrollment_responsible']);
        } else {
            $lists = EnrollmentList::with([
                'enrollments.olympicDetail..olympist:names,surnames,ci_person',
                'enrollments.nivel.enrollments.area:name,id_area'
            ])->where('ci_enrollment_responsible', $ci)->get(['id_list', 'status', 'ci_enrollment_responsible']);
        }

        if ($lists->isEmpty()) {
            return response()->json(
                [
                    'message' => 'No existe ninguna lista asociada a este CI.',
                    'ci_buscado' => $ci
                ],
                404 // Not Found
            );
        }

        $answer = [];
        foreach ($lists as $list) {
            // Primero: Todos los campos de la lista
            $enrollments = $list->enrollments;

            $allSameDetail = $enrollments->count() > 0 && 
            $enrollments->every(function ($insc) use ($enrollments) {
                return $insc->id_olympist_detail === $enrollments->first()->id_olympist_detail;
            });
            $item = [
                'id_list' => $list->id_list,
                'status' => $list->status,
                'detail' => $allSameDetail ? $this->individualFormat($enrollments) : $this->groupFormat($enrollments)
            ];
            $answer[] = $item;
        }
        return response()->json([
        'responsible' => [
            'ci' => $responsible->ci_person,
            'nombres' => $responsible->names,
            'apellidos' => $responsible->surnames
        ],
        'lists' => $answer
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
        $lists = ListaInscripcion::with([
            'inscripciones.detalleOlimpista.olimpista:nombres,apellidos,ci_persona',
            'inscripciones.nivel.asociaciones.area:nombre,id_area'
        ])
        ->whereIn('id_lista', $idsListas)
        ->get(['id_lista', 'estado', 'ci_responsable_inscripcion']);

        $answer = [];
        foreach ($lists as $list) {
            $enrollments = $list->inscripciones;
            $allSameDetalle = $enrollments->count() > 0 && 
                $enrollments->every(function ($insc) use ($enrollments) {
                    return $insc->id_detalle_olimpista === $enrollments->first()->id_detalle_olimpista;
                });

            $item = [
                'id_lista' => $list->id_lista,
                'estado' => $list->estado,
                'detalle' => $allSameDetalle ? $this->formatoIndividual($enrollments) : $this->formatoGrupal($enrollments)
            ];
            $answer[] = $item;
        }

        return response()->json([
            'responsable' => [
                'ci' => $responsable->ci_persona,
                'nombres' => $responsable->nombres,
                'apellidos' => $responsable->apellidos
            ],
            'listas' => $answer
        ], 200);
    }

    public function individual($id){
        try {
            $list = ListaInscripcion::with([
                'olimpiada:costo,id_olimpiada',
                'inscripciones.detalleOlimpista.olimpista',
                'inscripciones.nivel.asociaciones.area',
            ])->findOrFail($id);
            
            $responsable = Persona::where('ci_persona', $list->ci_responsable_inscripcion)
            ->first(['nombres', 'apellidos', 'ci_persona']);

            // Verificar que sea individual
            if ($list->inscripciones->groupBy('id_detalle_olimpista')->count() > 1) {
                throw new \Exception('Esta función es solo para listas individuales');
            }
    
            $precioUnitario = (float)$list->olimpiada->costo;
            $montoTotal = round((float)$precioUnitario * $list->inscripciones->count(), 2);
            $cantidad = $list->inscripciones->count();

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
            $niveles = $list->inscripciones->map(function ($enrollment) {
                return [
                    'nivel_id' => $enrollment->nivel->id_nivel,
                    'nombre_nivel' => $enrollment->nivel->nombre,
                    'area' => optional($enrollment->nivel->asociaciones->first())->area->nombre ?? 'Sin área'
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
                    'ci' => $list->inscripciones->first()->detalleOlimpista->olimpista->ci_persona,
                    'nombres' => $list->inscripciones->first()->detalleOlimpista->olimpista->nombres,
                    'apellidos' => $list->inscripciones->first()->detalleOlimpista->olimpista->apellidos
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
            $list = ListaInscripcion::with([
                'inscripciones',
                'responsable',
                'olimpiada'
            ])->findOrFail($id);
            $responsable = Persona::where('ci_persona', $list->ci_responsable_inscripcion)
            ->first(['nombres', 'apellidos', 'ci_persona']);

            // Cálculos básicos
            $precioUnitario = (float)$list->olimpiada->costo;
            $cantidad = $list->inscripciones->count();
            
            $montoTotal = round((float)$list->olimpiada->costo * $list->inscripciones->count(), 2);

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
                    'ci' => $list->ci_responsable_inscripcion,
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
                    'participantes_unicos' => $list->inscripciones->groupBy('id_detalle_olimpista')->count()
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
        $lists = ListaInscripcion::with(
            'inscripciones.detalleOlimpista.olimpista',
            'inscripciones.detalleOlimpista.grado',
            'inscripciones.detalleOlimpista.colegio.provincia.departamento',
            'inscripciones.nivel.asociaciones.area')
                ->where('estado', 'PAGADO')
                ->where('id_olimpiada', $id)
                ->get();
        $data = [];
        foreach ($lists as $list) {
            // Acceder al olimpista relacionado (asumiendo que hay una relación definida en el modelo)
            $enrollments = $list->inscripciones;
            foreach ($enrollments as $enrollment) {
                $olimpista = $enrollment -> detalleOlimpista;
                $persona = $olimpista -> olimpista;
                $grado = $olimpista -> grado;
                $colegio = $olimpista-> colegio;
                $provincia = $colegio -> provincia;
                $departamento = $provincia -> departamento ?? null;
                $nivel = $enrollment -> nivel;
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
