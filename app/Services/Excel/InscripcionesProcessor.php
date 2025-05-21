<?php

namespace App\Services\Excel;

use App\Services\Registers\InscripcionService;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleOlimpista;
use Illuminate\Support\Carbon;
use App\Services\Registers\ListaInscripcionService;

class InscripcionesProcessor
{
    public static function save(array $sanitizedData, int $ci_responsable, array &$resultado): void
    {
        $service = app(InscripcionService::class);
        $listaService = app(\App\Services\Registers\ListaInscripcionService::class);
        $interesados = self::selectData($sanitizedData);
        $hoy = \Carbon\Carbon::now();

        // Obtener CI de un olimpista para encontrar la olimpiada
        $primerCI = $interesados[0]['ci'] ?? null;
        $detalle = \App\Models\DetalleOlimpista::where('ci_olimpista', $primerCI)->first();

        if (!$detalle) {
            $resultado['inscripciones_errores'][] = [
                'ci' => $primerCI,
                'error' => 'No se pudo obtener la olimpiada para crear la lista'
            ];
            return;
        }

        // Crear UNA sola lista
        $lista = $listaService->crearLista($ci_responsable, $detalle->id_olimpiada);
        $idLista = $lista->id_lista;

        foreach ($interesados as $data) {
            try {
                if (!is_numeric($data['ci'])) {
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'] ?? 'Desconocido',
                        'error' => 'El CI del olimpista no es válido',

                    ];
                    continue;
                }

                $detalle = \App\Models\DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();
                if (!$detalle) {
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'] ?? 'Desconocido',
                        'error' => 'El CI: "'.$data['ci'].'" del olimpista no es válido',
                        'fila'=> $data['fila'] + 2
                    ];
                    continue;
                }
                //Verificamos que tenga un nivel asociado.
                if($data['nivel'] == null){
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'] ?? 'Desconocido',
                        'error' => 'Ha ocurrido un error al intentar obtener el nivel del olimpista',
                        'fila'=> $data['fila'] + 2
                    ];
                    continue;
                }

                //obtenemos el limite permitido para la olimpiada
                $limite = DB::table('olimpiada')
                    ->where('fecha_inicio', '<=', $hoy)
                    ->where('fecha_fin', '>=', $hoy)
                    ->pluck('max_categorias_olimpista')
                    ->first();

                $areasinscrito = DB::table('inscripcion')
                    ->join('detalle_olimpista', 'inscripcion.id_detalle_olimpista', 'detalle_olimpista.id_detalle_olimpista')
                    ->join('nivel_area_olimpiada', 'inscripcion.id_nivel', 'nivel_area_olimpiada.id_nivel')
                    ->where('detalle_olimpista.ci_olimpista', $data['ci'])
                    ->select('nivel_area_olimpiada.id_area')
                    ->distinct()
                    ->count();

                if ($areasinscrito >= $limite) {
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'],
                        'error' => 'El olimpista ya alcanzó el límite de inscripciones',
                        'fila'=> $data['fila'] + 2
                    ];
                    continue;
                }
                //Verificamos que no se inscriba a una mismo nivel
                $nivelExistente = DB::table('inscripcion')
                    ->join('detalle_olimpista', 'inscripcion.id_detalle_olimpista', 'detalle_olimpista.id_detalle_olimpista')
                    ->where('detalle_olimpista.ci_olimpista', $data['ci'])
                    ->where('inscripcion.id_nivel', $data['nivel'])
                    ->pluck('inscripcion.id_nivel')
                    ->first();

                if ($nivelExistente == $data['nivel']) {
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'],
                        'error' => 'El olimpista ya está inscrito en el nivel seleccionado ',
                        'fila'=> $data['fila'] + 2
                    ];
                    continue;
                }
                $data['ci_responsable_inscripcion'] = $ci_responsable;
                $data['id_lista'] = $idLista;

                $inscripcion = $service->register($data);

                $resultado['inscripciones_guardadas'][] = [
                    'ci' => $data['ci'],
                    'nivel' => $data['nivel'],
                    'limite' => $limite,
                    'areas_inscrito' => $areasinscrito,
                    'id_lista' => $inscripcion->id_lista ?? null,
                ];
            } catch (\Throwable $e) {
                $resultado['inscripciones_errores'][] = [
                    'ci' => $data['ci'] ?? 'Desconocido',
                    'error' => $e->getMessage(),
                    'fila'=> $data['fila'] + 2
                ];
            }
        }
    }


    private static function selectData(array $sanitizedData): array
    {
        return collect($sanitizedData)->map(function ($item) {
            return [
                'ci' => $item[2],
                'nivel' => $item[15],
                'estado' => 'PENDIENTE',
                'ci_tutor_academico' => $item[18] ?? null,
                'fila' => $item['fila'] ?? null,
            ];
        })->toArray();
    }
}
