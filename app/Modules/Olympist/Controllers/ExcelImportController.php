<?php

// namespace App\Http\Controllers;
namespace App\Modules\Olympist\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\EnrollmentsImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $import = new EnrollmentsImport();
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
