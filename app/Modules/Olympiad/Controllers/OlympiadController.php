<?php

namespace App\Modules\Olympiad\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Olympiad\Models\Olympiad;
use Illuminate\Http\Request;

class OlympiadController extends Controller
{
    public function index()
    {
        $olympiads = Olympiad::all();
        return response()->json($olympiads, 200);
    }
    public function upcoming()
    {
        $today = Carbon::now();
        $olympiads = Olympiad::where('start-date', '>', $today)->get();
        return response()->json($olympiads, 200);
    }
    public function getByYear($year)
    {
        $olympiads = Olympiad::where('year', $year) -> get();
        if (!$olympiad) {
            return response()->json([
                'message' => "No Olympiad found for $year management."
            ], 404);
        }
        return response()->json($olympiads, 200);
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'year' => 'required|integer',
                'cost' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'max_olympic_categories' => 'required|integer',
                'olympiad_name' => 'required|string|max:255',
            ]);

            $olympiad = Olympiad::create($validated);
            return response()->json([
                'message' => 'Olympiad created successfully',
                'olympiad' => $olympiad
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating olympiad',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function maxCategoriesOlympiad($id)
    {
        $olympiad = Olimpiad::find($id);

        if (!$olympiad) {
            return response()->json([
                'message' => 'Olympiad not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'maximum number of Olympic categories successfully obtained',
            'max_categories' => $olympiad->max_olympic_categories
        ], 200);
    }
    public function getMaxCategories()
    {
        $today = Carbon::now();
        $olympiad = Olympiad::where('start_date', '<=', $today)
            ->where('fecha_fin', '>=', $today)
            ->first();

        if (!$olympiad) {
            return response()->json([
                'success' => false,
                'message' => 'No active Olympiad found on the current date'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'date' => $today,
            'id_olympiad' => $olympiad->id_olympiad,
            'max_olympic_categories' => $olympiad->max_olympic_categories
        ]);
    }
    public function getAreasWithLevels($idOlympiad)
    {
        try {
            $olympiad = Olympiad::findOrFail($idOlympiad);
            
            $LevelsInAreas = AreaLevelOlympiad::with([
                'area:id_area,name',
                'category_level:id_level,name'
            ])
            ->where('id_olympiad', $idOlympiad)
            ->get()
            ->groupBy('id_area'); // Agrupar por área

            $response = [
                'year' => $olympiad->year,
                'areas' => $LevelsInAreas->map(function ($items, $idArea) {
                    return [
                        'id_area' => (int) $idArea,
                        'area_name' => $items->first()->area->name,
                        'levels' => $items->map(function ($item) {
                            return [
                                'id_level' => $item->id_level,
                                'level_name' => trim($item->level->name),
                            ];
                        })->unique('id_level')->values()
                    ];
                })->values()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching areas with levels', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}