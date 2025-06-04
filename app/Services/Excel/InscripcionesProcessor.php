<?php

namespace App\Services\Excel;

use App\Services\Registers\InscripcionService;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleOlimpista;
use Illuminate\Support\Carbon;
use App\Services\Registers\ListaInscripcionService;
use App\Services\ImportHelpers\NivelResolver;
use App\Models\Olimpiada;

class InscripcionesProcessor
{
    public static function save(array $sanitizedData, int $ci_responsable, array &$resultado): void
    {
        $service = app(InscripcionService::class);
        $listaService = app(\App\Services\Registers\ListaInscripcionService::class);

        

        $interesados = self::selectData($sanitizedData);
        $hoy = Carbon::now();

        // Obtener CI de un olimpista para encontrar la olimpiada
        $idOlimpiada = Olimpiada::where('fecha_inicio', '<=', $hoy) ->first();
        // $primerCI = $interesados[0]['ci'] ?? null;
        // $detalle = DetalleOlimpista::where('ci_olimpista', $primerCI)->first();

        // if (!$detalle) {
        //     $resultado['inscripciones_errores'][] = [
        //         // 'ci' => $primerCI?? 'Desconocido',
        //         'message' => 'Complete los campos campos correctamente antes de inscribir',
        //         // 'fila' => $sanitizedData['fila'] + 2
        //     ];
        //     return;
        // }

        // Crear UNA sola lista
        $lista = $listaService->crearLista($ci_responsable, $idOlimpiada->id_olimpiada);
        $idLista = $lista->id_lista;

        foreach ($interesados as $data) {
            try {
                // 'ci' => $item[2],
        //         'nivel' => $item[15],
        //         'estado' => 'PENDIENTE',
        //         'ci_tutor_academico' => $item[18] ?? null,
        //         'fila' => $item['fila'] ?? null,
                //validacion de nivel
                $data['nivel'] = NivelResolver::resolve($data['nivel']);

                if (!is_numeric($data['ci'])) {
                    self::agregarErrorInscripcion(
                        $resultado,
                        $data['ci'],
                        'El CI del olimpista no es válido',
                        $data['fila'] + 2
                    );
                    continue;
                }

                
                //Verificamos que tenga un nivel asociado.
                if($data['nivel'] == null){
                    self::agregarErrorInscripcion(
                        $resultado,
                        $data['ci'],
                        'El nivel que desea inscribir no es válido',
                        $data['fila'] + 2
                    );
                    // continue;
                }
                $detalle = DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();
                if ($detalle == null) {
                    self::agregarErrorInscripcion(
                        $resultado,
                        $data['ci'],
                        'El CI: "'.$data['ci'].'" no se encuentra registrado como olimpista, revise los datos ingresados',
                        $data['fila'] + 2
                    );
                    continue;
                }else{
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
                        self::agregarErrorInscripcion(
                            $resultado,
                            $data['ci'],
                            'El olimpista ya alcanzó el límite de inscripciones',
                            $data['fila'] + 2
                        );
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
                        self::agregarErrorInscripcion(
                            $resultado,
                            $data['ci'],
                            'El olimpista ya está inscrito en el nivel seleccionado',
                            $data['fila'] + 2
                        );
                        continue;
                    }
                    $data['ci_responsable_inscripcion'] = $ci_responsable;
                    $data['id_lista'] = $idLista;

                    $inscripcion = $service->register($data);

                    $resultado['inscripciones_guardadas'][] = [
                        'ci' => $data['ci'],
                        'nivel' => $data['nivel'],
                        'id_lista' => $inscripcion->id_lista ?? null,
                    ];
                }
                
            } catch (\Throwable $e) {
                $resultado['inscripciones_errores'][] = [
                    'ci' => $data['ci'] ?? 'Desconocido',
                    'message' => $e->getMessage(),
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
    private static function agregarErrorInscripcion(array &$resultado, $ci, $mensaje, $fila)
    {
        // Buscar si ya hay un error con ese CI y fila
        $indice = null;
        foreach ($resultado['inscripciones_errores'] as $i => $error) {
            if ($error['ci'] == $ci && $error['fila'] == $fila) {
                $indice = $i;
                break;
            }
        }

        if ($indice !== null) {
            // Ya existe, agregar nuevo mensaje
            if (!isset($resultado['inscripciones_errores'][$indice]['message'])) {
                $resultado['inscripciones_errores'][$indice]['message'] = [];
                if (isset($resultado['inscripciones_errores'][$indice]['message'])) {
                    // Migrar error plano si existe
                    $resultado['inscripciones_errores'][$indice]['message'][] = $resultado['inscripciones_errores'][$indice]['message'];
                    unset($resultado['inscripciones_errores'][$indice]['message']);
                }
                if (isset($resultado['inscripciones_errores'][$indice]['message'])) {
                    $resultado['inscripciones_errores'][$indice]['message'][] = $resultado['inscripciones_errores'][$indice]['message'];
                    unset($resultado['inscripciones_errores'][$indice]['message']);
                }
            }

            $resultado['inscripciones_errores'][$indice]['message'][] = $mensaje;
        } else {
            // No existe, crear nuevo
            $resultado['inscripciones_errores'][] = [
                'ci' => $ci,
                'message' => [$mensaje],
                'fila' => $fila
            ];
        }
    }
}
