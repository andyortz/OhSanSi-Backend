<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OlimpiadaAreaController;
use App\Http\Controllers\NivelCategoriaController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\InscripcionAreaController;
use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\OlympiadRegistrationController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\OlimpiadaGestionController;
use App\Http\Controllers\AreasFiltroController;
use App\Http\Controllers\colegiosController;
use App\Http\Controllers\OlimpistaController;
use App\Http\Controllers\VincularController;
use App\Http\Controllers\EstructuraOlimpiadaController;
use App\Http\Controllers\OlimpiadaController;
use App\Http\Controllers\VerificarInscripcionController;
use App\Http\Controllers\InscripcionNivelesController;
use App\Imports\OlimpistaImport;
use App\Imports\TutoresImport;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Niveles
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);

// Grados
Route::get('/grados', [GradosController::class, 'index']);

// Áreas por olimpiada
Route::get('/olimpiada/{id}/areas', [AreasController::class, 'areasPorOlimpiada']);

// Niveles por área
Route::get('/areas/{id}/niveles', [NivelCategoriaController::class, 'nivelesPorArea']);
Route::get('/get-niveles', [NivelCategoriaController::class, 'index']);
Route::get('/olimpiadas/{id}/max-categorias', [OlimpiadaAreaController::class, 'maxCategorias']);

//Route::post('/inscripciones', [InscripcionAreaController::class, 'store']);
Route::post('/inscripciones', [InscripcionNivelesController::class, 'store']);

// Tutores
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi' ]);
route::post('/tutores/excel', function() {
    try {
        Excel::import(new TutoresImport, request()->file('file'));
        return response()->json(['message' => 'Archivo importado con éxito'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al importar el archivo', 'error' => $e->getMessage()], 400);
    }
});

// Áreas
Route::get('/areas', [AreasController::class, 'index']);
Route::post('/areas', [AreasController::class, 'store']);
Route::get('/areas-niveles-grados', [AreasController::class, 'areasConNivelesYGrados']);

//Colegios
Route::get('/colegios', [colegiosController::class, 'index']);
//Olimpiadas
Route::get('/olimpiadas', [OlimpiadaGestionController::class, 'index']);
Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);
Route::get('/olympiad/{gestion}', [OlimpiadaGestionController::class, 'show']);

//Olimpista Regitro
Route::post('/student-registration', [StudentRegistrationController::class, 'store']);
Route::get('/student-registration', [StudentRegistrationController::class, 'index']);

//Olimpista
Route::get('olimpistas/cedula/{cedula}', [OlimpistaController::class, 'getByCedula']);
Route::get('olimpistas/email/{email}', [OlimpistaController::class, 'getByEmail']);
Route::post('/olimpistas',[OlimpistaController::class, 'store']);
Route::post('/olimpistas/excel', function() {
    try {
        Excel::import(new OlimpistaImport, request()->file('file'));
        return response()->json(['message' => 'Archivo importado con éxito'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al importar el archivo', 'error' => $e->getMessage()], 400);
    }
});

//Departamentos
Route::get('/departamentos', [DepartamentoController::class, 'index']);

//Provincias
Route::get('/provincias/{id}', [ProvinciaController::class, 'porDepartamento']);
Route::get('/olimpistas/{id_olimpista}/olimpiadas/{id_olimpiada}/areas-disponibles', [AreasFiltroController::class, 'obtenerAreasDisponibles']);
Route::get('/estructura-olimpiada/{id_olimpiada}', [EstructuraOlimpiadaController::class, 'obtenerEstructuraOlimpiada']);

//new db
Route::post('/vincular-olimpista-tutor', [VincularController::class, 'registrarConParentesco']);
Route::get('/olimpiada/abierta', [OlimpiadaController::class, 'verificarOlimpiadaAbierta']);
Route::get('/verificar-inscripcion', [VerificarInscripcionController::class, 'verificar']);