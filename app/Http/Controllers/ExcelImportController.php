<?php

namespace App\Http\Controllers;

use App\Imports\InscripcionesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $import = new InscripcionesImport();
            Excel::import($import, $request->file('file'));

            return response()->json([
                'message' => 'Excel import completed.',
                'raw_data' => $import->rawRows,          
                'validated_data' => $import->processedData 
            ], 201);

        } catch (\Exception $e) {
            logger()->error("Excel import failed: " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred during import.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
