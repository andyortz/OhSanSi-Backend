<?php

namespace App\Modules\Enrollments\Controllers;

use Illuminate\Http\Request;
use App\Imports\InscripcionesImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $import = new InscripcionesImport();
            $import->import($request->file('file'));

            return response()->json([
                'data' => $import->rawRows,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
