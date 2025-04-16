<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\InscripcionesImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        // Validación del archivo
        $request->validate([
            'file' => 'required|file'
        ]);

        // Importar el archivo y obtener los datos
        $import = new InscripcionesImport();
        Excel::import($import, $request->file('file'));

        // Devolver los datos extraídos en formato JSON
        return response()->json([
            'message' => 'Excel import completed.',
            'raw_data' => $import->rawRows,
        ], 201);
    }
}
