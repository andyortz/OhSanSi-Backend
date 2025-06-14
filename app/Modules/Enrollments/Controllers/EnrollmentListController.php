<?php

namespace App\Modules\Enrollments\Controllers;

use App\Modules\Enrollments\Models\EnrollmentList;
use App\Modules\Olympiads\Models\OlympiadAreaLevel;
use App\Modules\Enrollments\Models\Payment;
use App\Modules\Persons\Models\Person;
use App\Modules\Enrollments\Models\Enrollment;

use Illuminate\Http\Request\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentListController
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

    private function individualFormat($enrollments)
    {
        $olympist = $enrollments->first()->olympistDetail->olympist;
        
        return [
            'kind' => 'individual',
            'registration_quantity' => $enrollments->count(),
            'olympist' => [
                'olympist_ci' => $olympist->person_ci,
                'names' => $olympist->names,
                'surnames' => $olympist->surnames
            ],
            'levels' => $enrollments->map(function ($insc) {
                return [
                    'level_id' => $insc->level->level_id,
                    'name_level' => $insc->level->level_name,
                    'name_area' => $insc->level->olympiadAreaLevel->first()->area->area_name ?? 'Sin área'
                ];
            })->unique('id')->values()->toArray()
        ];
    }

    private function groupFormat($enrollments)
    {
        return [
            'kind' => 'grupal',
            'number_of_students' => $enrollments->groupBy('olympist_detail_id')->count(),
            'number_of_enrollments' => $enrollments->count()
        ];
    }
    public function getByResponsible($ci, $status)
    {
        $responsible = Person::where('person_ci', $ci)
            ->first(['names', 'surnames', 'person_ci']);
        if ($status !== 'TODOS') {
            $lists = EnrollmentList::with([
                'enrollments.olympistDetail.olympist:names,surnames,person_ci',
                'enrollments.level.olympiadAreaLevel.area:area_name,area_id'
            ])->where('enrollment_responsible_ci', $ci)->where('status', $status)->get(['list_id', 'status', 'enrollment_responsible_ci']);
        } else {
            $lists = EnrollmentList::with([
                'enrollments.olympistDetail.olympist:names,surnames,person_ci',
                'enrollments.level.olympiadAreaLevel.area:area_name,area_id'
            ])->where('enrollment_responsible_ci', $ci)->get(['list_id', 'status', 'enrollment_responsible_ci']);
        }

        if ($lists->isEmpty()) {
            return response()->json(
                [
                    'message' => 'No existe ninguna lista asociada a este CI.',
                    'searched_ci' => $ci
                ],
                404 // Not Found
            );
        }
        //Desgloce
        $answer = [];
        foreach ($lists as $list) {
            // Primero: Todos los campos de la lista
            $enrollments = $list->enrollments;

            $allSameDetalle = $enrollments->count() > 0 && 
            $enrollments->every(function ($insc) use ($enrollments) {
                return $insc->olympist_detail_id === $enrollments->first()->olympist_detail_id;
            });
            $item = [
                'list_id' => $list->list_id,
                'status' => $list->status,
                'detail' => $allSameDetalle ? $this->individualFormat($enrollments) : $this->groupFormat($enrollments)
            ];
            $answer[] = $item;
        }
        return response()->json([
            'responsible' => [
                'ci' => $responsible->person_ci,
                'names' => $responsible->names,
                'surnames' => $responsible->surnames
            ],
            'lists' => $answer
        ], 200);
    }

    public function pendingPaymentlists($ci)
    {
        $responsible = Person::where('person_ci', $ci)
            ->first(['names', 'surnames', 'person_ci']);

        if (!$responsible) {
            return response()->json([
                'message' => 'No existe ninguna persona con ese CI.',
                'searched_ci' => $ci
            ], 404);
        }

        // Obtén los id_lista que tengan pago pendiente (verificado = false)
        $listsWithPaymentPending = \DB::table('enrollment_list')
            ->join('payment', 'enrollment_list.list_id', '=', 'payment.list_id')
            ->where('enrollment_list.enrollment_responsible_ci', $ci)
            ->where('payment.verified', false);

        $listsIds = $listsWithPaymentPending
            ->pluck('enrollment_list.list_id')
            ->unique()
            ->toArray();

        if (empty($listsIds)) {
            return response()->json([
                'message' => 'No existen listas con pagos pendientes para este responsable.',
                'searched_ci' => $ci
            ], 404);
        }

        // Trae solo las listas filtradas por esos IDs
        $lists = EnrollmentList::with([
            'enrollments.olympistDetail.olympist:names,surnames,person_ci',
            'enrollments.level.olympiadAreaLevel.area:area_name,area_id'
        ])
        ->whereIn('list_id', $listsIds)
        ->get(['list_id', 'status', 'enrollment_responsible_ci']);

        $answer = [];
        foreach ($lists as $list) {
            $enrollments = $list->enrollments;
            $allSameDetalle = $enrollments->count() > 0 && 
                $enrollments->every(function ($insc) use ($enrollments) {
                    return $insc->olympist_detail_id === $enrollments->first()->olympist_detail_id;
                });

            $item = [
                'list_id' => $list->list_id,
                'status' => $list->status,
                'detalle' => $allSameDetalle ? $this->individualFormat($enrollments) : $this->groupFormat($enrollments)
            ];
            $answer[] = $item;
        }

        return response()->json([
            'responsible' => [
                'ci' => $responsible->person_ci,
                'names' => $responsible->names,
                'surnames' => $responsible->surnames
            ],
            'lists' => $answer
        ], 200);
    }

    public function individual($id)
    {
        try {
            $lista = EnrollmentList::with([
                'olympiad:cost,olympiad_id',
                'enrollments.olympistDetail.olympist',
                'enrollments.level.olympiadAreaLevel.area',
            ])->findOrFail($id);
            
            $responsible = Person::where('person_ci', $list->enrollment_responsible_ci)
            ->first(['names', 'surnames', 'person_ci']);

            // Verificar que sea individual
            if ($list->enrollments->groupBy('olympist_detail_id')->count() > 1) {
                throw new \Exception('Esta función es solo para listas individuales');
            }
    
            $unitPrice = (float)$list->olympiad->cost;
            $totalAmount = round((float)$unitPrice * $list->enrollments->count(), 2);
            $amount = $list->enrollments->count();

            $payment = Payment::firstOrCreate(
                ['list_id' => $id],
                [
                    'voucher' => 'PAGO-' . uniqid(),
                    'payment_date' => now(),
                    'total_amount' => $totalAmount,
                    'status' => 'PENDIENTE'
                ]
            );
    
            // Procesar niveles
            $levels = $list->enrollments->map(function ($enrollment) {
                return [
                    'level_id' => $enrollment->level->level_id,
                    'level_name' => $enrollment->level->level_name,
                    'area' => optional($enrollment->level->olympiadAreaLevel->first())->area->area_nombre ?? 'Sin área'
                ];
            });
            return response()->json([
                'responsible' => [
                    'ci' => $responsible->person_ci,
                    'names' => $responsible->names,
                    'surnames' => $responsible->surnames
                ],
                'payment' => [
                    'id' => $payment->payment_id,
                    'reference' => $payment->voucher,
                    'unit_amount' => $unitPrice,
                    'total_registrations' => $amount,
                    'total_to_pay' => $totalAmount,
                    'status' => $payment->status,
                    'payment_date' => now()
                ],
                'olympist' => [
                    'ci' => $list->enrollments->first()->olympistDetail->olympist->person_ci,
                    'names' => $list->enrollments>first()->olympistDetail->olympist->names,
                    'surnames' => $list->enrollments->first()->olympistDetail->olympist->surnames
                ],
                'levels' => $levels
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    public function group($id)
    {
        try {
            $list = EnrollmentList::with([
                'enrollments',
                'responsible',
                'olympiad'
            ])->findOrFail($id);
            $responsible = Person::where('person_ci', $list->enrollment_responsible_ci)
            ->first(['names', 'surnames', 'person_ci']);

            // Cálculos básicos
            $unitPrice = (float)$list->olympiad->cost;
            $amount = $list->enrollments->count();
            
            $totalAmount = round((float)$list->olympiad->cost * $list->enrollments->count(), 2);

            // Verificar/crear pago
            $payment = Payment::firstOrCreate(
                ['list_id' => $id],
                [
                    'voucher' => 'PAGO-' . uniqid(),
                    'payment_date' => now(),
                    'total_amount' => $totalAmount,
                    'status' => 'PENDIENTE'
                ]
            );
    
            return response()->json([
                'responsible' => [
                    'ci' => $list->enrollment_responsible_ci,
                    'names' => $responsible->names,
                    'surnames' => $responsible->surnames
                ],
                'payment' => [
                    'id' => $payment->payment_id,
                    'reference' => $payment->voucher,
                    'unit_amount' => $unitPrice,
                    'total_registrations' => $amount,
                    'total_to_pay' => $totalAmount,
                    'status' => $payment->status,
                    'payment_date' => now()
                ],
                'group_detail' => [
                    'unique_participants' => $list->enrollments->groupBy('olympist_detail_id')->count()
                ]
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar la solicitud',
                'message' => $e->getMessage()
            ], 500);
        }
    
    }
    
    public function getById($id)
    {
        $lists = EnrollmentList::with(
            'enrollments.olympistDetail.olympist',
            'enrollments.olympistDetail.grade',
            'enrollments.olympistDetail.school.province.department',
            'enrollments.level.olympiadAreaLevel.area')
                ->where('status', 'PAGADO')
                ->where('olympiad_id', $id)
                ->get();
        $data = [];
        foreach ($lists as $list) {
            // Acceder al olimpista relacionado (asumiendo que hay una relación definida en el modelo)
            $enrollments = $list->enrollments;
            foreach ($enrollments as $enrollment) {
                $olympist = $enrollment -> olympistDetail;
                $person = $olympist -> olympist;
                $grade = $olympist -> grade;
                $school = $olympist-> school;
                $province = $school -> province;
                $department = $province -> department ?? null;
                $level = $enrollment -> level;
                $area = $level -> olympiadAreaLevel -> first() -> area;
    
                $data[] = [
                    'surnames' => $person->surnames,
                    'names' => $person->names,
                    'ci_person' => $person -> person_ci,
                    'school' => $school->school_name,
                    'grade' => $grade -> grade_name,
                    'department' => $department->department_name,
                    'province' => $province-> province_name,
                    'area' => $area->area_name,
                    'level' => $level->Level_name,
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
