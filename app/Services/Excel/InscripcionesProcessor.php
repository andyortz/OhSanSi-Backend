<?php

namespace App\Services\Excel;

use App\Services\Registers\InscripcionService;
use Illuminate\Support\Facades\DB;
use App\Models\DetalleOlimpista;
use Illuminate\Support\Carbon;

class InscripcionesProcessor
{
    public static function save(array $sanitizedData, int $ci_responsable, array &$resultado): void
    {
        $service = app(InscripcionService::class);
        $interesados = self::selectData($sanitizedData);
        $hoy = Carbon::now();
        
        foreach ($interesados as $data) {
            try {
                //Verificamos si el olimpista ya se encuentra registrado para realizar la inscripcion
                if(is_numeric($data['ci'])){
                    $detalle = DetalleOlimpista::where('ci_olimpista', $data['ci'])->first();
                    if (!$detalle) {
                        // throw new \Exception('El Olimpista no se encuentra registrado.', 404);
                        $resultado['inscripciones_errores'][] = [
                            'ci' => $data['ci'] ?? 'Desconocido',
                            'error' => $e->getMessage(),
                            // 'fila'=> $fila + 1
                        ];
                        continue;
                    }
                }else{
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'] ?? 'Desconocido',
                        'error' => 'El CI: "'.$data['ci'].'" del olimpista no es válido',
                        // 'fila'=> $fila + 1
                    ];
                    continue;
                }
                
                //obtenemos el limite permitido para la olimpiada
                $limite = DB::table('olimpiada')
                    ->where('fecha_inicio','<=', $hoy)
                    ->where('fecha_fin','>=',$hoy)
                    ->pluck('max_categorias_olimpista')
                    ->first();
                
                //en aqui verificamos a cuantas areas va inscrito el olimpista
                $areasinscrito = DB::table('inscripcion')
                    ->join('detalle_olimpista', 'inscripcion.id_detalle_olimpista', 'detalle_olimpista.id_detalle_olimpista')
                    ->join('nivel_area_olimpiada', 'inscripcion.id_nivel', 'nivel_area_olimpiada.id_nivel')
                    ->where('detalle_olimpista.ci_olimpista', $data['ci'])
                    ->select('nivel_area_olimpiada.id_area')
                    ->distinct()
                    ->count();

                //verificar si la cantidad de inscripciones no supere el limite de la olimpiada.
                if($areasinscrito >= $limite){
                    $resultado['inscripciones_errores'][] = [
                        'ci' => $data['ci'],
                        // 'fila'=> $fila + 2,
                        'error' => 'El olimpista ya alcanzó el limite de inscripciones alcanzado'
                    ];
                    continue;
                }
                // Inyectar CI del responsable directamente
                $data['ci_responsable_inscripcion'] = $ci_responsable;

                $inscripcion = $service->register($data);
                
                $resultado['inscripciones_guardadas'][] = [
                    'ci' => $data['ci'],
                    'nivel' => $data['nivel'],
                    'limite'=> $limite,
                    'areas_inscrito' => $areasinscrito,
                    'id_lista' => $inscripcion->id_lista ?? null
                ];
            } catch (\Throwable $e) {
                $resultado['inscripciones_errores'][] = [
                    'ci' => $data['ci'] ?? 'Desconocido',
                    // 'fila'=> $fila + 2,
                    'error' => $e->getMessage()
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
                'ci_tutor_academico' => $item[18] ?? null
            ];
        })->toArray();
    }
}
