<?php

namespace App\Http\Controllers;

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
}