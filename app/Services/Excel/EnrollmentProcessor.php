<?php

namespace App\Services\Excel;

use App\Services\Registers\InscripcionService;
use Illuminate\Support\Facades\DB;
use App\Modules\Persons\Models\OlympistDetail;
use Illuminate\Support\Carbon;
use App\Services\Registers\EnrollmentListService;
use App\Services\Registers\EnrollmentService;
use App\Services\ImportHelpers\LevelResolver;
use App\Modules\Olympiads\Models\Olympiad;

class EnrollmentProcessor
{
    public static function save(array $sanitizedData, int $responsible_ci, array &$finalResponse): void
    {
        $service = app(EnrollmentService::class);
        $listService = app(EnrollmentListService::class);

        

        $interested = self::selectData($sanitizedData);
        $today = Carbon::now();

        // Obtener CI de un olimpista para encontrar la olimpiada
        $olympiadId = Olympiad::where('start_date', '<=', $today) ->first();
        // $primerCI = $interested[0]['ci'] ?? null;
        // $detalle = DetalleOlimpista::where('ci_olimpista', $primerCI)->first();

        // if (!$detalle) {
        //     $finalResponse['enrollments_errors'][] = [
        //         // 'ci' => $primerCI?? 'Desconocido',
        //         'message' => 'Complete los campos campos correctamente antes de inscribir',
        //         // 'fila' => $sanitizedData['fila'] + 2
        //     ];
        //     return;
        // }

        // Crear UNA sola lista
        $list = $listService->createList($responsible_ci, $olympiadId->olympiad_id);
        $listId = $list->list_id;

        foreach ($interested as $data) {
            try {
                // 'ci' => $item[2],
        //         'nivel' => $item[15],
        //         'estado' => 'PENDIENTE',
        //         'ci_tutor_academico' => $item[18] ?? null,
        //         'fila' => $item['fila'] ?? null,
                //validacion de nivel
                $data['level'] = LevelResolver::resolve($data['level']);

                if (!is_numeric($data['ci'])) {
                    self::agregarErrorInscripcion(
                        $finalResponse,
                        $data['ci'],
                        'El CI del olimpista no es válido',
                        $data['row'] + 2
                    );
                    continue;
                }

                
                //Verificamos que tenga un nivel asociado.
                if($data['level'] == null){
                    self::agregarErrorInscripcion(
                        $finalResponse,
                        $data['ci'],
                        'El nivel que desea inscribir no es válido',
                        $data['row'] + 2
                    );
                    // continue;
                }
                $detalle = DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();
                if ($detalle == null) {
                    self::agregarErrorInscripcion(
                        $finalResponse,
                        $data['ci'],
                        'El CI: "'.$data['ci'].'" no se encuentra registrado como olimpista, revise los datos ingresados',
                        $data['row'] + 2
                    );
                    continue;
                }else{
                    //obtenemos el limite permitido para la olimpiada
                    $limit = DB::table('olympiad')
                        ->where('start_date', '<=', $today)
                        ->where('end_date', '>=', $today)
                        ->pluck('max_categories_per_olympist')
                        ->first();

                    $areasregistered = DB::table('enrollment')
                        ->join('olympist_detail', 'enrollment.olympist_detail_id', 'olympist_detail.olympist_detail_id')
                        ->join('olympiad_area_level', 'enrollment.level_id', 'olympiad_area_level.level_id')
                        ->where('olympist_detail.olympist_ci', $data['ci'])
                        ->select('olympiad_area_level.area_id')
                        ->distinct()
                        ->count();

                    if ($areasregistered >= $limit) {
                        self::agregarErrorInscripcion(
                            $finalResponse,
                            $data['ci'],
                            'El olimpista ya alcanzó el límite de inscripciones',
                            $data['row'] + 2
                        );
                        continue;
                    }
                    //Verificamos que no se inscriba a una mismo nivel
                    $existingLevel = DB::table('enrollment')
                        ->join('olympist_detail', 'enrollment.olympist_detail_id', 'olympist_detail.olympist_detail_id')
                        ->where('olympist_detail.olympist_ci', $data['ci'])
                        ->where('enrollment.level_id', $data['nivel'])
                        ->pluck('enrollment.level_id')
                        ->first();

                    if ($existingLevel == $data['nivel']) {
                        self::agregarErrorInscripcion(
                            $finalResponse,
                            $data['ci'],
                            'El olimpista ya está inscrito en el nivel seleccionado',
                            $data['row'] + 2
                        );
                        continue;
                    }
                    $data['enrollment_responsible_ci'] = $responsible_ci;
                    $data['list_id'] = $listId;

                    $enrollment = $service->register($data);

                    $finalResponse['enrollments_saved'][] = [
                        'ci' => $data['ci'],
                        'level' => $data['nivel'],
                        'list_id' => $enrollment->list_id ?? null,
                    ];
                }
                
            } catch (\Throwable $e) {
                $finalResponse['enrollments_errors'][] = [
                    'ci' => $data['ci'] ?? 'Desconocido',
                    'message' => $e->getMessage(),
                    'row'=> $data['row'] + 2
                ];
            }
        }
    }


    private static function selectData(array $sanitizedData): array
    {   
        
        return collect($sanitizedData)->map(function ($item) {
            return [
                'ci' => $item[2],
                'level' => $item[15],
                'status' => 'PENDIENTE',
                'academic_tutor_ci' => $item[18] ?? null,
                'row' => $item['index'] ?? null,
            ];
        })->toArray();
    }
    private static function agregarErrorInscripcion(array &$finalResponse, $ci, $message, $row)
    {
        // Buscar si ya hay un error con ese CI y fila
        $index = null;
        foreach ($finalResponse['enrollments_errors'] as $i => $error) {
            if ($error['ci'] == $ci && $error['row'] == $row) {
                $index = $i;
                break;
            }
        }

        if ($index !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($finalResponse['enrollments_errors'][$index]['message'])) {
                $finalResponse['enrollments_errors'][$index]['message'] = [];
                if (isset($finalResponse['enrollments_errors'][$index]['message'])) {
                    // Migrar error plano si existe
                    $finalResponse['enrollments_errors'][$index]['message'][] = $finalResponse['enrollments_errors'][$index]['message'];
                    unset($finalResponse['enrollments_errors'][$index]['message']);
                }
                if (isset($finalResponse['enrollments_errors'][$index]['message'])) {
                    $finalResponse['enrollments_errors'][$index]['message'][] = $finalResponse['enrollments_errors'][$index]['message'];
                    unset($finalResponse['enrollments_errors'][$index]['message']);
                }
            }

            $finalResponse['enrollments_errors'][$index]['message'][] = $message;
        } else {
            // No existe, crear nuevo
            $finalResponse['enrollments_errors'][] = [
                'ci' => $ci,
                'message' => [$message],
                'row' => $row
            ];
        }
    }
}
