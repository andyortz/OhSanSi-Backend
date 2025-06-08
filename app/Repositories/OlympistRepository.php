<?php
namespace App\Repositories;

use App\Modules\Olympiad\Models\Area;
use App\Modules\Olympist\Models\OlympistDetail;
use App\Modules\Olympiad\Models\GradeLevel;
use App\Modules\Olympiad\Models\Olympiad;

class OlympistRepository
{
    public function getAreasLevels($id)
    {
        // 1. Get the Olympian's grade level
        $olympist = OlympistDetail::select('id_grade')
            ->where('id_olympist_detail', $id)
            ->firstOrFail();

        // 2. Get the current Olympiad (based on today's date)
        $currentOlympiad = Olympiad::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$currentOlympiad) {
            return response()->json([
                'success' => false,
                'message' => 'There is no active Olympiad at the current date'
            ], 404);
        }

        // 3. Main query with all filters
        $result = Area::whereHas('nivel_area', function($query) use ($currentOlympiad, $olympist) {
                $query->where('id_olympiad', $currentOlympiad->id_olympiad)
                    ->whereHas('grade_level', function($q) use ($olympist) {
                        $q->where('id_grade', $olympist->id_grade);
                    });
            })
            ->with(['nivel_area' => function($query) use ($olympist) {
                $query->whereHas('grade_level', function($q) use ($olympist) {
                        $q->where('id_grade', $olympist->id_grade);
                    })
                    ->with(['grade_level.category_level']);
            }])
            ->get()
            ->map(function($area) use ($olympist) {
                $filteredLevels = $area->nivel_area
                    ->filter(function($areaLevel) {
                        return $areaLevel->grade_level !== null;
                    })
                    ->map(function($areaLevel) {
                        return [
                            'id_level' => $areaLevel->grade_level->id_level,
                            'level_name' => $areaLevel->grade_level->category_level->name,
                            'id_grade' => $areaLevel->grade_level->id_grade
                        ];
                    })
                    ->unique('level_id')
                    ->values();

                return $filteredLevels->isNotEmpty() ? [
                    'id_area' => $area->id_area,
                    'area_name' => $area->name,
                    'levels' => $filteredLevels
                ] : null;
            })
            ->filter()
            ->values();

        return $result;
    }
}