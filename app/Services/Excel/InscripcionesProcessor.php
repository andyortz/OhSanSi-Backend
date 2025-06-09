<?php

namespace App\Services\Excel;

use App\Services\Registers\EnrollmentService;
use Illuminate\Support\Facades\DB;
use App\Modules\Olympist\Models\OlympistDetail;
use Illuminate\Support\Carbon;
use App\Services\Registers\EnrollmentListService;
use App\Services\ImportHelpers\LevelResolver;
use App\Modules\Olympiad\Models\Olympiad;

class InscripcionesProcessor
{
    public static function save(array $sanitizedData, int $ci_responsible, array &$answerFinal): void
    {
        $service = app(EnrollmentService::class);
        $listService = app(EnrollmentListService::class);

        

        $interested = self::selectData($sanitizedData);
        $today = Carbon::now();

        // Obtener CI de un olimpista para encontrar la olimpiada
        $idOlympiad = Olympiad::where('start_date', '<=', $today) ->first();
        // $primerCI = $interested[0]['ci'] ?? null;
        // $detail = DetalleOlimpista::where('ci_olimpista', $primerCI)->first();

        // if (!$detail) {
        //     $answerFinal['registrations_errors'][] = [
        //         // 'ci' => $primerCI?? 'Desconocido',
        //         'message' => 'Complete los campos campos correctamente antes de inscribir',
        //         // 'fila' => $sanitizedData['fila'] + 2
        //     ];
        //     return;
        // }

        // Crear UNA sola lista
        $list = $listService->createList($ci_responsible, $idOlympiad->id_olympiad);
        $idList = $list->id_list;

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
                    self::addRegistrationError(
                        $answerFinal,
                        $data['ci'],
                        'El CI del olimpista no es válido',
                        $data['row'] + 2
                    );
                    continue;
                }

                
                //Verificamos que tenga un nivel asociado.
                if($data['level'] == null){
                    self::addRegistrationError(
                        $answerFinal,
                        $data['ci'],
                        'El nivel que desea inscribir no es válido',
                        $data['row'] + 2
                    );
                    // continue;
                }
                $detail = OlympistDetail::where('ci_olympic', $data['ci'])->first();
                if ($detail == null) {
                    self::addRegistrationError(
                        $answerFinal,
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
                        ->pluck('max_olympic_categories')
                        ->first();

                    $areasregistered = DB::table('enrollment')
                        ->join('olympist_detail', 'enrollment.id_olympist_detail', 'olympist_detail.id_olympist_detail')
                        ->join('area_level_olympiad', 'enrollment.id_level', 'area_level_olympiad.id_level')
                        ->where('olympist_detail.ci_olympic', $data['ci'])
                        ->select('area_level_olympiad.id_area')
                        ->distinct()
                        ->count();

                    if ($areasregistered >= $limit) {
                        self::addRegistrationError(
                            $answerFinal,
                            $data['ci'],
                            'El olimpista ya alcanzó el límite de inscripciones',
                            $data['row'] + 2
                        );
                        continue;
                    }
                    //Verificamos que no se inscriba a una mismo nivel
                    $existingLevel = DB::table('enrollment')
                        ->join('olympist_detail', 'enrollment.id_olympist_detail', 'olympist_detail.id_olympist_detail')
                        ->where('olympist_detail.ci_olympic', $data['ci'])
                        ->where('enrollment.id_level', $data['level'])
                        ->pluck('enrollment.id_level')
                        ->first();

                    if ($existingLevel == $data['level']) {
                        self::addRegistrationError(
                            $answerFinal,
                            $data['ci'],
                            'El olimpista ya está inscrito en el nivel seleccionado',
                            $data['row'] + 2
                        );
                        continue;
                    }
                    $data['ci_enrollment_responsible'] = $ci_responsible;
                    $data['id_list'] = $idList;

                    $enrollment = $service->register($data);

                    $answerFinal['registrations_saved'][] = [
                        'ci' => $data['ci'],
                        'level' => $data['level'],
                        'id_list' => $enrollment->id_list ?? null,
                    ];
                }
                
            } catch (\Throwable $e) {
                $answerFinal['registrations_errors'][] = [
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
                'ci_academic_advisor' => $item[18] ?? null,
                'row' => $item['row'] ?? null,
            ];
        })->toArray();
    }
    private static function addRegistrationError(array &$answerFinal, $ci, $message, $row)
    {
        // Buscar si ya hay un error con ese CI y fila
        $index = null;
        foreach ($answerFinal['registrations_errors'] as $i => $error) {
            if ($error['ci'] == $ci && $error['fila'] == $row) {
                $index = $i;
                break;
            }
        }

        if ($index !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($answerFinal['registrations_errors'][$index]['message'])) {
                $answerFinal['registrations_errors'][$index]['message'] = [];
                if (isset($answerFinal['registrations_errors'][$index]['message'])) {
                    // Migrar error plano si existe
                    $answerFinal['registrations_errors'][$index]['message'][] = $answerFinal['registrations_errors'][$index]['message'];
                    unset($answerFinal['registrations_errors'][$index]['message']);
                }
                if (isset($answerFinal['registrations_errors'][$index]['message'])) {
                    $answerFinal['registrations_errors'][$index]['message'][] = $answerFinal['registrations_errors'][$index]['message'];
                    unset($answerFinal['registrations_errors'][$index]['message']);
                }
            }

            $answerFinal['registrations_errors'][$index]['message'][] = $message;
        } else {
            // No existe, crear nuevo
            $answerFinal['registrations_errors'][] = [
                'ci' => $ci,
                'message' => [$message],
                'row' => $row
            ];
        }
    }
}
