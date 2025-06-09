<?php

namespace App\Modules\Olympist\Controllers;

use App\Modules\Olympist\Models\EnrollmentList;
use App\Modules\Olympiad\Models\AreaLevelOlympiad;
use App\Modules\Olympist\Models\Payment;
use App\Modules\Olympist\Models\Person;
use App\Modules\Olympiad\Models\Enrollment;

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

    public function pendingPaymentLists($id)
    {
        $responsiblePerson = Person::where('id_person', $id)
            ->first(['names', 'surnames', 'id_person']);

        if (!$responsiblePerson) {
            return response()->json([
                'message' => 'No person found with this ID.',
                'searched_id' => $id
            ], 404);
        }

        // Get enrollment lists with pending payments (verified = false)
        $listsWithPendingPayment = \DB::table('enrollment_list')
            ->join('payment', 'enrollment_list.id_list', '=', 'payment.id_list')
            ->where('enrollment_list.ci_enrollment_responsible', $id)
            ->where('payment.verified', false);

        $listIds = $listsWithPendingPayment
            ->pluck('enrollment_list.id_list')
            ->unique()
            ->toArray();

        if (empty($listIds)) {
            return response()->json([
                'message' => 'No lists with pending payments found for this responsible person.',
                'searched_id' => $id
            ], 404);
        }

        // Fetch only the lists filtered by those IDs
        $lists = EnrollmentList::with([
                'enrollments.olimpist_detail.olympist:names,surnames,ci_person',
                'enrollments.category_level.area_level_olympiad.area:name,id_area'
            ])
            ->whereIn('id_list', $listIds)
            ->get(['id_list', 'status', 'ci_enrollment_responsible']);

        $response = [];
        foreach ($lists as $list) {
            $enrollments = $list->enrollments;
            $allSameDetail = $enrollments->count() > 0 && 
                $enrollments->every(function ($enrollment) use ($enrollments) {
                    return $enrollment->id_olympist_detail === $enrollments->first()->id_olympist_detail;
                });

            $item = [
                'id_list' => $list->id_list,
                'status' => $list->status,
                'detail' => $allSameDetail ? $this->individualFormat($enrollments) : $this->groupFormat($enrollments)
            ];
            $response[] = $item;
        }

        return response()->json([
            'responsible_person' => [
                'id' => $responsiblePerson->ci_perosn,
                'names' => $responsiblePerson->names,
                'surnames' => $responsiblePerson->surnames
            ],
            'lists' => $response
        ], 200);
    }
    public function individual($id) {
        try {
            $list = EnrollmentList::with([
                'olympiad:cost,id_olympiad',
                'enrollments.olympist_detail.olympist',
                'enrollments.category_level.area_level_olympiad.area',
            ])->findOrFail($id);
            
            $responsiblePerson = Person::where('ci_person', $list->person)
                ->first(['names', 'surnames', 'ci_person']);

            // Verify it's individual
            if ($list->enrollments->groupBy('id_olympist_detail')->count() > 1) {
                throw new \Exception('This function is only for individual lists');
            }
            $unitPrice = (float)$list->olympiad->cost;
            $totalAmount = round($unitPrice * $list->enrollments->count(), 2);
            $quantity = $list->enrollments->count();

            $payment = Payment::firstOrCreate(
                ['id_list' => $id],
                [
                    'receipt' => 'PAYMENT-' . uniqid(),
                    'payment_date' => now(),
                    'total_amount' => $totalAmount,
                ]
            );
            // Process levels
            $levels = $list->enrollments->map(function ($enrollment) {
                return [
                    'id_level' => $enrollment->category_level->id_level,
                    'level_name' => $enrollment->category_level->name,
                    'area' => optional($enrollment->category_level->area_level_olympiad->first())->area->name ?? 'No area'
                ];
            });

            return response()->json([
                'responsible_person' => [
                    'id' => $responsiblePerson->person_ci,
                    'names' => $responsiblePerson->names,
                    'surnames' => $responsiblePerson->surnames
                ],
                'payment' => [
                    'id' => $payment->id_payment,
                    'reference' => $payment->receipt,
                    'unit_price' => $unitPrice,
                    'total_enrollments' => $quantity,
                    'total_to_pay' => $totalAmount,
                    'payment_date' => now()
                ],
                'olympist' => [
                    'id' => $list->enrollments->first()->olympist_detail->olympist->ci_person,
                    'names' => $list->enrollments->first()->olympist_detail->olympist->names,
                    'surnames' => $list->enrollments->first()->olympist_detail->olympist->surnames
                ],
                'levels' => $levels
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function group($id) {
        try {
            $list = EnrollmentList::with([
                'enrollments',
                'person',
                'olympiad'
            ])->findOrFail($id);
            
            $responsiblePerson = Person::where('ci_person', $list->ci_enrollment_responsible)
                ->first(['names', 'surnames', 'ci_person']);

            // Basic calculations
            $unitPrice = (float)$list->olympiad->cost;
            $quantity = $list->enrollments->count();
            $totalAmount = round($unitPrice * $quantity, 2);

            // Verify/create payment
            $payment = Payment::firstOrCreate(
                ['id_list' => $id],
                [
                    'receipt' => 'PAYMENT-' . uniqid(),
                    'payment_date' => now(),
                    'total_amount' => $totalAmount,
                ]
            );
            return response()->json([
                'responsible_person' => [
                    'ci' => $list->ci_enrollment_responsible,
                    'names' => $responsiblePerson->names,
                    'surnames' => $responsiblePerson->surnames
                ],
                'payment' => [
                    'id' => $payment->payment_id,
                    'reference' => $payment->receipt,
                    'unit_price' => $unitPrice,
                    'total_enrollments' => $quantity,
                    'total_to_pay' => $totalAmount,
                    'payment_date' => now()
                ],
                'group_details' => [
                    'unique_participants' => $list->enrollments->groupBy('id_olympist_detail')->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error processing request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getById($id){
        $lists = ListaInscripcion::with(
            'enrollments.olympist_detail.olympist',
            'enrollments.olympist_detail.grade',
            'enrollments.olympist_detail.school.province.departament',
            'enrollments.category_level.area_level_olympiad.area')
                ->where('status', 'PAGADO')
                ->where('id_olympiad', $id)
                ->get();
        $data = [];
        foreach ($lists as $list) {
            $enrollments = $list->enrollments;
            foreach ($enrollments as $enrollment) {
                $olympist = $enrollment -> olympist_detail;
                $person = $olympist -> olympist;
                $grade = $olympist -> grade;
                $colegio = $olympist-> school;
                $province = $colegio -> province;
                $departament = $province -> departament ?? null;
                $level = $enrollment -> level;
                $area = $level -> area_level_olympiad -> first() -> area;
    
                $data[] = [
                    'surnames' => $person->surnames,
                    'names' => $person->names,
                    'ci' => $person -> ci_person,
                    'school' => $colegio->school_name,
                    'grade' => $grade -> grade_name,
                    'departament' => $departament->departament_name,
                    'province' => $provincia-> province_name,
                    'area' => $area->name,
                    'level' => $level->name,
                ];
            }
        }
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);

    }
}
