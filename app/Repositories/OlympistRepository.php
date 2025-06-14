<?php
namespace App\Repositories;

use App\Modules\Olympiads\Models\Area;
use App\Modules\Persons\Models\OlympistDetail;
use App\Modules\Olympiads\Models\LevelGrade;
use App\Modules\Olympiads\Models\Olympiad;

class OlympistRepository
{
    public function getLevelAreas($ci)
    {
        // 1. Obtener el grado del olimpista
        $olympist = OlympistDetail::select('grade_id')
            ->where('olympist_ci', $ci)
            ->firstOrFail();

        // 2. Obtener la olimpiada actual (basada en la fecha de hoy)
        $currentOlympiad = Olympiad::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$currentOlympiad) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una olimpiada activa en la fecha actual'
            ], 404);
        }

        // 3. Consulta principal con todos los filtros
        $answer = Area::whereHas('olympiadAreaLevel', function($query) use ($currentOlympiad, $olympist) {
                $query->where('olympiad_id', $currentOlympiad->olympiad_id)
                    ->whereHas('gradeLevel', function($q) use ($olympist) {
                        $q->where('grade_id', $olympist->grade_id);
                    });
            })
            ->with(['olympiadAreaLevel' => function($query) use ($olympist) {
                $query->whereHas('gradeLevel', function($q) use ($olympist) {
                        $q->where('grade_id', $olympist->grade_id);
                    })
                    ->with(['gradeLevel.level']);
            }])
            ->get()
            ->map(function($area) use ($olympist) {
                $filteredLevels = $area->olympiadAreaLevel
                    ->filter(function($areaLevel) {
                        return $areaLevel->gradeLevel !== null;
                    })
                    ->map(function($areaLevel) {
                        return [
                            'level_id' => $areaLevel->gradeLevel->level_id,
                            'level_name' => $areaLevel->gradeLevel->level->name,
                            'grade_id' => $areaLevel->gradeLevel->grade_id
                        ];
                    })
                    ->unique('level_id')
                    ->values();

                return $filteredLevels->isNotEmpty() ? [
                    'area_id' => $area->area_id,
                    'area_name' => $area->area_name,
                    'levels' => $filteredLevels
                ] : null;
            })
            ->filter()
            ->values();

        return $answer;
    }
}